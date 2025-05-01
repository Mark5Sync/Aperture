<?php
namespace Aperture\_markers;
use marksync\provider\provider;
use Aperture\api\Pagination;
use Aperture\api\Tag;
use Aperture\api\Request;
use Aperture\api\Redirect;

/**
 * @property-read Pagination $pagination
 * @property-read Tag $tag
 * @property-read Request $request
 * @property-read Redirect $redirect

*/
trait api {
    use provider;

   function createPagination(): Pagination { return new Pagination; }
   function createTag(): Tag { return new Tag; }
   function createRequest(): Request { return new Request($this, $this->super('createRequest')); }
   function createRedirect(): Redirect { return new Redirect; }

}