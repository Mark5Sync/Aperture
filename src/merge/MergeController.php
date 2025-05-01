<?php

namespace Aperture\merge;
use Aperture\_markers\api;

class MergeController
{
    use api;

    function handle()
    {
        [$tasks, $props] = $this->request->params;

        $merge = [];

        foreach ($tasks as $task => $indexes) {
            $args = [];

            foreach ($indexes as $name => $index) 
                $args[$name] = $props[$index];

            try {
                $class = str_replace('/', '\\', substr($task, 1));
                $merge[] = [
                    'url' => $task,
                    'data' => (new $class)(...$args),
                ];
            } catch (\Throwable $th) {
                $merge[] = [
                    'url' => $task,
                    'error' => ['message' => $th->getMessage(), 'code' => $th->getCode()],
                ];
            }
        }

        $result = [
            'data' => $merge,
        ];

        exit(json_encode($result));
    }
}