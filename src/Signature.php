<?php

namespace Aperture;

use Aperture\_markers\api;

abstract class Signature extends ApertureConfig
{
    use api;


    function runTask()
    {
        $class = stripslashes($this->namespace . "\\" . $this->request->task);

        try {
            $task = new $class;
        } catch (\Throwable $th) {
            http_response_code(404);

            return [
                'message' => "{$this->request->task} not found",
                'code' => 404,
            ];
        }

        try {
            $result['data'] = $task(...$this->request->params);
        } catch (\Throwable $th) {
            $result['error'] = [
                'message' => $th->getMessage(),
                'code' => $th->getCode(),
            ];
            $this->onError($th);
            http_response_code(400);
        }

        if ($this->redirect->to)
            $result['redirect'] = $this->redirect->to;

        if (!empty($this->request->exceptions))
            $result['exceptions'] = $this->request->exceptions;

        return $result;
    }



    protected function onInit(string $task)
    {
    }



    protected function onError(\Throwable $exception)
    {
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
}
