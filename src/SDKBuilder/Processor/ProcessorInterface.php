<?php

namespace SDKBuilder\Processor;

interface ProcessorInterface
{
    /**
     * @return ProcessorInterface
     */
    public function process() : ProcessorInterface;
    /**
     * @return string
     */
    public function getProcessed() : string;
}