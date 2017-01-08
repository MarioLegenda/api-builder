<?php

namespace SDKBuilder\Processor\Factory;

 use SDKBuilder\Processor\ProcessorInterface;
 use SDKBuilder\Request\RequestInterface;
 use SDKBuilder\RestoreDefaultsInterface;

 class ProcessorFactory implements RestoreDefaultsInterface
{
    /**
     * @var array $processors
     */
    private $processors = array();
    /**
     * @param string $method
     * @param string $processor
     * @return ProcessorFactory
     */
    public function registerProcessor(string $method, string $processor) : ProcessorFactory
    {
        $this->processors[$method][] = array(
            'resolver' => $processor,
        );

        return $this;
    }

    public function registerCallbackProcessor(string $method, \Closure $callback)
    {
        $this->processors[$method][] = array(
            'resolver' => $callback,
        );
    }
    /**
     * @param string $method
     * @param string $processor
     * @return ProcessorFactory
     */
    public function unregisterProcessor(string $method, string $processor) : ProcessorFactory
    {
        foreach ($this->processors[$method] as $key => $prc) {
            if ($prc['namespace'] === $processor) {
                unset($this->processors[$method][$key]);
            }
        }

        return $this;
    }
    /**
     * @param RequestInterface $request
     * @return array
     */
    public function createProcessors(RequestInterface $request) : array
    {
        $method = $request->getMethod();

        $processors = array();

        foreach ($this->processors[$method] as $key => $processor) {
            $resolver = $processor['resolver'];

            if ($resolver instanceof \Closure) {
                $object = $resolver->__invoke($request);

                if ($object instanceof ProcessorInterface) {
                    $processors[] = $object;
                }
            }

            if (is_string($resolver) and class_exists($resolver)) {
                $object = new $resolver($request);

                if ($object instanceof ProcessorInterface) {
                    $processors[] = $object;
                }
            }
        }

        $this->processors[$method] = array();

        return $processors;
    }

    public function restoreDefaults() : void
    {
        $this->processors = array();
    }
}