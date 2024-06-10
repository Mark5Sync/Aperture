<?php

namespace Aperture\cli;

use Aperture\ApertureConfig;
use JsonSerializable;
use marksync\provider\MarkInstance;

#[MarkInstance]
class Task implements JsonSerializable
{
    private mixed $result;


    function __construct(private ApertureConfig $config, string $task, $data = [])
    {
        $this->result = $this->{$task}(...$data);
    }





    private function createRoute(string $path, string $name, string $description = '')
    {
        $folder = "{$this->config->routes}/{$path}";
        $namespace = str_replace('/', '\\', "{$this->config->namespace}{$path}");
        $fileName = "{$name}.php";

        if (!file_exists($folder))
            if (!mkdir($folder, 0777, true))
                throw new \Exception("Не удалось создать Route path [$path]", 32);

        date_default_timezone_set('Europe/Moscow');
        $createdAt = date("H:i:s d-m-Y");

        $full = "{$folder}/{$fileName}";

        if (file_exists($full))
            throw new \Exception("Route уже существует", 33);


        if (!file_put_contents($full, <<<PHP
        <?php

        namespace $namespace;

        use Aperture\Route;

        /**
         * $description
         * created at $createdAt
         **/
        class $name extends Route {

        
            function test(){
                yield \$this('Xarmerong');
            }


            function __invoke(string \$name){
                throw new \Exception("[DefaultRoute]", 12);
                return "Hello, \$name";
            }


        }
        
        PHP)) {
            throw new \Exception("Не удалось создать Route [$full]", 34);
        }

        return "OK";
    }



    function jsonSerialize(): mixed
    {
        return $this->result;
    }
}
