<?php

namespace Aperture\doc;

use Aperture\Error;
use Aperture\Route;
use Composer\ClassMapGenerator\ClassMapGenerator;
use ReflectionMethod;

class Doc
{
    private $schema = [];


    function __construct(private string $routes, private string $namespace)
    {
    }


    function build()
    {
        $map = ClassMapGenerator::createMap($this->routes);

        foreach ($map as $route => $path) {
            try {
                $reflection = new \ReflectionClass($route);
            } catch (\Throwable $th) {
                continue;
            }

            if (!$reflection->isSubclassOf(Route::class))
                continue;

            [$url, $alias, $section, $breadcrumbs] = $this->getQueryFromNamespace($route);
            $result = [];
            $exceptions = [];
            $times = [];




            try {
                $task = new $route;


                $test = function (...$props) use ($task, &$exceptions, &$result, &$times) {
                    try {
                        $start = microtime(true);
                        $result = [...$result, $task(...$props)];
                        $times[] = microtime(true) - $start;
                    } catch (\Throwable $th) {
                        $exceptions[] = new Error($th->getMessage(), $th->getCode());
                    }
                };

                foreach ($task->test($test) as $_);

                $inputs = $this->getTaskInputs($task, $alias);
            } catch (\Throwable $th) {
                $exceptions[] = new Error($th->getMessage(), $th->getCode());
            }

            $time = empty($times) ? 0 : array_sum($times) / count($times);

            $this->schema[] = [
                'url' => $url,
                'alias' => $alias,
                'section' => $section,
                'breadcrumbs' => $breadcrumbs,
                'inputType'   => $inputs,
                'outputType'  => count($result) ? (count($result) < 2 ? $result[0] : new Join("{$alias}Output", $result)) : null,
                'exceptions' => $exceptions,
                'time' => $time,
            ];
        }
    }


    private function getTaskInputs($task, string $alias)
    {
        $reflectionMethod = new ReflectionMethod($task, '__invoke');

        $inputs = [];

        foreach ($reflectionMethod->getParameters() as $propertie) {
            $ucfirst = ucfirst($propertie->name);
            $inputs[$propertie->name] = $this->getParametrType($propertie, "{$alias}{$ucfirst}Input");
        }

        if (empty($inputs))
            return null;

        return $inputs;
    }



    private function getParametrType(\ReflectionParameter $propertie, string $alias)
    {
        $type = $propertie->getType();

        if (!$type)
            $type = 'null';
        else if ($type instanceof \ReflectionNamedType)
            $type = $this->getInputType($alias, $type->getName(), $propertie->allowsNull());
        else if ($type instanceof \ReflectionUnionType)
            $type = array_map(fn ($tp) => "$tp", $type->getTypes());


        return $type;
    }


    private function getInputType($alias, $name, $canToBeNull)
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
                $result = [];
                break;

            case 'bool':
                $result = true;
                break;

            default:
                $result = 'avy';
        }

        if ($canToBeNull)
            return new Join($alias, [null, $result]);

        return $result;
    }



    private function getQueryFromNamespace(string $namespace)
    {
        $url = substr($namespace, strlen($this->namespace));
        $breadcrumbs = explode('\\', $url);

        $url = str_replace('\\', '/', $url);

        $alias = implode('', $breadcrumbs);
        return [$url, $alias, count($breadcrumbs) == 1 ? 'index' : $breadcrumbs[0], $breadcrumbs];
    }



    function getScheme(): array
    {
        return [
            'schema' => $this->schema,
            'version' => 2,
        ];
    }
}
