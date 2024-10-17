<?php
namespace Aperture\_markers;
use marksync\provider\provider;
use Aperture\doc\Doc;
use Aperture\doc\Join;

/**
 * @property-read Doc $doc
 * @property-read Join $join

*/
trait doc {
    use provider;

   function createDoc(): Doc { return new Doc; }
   function createJoin(): Join { return new Join; }

}