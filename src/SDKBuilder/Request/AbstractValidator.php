<?php

namespace SDKBuilder\Request;

abstract class AbstractValidator
{
    /**
     * @void
     */
    abstract function validate() : void;

    /**
     * @var AbstractRequest $request
     */
    private $request;
    /**
     * @var array $errors
     */
    private $errors = array();
    /**
     * AbstractValidator constructor.
     * @param AbstractRequest $request
     */
    public function __construct(AbstractRequest $request)
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
     * @return AbstractRequest
     */
    public function getRequest() : AbstractRequest
    {
        return $this->request;
    }
}