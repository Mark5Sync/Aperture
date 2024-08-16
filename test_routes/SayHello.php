<?php

namespace routes;

use Aperture\Route;

class SayHello extends Route
{

    function test(callable $invoke)
    {
        yield $invoke('Gordon');
        yield $invoke('Max');
        yield $invoke('Семен');
        yield $invoke(null);
    }



    function __invoke(?string $name = null)
    {
        if (!$name)
            return 'Whats is your name';

        return [
            "first_name" => $name,
            "second_name" => 'Freeman',
        ];
    }

}
