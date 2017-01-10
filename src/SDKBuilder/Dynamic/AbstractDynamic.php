<?php

namespace SDKBuilder\Dynamic;

use SDKBuilder\Processor\UrlifyInterface;

abstract class AbstractDynamic implements DynamicInterface, UrlifyInterface
{
    /**
     * @var string $name
     */
    protected $name;
    /**
     * @var array $dynamicValue
     */
    protected $dynamicValue;
    /**
     * @var array $configuration
     */
    protected $configuration;
    /**
     * @var array $exceptionMessages
     */
    protected $exceptionMessages = array();
    /**
     * @param string $name
     * @param array $dynamicValue
     * @param array $configuration
     */
    public function __construct(string $name, array $dynamicValue, array $configuration)
    {
        $this->name = $name;
        $this->dynamicValue = $dynamicValue;
        $this->configuration = $configuration;
    }
    /**
     * @return string
     */
    public function __toString() : string
    {
        if (empty($this->exceptionMessages)) {
            return 'There are no exception messages for '.$this->name.' but there should be.';
        }

        return 'Errors: '.implode(', ', $this->exceptionMessages);
    }

    protected function genericValidation(array $value, $count = null) : bool
    {
        if (empty($value)) {
            $this->exceptionMessages[] = 'Argument for item filter '.$this->name.' cannot be empty.';

            return false;
        }

        if ($count !== null) {
            if (count($value) > $count) {
                $this->exceptionMessages[] = $this->name.' can receive an array argument with only '.$count.' value(s)';

                return false;
            }
        }

        return true;
    }

    protected function checkBoolean($value) : bool
    {
        if (!is_bool($value)) {
            $this->exceptionMessages[] = $this->name.' can only accept true or false boolean values';

            return false;
        }

        return true;
    }
}