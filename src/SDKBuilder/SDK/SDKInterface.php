<?php

namespace SDKBuilder\SDK;

use SDKBuilder\Request\AbstractRequest;
use SDKBuilder\Request\Method\Method;
use SDKBuilder\Request\Parameter;

interface SDKInterface
{
    /**
     * @return void
     */
    public function send() : SDKInterface;
    /**
     * @return AbstractRequest
     */
    public function getRequest() : AbstractRequest;
    /**
     * @param Method $method
     * @return SDKInterface
     */
    public function addMethod(Method $method) : SDKInterface;
    /**
     * @param string $parameterType
     * @param Parameter $parameter
     * @return SDKInterface
     */
    public function addParameter(string $parameterType, Parameter $parameter) : SDKInterface;
    /**
     * @return string
     */
    public function getProcessedRequestString() : string;
    /**
     * @return bool
     */
    public function hasErrors() : bool;
    /**
     * @return array
     */
    public function getErrors() : array;
    /**
     * @void
     */
    public function getResponse();
    /**
     * @return SDKInterface
     */
    public function compile() : SDKInterface;
}