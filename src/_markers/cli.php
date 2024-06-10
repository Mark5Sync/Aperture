<?php

namespace Aperture\_markers;

use Aperture\ApertureConfig;
use marksync\provider\provider;
use Aperture\cli\Route;
use Aperture\cli\Task;

/**
 * @property-read Route $route

 */
trait cli
{
    use provider;

    function createRoute(): Route
    {
        return new Route;
    }
    function createTask(ApertureConfig $config, string $task, array $data = array()): Task
    {
        return new Task($config, $task, $data);
    }
}
