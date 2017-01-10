<?php

namespace SDKBuilder;

use SDKBuilder\Event\ {
    AddProcessorEvent,
    PostProcessRequestEvent,
    PostSentRequestEvent,
    PreProcessRequestEvent,
    RequestEvent,
    SDKEvent,
    SendRequestEvent
};

use SDKBuilder\Exception\SDKException;

use SDKBuilder\Processor\ {
    Factory\ProcessorFactory,
    Get\GetDynamicProcessor,
    Get\GetRequestParametersProcessor
};

use SDKBuilder\Request\ {
    Method\MethodParameters,
    Method\Method,
    Parameter,
    RequestInterface,
    ValidatorsProcessor
};

use SDKBuilder\SDK\SDKInterface;
use SDKBuilder\Processor\RequestProducer;
use SDKBuilder\SDKOfflineMode\SDKOfflineMode;
use SDKBuilder\Exception\MethodParametersException;

use Symfony\Component\EventDispatcher\EventDispatcher;

abstract class AbstractSDK implements SDKInterface
{
    /**
     * @var string $processed
     */
    private $processed;
    /**
     * @var bool $isCompiled
     */
    private $isCompiled = false;
    /**
     * @var RequestInterface $request
     */
    private $request;
    /**
     * @var MethodParameters $methodParameters
     */
    private $methodParameters;
    /**
     * @var ProcessorFactory $processorFactory
     */
    private $processorFactory;
    /**
     * @var EventDispatcher
     */
    private $eventDispatcher;
    /**
     * @var ValidatorsProcessor
     */
    protected $validatorsProcessor;
    /**
     * @var string $responseBody
     */
    protected $responseBody;
    /**
     * @var SDKOfflineMode
     */
    protected $offlineMode;
    /**
     * @var bool $offlineModeSwitch
     */
    protected $offlineModeSwitch = false;
    /**
     * AbstractSDK constructor.
     * @param RequestInterface $request
     * @param MethodParameters $methodParameters
     * @param ProcessorFactory $processorFactory
     * @param EventDispatcher $eventDispatcher
     * @param ValidatorsProcessor $validatorsProcessor
     */
    public function __construct(
        RequestInterface $request,
        ProcessorFactory $processorFactory,
        EventDispatcher $eventDispatcher,
        ?MethodParameters $methodParameters,
        ValidatorsProcessor $validatorsProcessor)
    {
        $this->request = $request;
        $this->methodParameters = $methodParameters;
        $this->processorFactory = $processorFactory;
        $this->eventDispatcher = $eventDispatcher;
        $this->validatorsProcessor = $validatorsProcessor;

        $this->switchOfflineMode(false);
    }
    /**
     * @param bool $switch
     * @return SDKInterface
     */
    public function switchOfflineMode(bool $switch) : SDKInterface
    {
        switch ($switch) {
            case true:
                $this->offlineMode = new SDKOfflineMode($this);
                break;
            case false:
                $this->offlineMode = null;
                break;
        }

        $this->offlineModeSwitch = $switch;

        return $this;
    }
    /**
     * @return bool
     */
    public function isInOfflineMode() : bool
    {
        return $this->offlineModeSwitch;
    }
    /**
     * @param Method $method
     * @return SDKInterface
     */
    public function addMethod(Method $method) : SDKInterface
    {
        $validMethodsParameter = $this->getRequest()->getGlobalParameters()->getParameter($this->methodParameters->getValidMethodsParameter());

        $method->validate($validMethodsParameter);

        $this->methodParameters->addMethod($method);

        return $this;
    }
    /**
     * @param string $parameterType
     * @param Parameter $parameter
     * @return SDKInterface
     */
    public function addParameter(string $parameterType, Parameter $parameter) : SDKInterface
    {
        if ($parameterType === 'global_parameter') {
            $this->getRequest()->getGlobalParameters()->addParameter($parameter);

            return $this;
        }

        if ($parameterType === 'special_parameter') {
            $this->getRequest()->getSpecialParameters()->addParameter($parameter);
        }

        return $this;
    }
    /**
     * @return SDKInterface
     * @throws SDKException
     */
    public function compile() : SDKInterface
    {
        if ($this->getRequest()->getMethod() === 'get') {
            $this->processorFactory->registerProcessor($this->getRequest()->getMethod(), GetRequestParametersProcessor::class);

            $this->processorFactory->registerCallbackProcessor($this->getRequest()->getMethod(), function(RequestInterface $request) {
                $dynamicStorage = $request->getDynamicStorage();

                if (!empty($dynamicStorage)) {
                    if ($request->getMethod() === 'get') {
                        return new GetDynamicProcessor($request, $dynamicStorage);
                    }
                }
            });
        }

        if ($this->eventDispatcher->hasListeners('sdk.add_processors')) {
            $this->eventDispatcher->dispatch('sdk.add_processors', new AddProcessorEvent(
                $this->getProcessorFactory(),
                $this->getRequest()
            ));
        }

        if ($this->eventDispatcher->hasListeners(SDKEvent::PRE_PROCESS_REQUEST_EVENT)) {
            $this->eventDispatcher->dispatch(SDKEvent::PRE_PROCESS_REQUEST_EVENT, new PreProcessRequestEvent($this->getRequest()));
        }

        $this->processRequest();

        if ($this->eventDispatcher->hasListeners(SDKEvent::POST_PROCESS_REQUEST_EVENT)) {
            $this->eventDispatcher->dispatch(SDKEvent::POST_PROCESS_REQUEST_EVENT, new PostProcessRequestEvent($this->getRequest()));
        }

        if ($this->offlineModeSwitch === true) {
            if ($this->getRequest()->getMethod() !== 'get') {
                throw new SDKException('If this is your development environment and you are using SDKOfflineMode tool, you can only use it with \'get\' requests. If you are on a production server, it is advised to turn SDKOfflineMode off with AbstractSDK::switchOfflineMode(false)');
            }
        }

        $this->isCompiled = true;

        return $this;
    }
    /**
     * @return SDKInterface
     */
    public function send() : SDKInterface
    {
        $this->validatorsProcessor->validate();

        $this->sendRequest();

        return $this;
    }
    /**
     * @return RequestInterface
     */
    public function getRequest() : RequestInterface
    {
        return $this->request;
    }

