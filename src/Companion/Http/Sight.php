<?php

namespace Companion\Http;

use Companion\Config\CompanionConfig;
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
     * Send a request to the Companion API
     */
    public function request(CompanionRequest $request)
    {
        $client = new Client([
            'cookies' => Cookies::get(),
            'timeout' => CompanionSight::get('CLIENT_TIMEOUT'),
            'verify'  => CompanionSight::get('CLIENT_VERIFY'),
        ]);

        $uri     = $request->getUri();
        $options = $request->getOptions();

        // if async, return the request
        if (CompanionConfig::isAsync()) {
            return $client->{$request->method}($uri, $options);
        }

        // if we're not looping query, perform it and return response
        if (CompanionSight::get('QUERY_LOOPED') === false) {
            return $client->{$request->method}($uri, $options);
        }
        
        // query multiple times, as SE provide a "202" Accepted which is
        // their way of saying "Soon(tm)", so... try again.
        foreach (range(0, CompanionSight::get('QUERY_LOOP_COUNT')) as $i) {
            /** @var Response $response */
            $response = $client->{$request->method}($uri, $options);
        
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
            $promises[$requestId] = $this->request($request);
        }

        return $promises;
    }
}
