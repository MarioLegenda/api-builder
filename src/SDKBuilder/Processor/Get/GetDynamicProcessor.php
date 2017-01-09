<?php

namespace SDKBuilder\Processor\Get;

use SDKBuilder\Processor\AbstractProcessor;
use SDKBuilder\Processor\ProcessorInterface;
use FindingAPI\Core\ItemFilter\ItemFilterStorage;
use SDKBuilder\Request\RequestInterface;
use SDKBuilder\Processor\UrlifyInterface;

class GetDynamicProcessor extends AbstractProcessor implements ProcessorInterface
{
    /**
     * @var string $processed
     */
    private $processed = '';
    /**
     * @var ItemFilterStorage $itemFilterStorage
     */
    private $itemFilterStorage;
    /**
     * GetItemFiltersProcessor constructor.
     * @param RequestInterface $request
     * @param ItemFilterStorage $itemFilterStorage
     */
    public function __construct(RequestInterface $request, ItemFilterStorage $itemFilterStorage)
    {
        parent::__construct($request);

        $this->itemFilterStorage = $itemFilterStorage;
    }
    /**
     * @return ProcessorInterface
     */
    public function process() : ProcessorInterface
    {
        $finalProduct = '';
        $count = 0;

        $onlyAdded = $this->itemFilterStorage->filterAddedFilter();

        if (!empty($onlyAdded)) {
            foreach ($onlyAdded as $name => $itemFilterItems) {
                $itemFilter = $this->itemFilterStorage->getItemFilterInstance($name);

                if ($itemFilter instanceof UrlifyInterface) {
                    $finalProduct.=$itemFilter->urlify($count);
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