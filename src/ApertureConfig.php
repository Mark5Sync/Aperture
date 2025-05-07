<?php

namespace Aperture;

use Aperture\cashe\Cashe;

abstract class ApertureConfig
{
    public string $routes;
    public string $namespace;
    public string $prefix = '';
    public ?string $casheClass = null;
    
}
