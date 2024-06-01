<?php
namespace routes\_markers;
use marksync\provider\provider;
use routes\SayHello;

/**
 * @property-read SayHello $sayHello

*/
trait main {
    use provider;

   function createSayHello(): SayHello { return new SayHello; }

}