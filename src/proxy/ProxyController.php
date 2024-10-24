<?php

namespace Aperture\proxy;

use Aperture\Aperture;
use marksync\provider\Mark;

#[Mark(args: ['parent'])]
class ProxyController
{
    private ?string $task = null;


    function createServer(string $url, string $token)
    {
        return (object)['url' => $url, 'token' => $token];
    }



    function __construct(private Aperture $api) {}



    function checkMask(string $task = null)
    {
        $this->task = $task;
        $this->api->proxys($this);
    }


    function task(string $mask, string $to, $server)
    {
        $url = $this->compareMask($mask, $this->task, $to);
        if (!$url)
            return;

        $proxyUrl = $server->url . '/' . str_replace('\\', '/', $url);

        $result = file_get_contents($proxyUrl);
        exit($result);
    }


    private function compareMask(string $mask, string $task, string $to): ?string
    {
        if (!str_ends_with($mask, '*') && $mask == $task)
            return $to;

        if (str_starts_with($task, substr($mask, 0, -1))) {
            $result = substr($to, 0, -1) . substr($task, strlen($mask) - 1);
            return $result;
        }

        return false;
    }
}
