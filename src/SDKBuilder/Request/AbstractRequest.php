<?php

namespace SDKBuilder\Request;

use SDKBuilder\Exception\DynamicException;
use SDKBuilder\Exception\RequestException;
use SDKBuilder\Exception\RequestParametersException;
use SDKBuilder\Response\ResponseClient;
use SDKBuilder\RestoreDefaultsInterface;
use SDKBuilder\Dynamic\DynamicStorage;

abstract class AbstractRequest implements RequestInterface, RestoreDefaultsInterface
{
    /**
     * @var string $method
     */
    private $method = 'get';
    /**
     * @var RequestParameters $specialParameters
     */
    private $specialParameters;
    /**
     * @var RequestParameters $globalParameters
     */
    private $globalParameters;
    /**
     * @var mixed $client
     */
    private $client;
    /**
     * @var DynamicStorage $dynamicsStorage
     */
    protected $dynamicsStorage;
    /**
     * AbstractRequest constructor.
     * @param RequestParameters $globalParameters
     * @param RequestParameters $specialParameters
     * @param DynamicStorage $dynamicStorage
     */
    public function __construct(
        RequestParameters $globalParameters,
        RequestParameters $specialParameters,
        DynamicStorage $dynamicStorage
    )
    {
        $this->specialParameters = $specialParameters;
        $this->globalParameters = $globalParameters;
        $this->dynamicsStorage = $dynamicStorage;

        $this->restoreDefaults();

        $this->client = new RequestClient();
    }
    /**
     * @return string
     */
    public function getMethod() : string
    {
        return strtolower($this->method);
    }
    /**
     * @param string $method
     * @return RequestInterface
     * @throws RequestException
     */
    public function setMethod(string $method) : RequestInterface
    {
        $validMethods = array('get', 'post');

        if (in_array(strtolower($method), $validMethods) === false) {
            throw new RequestException('Invalid method. Valid methods are \''.implode(', ', $validMethods).'\'. \''.$method.'\' given');
        }

        $this->method = strtolower($method);

        return $this;
    }
    /**
     * @param string $name
     * @param $value
     * @return RequestInterface
     * @throws RequestParametersException
     */
    public function setGlobalParameter(string $name, $value) : RequestInterface
    {
        if (!$this->globalParameters->hasParameter($name)) {
            throw new RequestParametersException('global_parameter \''.$name.'\' does not exist');
        }

        $this->globalParameters->getParameter($name)->setValue($value);

        return $this;
    }
    /**
     * @return RequestParameters
     */
    public function getGlobalParameters() : RequestParameters
    {
        return $this->globalParameters;
    }
    /**
     * @param string $name
     * @param $value
     * @return RequestInterface
     * @throws RequestParametersException
     */
    public function setSpecialParameter(string $name, $value) : RequestInterface
    {
        if (!$this->specialParameters->hasParameter($name)) {
            throw new RequestParametersException('special_parameter \''.$name.'\' does not exist');
        }

        $this->specialParameters->getParameter($name)->setValue($value);

        return $this;
    }
    /**
     * @return RequestParameters
     */
    public function getSpecialParameters() : RequestParameters
    {
        return $this->specialParameters;
    }
    /**
     * @param string $dynamicName
     * @param array $dynamicValue
     * @return RequestInterface
     * @throws DynamicException
     */
    public function addDynamic(string $dynamicName, array $dynamicValue) : RequestInterface
    {
        if (!$this->dynamicsStorage->hasDynamic($dynamicName)) {
            throw new DynamicException('Dynamic \''.$dynamicName.'\' does not exists. Use '.RequestInterface::class.'::addDynamic() to add a new dynamic');
        }

        $this->dynamicsStorage->updateDynamicValue($dynamicName, $dynamicValue);

        return $this;
    }
    /**
     * @return DynamicStorage
     */
    public function getDynamicStorage() : DynamicStorage
    {
        return $this->dynamicsStorage;
    }
    /**
     * @void
     */
    public function restoreDefaults() : void
    {
        $this->globalParameters->restoreDefaults();
        $this->specialParameters->restoreDefaults();
    }
    /**
     * @param string $request
     * @return mixed
     */
    public function sendRequest(string $request) : ResponseClient
    {
        return $this->client
            ->setUri($request)
            ->setMethod($this->getMethod())
            ->send()
            ->getResponse();
    }
}