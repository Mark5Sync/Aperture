<?php

namespace Aperture\api;


class Tag {
    private $collection = [];


    function add(string $tag){
        $this->collection[] = $tag;
    }


    function export(){
        $collection = $this->collection;
        $this->collection = [];
        return $collection;
    }
}