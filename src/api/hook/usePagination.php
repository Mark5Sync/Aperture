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
        // /** @var Model $this */
        $this->pagination->use = 'pagination';

        $this->page(
            $this->pagination->page,
            $this->pagination->size,
            $this->pagination->pages,
        );

        return $this;
    }


    function useLoadMore()
    {
        $this->pagination->use = 'load_more';

        // /** @var Model $this */
        $this->loadMore(
            $this->pagination->page,
            $this->pagination->size,
            $this->pagination->loadMore,
        );

        return $this;
    }
}
