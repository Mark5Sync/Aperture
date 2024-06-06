<?php
namespace routes\_markers;
use marksync\provider\provider;
use routes\basket\GetList;

/**
 * @property-read GetList $getList

*/
trait basket {
    use provider;

   function createGetList(): GetList { return new GetList; }

}