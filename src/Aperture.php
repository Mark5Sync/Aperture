<?php

namespace Aperture;

abstract class Aperture extends Signature
{

    public string $routes;
    public string $namespace;
    public string $prefix = '';

    final function __construct()
    {
        header('Content-Type: application/json');
        ini_set('display_errors', 0);

        $strResult = json_encode($this->runTask());

        if ($strResult === false)
            $strResult = json_encode(['error' => $this->getJsonError()]);

        exit($strResult);
    }
}
