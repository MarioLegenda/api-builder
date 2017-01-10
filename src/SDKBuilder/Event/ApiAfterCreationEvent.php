<?php

namespace SDKBuilder\Event;

use SDKBuilder\SDK\SDKInterface;
use Symfony\Component\EventDispatcher\Event;

class ApiAfterCreationEvent extends Event
{
    /**
     * @var SDKInterface $api
     */
    private $api;
    /**
     * ApiAfterCreationEvent constructor.
     * @param SDKInterface $api
     */
    public function __construct(SDKInterface $api)
    {
        $this->api = $api;
    }
    /**
     * @return SDKInterface
     */
    public function getApi() : SDKInterface
    {
        return $this->api;
    }
}