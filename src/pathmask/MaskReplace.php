<?php

namespace Aperture\pathmask;

class MaskReplace
{


    function compare(string $from, string $task, string $to): ?string
    {
        if (!str_ends_with($from, '*') && $from == $task)
            return $to;

        if (str_starts_with($task, substr($from, 0, -1))) {
            $result = substr($to, 0, -1) . substr($task, strlen($from) - 1);
            return $result;
        }

        return null;
    }
}
