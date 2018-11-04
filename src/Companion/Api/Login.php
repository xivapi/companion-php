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
     * todo - implement
     * @DELETE("login/auth")
     */
    public function deleteAuth()
    {
    
    }
    
    /**
     * @POST("login/auth")
     */
    public function postAuth()
    {
        // unsure if request-id and query request_id actually need to match..
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
     * @GET("login/character")
     */
    public function getCharacter()
    {
        $req = new CompanionRequest([
            'uri'      => CompanionRequest::URI,
            'endpoint' => '/login/character',
        ]);
        
        return $this->get($req)->getJson();
    }
    
    /**
     * If you provide the character id, you will login as this character.
     *
     * @GET("login/characters")
     * @POST("login/characters/{characterId}")
     */
    public function getCharacters(string $characterId = null)
    {
        if ($characterId) {
            $req = new CompanionRequest([
                'uri'      => CompanionRequest::URI,
                'endpoint' => "/login/characters/{$characterId}",
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
     * Get the uri region for the logged in character, this
     * will save to the config and data center specific requests will
     * use this region endpoint
     *
     * @GET("login/region")
     */
    public function getRegion()
    {
        $req = new CompanionRequest([
            'uri'      => CompanionRequest::URI,
            'endpoint' => '/login/region',
        ]);
        
        $res = $this->get($req)->getJson();
        SightConfig::save('region', $res->region);
        
        return $res;
    }
    
    /**
     * refresh token (you get a new one)
     *
     * @POST("login/token")
     */
    public function postToken()
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
     * todo - investigate
     * @POST("login/advertising-id")
     */
    public function advertisingId()
    {
        $req = new CompanionRequest([
            'uri'      => SightConfig::get('region'),
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
     * todo - investigate
     * @POST("login/fcm-token")
     */
    public function fcmToken()
    {
        $req = new CompanionRequest([
            'uri'      => SightConfig::get('region'),
            'endpoint' => '/login/fcm-token',
            'json'     => [
                // eIFDHHYjFIM:APA91bFqbkO67xob2YlF-nEWZaG2vwJ_WcxLnpJbcMw415vvF-xtNNtQGRm8V28D67Bny7DXb-Acagx7MfBXob1F510hNKdLoA3sWWfNZ04oJSV2wCvjV1L1XfImG9pn7uXtOdYONNbl
                'fcmToken' => 'not sure what to do here?'
            ]
        ]);
    
        return $this->post($req)->getJson();
    }
}
