<?php

namespace Aperture;

use Aperture\_markers\api;

class Route
{
    use api;

    function __invoke()
    {
        return 'Hello from default route';
    }



    function beforeTest()
    {

    }

    function test(callable $invoke)
    {
        return $invoke();
    }

    function afterTest()
    {
        
    }
}
