<?php

namespace Aperture\preload;

use marksync\provider\Mark;

#[Mark('preload')]
class PreloadController extends PreloadHandler {

    function add(string $route, ...$props)
    {
        $this->preloads[] = [
            $route, $props
        ];
    }

}