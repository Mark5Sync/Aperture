<?php

namespace Aperture;


class Ob
{

    private $logs = [];
    private $catch = false;

    function start()
    {
        ob_start(
            function ($log) {
                if ($log)
                    $this->logs[] = $log;
            },
            1,
        );

        $this->catch = true;
    }


    function clear()
    {
        if ($this->catch)
            ob_end_flush();

        $this->catch = false;
    }


    function getLog()
    {
        if (!empty($this->logs))
            return $this->logs;
    }
}
