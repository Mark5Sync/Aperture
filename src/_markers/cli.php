<?php
namespace Aperture\_markers;
use marksync\provider\provider;
use Aperture\cli\Route;
use Aperture\cli\Task;

/**
 * @property-read Route $route

*/
trait cli {
    use provider;

   function createRoute(): Route { return new Route; }
   function createTask(Aperture\ApertureConfig $config, string $task, $data = array (
)): Task { return new Task($config, $task, $data); }

}