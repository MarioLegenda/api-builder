<?php

namespace SDKBuilder\Processor;

interface UrlifyInterface
{
    /**
     * @param int $counter
     * @return string
     */
    public function urlify(int $counter) : string;
}