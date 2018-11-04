<?php

namespace Companion\Api;

use Companion\Config\SightConfig;
use Companion\Http\Sight;
use Companion\Models\CompanionRequest;
use Ramsey\Uuid\Uuid;

/**
 * Based on: LoginService.java
 */
class Login extends Sight
{
    /**
     * Methods: delete, post
     */
    public function auth()
    {
        $uuid = Uuid::uuid4()->toString();
        
        $req = new CompanionRequest([
            'uri'      => CompanionRequest::URI,
            'endpoint' => '/login/auth',
            'headers'  => [
                'request-id' => $uuid,
            ],
            'query'    => [
                'token'      => SightConfig::get('token'),
                'uid'        => SightConfig::get('uid'),
                'request_id' => $uuid
            ],
        ]);
    
        return $this->post($req)->getJson();
    }
    
    /**
     * Methods: get
     */
    public function character()
    {
        $req = new CompanionRequest([
            'uri'      => CompanionRequest::URI_EU,
            'endpoint' => '/login/character',
        ]);
    
        return $this->get($req)->getJson();
    }
    
    /**
     * Login with a specific character,
     * this will return the region
     *
     * Methods: get, post(id)
     */
    public function characters(string $id = null)
    {
        if ($id) {
            $req = new CompanionRequest([
                'uri'      => CompanionRequest::URI,
                'endpoint' => "/login/characters/{$id}",
                'json'     => [
                    'appLocaleType' => 'EU' // not sure what this is
                ]
            ]);
            
            return $this->post($req)->getJson();
        }
        
        $req = new CompanionRequest([
            'uri'      => CompanionRequest::URI,
            'endpoint' => '/login/characters',
        ]);
        
        return $this->get($req)->getJson();
    }
    
    /**
     * Methods: get
     */
    public function region()
    {
        $req = new CompanionRequest([
            'uri'      => CompanionRequest::URI,
            'endpoint' => '/login/region',
        ]);
    
        return $this->get($req)->getJson();
    }
    
    /**
     * Regenerate a token
     *
     * Methods: post
     */
    public function token()
    {
        $req = new CompanionRequest([
            'uri'      => CompanionRequest::URI,
            'endpoint' => '/login/token',
            'json'     => [
                // not sure if this has to be the same UID or a new one
                // if it's a new one, need userId + salt
                'uid'       => SightConfig::get('uid'),
                'platform'  => OAuth::PLATFORM_ANDROID,
            ]
        ]);
    
        return $this->post($req)->getJson();
    }
    
    /**
     * Methods: post
     */
    public function advertisingId()
    {
        $req = new CompanionRequest([
            'uri'      => CompanionRequest::URI_EU,
            'endpoint' => '/login/advertising-id',
            'json'     => [
                // maintain a static UUID?
                'advertisingId'     => 'CDC61D75-5F00-4516-B6A5-F353F1C03179',
                'isTrackingEnabled' => 1,
            ]
        ]);
    
        return $this->post($req)->getJson();
    }
    
    /**
     * Methods: post
     */
    public function fcmToken()
    {
        $req = new CompanionRequest([
            'uri'      => CompanionRequest::URI_EU,
            'endpoint' => '/login/fcm-token',
            'json'     => [
                // eIFDHHYjFIM:APA91bFqbkO67xob2YlF-nEWZaG2vwJ_WcxLnpJbcMw415vvF-xtNNtQGRm8V28D67Bny7DXb-Acagx7MfBXob1F510hNKdLoA3sWWfNZ04oJSV2wCvjV1L1XfImG9pn7uXtOdYONNbl
                'fcmToken' => 'not sure what to do here?'
            ]
        ]);
    
        return $this->post($req)->getJson();
    }
}
