<?php

namespace Aperture\proxy;

use Aperture\_markers\api;
use Aperture\Aperture;
use Aperture\doc\Doc;
use marksync\provider\Mark;

#[Mark(args: ['parent'])]
class ProxyController
{
    use api;

    private ?string $task = null;
    private ?Doc $doc;

    function createServer(string $url, string $api, string $token)
    {
        return (object)['url' => $url, 'api' => $api, 'token' => $token];
    }

    function __construct(private Aperture $api) {}



    function checkMask(string $task = null)
    {
        $this->task = $task;
        $this->api->proxys($this);
    }


    function useDoc(Doc $doc)
    {
        $this->doc = $doc;
        $this->api->proxys($this);
    }


    function task(string $mask, string $to, $server)
    {
        if (!$this->task)
            return $this->doc->proxyDoc($server);

        $url = $this->compareMask($mask, $this->task, $to);
        if (!$url)
            return;

        $proxyUrl = $server->url . '/' . str_replace('\\', '/', $url);
        exit($this->fetch($proxyUrl));
    }


    private function fetch($url)
    {
        $c = curl_init($url);
        curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($c, CURLOPT_POSTFIELDS, json_encode($this->request->params, JSON_UNESCAPED_UNICODE));
        $result = curl_exec($c);
        
        return $result;
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
