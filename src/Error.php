<?php

namespace Aperture;

class Error implements \JsonSerializable
{

    function __construct(private string $message, private int $code)
    {
    }

    function jsonSerialize(): array
    {
        return [
            'message' => $this->message,
            'code' => $this->code,
        ];
    }
}
