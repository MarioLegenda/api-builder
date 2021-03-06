<?php

namespace SDKBuilder\Event;

use SDKBuilder\Request\RequestInterface;
use Symfony\Component\EventDispatcher\Event;

class PostProcessRequestEvent extends Event
{
    /**
     * @var RequestInterface $request
     */
    private $request;
    /**
     * PreRequestEvent constructor.
     * @param RequestInterface $request
     */
    public function __construct(RequestInterface $request)
    {
        $this->request = $request;
    }
    /**
     * @return RequestInterface
     */
    public function getRequest() : RequestInterface
    {
        return $this->request;
    }
}