    public function setRequest(RequestInterface $request) : SDKInterface
    {
        $this->request = $request;

        return $this;
    }
    /**
     * @return string
     */
    public function getProcessedRequestString() : string
    {
        return $this->processed;
    }
    /**
     * @return bool
     */
    public function hasErrors() : bool
    {
        return $this->validatorsProcessor->hasErrors();
    }
    /**
     * @return array
     */
    public function getErrors() : array
    {
        return $this->validatorsProcessor->getErrors();
    }
    /**
     * @return string
     */
    public function getResponseBody()
    {
        return $this->responseBody;
    }
    /**
     * @return EventDispatcher
     */
    public function getEventDispatcher() : EventDispatcher
    {
        return $this->eventDispatcher;
    }
    /**
     * @param $methodName
     * @param $arguments
     * @return RequestInterface
     */
    public function __call($methodName, $arguments) : RequestInterface
    {
        $method = $this->methodParameters->getMethod($methodName);

        $validMethodsParameter = $this->getRequest()->getGlobalParameters()->getParameter($this->methodParameters->getValidMethodsParameter());

        $method->validate($validMethodsParameter);

        $this->setRequest($this->createMethod($method));

        return $this->getRequest();
    }
    /**
     * @return ProcessorFactory
     */
    public function getProcessorFactory() : ProcessorFactory
    {
        return $this->processorFactory;
    }
    /**
     * @void
     */
    public function restoreDefaults() : void
    {
        $objectProperties = get_object_vars($this);

        foreach ($objectProperties as $objectProperty) {
            if ($objectProperty instanceof RestoreDefaultsInterface) {
                $objectProperty->restoreDefaults();
            }
        }

        $this->isCompiled = false;
    }

