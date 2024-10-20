<?php

namespace Aperture;

use Aperture\_markers\api;
use Aperture\_markers\main;

abstract class Signature extends ApertureConfig
{
    use api;
    use main;

    protected Route $task;

    final function __construct()
    {
        header('Content-Type: application/json');
        ini_set('display_errors', 0);

        $this->ob->start();

        $strResult = json_encode($this->runTask());

        $this->ob->clear();

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
        } catch (\Throwable $th) {
            http_response_code(404);

            return ['error' => new Error($th->getMessage(), $th->getCode())];
        }

        try {
            $this->task = new $class;
        } catch (\Throwable $th) {
            http_response_code(404);

            return ['error' => new Error("{$this->request->task} - {$th->getMessage()}", 404)];
        }

        try {
            $params = $this->request->params;
            $result['data'] = $this->pagination->wrapResult(
                $this->gen->handle(($this->task)(...$params))
            );
        } catch (\Throwable $th) {
            $result['error'] = new Error($th->getMessage(), $th->getCode());
            $this->onError($th);
            http_response_code(400);
        }

        if ($this->redirect->to)
            $result['redirect'] = $this->redirect->to;

        if (!empty($this->request->exceptions))
            $result['exceptions'] = $this->request->exceptions;

        return $result;
    }


    private function print(string $json)
    {
        echo $json;
        $this->gen->finish();
    }


    protected function onInit(string $task) {}



    protected function onError(\Throwable $exception) {}



    protected function onLog(array $logs)
    {
        $this->task->onLog($logs);
    }



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
