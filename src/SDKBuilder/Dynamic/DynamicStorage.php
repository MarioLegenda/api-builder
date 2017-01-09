<?php

namespace SDKBuilder\Dynamic;

use SDKBuilder\Exception\DynamicException;

class DynamicStorage
{
    /**
     * @var array $itemFilters
     */
    private $dynamics = array(
        'AuthorizedSellerOnly' => array (
            'object' => 'FindingAPI\\Core\\ItemFilter'.'\\AuthorizedSellerOnly',
            'value' => null,
            'multiple_values' => false,
            'date_time' => false,
        ),
        'AvailableTo' => array(
            'object' => 'FindingAPI\\Core\\ItemFilter'.'\\AvailableTo',
            'value' => null,
            'multiple_values' => false,
            'date_time' => false,
        ),
        'BestOfferOnly' => array(
            'object' => 'FindingAPI\\Core\\ItemFilter'.'\\BestOfferOnly',
            'value' => null,
            'multiple_values' => false,
            'date_time' => false,
        ),
        'CharityOnly' => array(
            'object' => 'FindingAPI\\Core\\ItemFilter'.'\\CharityOnly',
            'value' => null,
            'multiple_values' => false,
            'date_time' => false,
        ),
        'Condition' => array(
            'object' => 'FindingAPI\\Core\\ItemFilter'.'\\Condition',
            'value' => null,
            'multiple_values' => true,
            'date_time' => false,
        ),
        'Currency' => array(
            'object' => 'FindingAPI\\Core\\ItemFilter'.'\\Currency',
            'value' => null,
            'multiple_values' => false,
            'date_time' => false,
        ),
        'EndTimeFrom' => array(
            'object' => 'FindingAPI\\Core\\ItemFilter'.'\\EndTimeFrom',
            'value' => null,
            'multiple_values' => false,
            'date_time' => true,
        ),
        'EndTimeTo' => array(
            'object' => 'FindingAPI\\Core\\ItemFilter'.'\\EndTimeTo',
            'value' => null,
            'multiple_values' => false,
            'date_time' => true,
        ),
        'ExcludeAutoPay' => array(
            'object' => 'FindingAPI\\Core\\ItemFilter'.'\\ExcludeAutoPay',
            'value' => null,
            'multiple_values' => false,
            'date_time' => false,
        ),
        'ExcludeCategory' => array(
            'object' => 'FindingAPI\\Core\\ItemFilter'.'\\ExcludeCategory',
            'value' => null,
            'multiple_values' => true,
            'date_time' => false,
        ),
        'ExcludeSeller' => array(
            'object' => 'FindingAPI\\Core\\ItemFilter'.'\\ExcludeSeller',
            'value' => null,
            'multiple_values' => true,
            'date_time' => false,
        ),
        'ExpeditedShippingType' => array(
            'object' => 'FindingAPI\\Core\\ItemFilter'.'\\ExpeditedShippingType',
            'value' => null,
            'multiple_values' => false,
            'date_time' => false,
        ),
        'FeaturedOnly' => array(
            'object' => 'FindingAPI\\Core\\ItemFilter'.'\\FeaturedOnly',
            'value' => null,
            'multiple_values' => false,
            'date_time' => false,
        ),
        'FeedbackScoreMax' => array(
            'object' => 'FindingAPI\\Core\\ItemFilter'.'\\FeedbackScoreMax',
            'value' => null,
            'multiple_values' => false,
            'date_time' => false,
        ),
        'FeedbackScoreMin' => array(
            'object' => 'FindingAPI\\Core\\ItemFilter'.'\\FeedbackScoreMin',
            'value' => null,
            'multiple_values' => false,
            'date_time' => false,
        ),
        'FreeShippingOnly' => array(
            'object' => 'FindingAPI\\Core\\ItemFilter'.'\\FreeShippingOnly',
            'value' => null,
            'multiple_values' => false,
            'date_time' => false,
        ),
        'GetItFastOnly' => array(
            'object' => 'FindingAPI\\Core\\ItemFilter'.'\\GetItFastOnly',
            'value' => null,
            'multiple_values' => false,
            'date_time' => false,
        ),
        'HideDuplicateItems' => array(
            'object' => 'FindingAPI\\Core\\ItemFilter'.'\\HideDuplicateItems',
            'value' => null,
            'multiple_values' => false,
            'date_time' => false,
        ),
        'ListedIn' => array(
            'object' => 'FindingAPI\\Core\\ItemFilter'.'\\ListedIn',
            'value' => null,
            'multiple_values' => false,
            'date_time' => false,
        ),
        'ListingType' => array(
            'object' => 'FindingAPI\\Core\\ItemFilter'.'\\ListingType',
            'value' => null,
            'multiple_values' => true,
            'date_time' => false,
        ),
        'LocalPickupOnly' => array(
            'object' => 'FindingAPI\\Core\\ItemFilter'.'\\LocalPickupOnly',
            'value' => null,
            'multiple_values' => false,
            'date_time' => false,
        ),
        'LocalSearchOnly' => array(
            'object' => 'FindingAPI\\Core\\ItemFilter'.'\\LocalSearchOnly',
            'value' => null,
            'multiple_values' => false,
            'date_time' => false,
        ),
        'LocatedIn' => array(
            'object' => 'FindingAPI\\Core\\ItemFilter'.'\\LocatedIn',
            'value' => null,
            'multiple_values' => true,
            'date_time' => false,
        ),
        'LotsOnly' => array(
            'object' => 'FindingAPI\\Core\\ItemFilter'.'\\LotsOnly',
            'value' => null,
            'multiple_values' => false,
            'date_time' => false,
        ),
        'MaxBids' => array(
            'object' => 'FindingAPI\\Core\\ItemFilter'.'\\MaxBids',
            'value' => null,
            'multiple_values' => false,
            'date_time' => false,
        ),
        'MaxDistance' => array(
            'object' => 'FindingAPI\\Core\\ItemFilter'.'\\MaxDistance',
            'value' => null,
            'multiple_values' => false,
            'date_time' => false,
        ),
        'MaxHandlingTime' => array(
            'object' => 'FindingAPI\\Core\\ItemFilter'.'\\MaxHandlingTime',
            'value' => null,
            'multiple_values' => false,
            'date_time' => false,
        ),
        'SortOrder' => array(
            'object' => 'FindingAPI\\Core\\ItemFilter'.'\\SortOrder',
            'value' => null,
            'multiple_values' => false,
            'date_time' => false,
        ),
        'BuyerPostalCode' => array(
            'object' => 'FindingAPI\\Core\\ItemFilter'.'\\BuyerPostalCode',
            'value' => null,
            'multiple_values' => false,
            'date_time' => false,
        ),
        'PaginationInput' => array(
            'object' => 'FindingAPI\\Core\\ItemFilter'.'\\PaginationInput',
            'value' => null,
            'multiple_values' => false,
            'date_time' => false,
        ),
        'MaxPrice' => array(
            'object' => 'FindingAPI\\Core\\ItemFilter'.'\\MaxPrice',
            'value' => null,
            'multiple_values' => false,
            'date_time' => false,
        ),
        'MaxQuantity' => array(
            'object' => 'FindingAPI\\Core\\ItemFilter'.'\\MaxQuantity',
            'value' => null,
            'multiple_values' => false,
            'date_time' => false,
        ),
        'MinBids' => array(
            'object' => 'FindingAPI\\Core\\ItemFilter'.'\\MinBids',
            'value' => null,
            'multiple_values' => false,
            'date_time' => false,
        ),
        'MinPrice' => array(
            'object' => 'FindingAPI\\Core\\ItemFilter'.'\\MinPrice',
            'value' => null,
            'multiple_values' => false,
            'date_time' => false,
        ),
        'MinQuantity' => array(
            'object' => 'FindingAPI\\Core\\ItemFilter'.'\\MinQuantity',
            'value' => null,
            'multiple_values' => false,
            'date_time' => false,
        ),
        'ModTimeFrom' => array(
            'object' => 'FindingAPI\\Core\\ItemFilter'.'\\ModTimeFrom',
            'value' => null,
            'multiple_values' => false,
            'date_time' => true,
        ),
        'OutletSellerOnly' => array(
            'object' => 'FindingAPI\\Core\\ItemFilter'.'\\OutletSellerOnly',
            'value' => null,
            'multiple_values' => false,
            'date_time' => false,
        ),
        'PaymentMethod' => array(
            'object' => 'FindingAPI\\Core\\ItemFilter'.'\\PaymentMethod',
            'value' => null,
            'multiple_values' => false,
            'date_time' => false,
        ),
        'ReturnsAcceptedOnly' => array(
            'object' => 'FindingAPI\\Core\\ItemFilter'.'\\ReturnsAcceptedOnly',
            'value' => null,
            'multiple_values' => false,
            'date_time' => false,
        ),
        'Seller' => array(
            'object' => 'FindingAPI\\Core\\ItemFilter'.'\\Seller',
            'value' => null,
            'multiple_values' => true,
            'date_time' => false,
        ),
        'SellerBusinessType' => array(
            'object' => 'FindingAPI\\Core\\ItemFilter'.'\\SellerBusinessType',
            'value' => null,
            'multiple_values' => true,
            'date_time' => false,
        ),
        'SoldItemsOnly' => array(
            'object' => 'FindingAPI\\Core\\ItemFilter'.'\\SoldItemsOnly',
            'value' => null,
            'multiple_values' => false,
            'date_time' => false,
        ),
        'StartTimeFrom' => array(
            'object' => 'FindingAPI\\Core\\ItemFilter'.'\\StartTimeFrom',
            'value' => null,
            'multiple_values' => false,
            'date_time' => true,
        ),
        'StartTimeTo' => array(
            'object' => 'FindingAPI\\Core\\ItemFilter'.'\\StartTimeTo',
            'value' => null,
            'multiple_values' => false,
            'date_time' => true,
        ),
        'TopRatedSellerOnly' => array(
            'object' => 'FindingAPI\\Core\\ItemFilter'.'\\TopRatedSellerOnly',
            'value' => null,
            'multiple_values' => false,
            'date_time' => false,
        ),
        'WorldOfGoodOnly' => array(
            'object' => 'FindingAPI\\Core\\ItemFilter'.'\\WorldOfGoodOnly',
            'value' => null,
            'multiple_values' => false,
            'date_time' => false,
        ),
        'OutputSelector' => array(
            'object' => 'FindingAPI\\Core\\ItemFilter'.'\\OutputSelector',
            'value' => null,
            'multiple_values' => false,
            'date_time' => false,
        )
    );
    /**
     * ItemFilterStorage constructor.
     */
    public function __construct()
    {
        foreach ($this->dynamics as $dynamicName => $dynamic) {
            $this->validateDynamic(array(
                $dynamicName => $dynamic,
            ));
        }
    }
    /**
     * @param string $name
     * @return mixed|null
     */
    public function getDynamic(string $name)
    {
        if (!$this->hasDynamic($name)) {
            return null;
        }

        return $this->dynamics[$name];
    }

