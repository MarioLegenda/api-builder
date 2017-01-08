<?php

namespace SDKBuilder\Common;

class Logger
{
    public static function log(string $message)
    {
        file_put_contents(__DIR__.'/../log/requests.log', $message."\r\n", FILE_APPEND);
    }
}