<?php

namespace SDKBuilder\Dynamic;

use SDKBuilder\Exception\DynamicException;

class DynamicStorage
{
    private $dynamics = array();
    /**
     * @param string $name
     * @return mixed|null
     */
    public function getDynamic(string $name)
    {
        if (!$this->hasDynamic($name)) {
            return null;
        }

        return $this->dynamics[$name];
    }
    /**
     * @param array $dynamics
     * @param bool $includeEmpty
     * @return array
     */
    public function getDynamicsInBulk(array $dynamics, $includeEmpty = true) : array
    {
        $found = array();
        foreach ($dynamics as $dynamic) {
            if ($this->hasDynamic($dynamic)) {
                $foundDynamic = $this->getDynamic($dynamic);

                if ($includeEmpty === false) {
                    $found[] = $foundDynamic;

                    continue;
                }

                if ($foundDynamic['value'] !== null) {
                    $found[] = $foundDynamic;
                }
            }
        }

        return $found;
    }

    /**
     * @param string $dynamicName
     * @return bool
     */
    public function isDynamicInRequest(string $dynamicName) : bool
    {
        $dynamic = $this->getDynamic($dynamicName);
        if ($dynamic !== null) {
            return $dynamic['value'] !== null;
        }

        return false;
    }
    /**
     * @param string $name
     * @return bool
     */
    public function hasDynamic(string $name) : bool
    {
        return array_key_exists($name, $this->dynamics);
    }
    /**
     * @param array $configuration
     * @return string
     * @throws DynamicException
     */
    public function addDynamic(array $configuration)
    {
        $this->validateDynamic($configuration);

        $dynamicName = $configuration['name'];

        if (!$this->hasDynamic($dynamicName)) {
            $this->dynamics[$dynamicName] = $configuration;
        }
    }
    /**
     * @param string $name
     * @return bool
     */
    public function removeDynamic(string $name) : bool
    {
        if (!$this->hasDynamic($name)) {
            return false;
        }

        unset($this->dynamics[$name]);

        return true;
    }
    /**
     * @param string $name
     * @param $anonymousClass
     * @return bool
     */
    public function updateDynamicValidator(string $name, $validator) : bool
    {
        if ($this->hasDynamic($name)) {
            $this->dynamics[$name]['object'] = $validator;

            return true;
        }

        return false;
    }
    /**
     * @param string $name
     * @param array $value
     * @throws DynamicException
     */
    public function updateDynamicValue(string $name, array $value)
    {
        if (!$this->hasDynamic($name)) {
            throw new DynamicException('Item filter '.$name.' does not exist');
        }

        $this->dynamics[$name]['value'] = $value;
    }

    /**
     * @param string $name
     * @return DynamicInterface
     * @throws DynamicException
     */
    public function getDynamicInstance(string $name) : DynamicInterface
    {
        if (!$this->hasDynamic($name)) {
            throw new DynamicException('Dynamic \''.$name.'\' does not exist');
        }

        if (!$this->dynamics[$name]['object'] instanceof DynamicInterface) {
            $dynamicClass = $this->dynamics[$name]['object'];
            $dynamicValue = $this->dynamics[$name]['value'];

            $configuration = array(
                'multiple_values' => $this->dynamics[$name]['multiple_values'],
                'date_time' => $this->dynamics[$name]['date_time'],
            );

            $this->dynamics[$name]['object'] = new $dynamicClass($name, $dynamicValue, $configuration);

            return $this->dynamics[$name]['object'];
        }

        return $this->dynamics[$name]['object'];
    }

    /**
     * @param mixed $toExclude
     * @return array
     */
    public function filterAddedDynamics($toExclude = array()) : array
    {
        $filtered = array();

        foreach ($this->dynamics as $name => $dynamic) {
            if (in_array($name, $toExclude) === false) {
                if ($dynamic['value'] !== null) {
                    $filtered[$name] = $dynamic;
                }
            }
        }

        return $filtered;
    }
    /**
     * @return int
     */
    public function count() : int
    {
        return count($this->dynamics);
    }
    /**
     * @return \ArrayIterator
     */
    public function getIterator() : \ArrayIterator
    {
        return new \ArrayIterator();
    }

    private function validateDynamic(array $configuration)
    {
        $allowedKeys = array('object', 'value', 'multiple_values', 'date_time', 'name');

        if (!array_key_exists('name', $configuration)) {
            throw new DynamicException('Invalid configuration. \'name\' dynamic configuration missing');
        }

        foreach ($allowedKeys as $validKey) {
            if (!array_key_exists($validKey, $configuration)) {
                throw new DynamicException('Invalid dynamic. Missing dynamic configuration \''.$validKey.'\' for dynamic with name \''.$configuration['name'].'\'');
            }

            if ($validKey === 'object' or $validKey === 'name') {
                if (!is_string($configuration[$validKey])) {
                    throw new DynamicException('Invalid dynamic. \'name\' and \'object\' dynamic configuration value have to be a string. '.gettype($configuration[$validKey]).' given for \''.$validKey.'\'');
                }
            }

            if ($validKey === 'object') {
                if (!class_exists($configuration[$validKey])) {
                    throw new DynamicException('Invalid dynamic. Provided class \''.$configuration[$validKey].'\' does not exist');
                }
            }
        }
    }
}