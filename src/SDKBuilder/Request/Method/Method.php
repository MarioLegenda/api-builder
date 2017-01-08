<?php

namespace SDKBuilder\Request\Method;

use SDKBuilder\Exception\MethodParametersException;
use SDKBuilder\Request\Parameter;

class Method
{
    /**
     * @var string $name
     */
    private $name;
    /**
     * @var string $instanceObjectString
     */
    private $instanceObjectString;
    /**
     * @var $className
     */
    private $className;
    /**
     * @var array $methods
     */
    private $methods;

    public function __construct(string $name, array $method)
    {
        $this
            ->setName($name)
            ->setInstanceObjectString($method['object'])
            ->setClassName($method['name'])
            ->setMethods($method['methods']);
    }

    /**
     * @return string
     */
    public function getName() : string
    {
        return $this->name;
    }
    /**
     * @param string $name
     * @return Method
     */
    public function setName(string $name) : Method
    {
        $this->name = $name;

        return $this;
    }
    /**
     * @return string
     */
    public function getInstanceObjectString() : string
    {
        return $this->instanceObjectString;
    }
    /**
     * @param string $instanceObjectString
     * @return Method
     */
    public function setInstanceObjectString(string $instanceObjectString) : Method
    {
        $this->instanceObjectString = $instanceObjectString;

        return $this;
    }
    /**
     * @return string
     */
    public function getClassName() : string
    {
        return $this->className;
    }
    /**
     * @param string $className
     * @return Method
     */
    public function setClassName(string $className) : Method
    {
        $this->className = $className;

        return $this;
    }
    /**
     * @return array
     */
    public function getMethods()
    {
        return $this->methods;
    }

    /**
     * @param array $methods
     * @return Method
     */
    public function setMethods(array $methods) : Method
    {
        $this->methods = $methods;

        return $this;
    }
    /**
     * @param Parameter $validMethodsParameter
     */
    public function validate(Parameter $validMethodsParameter)
    {
        if (in_array($this->getClassName(), $validMethodsParameter->getValid()) === false) {
            throw new MethodParametersException('Method with name \''.$this->getName().'\' is not registered as a valid method under \''.$validMethodsParameter->getName().'\' parameter');
        }

        if (!class_exists($this->getInstanceObjectString())) {
            throw new MethodParametersException('Class '.$this->getInstanceObjectString().' does not exist and cannot be instantiated');
        }
    }
}