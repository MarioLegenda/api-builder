<?php

namespace SDKBuilder\Request;

use SDKBuilder\Dynamic\DynamicStorage;
use SDKBuilder\Response\ResponseClient;

interface RequestInterface
{
    /**
     * @return string
     */
    public function getMethod() : string;
    /**
     * @param string $method
     * @return RequestInterface
     */
    public function setMethod(string $method) : RequestInterface;
    /**
     * @param string $name
     * @param $value
     * @return RequestInterface
     */
    public function setGlobalParameter(string $name, $value) : RequestInterface;
    /**
     * @return RequestParameters
     */
    public function getGlobalParameters() : RequestParameters;
    /**
     * @param string $name
     * @param $value
     * @return RequestInterface
     */
    public function setSpecialParameter(string $name, $value) : RequestInterface;
    /**
     * @return RequestParameters
     */
    public function getSpecialParameters() : RequestParameters;
    /**
     * @param string $request
     * @return ResponseClient
     */
    public function sendRequest(string $request) : ResponseClient;
    /**
     * @param string $dynamicName
     * @param array $dynamicValue
     * @return RequestInterface
     */
    public function addDynamic(string $dynamicName, array $dynamicValue) : RequestInterface;
    /**
     * @return DynamicStorage
     */
    public function getDynamicStorage() : DynamicStorage;
}