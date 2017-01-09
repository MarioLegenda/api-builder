<?php

namespace SDKBuilder;

use SDKBuilder\Request\RequestInterface;

interface ValidatorInterface
{
    /**
     * @return array
     */
    public function getErrors() : array;
    /**
     * @param string $errorKey
     * @return bool
     */
    public function hasError(string $errorKey) : bool;
    /**
     * @param string $errorKey
     * @param array $error
     * @return ValidatorInterface
     */
    public function addError(string $errorKey, array $error) : ValidatorInterface;
    /**
     * @return RequestInterface
     */
    public function getRequest() : RequestInterface;
}