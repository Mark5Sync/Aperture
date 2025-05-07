<?php

namespace Aperture\cashe;


interface Cashe
{

    function exists(string $key): bool;
    function setValue(string $key, string $value): void;
    function getValue(string $key): string;

}
