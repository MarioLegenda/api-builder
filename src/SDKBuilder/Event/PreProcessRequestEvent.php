<?php

namespace SDKBuilder\Event;

use SDKBuilder\Request\AbstractRequest;
use Symfony\Component\EventDispatcher\Event;

class PreProcessRequestEvent extends Event
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