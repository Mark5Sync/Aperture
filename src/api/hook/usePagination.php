<?php

namespace Aperture\api\hook;

use Aperture\_markers\api;
use FLD\Elastic\ElasticFilters;
use marksync_libs\Elastic\Search\Search;

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

        /** @var ElasticFilters $this */
        $this->loadMore(
            $this->pagination->page,
            $this->pagination->size,
            $this->pagination->loadMore,
        );

        return $this;
    }
}
