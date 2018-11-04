<?php

namespace Companion\Models;

use GuzzleHttp\Psr7\Response;

class CompanionResponse
{
    /** @var Response */
    private $response;
    
    public function __construct(Response $response)
    {
        $this->response = $response;
    }
    
    public function getResponse(): Response
    {
        return $this->response;
    }
    
    public function getStatusCode()
    {
        return $this->response->getStatusCode();
    }
    
    public function getJson()
    {
        return json_decode($this->response->getBody());
    }
}
