<?php

namespace Aperture\cli;

use JsonSerializable;
use marksync\provider\MarkInstance;

#[MarkInstance]
class Task implements JsonSerializable
{
    private mixed $result;

    function __construct(string $task, $data = [])
    {
        $this->result = $this->{$task}(...$data);
    }





    private function createRoute(string $path, string $name)
    {
        return "OK";
    }



    function jsonSerialize(): mixed
    {
        return $this->result;
    }
}
