<?php
namespace Aperture\_markers;
use marksync\provider\provider;
use Aperture\cli\Task;
use Aperture\cli\Route;

/**
 * @property-read Route $route

*/
trait cli {
    use provider;

   function createTask(\Aperture\ApertureConfig $config, string $task, array $data = array (
)): Task { return new Task($config, $task, $data); }
   function createRoute(): Route { return new Route; }

}