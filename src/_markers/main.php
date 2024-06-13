<?php
namespace Aperture\_markers;
use marksync\provider\provider;
use Aperture\Route;
use Aperture\Error;
use Aperture\Middleware;

/**
 * @property-read Route $route
 * @property-read Error $error
 * @property-read Middleware $middleware

*/
trait main {
    use provider;

   function createRoute(): Route { return new Route; }
   function createError(): Error { return new Error; }
   function createMiddleware(): Middleware { return new Middleware; }

}