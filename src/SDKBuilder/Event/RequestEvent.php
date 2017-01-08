<?php

namespace SDKBuilder\Event;

use Symfony\Component\EventDispatcher\Event;
use SDKBuilder\Request\AbstractRequest;

class RequestEvent extends Event
{
    /**
     * @var AbstractRequest $request
     */
    private $request;
    /**
     * PreRequestEvent constructor.
     * @param AbstractRequest $request
     */
    public function __construct(AbstractRequest $request)
    {
        $this->request = $request;
    }
    /**
     * @return AbstractRequest
     */
    public function getRequest() : AbstractRequest
    {
        return $this->request;
    }
}