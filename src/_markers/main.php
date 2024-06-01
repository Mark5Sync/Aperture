<?php
namespace Aperture\_markers;
use marksync\provider\provider;
use Aperture\Middleware;
use Aperture\Signature;
use Aperture\Route;

/**
 * @property-read Middleware $middleware
 * @property-read Signature $signature
 * @property-read Route $route

*/
trait main {
    use provider;

   function createMiddleware(): Middleware { return new Middleware; }
   function createSignature(): Signature { return new Signature; }
   function createRoute(): Route { return new Route; }

}