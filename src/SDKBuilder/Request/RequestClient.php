<?php

namespace SDKBuilder\Request;

use SDKBuilder\Exception\RequestException;
use SDKBuilder\Response\ResponseClient;
use SDKBuilder\RestoreDefaultsInterface;

class RequestClient implements RestoreDefaultsInterface
{
    /**
     * @var ResponseClient $responseClient
     */
    private $responseClient;
    /**
     * @var string $data
     */
    private $data;
    /**
     * @var string $method
     */
    private $method;
    /**
     * @var string $uri
     */
    private $uri;
    /**
     * @var array $headers
     */
    private $headers = array();
    /**
     * @void
     */
    public function restoreDefaults(): void
    {
        $this->responseClient = null;
        $this->method = null;
        $this->uri = null;
        $this->headers = array();
    }
    /**
     * @param string $method
     * @return RequestClient
     */
    public function setMethod(string $method) : RequestClient
    {
        $this->method = $method;

        return $this;
    }
    /**
     * @return string
     */
    public function getMethod() : string
    {
        return $this->method;
    }
    /**
     * @param string $uri
     * @return RequestClient
     */
    public function setUri(string $uri) : RequestClient
    {
        $this->uri = $uri;

        return $this;
    }
    /**
     * @return string
     */
    public function getUri() : string
    {
        return $this->uri;
    }
    /**
     * @param array $headers
     * @return RequestClient
     */
    public function setHeaders(array $headers) : RequestClient
    {
        $this->headers = $headers;

        return $this;
    }
    /**
     * @param string $header
     * @param string $headerValue
     * @return RequestClient
     */
    public function addHeader(string $header, string $headerValue) : RequestClient
    {
        $this->headers[$header] = $headerValue;

        return $this;
    }
    /**
     * @return array
     */
    public function getHeaders() : array
    {
        return $this->headers;
    }
    /**
     * @param string $data
     * @return RequestClient
     */
    public function setData(string $data) : RequestClient
    {
        $this->data = $data;

        return $this;
    }
    /**
     * @return null|string
     */
    public function getData() : ?string
    {
        return $this->data;
    }
    /**
     * @return RequestClient
     */
    public function send() : RequestClient
    {
        $responseClient = new ResponseClient();

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $this->uri);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_HEADERFUNCTION, function($ch, $headerLine) use ($responseClient){
            $splittedHeader = preg_split('#:#', $headerLine);

            $headerName = $splittedHeader[0];
            $headerValue = null;

            if (array_key_exists(1, $splittedHeader)) {
                $headerValue = $splittedHeader[1];
            }

            $responseClient->addHeader($headerName, $headerValue);

            return strlen($headerLine);
        });

        if ($this->getMethod() === 'post') {
            curl_setopt($ch, CURLOPT_POST, 1);
        }

        if (is_array($this->headers)) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $this->headers);
        }

        if (is_string($this->data)) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, $this->data);
        }

        $responseClient->setStatusCode(curl_getinfo($ch, CURLINFO_RESPONSE_CODE));
        $responseClient->setBody(curl_exec($ch));

        curl_close($ch);

        $this->responseClient = $responseClient;

        return $this;
    }
    /**
     * @return ResponseClient
     * @throws RequestException
     */
    public function getResponse() : ResponseClient
    {
        if (!$this->responseClient instanceof ResponseClient) {
            throw new RequestException('Response does not exist. You have to send the request before asking for a response');
        }

        return $this->responseClient;
    }
    /**
     * @param string $uri
     * @return ResponseClient
     */
    public static function get(string $uri) : ResponseClient
    {
        $responseClient = new ResponseClient();

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $uri);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_HEADERFUNCTION, function($ch, $headerLine) use ($responseClient){
            $splittedHeader = preg_split('#:#', $headerLine);

            $headerName = $splittedHeader[0];
            $headerValue = null;

            if (array_key_exists(1, $splittedHeader)) {
                $headerValue = $splittedHeader[1];
            }

            $responseClient->addHeader($headerName, $headerValue);

            return strlen($headerLine);
        });

        $responseClient->setStatusCode(curl_getinfo($ch, CURLINFO_RESPONSE_CODE));
        $responseClient->setBody(curl_exec($ch));

        curl_close($ch);

        return $responseClient;
    }
    /**
     * @param string $uri
     * @param array|null $headers
     * @param null|string $data
     * @return ResponseClient
     */
    public static function post(string $uri, ?array $headers = null, ?string $data = null) : ResponseClient
    {
        $responseClient = new ResponseClient();

        $ch = \curl_init();

        curl_setopt($ch, CURLOPT_URL, $uri);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        curl_setopt($ch, CURLOPT_POST, 1);

        if (is_array($headers)) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        }

        if (is_string($data)) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        }

        curl_setopt($ch, CURLOPT_HEADERFUNCTION, function($ch, $headerLine) use ($responseClient){
            $splittedHeader = preg_split('#:#', $headerLine);

            $headerName = $splittedHeader[0];
            $headerValue = null;

            if (array_key_exists(1, $splittedHeader)) {
                $headerValue = $splittedHeader[1];
            }

            $responseClient->addHeader($headerName, $headerValue);

            return strlen($headerLine);
        });

        $responseClient->setStatusCode(curl_getinfo($ch, CURLINFO_RESPONSE_CODE));
        $responseClient->setBody(curl_exec($ch));

        curl_close($ch);

        return $responseClient;
    }
}