<?php

namespace Aperture\doc;

use Aperture\_markers\api;
use Aperture\_markers\main;
use Aperture\_markers\pathmask;
use Aperture\Aperture;
use Aperture\Error;
use Aperture\pathmask\Mask;
use Aperture\proxy\ProxyController;
use Aperture\Route;
use Composer\ClassMapGenerator\ClassMapGenerator;
use marksync\provider\NamespaceController;
use ReflectionMethod;

class Doc
{
    use api;
    use main;
    use pathmask;

    private $schema = [];
    private $version = 2;


    function __construct(private string $routes, private string $namespace) {}
    
    function build(Mask $mask, Aperture $api)
    {
        $map = ClassMapGenerator::createMap($this->routes);

        (new NamespaceController($this->routes, trim($this->namespace, '\\')))->handle($map);

        foreach ($map as $route => $path) {
            if (!$mask->check($route))
                continue;

            try {
                $reflection = new \ReflectionClass($route);
            } catch (\Throwable $th) {
                continue;
            }

            if (!$reflection->isSubclassOf(Route::class))
                continue;

            [$url, $alias, $section, $breadcrumbs] = $this->getQueryFromNamespace($route);
            $result = [];
            $warning = false;
            $exceptions = [];
            $times = [];
            $inputs = [];
            $inputData = [];

            $this->request->shortTask = $reflection->getShortName();


            try {
                $task = new $route;


                $test = function (...$props) use ($task, &$exceptions, &$result, &$times, &$inputData) {
                    try {
                        $start = microtime(true);
                        $inputData[] = $props;
                        $result = [...$result, $this->gen->handle($task(...$props))];
                        $times[] = microtime(true) - $start;
                    } catch (\Throwable $th) {
                        $exceptions[] = new Error($th->getMessage(), $th->getCode());
                    }
                };

                foreach ($task->test($test) as $pass) {
                    if (!$pass)
                        continue;
                    $warning = true;
                    $result = [...$result, $pass];
                }

                $inputs = $this->getTaskInputs($task, $alias, $inputData);
            } catch (\Throwable $th) {
                $exceptions[] = new Error($th->getMessage(), $th->getCode());
            }

            $time = empty($times) ? 0 : array_sum($times) / count($times);
            $docs = $reflection->getDocComment();

            $this->schema[] = [
                'warning' => $warning,
                'url' => $url,
                'alias' => $alias,
                'shortAlias' => $this->request->shortTask,
                'section' => $section,
                'breadcrumbs' => $breadcrumbs,
                'inputType'   => $inputs,
                'outputType'  => $this->pagination->wrapResult(count($result) ? (count($result) < 2 ? $result[0] : new Join("{$alias}Output", $result)) : null),
                'exceptions' => $exceptions,
                'time' => $time,
                'path' => $path,
                'doc' => $docs,
                'tags' => $this->tag->export(),
            ];
        }
    }

    private function getDataProps(array $data, string $key, int $index): array
    {
        if (empty($data))
            return [];

        $isAssociativeArray = array_keys($data[0]) !== range(0, count($data[0]) - 1);

        if (!$isAssociativeArray) {
            $result = array_column($data, $index);
            return $result;
        }

        $result = [];
        foreach ($data as $values) {
            if (isset($values[$key]))
                $result[] = $values[$key];
        }

        return $result;
    }

    private function getTaskInputs($task, string $alias, array $data)
    {
        $reflectionMethod = new ReflectionMethod($task, '__invoke');

        $inputs = [];

        foreach ($reflectionMethod->getParameters() as $index => $propertie) {
            $ucfirst = ucfirst($propertie->name);
            $type = $this->getParametrType($propertie, "{$alias}{$ucfirst}Input", $this->getDataProps($data, $propertie->name, $index));
            $inputs[$propertie->name] = $type;
        }

        if (empty($inputs))
            return null;

        return $inputs;
    }




    private function getParametrType(\ReflectionParameter $propertie, string $alias, array $data)
    {
        $type = $propertie->getType();

        if (!$type)
            $type = 'null';
        else if ($type instanceof \ReflectionNamedType)
            $type = $this->getInputType($alias, $type->getName(), $propertie->allowsNull(), $data);
        else if ($type instanceof \ReflectionUnionType)
            $type = array_map(fn($tp) => "$tp", $type->getTypes());


        return $type;
    }



    private function getInputType($alias, $name, $canToBeNull, $data)
    {
        $result = null;
        switch ($name) {
            case 'int':
            case 'float':
                $result = 1;

                break;
            case 'string':
                $result = 'string';
                break;
            case 'array':
                $result = $data;
                break;

            case 'bool':
                $result = true;
                break;

            default:
                $result = 'any';
        }

        if ($canToBeNull)
            return new Join($alias, [null, ...(array)$result]);


        return is_array($result) ? new Join($alias, $result) : $result;
    }



    private function getQueryFromNamespace(string $namespace)
    {
        $url = substr($namespace, strlen($this->namespace));
        $breadcrumbs = explode('\\', $url);

        $url = str_replace('\\', '/', $url);

        $alias = implode('', $breadcrumbs);
        return [$url, $alias, count($breadcrumbs) == 1 ? 'index' : $breadcrumbs[0], $breadcrumbs];
    }


    function proxys(ProxyController $proxy)
    {
        $this->parent->proxys($proxy);
    }


    function proxyDoc($server, array | string $alias)
    {
        $data = file_get_contents("{$server->url}/{$server->api}/__doc__?token={$server->token}");
        if (!$data)
            return;

        $data = json_decode($data, true);
        if (!isset($data['version']) || $data['version'] != $this->version)
            return;

        $reverceMask = $this->reverseMask($alias);
        $this->schema = [...$this->schema, ...array_map(fn($task) => $this->applyAliasToRemoteTask($task, $reverceMask), $data['schema'])];
    }


    private function applyAliasToRemoteTask($task, array $reverceMask)
    {
        foreach ($reverceMask as [$remote, $local]) {
            if ($url = $this->maskReplace->compare($local, $task['url'], $remote))
                return [...$task, 'url' => $url, 'alias' => str_replace('/', '', $url)];
        }

        return $task;
    }

    private function reverseMask(array | string $alias)
    {
        $result = [];

        foreach ((array)$alias as $masks) {
            [$remote, $local] = explode(':', $masks);
            $result[] = [
                str_replace('\\', '/', $remote),
                str_replace('\\', '/', $local)
            ];
        }

        return $result;
    }

    function getScheme(): array
    {
        return [
            'schema' => $this->schema,
            'version' => $this->version,
        ];
    }
}
