<?php

namespace console\models;

class LogService
{
    public function sendError(\Exception $message)
    {
        // Предположим, что тут идет отправка ошибки например в телегу
    }
}