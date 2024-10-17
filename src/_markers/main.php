<?php
namespace Aperture\_markers;
use marksync\provider\provider;
use Aperture\Route;
use Aperture\Ob;
use Aperture\Gen;
use Aperture\Error;
use Aperture\Middleware;

/**
 * @property-read Route $route
 * @property-read Ob $ob
 * @property-read Gen $gen
 * @property-read Error $error
 * @property-read Middleware $middleware

*/
trait main {
    use provider;

   function createRoute(): Route { return new Route; }
   function createOb(): Ob { return new Ob; }
   function createGen(): Gen { return new Gen; }
   function createError(): Error { return new Error; }
   function createMiddleware(): Middleware { return new Middleware; }

}