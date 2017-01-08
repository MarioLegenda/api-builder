<?php

namespace SDKBuilder\Event;

use SDKBuilder\Processor\Factory\ProcessorFactory;
use SDKBuilder\Request\RequestInterface;
use Symfony\Component\EventDispatcher\Event;

class AddProcessorEvent extends Event
{
    /**
     * @var RequestInterface $request
     */
    private $request;
    /**
     * @var ProcessorFactory $processorFactory
     */
    private $processorFactory;
    /**
     * AddProcessorEvent constructor.
     * @param ProcessorFactory $processorFactory
     * @param RequestInterface $request
     */
    public function __construct(ProcessorFactory $processorFactory, RequestInterface $request)
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
     * @return RequestInterface
     */
    public function getRequest() : RequestInterface
    {
        return $this->request;
    }
}