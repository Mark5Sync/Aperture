<?php
namespace Aperture\_markers;
use marksync\provider\provider;
use Aperture\api\Request;
use Aperture\api\Pagination;
use Aperture\api\Redirect;
use Aperture\api\Tag;

/**
 * @property-read Request $request
 * @property-read Pagination $pagination
 * @property-read Redirect $redirect
 * @property-read Tag $tag

*/
trait api {
    use provider;

   function createRequest(): Request { return new Request($this, $this->super('createRequest')); }
   function createPagination(): Pagination { return new Pagination; }
   function createRedirect(): Redirect { return new Redirect; }
   function createTag(): Tag { return new Tag; }

}