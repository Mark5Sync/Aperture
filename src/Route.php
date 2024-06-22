<?php

namespace Aperture;

use Aperture\_markers\api;

class Route
{
    use api;



    function test(callable $invoke)
    {
        return $invoke();
    }


    
    function beforeTest()
    {

    }


    function afterTest()
    {
        
    }
}