    private function processRequest()
    {
        $processors = $this->processorFactory->createProcessors($this->getRequest());

        $this->processed = (new RequestProducer($processors))->produce()->getFinalProduct();
    }

    private function sendRequest() : void
    {
        if (!$this->isCompiled) {
            throw new SDKException('Api is not compiled. If you extended the AbstractSDK::compile() method, you need to call parent::compile() in your extended method');
        }

        if ($this->eventDispatcher->hasListeners(SDKEvent::PRE_SEND_REQUEST_EVENT)) {
            $this->eventDispatcher->dispatch(SDKEvent::PRE_SEND_REQUEST_EVENT, new RequestEvent($this->getRequest()));
        }

        try {
            if ($this->eventDispatcher->hasListeners(SDKEvent::SEND_REQUEST_EVENT)) {
                $this->eventDispatcher->dispatch(SDKEvent::SEND_REQUEST_EVENT, new SendRequestEvent($this, $this->getRequest()));
            } else {
                if ($this->offlineModeSwitch === true) {
                    $responseBody = $this->offlineMode->getResponse();

                    if ($responseBody === false) {
                        $this->responseBody = $this->getRequest()->sendRequest($this->processed)->getBody();
                    } else {
                        $this->responseBody = $responseBody;
                    }
                } else {
                    $this->responseBody = $this->getRequest()->sendRequest($this->processed)->getBody();
                }
            }
        } catch (\Exception $e) {
            echo 'Generic exception caught with message: \''.$e->getMessage().'\'. Stack trace: '.$e->getTraceAsString();
        }

        if ($this->eventDispatcher->hasListeners(SDKEvent::POST_SEND_REQUEST_EVENT)) {
            $this->eventDispatcher->dispatch(SDKEvent::POST_SEND_REQUEST_EVENT, new PostSentRequestEvent($this, $this->getRequest()));
        }

        $this->restoreDefaults();
    }


    private function createMethod(Method $method) : RequestInterface
    {
        $instanceString = $method->getInstanceObjectString();

        $object = new $instanceString(
            $this->getRequest()->getGlobalParameters(),
            $this->getRequest()->getSpecialParameters(),
            $this->getRequest()->getDynamicStorage()
        );

        if (!$object instanceof RequestInterface) {
            throw new MethodParametersException(get_class($object).' has to extend '.RequestInterface::class);
        }

        $objectMethods = $method->getMethods();

        $specialParameters = $this->getRequest()->getSpecialParameters();

        foreach ($objectMethods as $objectMethod) {
            if (!$specialParameters->hasParameter($objectMethod)) {
                throw new MethodParametersException('Cannot create request method because parameter '.$objectMethod.' does not exist for request method '.$method->getName());
            }

            $parameter = $this->getRequest()->getSpecialParameters()->getParameter($objectMethod);
            $parameter->enable();

            $set = 'set'.preg_replace('#\s#', '', ucwords(preg_replace('#_#', ' ', $parameter->getName())));
            $add = 'add'.preg_replace('#\s#', '', ucwords(preg_replace('#_#', ' ', $parameter->getName())));
            $enable = 'enable'.preg_replace('#\s#', '', ucwords(preg_replace('#_#', ' ', $parameter->getName())));
            $disable = 'disable'.preg_replace('#\s#', '', ucwords(preg_replace('#_#', ' ', $parameter->getName())));

            $possibleMethods = array(
                $set,
                $add,
                $enable,
                $disable,
                $objectMethod,
            );

            $classMethods = get_class_methods($object);

            $methodValidated = false;
            foreach ($possibleMethods as $possibleMethod) {
                if (in_array($possibleMethod, $classMethods)) {
                    $methodValidated = true;

                    break;
                }
            }

            if ($methodValidated === false) {
                throw new MethodParametersException('Possible methods '.implode(', ', $possibleMethods).' for object '.$instanceString.' not found');
            }
        }

        return $object;
    }
}