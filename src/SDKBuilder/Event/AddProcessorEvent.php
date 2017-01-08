<?php

namespace SDKBuilder\Event;

use SDKBuilder\Processor\Factory\ProcessorFactory;
use SDKBuilder\Request\AbstractRequest;
use Symfony\Component\EventDispatcher\Event;

class AddProcessorEvent extends Event
{
    /**
     * @var AbstractRequest $request
     */
    private $request;
    /**
     * @var ProcessorFactory $processorFactory
     */
    private $processorFactory;
    /**
     * AddProcessorEvent constructor.
     * @param ProcessorFactory $processorFactory
     * @param AbstractRequest $request
     */
    public function __construct(ProcessorFactory $processorFactory, AbstractRequest $request)
    {
        $this->processorFactory = $processorFactory;
        $this->request = $request;
    }
    /**
     * @return ProcessorFactory
     */
    public function getProcessorFactory()
    {
        return $this->processorFactory;
    }
    /**
     * @return AbstractRequest
     */
    public function getRequest() : AbstractRequest
    {
        return $this->request;
    }
}