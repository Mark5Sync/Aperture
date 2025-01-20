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


    function set(int $page, int $size = 10)
    {
        $this->page = $page;
        $this->size = $size;
    }


    function usePagination()
    {
        $this->use = 'pagination';
    }


    function useLoadMore()
    {
        $this->use = 'load_more';
    }


    function wrapResult($result)
    {
        if ($this->use) {
            $type = $this->use;
            $this->use = null;

            switch ($type) {
                case 'load_more':
                    return [
                        "content{$this->request->shortTask}" => $result,
                        "load_more" => $this->loadMore,
                    ];
            
                case 'pagination':
                    return [
                        "content{$this->request->shortTask}" => $result,
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
