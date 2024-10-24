<?php

namespace Aperture;

use Aperture\proxy\ProxyController;

abstract class Aperture extends Signature
{

    public string $routes = 'routes';
    public string $namespace = "Api\\";
    public string $prefix = 'Api';


    public function verificateToken(?string $token): bool | string
    {
        return false;
    }

    public function middleware(Route $route, string $path) 
    {

    }

    protected function onInit(string $task) 
    {

    }

    protected function onError(\Throwable $exception) 
    {

    }

    public function proxys(ProxyController $proxy): void
    {
        
    }
}
