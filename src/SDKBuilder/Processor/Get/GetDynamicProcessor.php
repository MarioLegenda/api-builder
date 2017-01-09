<?php

namespace SDKBuilder\Processor\Get;

use SDKBuilder\Dynamic\DynamicStorage;
use SDKBuilder\Processor\AbstractProcessor;
use SDKBuilder\Processor\ProcessorInterface;
use SDKBuilder\Request\RequestInterface;
use SDKBuilder\Processor\UrlifyInterface;

class GetDynamicProcessor extends AbstractProcessor implements ProcessorInterface
{
    /**
     * @var string $processed
     */
    private $processed = '';
    /**
     * @var DynamicStorage $dynamicStorage
     */
    private $dynamicStorage;
    /**
     * GetDynamicProcessor constructor.
     * @param RequestInterface $request
     * @param DynamicStorage $dynamicStorage
     */
    public function __construct(RequestInterface $request, DynamicStorage $dynamicStorage)
    {
        parent::__construct($request);

        $this->dynamicStorage = $dynamicStorage;
    }
    /**
     * @return ProcessorInterface
     */
    public function process() : ProcessorInterface
    {
        $finalProduct = '';
        $count = 0;

        $onlyAdded = $this->dynamicStorage->filterAddedDynamics();

        if (!empty($onlyAdded)) {
            foreach ($onlyAdded as $name => $dynamicStorageItems) {
                $dynamic = $this->dynamicStorage->getDynamicInstance($name);

                if ($dynamic instanceof UrlifyInterface) {
                    $finalProduct.=$dynamic->urlify($count);
                }

                $count++;
            }

            $this->processed = $finalProduct.'&';
        }

        return $this;
    }
    /**
     * @return string
     */
    public function getProcessed() : string
    {
        return $this->processed;
    }
}