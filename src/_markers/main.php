<?php
namespace Aperture\_markers;
use marksync\provider\provider;
use Aperture\Gen;
use Aperture\Error;
use Aperture\Route;

/**
 * @property-read Gen $gen
 * @property-read Error $error
 * @property-read Route $route

*/
trait main {
    use provider;

   function createGen(): Gen { return new Gen; }
   function createError(): Error { return new Error; }
   function createRoute(): Route { return new Route; }

}