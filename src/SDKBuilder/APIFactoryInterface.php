<?php

namespace SDKBuilder;

use SDKBuilder\SDK\SDKInterface;

interface APIFactoryInterface
{
    /**
     * @return SDKInterface
     */
    public function create() : SDKInterface;
}