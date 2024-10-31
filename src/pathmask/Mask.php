<?php

namespace Aperture\pathmask;

class Mask
{
    private $starts_with = [];
    private $equals = [];
    private $all = false;

    function __construct(string | array $masks, string $routeNamespace)
    {
        if ($masks == '')
            return $this->all = true;

        foreach ((array)$masks as $mask) {
            if (str_ends_with($mask, '*')) {
                $this->starts_with[] = $routeNamespace . substr($mask, 0, -1);
                continue;
            }

            $this->equals[] = $routeNamespace . $mask;
        }
    }


    function check(string $namespace): bool
    {
        if ($this->all)
            return true;

        foreach ($this->equals as $mask) {
            if ($mask == $namespace)
                return true;
        }

        foreach ($this->starts_with as $mask) {
            if (str_starts_with($namespace, $mask))
                return true;
        }

        return false;
    }
}
