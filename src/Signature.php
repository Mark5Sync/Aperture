<?php

namespace Aperture;

use Aperture\_markers\api;
use Aperture\_markers\main;
use Aperture\_markers\preload;
use Aperture\_markers\proxy;
use Aperture\cashe\Cashe;
use Aperture\proxy\ProxyController;

abstract class Signature extends ApertureConfig
{
    use api;
    use main;
    use proxy;
    use preload;

    protected Route $task;
    public ?Cashe $cashe = null;

    final function __construct()
    {
        header('Content-Type: application/json');
        ini_set('display_errors', 0);

        if ($this->casheClass)
            $this->cashe = new $this->casheClass;

        $strResult = json_encode($this->runTask());

        if ($strResult === false)
            $strResult = json_encode(['error' => $this->getJsonError()]);

        $this->print($strResult);
    }


    function __destruct()
    {
        $this->ob->clear();

        if ($logs = $this->ob->getLog())
            $this->onLog($logs);
    }


    function runTask()
    {
        try {
            $class = stripslashes($this->namespace . "\\" . $this->request->task);
            $this->proxyController->checkMask(stripslashes($this->request->task));
        } catch (\Throwable $th) {
            http_response_code(404);

            return ['error' => new Error($th->getMessage(), $th->getCode())];
        }


        // try {
        //     $this->proxyController->checkMask($class);
        // } catch (\Throwable $th) {
        //     $result['error'] = new Error($th->getMessage(), $th->getCode());
        //     http_response_code(400);
        //     return $result;
        // }

        $this->ob->start();

        try {
            $this->task = new $class;
        } catch (\Throwable $th) {
            $this->ob->clear();
            http_response_code(404);

            return ['error' => new Error("{$this->request->task} - {$th->getMessage()}", 404)];
        }

        try {
            $params = $this->request->params;
            $result['data'] = $this->handler->run($this->task, $params);
        } catch (\Throwable $th) {
            $this->ob->clear();
            $result['error'] = new Error($th->getMessage(), $th->getCode());
            $this->onError($th);
            http_response_code(400);
        }

        if ($this->redirect->to)
            $result['redirect'] = $this->redirect->to;

        if (!empty($this->request->exceptions))
            $result['exceptions'] = $this->request->exceptions;

        if ($preload = $this->preload->get())
            $result['preload'] = $preload;

        $this->ob->clear();

        return $result;
    }


    public function useCashe(string $json) {
        $this->ob->clear();
        exit($json);
    }

    private function print(string $json)
    {
        echo $this->request->checkCashe($json);
        $this->gen->finish();
    }


    protected function onInit(string $task) {}



    protected function onError(\Throwable $exception) {}



    protected function onLog(array $logs)
    {
        $this->task->onLog($logs);
    }


    public function proxys(ProxyController $proxy): void {}


    protected function onResult($result)
    {
        if ($this->pagination->use)
            return [
                'content' => $result,
                'pagination' => $this->pagination,
            ];

        return $result;
    }



    protected function getJsonError()
    {
        switch (json_last_error()) {
            case JSON_ERROR_NONE:
                return 'json_encode - Ошибок нет';
            case JSON_ERROR_DEPTH:
                return 'json_encode - Достигнута максимальная глубина стека';
            case JSON_ERROR_STATE_MISMATCH:
                return 'json_encode - Некорректные разряды или несоответствие режимов';
            case JSON_ERROR_CTRL_CHAR:
                return 'json_encode - Некорректный управляющий символ';
            case JSON_ERROR_SYNTAX:
                return 'json_encode - Синтаксическая ошибка, некорректный JSON';
            case JSON_ERROR_UTF8:
                return 'json_encode - Некорректные символы UTF-8, возможно неверно закодирован';
            default:
                return 'json_encode - Неизвестная ошибка';
        }
    }


    function exit($content)
    {
        ob_end_flush();
        exit($content);
    }
}
