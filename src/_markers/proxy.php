<?php
namespace Aperture\_markers;
use marksync\provider\provider;
use Aperture\proxy\ProxyController;

/**
 * @property-read ProxyController $proxyController

*/
trait proxy {
    use provider;

   function createProxyController(): ProxyController { return new ProxyController($this); }

}