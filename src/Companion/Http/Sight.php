<?php

namespace Companion\Http;

use Companion\Models\SightRequest;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\RequestOptions;
use Ramsey\Uuid\Uuid;

/**
 * The name of the CompanionApp API is "Sight"
 */
class Sight
{
    const ENDPOINT      = 'https://companion{region}.finalfantasyxiv.com';

    const REGION_EU     = '-eu';
    const REGION_NA     = '-na';
    const REGION_JA     = '-ja';

    const METHOD_GET    = 'get';
    const METHOD_POST   = 'post';
    const METHOD_PUT    = 'put';
    const METHOD_DELETE = 'delete';

    const CONTENT_TYPE  = 'application/json;charset=utf-8';
    const USER_AGENT    = 'ffxivcomapp-e/1.0.1.0 CFNetwork/974.2.1 Darwin/18.0.0';
    const VERSION_PATH  = '/sight-v060/sight';
    const TIMEOUT_SEC   = 15;
    const MAX_TRIES     = 15;
    const DELAY_MS      = 500000; // 0.5s

    /**
     * @param SightRequest $request
     * @return null|\stdClass
     * @throws \Exception
     */
    protected function request(SightRequest $request): ?\stdClass
    {
        $client = new Client([
            'base_uri' => $request->getBaseUri(),
            'timeout'  => self::TIMEOUT_SEC
        ]);

        // append some extra headers
        $request
            ->addHeader('request-id', Uuid::uuid4()->toString())
            ->addHeader('Content-Type', self::CONTENT_TYPE)
            ->addHeader('User-Agent', self::USER_AGENT);

        // todo - handle token

        // send request
        try {
            // add headers
            $options = [
                RequestOptions::HEADERS => $request->getHeaders()
            ];

            if ($json = $request->getJson()) {
                $options[RequestOptions::JSON] = $json;
            }

            if ($query = $request->getQuery()) {
                $options[RequestOptions::QUERY] = $query;
            }

            foreach (range(0, self::MAX_TRIES) as $i) {
                $endpoint = self::VERSION_PATH . $request->getEndpoint();

                /** @var Response $response */
                $response = $client->{$request->getMethod()}($endpoint, $options);

                // if the response is 202, then we wait and try again
                if ($response->getStatusCode() == 202) {
                    usleep(self::DELAY_MS);
                    continue;
                }

                // valid response, return json
                if ($response->getStatusCode() == 200) {
                    // todo - this may not always be JSON (eg login forms)
                    return json_decode((string)$response->getBody());
                }

                // todo - add custom library exception
                throw new \Exception('Could not fetch data from Companion API');
            }
        } catch (\Exception $ex) {
            // todo - do something with it?
            throw $ex;
        }

        return null;
    }
}
