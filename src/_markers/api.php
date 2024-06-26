<?php
namespace Aperture\_markers;
use marksync\provider\provider;
use Aperture\api\Request;
use Aperture\api\Tag;
use Aperture\api\Redirect;

/**
 * @property-read Request $request
 * @property-read Tag $tag
 * @property-read Redirect $redirect

*/
trait api {
    use provider;

   function createRequest(): Request { return new Request($this, $this->super('createRequest')); }
   function createTag(): Tag { return new Tag; }
   function createRedirect(): Redirect { return new Redirect; }

}