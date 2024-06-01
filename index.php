<?php

require './vendor/autoload.php';

use Aperture\Aperture;

new class extends Aperture 
{

    public string $prefix = 'api\/v2';
    protected string $routes = './test_routes';
    protected string $namespace = 'routes'; 

};