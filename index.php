<?php

require './vendor/autoload.php';

use Aperture\Aperture;

new class extends Aperture 
{

    public string $prefix = 'api';
    public string $routes = './test_routes';
    public string $namespace = 'routes'; 

};