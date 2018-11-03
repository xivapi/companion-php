<?php

namespace Companion\Http;

use Companion\Config\SightConfig;
use Companion\Exceptions\InvalidStatusCodeException;
use Companion\Models\SightRequest;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\RequestOptions;
use Ramsey\Uuid\Uuid;

/**
 * The name of the CompanionApp API is "Sight"
 */
class Sight
{
    const ENDPOINT      = 'https://companion{region}.finalfantasyxiv.com';
    const ENDPOINT_SE   = 'https://secure.square-enix.com';
    
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
     * @return null|\stdClass|string|array
     * @throws \Exception
     */
    protected function request(SightRequest $request, $returnResponse = false)
    {
        $client = new Client([
            'base_uri'          => $request->getBaseUri(),
            'timeout'           => self::TIMEOUT_SEC,
        ]);

        // append some extra headers
        $request
            ->addHeader('request-id', Uuid::uuid4()->toString())
            ->addHeader('Content-Type', self::CONTENT_TYPE)
            ->addHeader('User-Agent', self::USER_AGENT);

        // add stored token
        if ($token = SightConfig::get('token')) {
            $request->addHeader('token', $token);
            echo "Using token: {$token} \n";
        }
    
        // add any referers
        if ($referer = $request->getReferer()) {
            $request->addHeader('Referer', $referer);
        }
        
        // send request
        try {
            // add headers
            $options = [
                RequestOptions::HEADERS => $request->getHeaders(),
                RequestOptions::ALLOW_REDIRECTS => false,
            ];
    
            if ($json = $request->getJson()) {
                $options[RequestOptions::JSON] = $json;
            }
    
            if ($query = $request->getQuery()) {
                $options[RequestOptions::QUERY] = $query;
            }
            
            if ($formData = $request->getFormData()) {
                $options[RequestOptions::FORM_PARAMS] = $formData;
            }
    
            if ($body = $request->getBody()) {
                $options[RequestOptions::BODY] = $body;
            }
    
            foreach (range(0, self::MAX_TRIES) as $i) {
                /** @var Response $response */
                $response = $client->{$request->getMethod()}(
                    $request->getEndpoint(),
                    $options
                );
        
                // if the response is 202, then we wait and try again
                if ($response->getStatusCode() == 202) {
                    usleep(self::DELAY_MS);
                    continue;
                }
                
                // if 302 found
                if ($response->getStatusCode() == 302) {
                    return $response;
                }
        
                // valid response, return json
                if ($response->getStatusCode() == 200) {
                    if ($returnResponse) {
                        return $response;
                    }

                    $body = (string)$response->getBody();
                    
                    if ($response->getHeader('Content-Type')[0] == 'text/html;charset=utf-8') {
                        return $body;
                    }
                    
                    return json_decode($body);
                }
        
                throw new InvalidStatusCodeException('Could not fetch data from Companion API');
            }
        } catch (ClientException $ex) {
            $response = $ex->getResponse();

            print_r((string)$response->getBody());
            
            throw $ex;
        } catch (\Exception $ex) {
            throw $ex;
        }

        return null;
    }
    
    /**
     * @param SightRequest $request
     * @return array
     * @throws \Exception
     */
    protected function response(SightRequest $request): Response
    {
        return $this->request($request, true);
    }
}
