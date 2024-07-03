<?php

namespace Aperture\api;

class Pagination
{

    public $page = 1;
    public $size = 10;
    public $pages = null;
    public $use = false;


    function set(int $page, int $size = 10)
    {
        $this->page = $page;
        $this->size = $size;

        $this->use = true;
    }



    function wrapResult($result)
    {
        if ($this->use)
            return [
                'content' => $result,
                'pagination' => [
                    'page'  => $this->page,
                    'size'  => $this->size,
                    'pages' => $this->pages,
                ]
            ];

        return $result;
    }
}
