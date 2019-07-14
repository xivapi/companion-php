<?php

namespace Companion\Http;

use Companion\Config\CompanionSight;
use Companion\Models\CompanionRequest;
use Companion\Models\CompanionResponse;
use GuzzleHttp\Client;
use GuzzleHttp\Promise;
use GuzzleHttp\Psr7\Response;

/**
 * The name of the CompanionApp API is "Sight"
 */
class Sight
{
    /**
     * Perform a sight request using a CompanionRequest
     */
    public function json(CompanionRequest $req)
    {
        // if async, we don't need to do anything
        if (CompanionSight::isAsync()) {
            return $req;
        }
        
        return $this->handleRequest($req)->getJson();
    }
    
    /**
     * Perform a request and return the body using a CompanionRequest
     */
    public function body(CompanionRequest $req)
    {
        return $this->handleRequest($req)->getBody();
    }
    
    /**
     * Perform a request and return the status using a CompanionRequest
     */
    public function statusCode(CompanionRequest $req)
    {
        return $this->handleRequest($req)->getStatusCode();
    }
    
    /**
     * Send a request to the Companion API
     */
    private function handleRequest(CompanionRequest $request)
    {
        $client = new Client([
            'cookies' => Cookies::get(),
            'timeout' => CompanionSight::get('CLIENT_TIMEOUT'),
            'verify'  => CompanionSight::get('CLIENT_VERIFY'),
        ]);

        $uri     = $request->getUri();
        $options = $request->getOptions();

        // if async, return the request
        if (CompanionSight::isAsync()) {
            return $client->{$request->method}($uri, $options);
        }

        // if we're not looping query, perform it and return response
        if (CompanionSight::get('QUERY_LOOPED') === false) {
            return new CompanionResponse(
                $client->{$request->method}($uri, $options), $uri
            );
        }

        $loopCount = CompanionSight::get('QUERY_LOOP_COUNT');
        $loopDelay = CompanionSight::get('QUERY_DELAY_MS');
        
        // query multiple times, as SE provide a "202" Accepted which is
        // their way of saying "Soon(tm)", so... try again.
        foreach (range(0, $loopCount) as $i) {
            /** @var Response $response */
            $response = $client->{$request->method}($uri, $options);
        
            // if the response is 202, try again
            if (!$request->return202 && $response->getStatusCode() == 202) {
                // wait half a second
                usleep($loopDelay);
                continue;
            }
        
            return new CompanionResponse($response, $uri);
        }

        $loopDelayText = ceil($loopDelay / 1000);
        $lastResponseCode = isset($response) ? $response->getStatusCode() : 'None';
        throw new \Exception("No valid response from companion, Loops: {$loopCount}/{$loopDelayText}ms - Last code: {$lastResponseCode}");
    }

    // ------------------------------------------------
    // Async logic
    // ------------------------------------------------

    /**
     * Settle each request
     */
    public function settle($promises)
    {
        return Promise\settle(
            $this->buildPromiseRequests($promises)
        );
    }

    /**
     * Settle each request
     */
    public function unwrap($promises)
    {
        return Promise\unwrap(
            $this->buildPromiseRequests($promises)
        );
    }

    /**
     * Fulfill promise results
     */
    public function handle($results): \stdClass
    {
        $unwrapped = (Object)[];
        foreach ($results as $key => $response) {
            // convert to object
            $response = (Object)$response;

            // unwrap to our key
            $unwrapped->{$key} = ($response->state == 'fulfilled')
                ? (new CompanionResponse($response->value))->getJson()
                : (Object)[
                    'error'  => true,
                    'state'  => $response->state,
                    'reason' => get_class($response->reason) ." -- ". $response->reason->getMessage()
                ];
        }

        return $unwrapped;
    }

    /**
     * Builds up promise requests using static request ids
     */
    private function buildPromiseRequests($promises)
    {
        /** @var CompanionRequest $request */
        foreach ($promises as $requestId => $request) {
            // force an assigned request id
            $request->setRequestId($requestId);

            // if the request is not already async converted, do it
            if ($request->async === false) {
                // modify the method to async
                $request->setMethod("{$request->method}Async");
    
                // ensure request is marked as async so we don't repeat this step
                $request->setAsync();
            }

            // run the async request
            $promises[$requestId] = $this->handleRequest($request);
        }

        return $promises;
    }
}