    /**
     * @param array $dynamics
     * @param bool $includeEmpty
     * @return array
     */
    public function getDynamicsInBulk(array $dynamics, $includeEmpty = true) : array
    {
        $found = array();
        foreach ($dynamics as $dynamic) {
            if ($this->hasDynamic($dynamic)) {
                $foundDynamic = $this->getDynamic($dynamic);

                if ($includeEmpty === false) {
                    $found[] = $foundDynamic;

                    continue;
                }

                if ($foundDynamic['value'] !== null) {
                    $found[] = $foundDynamic;
                }
            }
        }

        return $found;
    }

    /**
     * @param string $dynamicName
     * @return bool
     */
    public function isDynamicInRequest(string $dynamicName) : bool
    {
        $dynamic = $this->getDynamic($dynamicName);
        if ($dynamic !== null) {
            return $dynamic['value'] !== null;
        }

        return false;
    }
    /**
     * @param string $name
     * @return bool
     */
    public function hasDynamic(string $name) : bool
    {
        return array_key_exists($name, $this->dynamics);
    }

    /**
     * @param array $configuration
     * @return string
     * @throws DynamicException
     */
    public function addDynamic(array $configuration)
    {
        $this->validateDynamic($configuration);

        $itemFilterName = array_keys($configuration)[0];

        $this->dynamics[$itemFilterName] = $configuration[$itemFilterName];
    }
    /**
     * @param string $name
     * @return bool
     */
    public function removeDynamic(string $name) : bool
    {
        if (!$this->hasDynamic($name)) {
            return false;
        }

        unset($this->dynamics[$name]);

        return true;
    }
    /**
     * @param string $name
     * @param $anonymousClass
     * @return bool
     */
    public function updateDynamicValidator(string $name, $validator) : bool
    {
        if ($this->hasDynamic($name)) {
            $this->dynamics[$name]['object'] = $validator;

            return true;
        }

        return false;
    }
    /**
     * @param string $name
     * @param array $value
     * @throws DynamicException
     */
    public function updateDynamicValue(string $name, array $value)
    {
        if (!$this->hasDynamic($name)) {
            throw new DynamicException('Item filter '.$name.' does not exist');
        }

        $this->dynamics[$name]['value'] = $value;
    }

