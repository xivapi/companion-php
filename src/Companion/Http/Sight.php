<?php

namespace Companion\Http;

use Companion\Config\CompanionConfig;
use Companion\Config\CompanionSight;
use Companion\Models\CompanionRequest;
use Companion\Models\CompanionResponse;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\RequestOptions;
use GuzzleHttp\TransferStats;

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
    
    /**
     * Send a request to the Companion API
     */
    private function request(string $method, CompanionRequest $request): CompanionResponse
    {
        $client = new Client([
            'cookies' => Cookies::get(),
            'timeout' => CompanionSight::get('CLIENT_TIMEOUT'),
            'verify'  => CompanionSight::get('CLIENT_VERIFY'),
        ]);

        $uri     = $request->getUri();
        $options = $request->getOptions();
        
        // query multiple times, as SE provide a "202" Accepted which is
        // their way of saying "Soon(tm)", so... try again.
        foreach (range(0, CompanionSight::get('QUERY_LOOP_COUNT')) as $i) {
            /** @var Response $response */
            $response = $client->{$method}($uri, $options);
        
            // if the response is 202, try again
            if (!$request->return202 && $response->getStatusCode() == 202) {
                // wait half a second
                usleep(CompanionSight::get('QUERY_DELAY_MS'));
                continue;
            }
        
            return new CompanionResponse($response, $uri);
        }
        
        throw new \Exception('Did not receive any valid HTTP code from the Companion API after 15 seconds and 30 attempts.');
    }
}
