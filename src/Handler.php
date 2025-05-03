<?php 

namespace Aperture;

use Aperture\_markers\api;
use Aperture\_markers\main;

class Handler {
    use api;
    use main;

    function run(Route $task, array $params, ?string $short = null) {
        return $this->pagination->wrapResult(
            $this->gen->handle(($task)(...$params)),
            $short
        );
    }

}