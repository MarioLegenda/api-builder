<?php

namespace SDKBuilder;

use SDKBuilder\Event\SDKEvent;
use SDKBuilder\Exception\SDKBuilderException;
use SDKBuilder\Request\AbstractRequest;
use SDKBuilder\Request\AbstractValidator;
use SDKBuilder\Request\BasicRequestValidator;
use SDKBuilder\Request\Request;
use SDKBuilder\Request\RequestParameters;
use SDKBuilder\Request\Method\MethodParameters;
use SDKBuilder\Request\ValidatorsProcessor;
use SDKBuilder\SDK\SDKInterface;
use Symfony\Component\EventDispatcher\EventDispatcher;
use SDKBuilder\Processor\Factory\ProcessorFactory;
use Symfony\Component\Config\Definition\Processor;
use SDKBuilder\Configuration\Configuration;

class ApiFactory
{
    /**
     * @var Request $request
     */
    protected $request;
    /**
     * @var MethodParameters $methodParameters
     */
    protected $methodParameters;
    /**
     * @var EventDispatcher $eventDispatcher
     */
    protected $eventDispatcher;
    /**
     * @var string $apiKey
     */
    protected $apiKey;
    /**
     * AbstractApiFactory constructor.
     * @param string $apiKey
     */
    public function __construct(string $apiKey)
    {
        $this->apiKey = $apiKey;
    }
    /**
     * @param string $apiKey
     * @param array $config
     * @throws SDKBuilderException
     * @return SDKInterface
     */

    public function createApi(array $config) : SDKInterface
    {
        $processor = new Processor();

        $processor->processConfiguration(new Configuration($this->apiKey), $config);

        $config = $this->validateSDK($this->apiKey, $config);

        $apiConfig = $config['sdk'][$this->apiKey];
        $requestClass = 'SDKBuilder\\Request\\Request';
        $apiClass = 'SDKBuilder\\SDK\\GenericApi';

        if (array_key_exists('api_class', $apiConfig)) {
            if (!class_exists($apiConfig['api_class'])) {
                throw new SDKBuilderException('Invalid api_class. Class '.$apiConfig['api_class'].' does not exist');
            }

            $apiClass = $apiConfig['api_class'];
        }

        if (array_key_exists('request_class', $apiConfig)) {
            $requestClass = $apiConfig['request_class'];

            if (!class_exists($requestClass)) {
                throw new SDKBuilderException('Invalid request class. Class '.$requestClass.' does not exist');
            }
        }

        if (!class_exists($apiClass)) {
            throw new SDKBuilderException('Api class '.$apiClass.' does not exist');
        }

        $this->request = $this->createRequest($requestClass, $this->apiKey, $config);

        $this->methodParameters = $this->createMethodParameters($this->apiKey, $config);

        $this->eventDispatcher = new EventDispatcher();

        $this->addListeners($this->eventDispatcher, $apiConfig);

        $processorFactory = new ProcessorFactory();

        $validatorProcessor = new ValidatorsProcessor();

        $validatorProcessor->addValidator(new BasicRequestValidator($this->request));

        if (array_key_exists('request_validators', $apiConfig)) {
            $validators = $apiConfig['request_validators'];

            foreach ($validators as $validator) {
                if (!class_exists($validator)) {
                    throw new SDKBuilderException('Invalid validator. Validator '.$validator.' does not exist');
                }

                $v = new $validator($this->request);

                if (!$v instanceof AbstractValidator) {
                    throw new SDKBuilderException('Invalid validator. Validator should extend '.AbstractValidator::class);
                }

                $validatorProcessor->addValidator($v);
            }
        }

        return new $apiClass(
            $this->request,
            $processorFactory,
            $this->eventDispatcher,
            $this->methodParameters,
            $validatorProcessor
        );
    }

    private function validateSDK(string $apiKey, array $config) : array
    {
        if (!array_key_exists('sdk', $config)) {
            throw new SDKBuilderException('\'sdk\' config key not found in configuration');
        }

        if (!array_key_exists($apiKey, $config['sdk'])) {
            throw new SDKBuilderException('\''.$apiKey.'\' not found under \'sdk\' configuration key');
        }

        return $this->addDefaults($apiKey, $config);
    }

    private function createRequest(string $requestClass, string $apiKey, array $config) : AbstractRequest
    {
        $request = new $requestClass(
            new RequestParameters($config['sdk'][$apiKey]['global_parameters']),
            new RequestParameters($config['sdk'][$apiKey]['special_parameters'])
        );

        return $request;
    }

    private function createMethodParameters(string $apiKey, array $config) : ?MethodParameters
    {
        if (array_key_exists('methods', $config['sdk'][$apiKey])) {
            return new MethodParameters($config['sdk'][$apiKey]['methods']);
        }

        return null;
    }

    private function addListeners(EventDispatcher $eventDispatcher, array $config)
    {
        if (!array_key_exists('listeners', $config)) {
            return null;
        }

        $listeners = $config['listeners'];

        $validListeners = array(
            'request_pre_process' => SDKEvent::PRE_PROCESS_REQUEST_EVENT,
            'request_post_process' => SDKEvent::POST_PROCESS_REQUEST_EVENT,
            'add_processor' => SDKEvent::ADD_PROCESSORS_EVENT,
            'pre_send_request' => SDKEvent::PRE_SEND_REQUEST_EVENT,
            'post_send_request' => SDKEvent::POST_SEND_REQUEST_EVENT,
        );

        foreach ($validListeners as $configKey => $listener) {
            if (array_key_exists($configKey, $listeners)) {
                $metadata = $listeners[$configKey];

                if (!class_exists($metadata['class'])) {
                    throw new SDKBuilderException('Class \''.$metadata['class'].'\'for listener \''.$configKey.'\' does not exist');
                }

                $eventDispatcher->addListener($listener, array(new $metadata['class'], $metadata['method']));
            }
        }
    }

    private function addDefaults(string $apiKey, array $config) : array
    {
        $sdkGlobalParameters = $config['sdk'][$apiKey];

        $params = array('global_parameters', 'special_parameters');

        foreach ($params as $param) {
            foreach ($sdkGlobalParameters[$param] as $paramName => $sdk) {
                if (!array_key_exists('deprecated', $sdk)) {
                    $config['sdk'][$apiKey][$param][$paramName]['deprecated'] = false;
                }

                if (!array_key_exists('throws_exception_if_deprecated', $sdk)) {
                    $config['sdk'][$apiKey][$param][$paramName]['throws_exception_if_deprecated'] = false;
                }

                if (!array_key_exists('obsolete', $sdk)) {
                    $config['sdk'][$apiKey][$param][$paramName]['obsolete'] = false;
                }

                if (!array_key_exists('error_message', $sdk)) {
                    $config['sdk'][$apiKey][$param][$paramName]['error_message'] = 'Invalid value for %s and represented as %s';
                }
            }
        }

        return $config;
    }
}