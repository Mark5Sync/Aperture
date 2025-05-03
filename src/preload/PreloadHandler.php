<?php

namespace Aperture\preload;

use Aperture\_markers\main;

class PreloadHandler {
    use main;

    protected $preloads = [];


    function get() {
        $result = [];

        foreach ($this->preloads as [$class, $props]) {
            try {
                $task = new $class;
                $data = $this->handler->run($task, $props);

                $result[] = [
                    'url' => '/' . str_replace('\\', '/', $class),
                    'data' => $data,
                    'props' => $props,
                ];
            } catch (\Throwable $th) {
                
            }
        }

        if (empty($result))
            return false;

        return $result;
    }

}