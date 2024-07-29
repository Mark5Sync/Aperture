<?php

namespace Aperture\api;

class Redirect
{
    public ?string $to = null;
    public array $exceptions = [];

    function to(string $path)
    {
        $this->to = $path;
    }


}
