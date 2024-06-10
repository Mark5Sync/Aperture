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



            try {
                $task = new $route;

                $test = function (...$props) use ($task, &$exceptions, &$result) {
                    try {
                        $result = [...$result, $task(...$props)];
                    } catch (\Throwable $th) {
                        $exceptions[] = new Error($th->getMessage(), $th->getCode());
                    }
                };


                foreach ($task->test($test) as $_);

                $inputs = $this->getTaskInputs($task);
            } catch (\Throwable $th) {
                $exceptions[] = new Error($th->getMessage(), $th->getCode());
            }


            $this->schema[] = [
                'url' => $url,
                'alias' => $alias,
                'section' => $section,
                'breadcrumbs' => $breadcrumbs,
                'inputType' => $inputs,
                'outputType'  => count($result) < 2 ? $result[0] : new Join("{$alias}Output", $result),
                'exceptions' => $exceptions,
            ];
        }
    }


    private function getTaskInputs($task)
    {
        $reflectionMethod = new ReflectionMethod($task, '__invoke');

        $inputs = [];

        foreach ($reflectionMethod->getParameters() as $propertie) {
            $inputs[] = [
                'name'  => $propertie->getName(),
                // 'type' => $propertie->getType(),
            ];
        }

        return $inputs;
    }


    private function getQueryFromNamespace(string $namespace)
    {
        $url = substr($namespace, strlen($this->namespace));
        $breadcrumbs = explode('\\', $url);
        $alias = implode('', $breadcrumbs);
        return [$url, $alias, count($breadcrumbs) == 1 ? 'index' : $breadcrumbs[0], $breadcrumbs];
    }



    function getScheme(): array
    {
        return $this->schema;
    }
}
