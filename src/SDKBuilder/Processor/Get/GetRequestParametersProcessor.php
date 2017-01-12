<?php

namespace SDKBuilder\Processor\Get;

use SDKBuilder\Processor\{ AbstractProcessor, ProcessorInterface };

class GetRequestParametersProcessor extends AbstractProcessor implements ProcessorInterface
{
    /**
     * @var string $processed
     */
    private $processed = '';
    /**
     * @return ProcessorInterface
     */
    public function process() : ProcessorInterface
    {
        $globalParameters = $this->request->getGlobalParameters();
        $specialParameters = $this->request->getSpecialParameters();
        $finalUrl = '';

        foreach ($globalParameters as $key => $parameter) {
            if ($parameter->isDeprecated()) {
                continue;
            }

            if ($key === 0) {
                $finalUrl.=$parameter->getValue().'?';

                continue;
            }

            if ($parameter->getType()->isStandalone()) {
                $finalUrl.=$parameter->getRepresentation().'&';

                continue;
            }

            $finalUrl.=$parameter->getRepresentation().'='.$parameter->getValue().'&';
        }

        foreach ($specialParameters as $specialParameter) {
            if ($specialParameter->isDeprecated()) {
                continue;
            }

            if ($specialParameter->getValue() !== null and $specialParameter->isEnabled()) {
                $value = $specialParameter->getValue();

                if ($specialParameter->shouldEncode()) {
                    $value = urlencode($specialParameter->getValue());
                }

                $finalUrl.=$specialParameter->getRepresentation().'='.$value.'&';
            }
        }

        $this->processed = $finalUrl;

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