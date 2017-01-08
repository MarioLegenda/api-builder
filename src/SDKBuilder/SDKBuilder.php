<?php

namespace SDKBuilder;

use SDKBuilder\Exception\SDKBuilderException;
use SDKBuilder\SDK\SDKInterface;
use Symfony\Component\Yaml\Yaml;

class SDKBuilder
{
    /**
     * @var static EbaySDK $instance
     */
    private static $instance;
    /**
     * @var array $sdkRepository
     */
    private $sdkRepository = array();
    /**
     * @return SDKBuilder
     */
    public static function inst()
    {
        self::$instance = (self::$instance instanceof self) ? self::$instance : new self();

        return self::$instance;
    }
    /**
     * @param string $apiKey
     * @param string $configFile
     * @return SDKBuilder
     * @throws SDKBuilderException
     */
    public function registerApi(string $apiKey, string $configFile) : SDKBuilder
    {
        if ($this->isRegisteredApi($apiKey)) {
            throw new SDKBuilderException('There is already a registered api with name \''.$apiKey.'\'');
        }

        if (!file_exists($configFile)) {
            throw new SDKBuilderException('Configuration file \''.$configFile.'\' for registering \''.$apiKey.'\' does not exist');
        }

        $this->sdkRepository[$apiKey] = array(
            'config_file' => $configFile,
            'instance' => null,
        );

        $this->sdkRepository[$apiKey]['config_file'] = $configFile;

        return $this;
    }
    /**
     * @param string $apiKey
     * @return bool
     */
    public function isRegisteredApi(string $apiKey) : bool
    {
        return array_key_exists($apiKey, $this->sdkRepository);
    }
    /**
     * @param string $apiKey
     * @param $singleton
     * @return SDKInterface
     * @throws SDKBuilderException
     */
    public function create(string $apiKey, bool $singleton = true) : SDKInterface
    {
        if (!$this->isRegisteredApi($apiKey)) {
            throw new SDKBuilderException('SDK with name \''.$apiKey.'\' not found');
        }

        if ($singleton === false) {
            $api = $this->createApi($apiKey);

            $this->sdkRepository[$apiKey]['instance'] = $api;

            return $api;
        }

        if ($this->sdkRepository[$apiKey]['instance'] instanceof SDKInterface) {
            $this->sdkRepository[$apiKey]['instance']->restoreDefaults();

            return $this->sdkRepository[$apiKey]['instance'];
        }

        $api = $this->createApi($apiKey);

        $this->sdkRepository[$apiKey]['instance'] = $api;

        return $api;
    }

    private function createApi(string $apiKey) : SDKInterface
    {

        $configuration = $this->sdkRepository[$apiKey]['config_file'];

        $apiFactory = new ApiFactory($apiKey);

        return $apiFactory->createApi(Yaml::parse(file_get_contents($configuration)));
    }
}