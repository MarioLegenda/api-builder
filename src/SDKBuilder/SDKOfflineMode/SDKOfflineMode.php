<?php

namespace SDKBuilder\SDKOfflineMode;

use SDKBuilder\SDK\SDKInterface;
use GuzzleHttp\Client;
use SDKBuilder\SDKOfflineMode\Exception\SDKOfflineModeException;

class SDKOfflineMode
{
    /**
     * @var SDKInterface $ebayApiObject
     */
    private $apiObject;
    /**
     * @var resource $requestHandle
     */
    private $requestHandle;
    /**
     * EbayOfflineMode constructor.
     * @param SDKInterface $api
     */
    public function __construct(SDKInterface $api)
    {
        $this->apiObject = $api;

        if (!file_exists(__DIR__.'/responses')) {
            mkdir(__DIR__.'/responses');
        }
    }
    /**
     * @return string
     */
    public function getResponse()
    {
        $request = $this->apiObject->getProcessedRequestString();
        $this->requestHandle = fopen(__DIR__.'/requests.csv', 'a+');

        if (!$this->isResponseStored($request)) {
            $requests = file(__DIR__.'/requests.csv');

            // if requests.csv is empty, fill it with first request
            if (empty($requests)) {
                // add a request to requests.csv
                fputcsv($this->requestHandle, array(1, $request), ';');
                $responseFile = __DIR__.'/responses/1.txt';
                fclose(fopen(__DIR__.'/responses/1.txt', 'a+'));

                // makes a request and adds the response to newly created response file
                $client = new Client();

                $guzzleResponse = $client->request($this->apiObject->getRequest()->getMethod(), $request);

                if ($guzzleResponse->getStatusCode() < 200 or $guzzleResponse->getStatusCode() > 200) {
                    return false;
                }

                $stringResponse = (string) $guzzleResponse->getBody();
                file_put_contents($responseFile, $stringResponse);

                fclose($this->requestHandle);

                return $stringResponse;
            }

            $lastRequest = preg_split('#;#', array_pop($requests));

            $nextResponse = (int) ++$lastRequest[0];

            fputcsv($this->requestHandle, array($nextResponse, $request), ';');

            $responseFile = __DIR__.'/responses/'.$nextResponse.'.txt';
            fclose(fopen($responseFile, 'a+'));

            $client = new Client();

            $guzzleResponse = $client->request($this->apiObject->getRequest()->getMethod(), $request);

            if ($guzzleResponse->getStatusCode() < 200 or $guzzleResponse->getStatusCode() > 200) {
                return false;
            }

            $stringResponse = (string) $guzzleResponse->getBody();
            file_put_contents($responseFile, $stringResponse);

            fclose($this->requestHandle);

            return $stringResponse;
        }

        if ($this->isResponseStored($request) === true) {
            $requests = file(__DIR__.'/requests.csv');

            foreach ($requests as $line) {
                $requestLine = preg_split('#;#', $line);

                if (trim($requestLine[1]) === $request) {
                    $responseFile = __DIR__.'/responses/'.$requestLine[0].'.txt';

                    $stringResponse = file_get_contents($responseFile);

                    fclose($this->requestHandle);

                    return $stringResponse;
                }
            }
        }

        throw new SDKOfflineModeException('There is a possible bug in EbayOfflineMode. Please, fix it');
    }

    public function isResponseStored(string $request) : bool
    {
        $requests = file(__DIR__.'/requests.csv');

        foreach ($requests as $line) {
            $requestLine = preg_split('#;#', $line);

            if (trim($requestLine[1]) === $request) {
                return true;
            }
        }

        return false;
    }
}