<?php

namespace Aperture\cashe;

class CasheMergeTransmitter extends \Exception {
    function __construct($message, $code = 0) {
        parent::__construct($message, $code);
    }
}