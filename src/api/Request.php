<?php

namespace Aperture\api;

use Aperture\_markers\cli;
use Aperture\_markers\merge;
use Aperture\_markers\preload;
use Aperture\Aperture;
use Aperture\doc\Doc;
use Aperture\pathmask\Mask;
use marksync\provider\Mark;
use ReflectionMethod;

#[Mark(args: ['parent', 'super'])]
class Request
{
    use cli;
    use merge;
    use preload;

    public string $task = 'Index';
    public string $shortTask = 'Index';
    public array $params = [];
    public array $post = [];
    public array $get  = [];

    public bool $isDebug = false;
    public array $debugProps = [];

    public array $exceptions = [];


    function __construct(private Aperture $parent, $super)
    {
        $super($this);

        $this->setPrefix();
        $this->setParams();
        $this->checkSystem();
    }


    function setPrefix()
    {
        $request_uri = $_SERVER['REQUEST_URI'];
        $pattern = "/{$this->parent->prefix}\/([\w_,\/]+)?\??/";

        if (preg_match($pattern, $request_uri, $matches)) {
            if (isset($matches[1])) {
                $this->task = str_replace('/', '\\\\', $matches[1]);
                $this->shortTask = array_slice(explode('\\', $this->task), -1)[0];
            }
        }

        
        $post = file_get_contents('php://input');
        if ($post)
            $this->post = json_decode($post, true);
        else
            $this->post = $_POST;

        $this->get = $_GET;
    }


    private function setParams()
    {
        if (!empty($this->post))
            return $this->params = $this->post;

        if (!empty($this->get))
            return $this->params = $this->get;
    }


    function getParamsFor($class, $method)
    {
        $params = $this->getParams($class, $method);
        $this->params = $params;
        return $this->params;
    }


    private function getParams($class, $method): array
    {
        if (!empty($this->post))
            return $this->post;

        if (empty($this->get))
            return [];

        $ref = new ReflectionMethod($class, $method);
        $result = [];
        foreach ($ref->getParameters() as $methodParametrName => $methodParametr) {
            if ($methodParametr->isVariadic())
                return $this->get;

            $key = $methodParametr->name;


            $methodParametrType = $methodParametr->getType();
            if (!$methodParametrType) {
                $result[$key] = $methodParametrType[$key];
                continue;
            }

            $type = $methodParametr->getName();
            $bNull = $methodParametr->allowsNull();

            if (!isset($this->get[$key])) {
                if (!$bNull)
                    throw new \Exception("$key param not found for $method", 1);

                $result[$key] = null;
                continue;
            }

            switch ($type) {
                case 'int':
                    $result[$key] = (int)$this->get[$key];
                    break;
                case 'float':
                    $result[$key] = (float)$this->get[$key];
                    break;
                case 'bool':
                    $result[$key] = (bool)$this->get[$key];
                    break;
                default:
                    $result[$key] = $this->get[$key];
            }
        }

        return $result;
    }


    function debugClear()
    {
        $this->debugProps = [];
    }

    function debugWrite(string $key, $value)
    {
        $this->debugProps[$key] = $value;
    }

    function debugRead(string $key)
    {
        if (!isset($this->debugProps[$key]))
            return null;

        return $this->debugProps[$key];
    }



    function exception(\Throwable $exception, string $method)
    {
        $this->exceptions[] = [
            'message' => $exception->getMessage(),
            'file' => $exception->getFile() . ':' . $exception->getLine(),
            'task' => $method,
        ];
    }


    private function checkSystem()
    {
        switch ($this->task) {
            case '_':
                $this->mergeController->handle($this->parent);
                break;

            case '__doc__':
                $mask = new Mask($this->getTokenMask(), $this->parent->namespace);
                $this->isDebug = true;

                $docs = new Doc($this->parent->routes, $this->parent->namespace);
                $docs->build($mask, $this->parent);
                $this->parent->proxyController->useDoc($docs);
                
                $this->parent->exit(json_encode($docs->getScheme()));

            case '__ApertureTask__':
                $this->getTokenMask();
                ['task' => $task, 'data' => $data] = $this->params;

                try {
                    $result = $this->createTask($this->parent, $task, $data);
                } catch (\Throwable $th) {
                    $result = [
                        'error' => [
                            'message' => $th->getMessage(),
                            'code' => $th->getCode(),
                        ],
                    ];
                }

                exit(json_encode($result));
        }
    }


    private function getTokenMask(): string | array
    {
        ['token' => $token] = ['token' => null, ...$this->params];
        $mask = $this->parent->verificateToken($token);
        if (!$mask) {
            http_response_code(401);
            throw new \Exception("Invalid token", 401);
        }

        return $mask === true ? '' : $mask;
    }


    
    public ?string $requestKey = null;
    function setСacheKeyIfExistsExit(string $key) {
        $this->requestKey = $key;

        if ($this->parent->cashe && $this->parent->cashe->exists($key))
            $this->parent->useCashe($this->parent->cashe->getValue($key));
    }


    function checkCashe(string $result): string {
        if ($this->requestKey && $this->parent->cashe) {
            $this->parent->cashe->setValue($this->requestKey, $result);
        }

        return $result;
    }
}
