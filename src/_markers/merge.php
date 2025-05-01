<?php
namespace Aperture\_markers;
use marksync\provider\provider;
use Aperture\merge\MergeController;

/**
 * @property-read MergeController $mergeController

*/
trait merge {
    use provider;

   function createMergeController(): MergeController { return new MergeController; }

}