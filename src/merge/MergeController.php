<?php

namespace Aperture\merge;
use Aperture\_markers\api;
use Aperture\_markers\main;
use Aperture\_markers\preload;

class MergeController
{
    use api;
    use main;
    use preload;

    function handle()
    {
        [$tasks, $props] = $this->request->params;

        $merge = [];

        foreach ($tasks as ['url' => $task, 'props' => $indexes, 'id' => $id]) {
            $args = [];

            foreach ($indexes as $name => $index) 
                $args[$name] = $props[$index];

            try {
                $class = str_replace('/', '\\', substr($task, 1));
                $path = explode('/', $task);
                $short = end($path);

                $merge[] = [
                    'id' => $id,
                    'data' => $this->handler->run(new $class, $args, $short), //$this->pagination->wrapResult((new $class)(...$args), $short),
                ];
            } catch (\Throwable $th) {
                $merge[] = [
                    'id' => $id,
                    'error' => ['message' => $th->getMessage(), 'code' => $th->getCode()],
                ];
            }
        }

        
        $result = [
            'data' => $merge,
        ];
        
        if ($preload = $this->preload->get())
            $result['preload'] = $preload;

        exit(json_encode($result));
    }
}