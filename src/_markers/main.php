<?php
namespace Aperture\_markers;
use marksync\provider\provider;
use Aperture\Error;
use Aperture\Route;
use Aperture\Gen;
use Aperture\Middleware;
use Aperture\Ob;

/**
 * @property-read Error $error
 * @property-read Route $route
 * @property-read Gen $gen
 * @property-read Middleware $middleware
 * @property-read Ob $ob

*/
trait main {
    use provider;

   function createError(): Error { return new Error; }
   function createRoute(): Route { return new Route; }
   function createGen(): Gen { return new Gen; }
   function createMiddleware(): Middleware { return new Middleware; }
   function createOb(): Ob { return new Ob; }

}