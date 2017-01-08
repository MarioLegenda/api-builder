<?php

namespace SDKBuilder\Exception;

abstract class AbstractException extends \Exception
{
    /**
     * AbstractException constructor.
     * @param string $message
     */
    public function __construct($message)
    {
        $this->message = $message;
    }
}