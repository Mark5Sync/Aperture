<?php
namespace Aperture\_markers;
use marksync\provider\provider;
use Aperture\apitools\CreateRoute;

/**
 * @property-read CreateRoute $createRoute

*/
trait apitools {
    use provider;

   function createCreateRoute(): CreateRoute { return new CreateRoute($this); }

}