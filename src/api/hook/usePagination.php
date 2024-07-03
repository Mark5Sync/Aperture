<?php

namespace Aperture\api\hook;

use Aperture\_markers\api;

/** 
 * blackpostgres plugin
 */
trait usePagination
{
    use api;

    function usePagination()
    {
        $this->pagination->use = true;

        $this->page(
            $this->pagination->page,
            $this->pagination->size,
            $this->pagination->pages,
        );

        return $this;
    }
}
