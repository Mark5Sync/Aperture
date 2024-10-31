<?php
namespace Aperture\_markers;
use marksync\provider\provider;
use Aperture\pathmask\MaskReplace;
use Aperture\pathmask\Mask;

/**
 * @property-read MaskReplace $maskReplace
 * @property-read Mask $mask

*/
trait pathmask {
    use provider;

   function createMaskReplace(): MaskReplace { return new MaskReplace; }
   function createMask(): Mask { return new Mask; }

}