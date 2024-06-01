<?php

namespace routes;

use Aperture\Route;

class SayHello extends Route
{

    function __invoke()
    {
        return 'Hello';
    }
}
