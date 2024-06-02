<?php

namespace routes;

use Aperture\Route;

class SayHello extends Route
{

    function test()
    {
        yield $this('Gordon');
        yield $this('Max');
        yield $this('Семен');
        yield $this(null);
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
