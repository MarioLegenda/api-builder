<?php

namespace SDKBuilder\Request;

use SDKBuilder\Exception\RequestParametersException;

class Type
{
    /**
     * @var array $type
     */
    private $type;
    /**
     * Type constructor.
     * @param array $type
     * @param array $validType
     * @throws RequestParametersException
     */
    public function __construct(array $type, array $validType)
    {
        foreach ($type as $t) {
            if (in_array($t, $validType) === false) {
                throw new RequestParametersException('\'type\' can be \''.implode(', ', $validType).'\'. Given \''.implode(', ', $type));
            }
        }

        $this->type = $type;
    }
    /**
     * @return bool
     */
    public function isRequired() : bool
    {
        return in_array('required', $this->type) === true;
    }
    /**
     * @return bool
     */
    public function isOptional() : bool
    {
        return in_array('optional', $this->type) === true;
    }
    /**
     * @return bool
     */
    public function isStandalone() : bool
    {
        return in_array('standalone', $this->type) === true;
    }
    /**
     * @return bool
     */
    public function isInjectable() : bool
    {
        return in_array('injectable', $this->type);
    }
}