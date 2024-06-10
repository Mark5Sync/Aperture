<?php

namespace Aperture;

abstract class Aperture extends Signature
{



    final function __construct()
    {
        header('Content-Type: application/json');
        ini_set('display_errors', 0);

        $strResult = json_encode($this->runTask());

        if ($strResult === false)
            $strResult = json_encode(['error' => $this->getJsonError()]);

        exit($strResult);
    }



    public function verificateToken(string $token): bool
    {
        return false;
    }
}
