<?php

namespace Aperture;

use Aperture\_markers\main;
use Generator;

class Gen
{
    use main;


    private ?Generator $gen = null;

    function handle($result)
    {
        if ($result instanceof Generator) {
            $this->gen = $result;
            return $this->gen->current();
        }

        return $result;
    }


    function finish()
    {
        if (!$this->gen)
            exit();

        fastcgi_finish_request();

        $this->ob->start();
        $this->gen->next();
    }
}
