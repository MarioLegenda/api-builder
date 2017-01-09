<?php

namespace SDKBuilder\Dynamic;

use SDKBuilder\Request\AbstractValidator;
use SDKBuilder\Exception\DynamicException;

class DynamicsValidator extends AbstractValidator
{
    public function validate(): void
    {
        $dynamicStorage = $this->getRequest()->getDynamicStorage();

        $addedDynamics = $dynamicStorage->filterAddedDynamics();

        foreach ($addedDynamics as $name => $value) {
            $dynamicData = $dynamicStorage->getDynamic($name);

            $dynamic = $dynamicStorage->getDynamicInstance($name);
            $dynamicValue = $dynamicData['value'];

            if ($dynamic->validateDynamic($dynamicValue) !== true) {
                throw new DynamicException((string) $dynamic);
            }
        }
    }
}