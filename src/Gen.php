<?php

namespace Aperture;

use Generator;

class Gen
{
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

        $this->gen->next();
    }
}
