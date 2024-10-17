<?php

namespace Aperture\mask;

class Mask
{
    private $starts_with = false;
    private $equals = false;
    private $all = false;

    function __construct(string $mask)
    {
        if (str_ends_with($mask, '*'))
            return $this->starts_with = substr($mask, 0, -1);

        if ($mask == '')
            return $this->all = true;

        $this->equals = $mask;
    }


    function check(string $namespace): bool
    {
        if ($this->all)
            return true;

        if ($this->starts_with)
            return str_starts_with($namespace, $this->starts_with);

        return $namespace == $this->equals;
    }
}
