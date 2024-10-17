<?php
namespace Aperture\_markers;
use marksync\provider\provider;
use Aperture\mask\Mask;

/**
 * @property-read Mask $mask

*/
trait mask {
    use provider;

   function createMask(): Mask { return new Mask; }

}