    /**
     * @param string $name
     * @return DynamicInterface
     * @throws DynamicException
     */
    public function getDynamicInstance(string $name) : DynamicInterface
    {
        if (!$this->hasDynamic($name)) {
            throw new DynamicException('Dynamic \''.$name.'\' does not exist');
        }

        if (!$this->dynamics[$name]['object'] instanceof DynamicInterface) {
            $dynamicClass = $this->dynamics[$name]['object'];
            $dynamicValue = $this->dynamics[$name]['value'];

            $configuration = array(
                'multiple_values' => $this->dynamics[$name]['multiple_values'],
                'date_time' => $this->dynamics[$name]['date_time'],
            );

            $this->dynamics[$name]['object'] = new $dynamicClass($name, $dynamicValue, $configuration);

            return $this->dynamics[$name]['object'];
        }

        return $this->dynamics[$name]['object'];
    }

    /**
     * @param mixed $toExclude
     * @return array
     */
    public function filterAddedDynamics($toExclude = array()) : array
    {
        $filtered = array();

        foreach ($this->dynamics as $name => $dynamic) {
            if (in_array($name, $toExclude) === false) {
                if ($dynamic['value'] !== null) {
                    $filtered[$name] = $dynamic;
                }
            }
        }

        return $filtered;
    }
    /**
     * @return int
     */
    public function count() : int
    {
        return count($this->dynamics);
    }
    /**
     * @return \ArrayIterator
     */
    public function getIterator() : \ArrayIterator
    {
        return new \ArrayIterator();
    }

    private function validateDynamic(array $configuration)
    {
        $allowedKeys = array('object', 'value', 'multiple_values', 'date_time');

        $exceptionMessage = 'When adding new item dynamics, only one key, as the name of the new dynamic, and an array of that key with keys '.implode(', ', $allowedKeys);

        if (count($configuration) > 1) {
            throw new DynamicException($exceptionMessage);
        }

        $dynamicName = array_keys($configuration);

        if (!is_string($dynamicName[0])) {
            throw new DynamicException($exceptionMessage);
        }

        $configKeys = array_keys($configuration[$dynamicName[0]]);

        if (!empty(array_diff($allowedKeys, $configKeys))) {
            throw new DynamicException($exceptionMessage.' for item filter '.$dynamicName[0]);
        }
    }
}