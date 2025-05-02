<?php

namespace Aperture\api;

use Aperture\_markers\api;

class Pagination
{
    use api;

    public $page = 1;
    public $size = 10;
    public $pages = null;
    public $loadMore = false;
    public ?string $use = null; // pagination or load_more


    function setPage(int $page, int $size = 10)
    {
        $this->page = $page;
        $this->size = $size;
        $this->usePagination();
    }


    function setPageMore(int $page, int $size = 10)
    {
        $this->page = $page;
        $this->size = $size;
        $this->useLoadMore();
    }


    function usePagination()
    {
        $this->use = 'pagination';
    }


    function useLoadMore()
    {
        $this->use = 'load_more';
        $this->pagination->loadMore = false;
    }


    function wrapResult($result, ?string $short = null)
    {
        if ($this->use) {
            $type = $this->use;
            $this->use = null;

            $task = $short ? $short : $this->request->shortTask;

            switch ($type) {
                case 'load_more':
                    return [
                        "content{$task}" => $result,
                        "load_more" => $this->loadMore,
                    ];

                case 'pagination':
                    return [
                        "content{$task}" => $result,
                        "pagination" => [
                            'page'  => $this->page,
                            'size'  => $this->size,
                            'pages' => $this->pages,
                        ],
                    ];
            }
        }


        return $result;
    }
}
