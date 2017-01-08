<?php

namespace SDKBuilder\Request;

use SDKBuilder\Exception\RequestParametersException;

class BasicRequestValidator extends AbstractValidator
{
    public function validate(): void
    {
        $globalParameters = $this->getRequest()->getGlobalParameters();
        $specialParameters = $this->getRequest()->getSpecialParameters();

        $globalParameters->validate();
        $specialParameters->validate();

        $globalParamErrors = $globalParameters->getErrors();

        if (!empty($globalParamErrors)) {
            $this->addError('global_parameters', $globalParamErrors);
        }

        $specialParamErrors = $specialParameters->getErrors();

        if (!empty($specialParamErrors)) {
            $this->addError('special_parameters', $specialParamErrors);
        }

        $domainParameter = $globalParameters[0];

        if (!$domainParameter->getType()->isStandalone()) {
            throw new RequestParametersException('The first configuration value under \'global_parameters\' has to be of \'standalone\' type because it is meant to be the domain part of the url');
        }
    }
}