<?php

namespace Companion\Models;

use Companion\Http\Sight;

class SightRequest
{
    /** @var string */
    private $method;
    /** @var string */
    private $region;
    /** @var string */
    private $endpoint;
    /** @var string */
    private $referer;
    /** @var array */
    private $query = [];
    /** @var array */
    private $json = [];
    /** @var array */
    private $headers = [];
    /** @var array */
    private $formData = [];
    /** @var array */
    private $body = [];
    /** @var bool */
    private $squareEnixDomain = false;

    /**
     * Returns the correct base uri for the DC request
     */
    public function getBaseUri()
    {
        $uri = $this->isSquareEnixDomain()
            ? Sight::ENDPOINT_SE
            : Sight::ENDPOINT;
        
        $uri = str_ireplace('{region}', $this->region, $uri);
        return $uri;
    }

    public function getMethod(): string
    {
        return strtolower($this->method);
    }

    public function setMethod(string $method): SightRequest
    {
        $this->method = $method;
        return $this;
    }

    public function getRegion(): string
    {
        return $this->region;
    }

    public function setRegion(string $region): SightRequest
    {
        $this->region = $region;
        return $this;
    }

    public function getEndpoint(): string
    {
        $endpoint = $this->endpoint;
        $endpoint = $this->isSquareEnixDomain() ? $endpoint : Sight::VERSION_PATH . $endpoint;
        return $endpoint;
    }

    public function setEndpoint(string $endpoint): SightRequest
    {
        $this->endpoint = $endpoint;
        return $this;
    }
    
    public function getReferer(): ?string
    {
        return $this->referer;
    }
    
    public function setReferer(string $referer): SightRequest
    {
        $this->referer = $referer;
        return $this;
    }
    
    public function getQuery(): array
    {
        return $this->query;
    }

    public function setQuery(array $query): SightRequest
    {
        $this->query = $query;
        return $this;
    }

    public function addQuery($field, $value): SightRequest
    {
        $this->query[$field] = $value;
        return $this;
    }

    public function getJson(): array
    {
        return $this->json;
    }

    public function setJson(array $json): SightRequest
    {
        $this->json = $json;
        return $this;
    }

    public function addJson($field, $value): SightRequest
    {
        $this->json[$field] = $value;
        return $this;
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function setHeaders(array $headers): SightRequest
    {
        $this->headers = $headers;
        return $this;
    }

    public function addHeader($field, $value): SightRequest
    {
        $this->headers[$field] = $value;
        return $this;
    }
    
    public function getFormData(): array
    {
        return $this->formData;
    }
    
    public function setFormData(array $formData): SightRequest
    {
        $this->formData = $formData;
        return $this;
    }
    
    public function addFormData($field, $value): SightRequest
    {
        $this->formData[$field] = $value;
        return $this;
    }
    
    public function getBody(): array
    {
        return $this->body;
    }
    
    public function setBody(array $body): SightRequest
    {
        $this->body = $body;
        return $this;
    }
    
    public function addBody($field, $value): SightRequest
    {
        $this->body[$field] = $value;
        return $this;
    }
    
    public function isSquareEnixDomain(): bool
    {
        return $this->squareEnixDomain;
    }
    
    public function setSquareEnixDomain(bool $squareEnixDomain)
    {
        $this->squareEnixDomain = $squareEnixDomain;
        return $this;
    }
}
