<?php

namespace SDKBuilder\Request;

use SDKBuilder\ValidatorInterface;

class ValidatorsProcessor
{
    /**
     * @var array $errors
     */
    private $errors = array();
    /**
     * @var array $validators
     */
    private $validators = array();
    /**
     * @param ValidatorInterface $validator
     * @return ValidatorsProcessor
     */
    public function addValidator(ValidatorInterface $validator) : ValidatorsProcessor
    {
        $this->validators[] = $validator;

        return $this;
    }
    /**
     * @return ValidatorsProcessor
     */
    public function validate() : ValidatorsProcessor
    {
        foreach ($this->validators as $validator) {
            $validator->validate();

            $this->errors[] = $validator->getErrors();
        }

        return $this;
    }
    /**
     * @return bool
     */
    public function hasErrors() : bool
    {
        return !empty($this->errors);
    }
    /**
     * @return array
     */
    public function getErrors() : array
    {
        return $this->errors;
    }
}