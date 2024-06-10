<?php

namespace Aperture;

class Route
{

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
