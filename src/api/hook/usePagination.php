<?php

namespace Aperture\api\hook;

use Aperture\_markers\api;
use blackpostgres\Model;

/** 
 * blackpostgres plugin
 */
trait usePagination
{
    use api;

    function usePagination()
    {
        /** @var Model $this */
        $this->pagination->use = true;

        $this->___page(
            $this->pagination->page,
            $this->pagination->size,
            $this->pagination->pages,
        );

        return $this;
    }
}
