<?php
namespace Aperture\_markers;
use marksync\provider\provider;
use Aperture\Route;
use Aperture\Error;

/**
 * @property-read Route $route
 * @property-read Error $error

*/
trait main {
    use provider;

   function createRoute(): Route { return new Route; }
   function createError(): Error { return new Error; }

}