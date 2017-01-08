<?php

namespace SDKBuilder\Processor;

use SDKBuilder\Request\RequestInterface;

abstract class AbstractProcessor
{
    /**
     * @var RequestInterface $request
     */
    protected $request;
    /**
     * UrlProcessor constructor.
     * @param RequestInterface $request
     */
    public function __construct(RequestInterface $request)
    {
        $this->request = $request;
    }
}