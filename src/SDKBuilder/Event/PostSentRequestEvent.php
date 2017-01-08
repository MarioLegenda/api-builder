<?php

namespace SDKBuilder\Event;

use SDKBuilder\Request\RequestInterface;
use SDKBuilder\SDK\SDKInterface;
use Symfony\Component\EventDispatcher\Event;

class PostSentRequestEvent extends Event
{
    /**
     * @var RequestInterface $request
     */
    private $request;
    /**
     * @var SDKInterface $api
     */
    private $api;

    public function __construct(SDKInterface $api, RequestInterface $request)
    {
        $this->api = $api;
        $this->request = $request;
    }
    /**
     * @return RequestInterface
     */
    public function getRequest() : RequestInterface
    {
        return $this->request;
    }
    /**
     * @return SDKInterface
     */
    public function getApi() : SDKInterface
    {
        return $this->api;
    }
}