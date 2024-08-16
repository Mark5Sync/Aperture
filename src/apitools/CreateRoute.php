<?php

namespace Aperture\apitools;

use Aperture\Aperture;
use Aperture\cli\Task;
use marksync\provider\Mark;

#[Mark(args: ['parent'])]
class CreateRoute
{

    function __construct(private Aperture $config) {}



    function createClass(string $className, string $description = 'autoroute')
    {
        $path = array_filter(explode('\\', $className), fn($itm) => $itm);
        $routeName = array_pop($path);
        
        new Task($this->config, 'createRoute', [
            'path' => implode(DIRECTORY_SEPARATOR, $path),
            'name' => $routeName,
            'description' => $description,
        ]);
    }
}
