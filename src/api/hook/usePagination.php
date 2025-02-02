<?php

namespace Aperture\api\hook;

use Aperture\_markers\api;
use blackpostgres\Table;

/** 
 * blackpostgres plugin
 */
trait usePagination
{
    use api;

    function usePagination()
    {
        
        $this->pagination->usePagination();

        /** @var Table $this */
        $this->page(
            $this->pagination->page,
            $this->pagination->size,
            $this->pagination->pages,
        );

        return $this;
    }


    function useLoadMore()
    {
        $this->pagination->useLoadMore();

        /** @var Table $this */
        $this->loadMore(
            $this->pagination->page,
            $this->pagination->size,
            $this->pagination->loadMore,
        );

        return $this;
    }
}
