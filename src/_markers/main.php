<?php
namespace Aperture\_markers;
use marksync\provider\provider;
use Aperture\Route;
use Aperture\Middleware;

/**
 * @property-read Route $route
 * @property-read Middleware $middleware

*/
trait main {
    use provider;

   function createRoute(): Route { return new Route; }
   function createMiddleware(): Middleware { return new Middleware; }

}