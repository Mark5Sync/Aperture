<?php

namespace Aperture\doc;

use Aperture\Route;
use Composer\ClassMapGenerator\ClassMapGenerator;

class Doc
{
    private $schema = [];


    function __construct(private string $routes)
    {
    }


    function build()
    {
        $map = ClassMapGenerator::createMap($this->routes);

        foreach ($map as $route => $path) {
            try {
                $reflection = new \ReflectionClass($route);
            } catch (\Throwable $th) {
            }

            if (!$reflection->isSubclassOf(Route::class))
                continue;

            try {
                $task = new $route;

                $results = [];

                $next = false;
                /** @var \Generator $generator */
                $generator = $task->test();

                

                while ($generator->valid()) {
                    $task->beforeTest();
                    if ($next) {
                        $generator->next();
                    }
                    $result[] = $generator->current();
                    $task->afterTest();

                    $next = true;
                }
                

                $this->schema[$route] = [
                    'type' => count($result) == 2 ? $result[0] : new Join($route, array_slice($result, 0, -1)),
                ];
            } catch (\Throwable $th) {
            }
        }
    }


    private function runTest(Route $route)
    {
    }


    function getScheme(): array
    {
        return $this->schema;
    }
}
