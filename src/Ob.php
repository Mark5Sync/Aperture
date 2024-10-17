<?php

namespace Aperture;


class Ob
{

    private $logs = [];

    function start()
    {
        ob_start(function ($log) use (&$logs) {
            if ($log)
                $this->logs[] = $log;
        }, 1);
    }


    function clear()
    {
        ob_end_flush();
    }


    function getLog()
    {
        if (!empty($this->logs))
            return $this->logs;
    }
}
