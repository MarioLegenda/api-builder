<?php

namespace SDKBuilder\Request;

abstract class AbstractValidator
{
    /**
     * @void
     */
    abstract function validate() : void;

    /**
     * @var RequestInterface $request
     */
    private $request;
    /**
     * @var array $errors
     */
    private $errors = array();
    /**
     * AbstractValidator constructor.
     * @param RequestInterface $request
     */
    public function __construct(RequestInterface $request)
    {
        $this->request = $request;
    }
    /**
     * @return array
     */
    public function getErrors() : array
    {
        return $this->errors;
    }
    /**
     * @param string $errorKey
     * @return bool
     */
    public function hasError(string $errorKey) : bool
    {
        return array_key_exists($errorKey, $this->errors);
    }
    /**
     * @param string $errorKey
     * @param array $error
     * @return AbstractValidator
     */
    public function addError(string $errorKey, array $error) : AbstractValidator
    {
        $this->errors[$errorKey][] = $error;

        return $this;
    }
    /**
     * @return RequestInterface
     */
    public function getRequest() : RequestInterface
    {
        return $this->request;
    }
}