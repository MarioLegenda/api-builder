<?php

namespace SDKBuilder\Request\Method;

use SDKBuilder\Exception\MethodParametersException;

class MethodParameters
{
    private $validMethodsParameter;
    /**
     * @var array $methodParameters
     */
    private $methodParameters;
    /**
     * MethodParameters constructor.
     * @param array $methodParameters
     */
    public function __construct(array $methodParameters)
    {
        $this->validMethodsParameter = $methodParameters['valid_methods'];

        unset($methodParameters['valid_methods']);

        foreach ($methodParameters['metadata'] as $configName => $methodParameter) {
            $this->methodParameters[$configName] = new Method($configName, $methodParameter);
        }
    }
    /**
     * @param Method $method
     * @return MethodParameters
     */
    public function addMethod(Method $method) : MethodParameters
    {
        $this->methodParameters[$method->getName()] = $method;

        return $this;
    }
    /**
     * @param string $methodName
     * @return mixed
     * @throws MethodParametersException
     */
    public function getMethod(string $methodName)
    {
        if (!$this->hasMethod($methodName)) {
            throw new MethodParametersException('API method \''.$methodName.'\' does not exist. Register it under \'methods\' configuration section');
        }

        foreach ($this->methodParameters as $methodParameter) {
            if ($methodParameter->getName() === $methodName or $methodParameter->getClassName() === $methodName) {
                return $methodParameter;
            }
        }
    }
    /**
     * @param string $methodName
     * @return bool
     */
    public function hasMethod(string $methodName) : bool
    {
        foreach ($this->methodParameters as $methodParameter) {
            if ($methodParameter->getName() === $methodName or $methodParameter->getClassName() === $methodName) {
                return true;
            }
        }

        return false;
    }
    /**
     * @return string
     */
    public function getValidMethodsParameter() : string
    {
        return $this->validMethodsParameter;
    }
}