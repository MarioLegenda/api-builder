<?php

namespace SDKBuilder\Request;

use SDKBuilder\Exception\RequestException;
use SDKBuilder\Exception\RequestParametersException;

class Parameter
{
    /**
     * @var bool $enable
     */
    private $enable = false;
    /**
     * @var string $representation
     */
    private $representation;
    /**
     * @var string $name
     */
    private $name = null;
    /**
     * @var Type $type
     */
    private $type = null;
    /**
     * @var string $value
     */
    private $value = null;
    /**
     * @var bool $deprecated
     */
    private $deprecated = false;
    /**
     * @var bool $obsolete
     */
    private $obsolete = false;
    /**
     * @var bool $throwExceptionMessageIfDeprecated
     */
    private $throwExceptionMessageIfDeprecated;
    /**
     * @var string $errorMessage
     */
    private $errorMessage;
    /**
     * @var array $valid
     */
    private $valid = array();
    /**
     * Parameter constructor.
     * @param array $parameter
     * @param string $parameterName
     *
     * A Parameters has to have a name, type, valid, value and synonyms field
     */
    public function __construct(string $parameterName, array $parameter = null)
    {
        if (empty($parameter)) {
            throw new RequestParametersException('Configuration parameters, weather it is global or special, cannot be empty');
        }

        $this
            ->setName($parameterName)
            ->setValid($parameter['valid'])
            ->setValue($parameter['value'])
            ->setType(new Type($parameter['type'], array('required', 'optional', 'standalone', 'injectable')));

        if (!$this->getType()->isStandalone()) {
            $representation = $parameter['representation'];

            if (empty($representation)) {
                throw new RequestParametersException('If \'type\' is not \'standalone\', \'representation\' should not be null');
            }

            $this->setRepresentation($representation);
        }

        $this->deprecated = (array_key_exists('deprecated', $parameter) and $parameter['deprecated'] === true) ? true : false;

        $this->obsolete = (array_key_exists('obsolete', $parameter) and $parameter['obsolete'] === true) ? true : false;

        $this->throwExceptionMessageIfDeprecated = (array_key_exists('throws_exception_if_deprecated', $parameter) and $parameter['throws_exception_if_deprecated'] === true) ? true : false;

        if (array_key_exists('error_message', $parameter)) {
            $this->setErrorMessage($parameter['error_message']);
        }
    }
    /**
     * @void
     */
    public function enable()
    {
        $this->enable = true;
    }
    /**
     * @void
     */
    public function disable()
    {
        $this->enable = false;
    }
    /**
     * @return bool
     */
    public function isEnabled() : bool
    {
        return $this->enable;
    }
    /**
     * @param string $representation
     * @return Parameter
     */
    public function setRepresentation(string $representation) : Parameter
    {
        $this->representation = $representation;

        return $this;
    }
    /**
     * @return string
     */
    public function getRepresentation()
    {
        return $this->representation;
    }
    /**
     * @return bool
     */
    public function shouldThrowExceptionIfDeprecated() : bool
    {
        return $this->throwExceptionMessageIfDeprecated;
    }
    /**
     * @param string $errorMessage
     * @return Parameter
     */
    public function setErrorMessage(string $errorMessage) : Parameter
    {
        $this->errorMessage = $errorMessage;

        return $this;
    }
    /**
     * @return string
     */
    public function getErrorMessage() : string
    {
        return $this->errorMessage;
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
     * @return Parameter
     */
    public function setName(string $name) : Parameter
    {
        $this->name = $name;

        return $this;
    }
    /**
     * @return Type
     */
    public function getType() : Type
    {
        return $this->type;
    }
    /**
     * @param Type $type
     * @return Parameter
     */
    public function setType(Type $type) : Parameter
    {
        $this->type = $type;

        return $this;
    }
    /**
     * @return bool
     */
    public function isObsolete() : bool
    {
        return $this->obsolete;
    }
    /**
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param string $value
     * @return Parameter
     */
    public function setValue($value) : Parameter
    {
        $this->value = $value;

        return $this;
    }

    /**
     * @return boolean
     */
    public function isDeprecated() : bool
    {
        return $this->deprecated;
    }

    /**
     * @param bool $deprecated
     * @return Parameter
     */
    public function setDeprecated() : Parameter
    {
        $this->deprecated = true;

        return $this;
    }

    /**
     * @return Parameter
     */
    public function removeDeprecated() : Parameter
    {
        $this->deprecated = false;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getValid()
    {
        return $this->valid;
    }

    /**
     * @param array $valid
     * @return Parameter
     */
    public function setValid($valid) : Parameter
    {
        $this->valid = $valid;

        return $this;
    }
    /**
     * @param string $value
     * @return bool
     */
    public function isValid(string $value) : bool
    {
        if (empty($this->valid)) {
            return true;
        }

        return in_array($value, $this->valid);
    }
    /**
     * @return mixed
     * @throws RequestException
     */
    public function validateParameter()
    {
        $errorMessages = array();

        if ($this->isObsolete()) {
            throw new RequestParametersException(sprintf($this->errorMessage, $this->getName(), $this->getRepresentation()));
        }

        if ($this->isDeprecated() and $this->shouldThrowExceptionIfDeprecated()) {
            throw new RequestParametersException(sprintf($this->errorMessage, $this->getName(), $this->getRepresentation()));
        }

        if ($this->isDeprecated()) {
            $errorMessages['deprecated'] = sprintf($this->errorMessage, $this->getName(), $this->getRepresentation());

            return $errorMessages;
        }

        if ($this->getType()->isRequired() and $this->getValue() === null) {
            throw new RequestParametersException('If \'type\' is required, then it\'s \'value\' should be present in the configuration, not at runtime for configuration \''.$this->getName().'\'');
        }

        if (!empty($this->getValid()) and !$this->getType()->isOptional()) {
            if (in_array($this->getValue(), $this->getValid()) === false) {
                throw new RequestParametersException('Invalid value for '.$this->getName().'. Valid values for '.$this->getName().' are '.implode(', ', $this->getValid()).'. '.$this->getValue().' given');
            }
        }
    }
}