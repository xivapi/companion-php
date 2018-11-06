<?php

namespace Companion\Models;

use GuzzleHttp\Psr7\Response;

class CompanionResponse
{
    /** @var Response */
    private $response;
    private $uri;
    
    public function __construct(Response $response, $uri)
    {
        $this->response = $response;
        $this->uri = $uri;
    }
    
    public function getResponse(): Response
    {
        return $this->response;
    }
    
    public function getStatusCode(): int
    {
        return $this->response->getStatusCode();
    }
    
    public function getJson(): \stdClass
    {
        return json_decode($this->response->getBody());
    }
    
    public function getBody(): string
    {
        return (string)$this->response->getBody();
    }
    
    public function getHeaders(): array
    {
        return $this->response->getHeaders();
    }
    
    public function getUri(): string
    {
        return $this->uri;
    }
}
