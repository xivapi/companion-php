<?php

namespace Companion\Http;

use Companion\Models\CompanionRequest;
use Companion\Models\CompanionResponse;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;

/**
 * The name of the CompanionApp API is "Sight"
 */
class Sight
{
    protected function get(CompanionRequest $request): CompanionResponse
    {
        return $this->request('get', $request);
    }
    
    protected function post(CompanionRequest $request): CompanionResponse
    {
        return $this->request('post', $request);
    }
    
    protected function put(CompanionRequest $request): CompanionResponse
    {
        return $this->request('put', $request);
    }
    
    protected function delete(CompanionRequest $request): CompanionResponse
    {
        return $this->request('delete', $request);
    }
    
    protected function patch(CompanionRequest $request): CompanionResponse
    {
        return $this->request('patch', $request);
    }

    private function request(string $method, CompanionRequest $request): CompanionResponse
    {
        $client  = new Client([ 'timeout' => 15 ]);
        
        $uri     = $request->getUri();
        $options = array_filter($request->getOptions());
        
        foreach (range(0, 15) as $i) {
            /** @var Response $response */
            $response = $client->{$method}($uri, $options);
        
            // if the response is 202, try again
            if (!$request->return202 && $response->getStatusCode() == 202) {
                usleep(500000);
                continue;
            }
        
            return new CompanionResponse($response);
        }
    }
}
