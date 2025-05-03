<?php
namespace Aperture\_markers;
use marksync\provider\provider;
use Aperture\preload\PreloadController;
use Aperture\preload\PreloadHandler;

/**
 * @property-read PreloadController $preload
 * @property-read PreloadHandler $preloadHandler

*/
trait preload {
    use provider;

   function createPreload(): PreloadController { return new PreloadController; }
   function createPreloadHandler(): PreloadHandler { return new PreloadHandler; }

}