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
        
        $this->pagination->usePagination();

        // /** @var Model $this */
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

        //  /** @var Model $this */
        $this->loadMore(
            $this->pagination->page,
            $this->pagination->size,
            $this->pagination->loadMore,
        );

        return $this;
    }
}
