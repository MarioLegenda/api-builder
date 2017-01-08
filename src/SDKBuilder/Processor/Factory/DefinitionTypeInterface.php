<?php

namespace SDKBuilder\Processor\Factory;

interface DefinitionTypeInterface
{
    /**
     * @param array $definitions
     * @return DefinitionTypeInterface
     */
    public function addDefinitions(array $definitions) : DefinitionTypeInterface;
    /**
     * @return DefinitionTypeInterface
     */
    public function process() : DefinitionTypeInterface;
    /**
     * @return mixed
     */
    public function getProcessed();
}