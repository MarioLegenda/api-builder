<?php

namespace SDKBuilder\Processor;

use SDKBuilder\Request\AbstractRequest;

abstract class AbstractProcessor
{
    /**
     * @var AbstractRequest $request
     */
    protected $request;
    /**
     * UrlProcessor constructor.
     * @param AbstractRequest $request
     */
    public function __construct(AbstractRequest $request)
    {
        $this->request = $request;
    }
}