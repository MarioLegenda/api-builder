<?php

namespace SDKBuilder\Request;

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
     * @param AbstractValidator $validator
     */
    public function addValidator(AbstractValidator $validator)
    {
        $this->validators[] = $validator;
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