<?php

namespace SDKBuilder\Response;

class ResponseClient
{
    /**
     * @var string $body
     */
    private $body;
    /**
     * @var int $statusCode;
     */
    private $statusCode;
    /**
     * @var array $headers
     */
    private $headers = array();
    /**
     * @param string $headerName
     * @return bool
     */
    public function hasHeader(string $headerName) : bool
    {
        return array_key_exists($headerName, $this->headers);
    }
    /**
     * @param array $headers
     * @return ResponseClient
     */
    public function setHeaders(array $headers) : ResponseClient
    {
        $this->headers = $headers;
    }
    /**
     * @param string $headerName
     * @param string $headerValue
     * @return null|ResponseClient
     */
    public function addHeader(string $headerName, ?string $headerValue) : ?ResponseClient
    {
        if (!$this->hasHeader($headerName)) {
            return null;
        }

        $this->headers[$headerName] = $headerValue;

        return $this;
    }
    /**
     * @param string $headerName
     * @return null|string
     */
    public function getHeader(string $headerName) : ?string
    {
        if (!$this->hasHeader($headerName)) {
            return null;
        }

        return $this->headers[$headerName];
    }
    /**
     * @param int $statusCode
     * @return ResponseClient
     */
    public function setStatusCode(int $statusCode) : ResponseClient
    {
        $this->statusCode = $statusCode;

        return $this;
    }
    /**
     * @return int
     */
    public function getStatusCode() : int
    {
        return $this->statusCode;
    }
    /**
     * @param string $body
     * @return ResponseClient
     */
    public function setBody(string $body) : ResponseClient
    {
        $this->body = $body;

        return $this;
    }
    /**
     * @return string
     */
    public function getBody() : string
    {
        return $this->body;
    }
}