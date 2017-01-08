<?php

namespace SDKBuilder\Event;

use SDKBuilder\Request\RequestInterface;
use SDKBuilder\SDK\SDKInterface;

class SendRequestEvent
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