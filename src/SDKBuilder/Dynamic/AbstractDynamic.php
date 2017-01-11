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
}