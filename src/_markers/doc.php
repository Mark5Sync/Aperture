<?php
namespace Aperture\_markers;
use marksync\provider\provider;
use Aperture\doc\Join;
use Aperture\doc\Doc;

/**
 * @property-read Join $join
 * @property-read Doc $doc

*/
trait doc {
    use provider;

   function createJoin(): Join { return new Join; }
   function createDoc(): Doc { return new Doc; }

}