<?php

namespace Aperture;

abstract class Aperture extends Signature
{

    public string $routes = 'routes';
    public string $namespace = "Api\\";
    public string $prefix = 'Api';


    public function verificateToken(?string $token): bool
    {
        return false;
    }


    public function middleware(Route $route, string $path) {}


    protected function onInit(string $task) {}


    protected function onError(\Throwable $exception) {}


    protected function onNotFound(string $route) {}
}
