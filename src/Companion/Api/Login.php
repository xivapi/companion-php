<?php

namespace Companion\Api;

use Companion\Config\Profile;
use Companion\Http\Sight;
use Companion\Models\CompanionRequest;
use Companion\Utils\ID;

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
     * Note: The `request_id` MUST BE THE SAME as the one used during the oauth callback uri
     *       which is called in: buildOAuthRedirectUri
     *
     * @POST("login/auth")
     */
    public function postAuth()
    {
        $req = new CompanionRequest([
            'uri'      => CompanionRequest::URI,
            'endpoint' => '/login/auth',
            'requestId' => ID::get(),
            'query'    => [
                'token'      => Profile::get('token'),
                'uid'        => Profile::get('uid'),
                'request_id' => ID::get()
            ],
        ]);
        
        return $this->post($req)->getJson();
    }
    
    /**
     * @GET("login/character")
     */
    public function getCharacter()
    {
        // log the character into the regional data center endpoint
        $req = new CompanionRequest([
            'uri'      => Profile::get('region'),
            'endpoint' => "/login/character",
        ]);
        
        return $this->get($req)->getJson();
    }
    
    /**
     * @GET("login/characters")
     */
    public function getCharacters()
    {
        $req = new CompanionRequest([
            'uri'      => CompanionRequest::URI,
            'endpoint' => '/login/characters',
        ]);
        
        return $this->get($req)->getJson();
    }
    
    /**
     * This will return the data center regional domain and log-ins this specific character
     *
     * @POST("login/characters/{characterId}")
     */
    public function loginCharacter(string $characterId = null)
    {
        // log the character into the base endpoint
        $req = new CompanionRequest([
            'uri'      => CompanionRequest::URI,
            'endpoint' => "/login/characters/{$characterId}",
            'json'     => [
                // This is the language of your app
                'appLocaleType' => 'EU'
            ]
        ]);
    
        $res = $this->post($req)->getJson();
        Profile::set('region', substr($res->region, 0, -1));
        
        // call get character on DC as this will log it in.
        $this->getCharacter();
    }
    
    public function getCharacterStatus()
    {
        $req = new CompanionRequest([
            'uri'      => Profile::get('region'),
            'endpoint' => '/character/login-status',
        ]);
    
        return $this->get($req)->getJson();
    }
    
    /**
     * Get the uri region for the logged in character.
     * Sometimes returns blank... Unsure why
     *
     * @GET("login/region")
     */
    public function getRegion()
    {
        $req = new CompanionRequest([
            'uri'      => CompanionRequest::URI,
            'endpoint' => '/login/region',
        ]);
        
        return $this->get($req)->getJson();
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
                'uid'       => Profile::get('uid'),
                'platform'  => Account::PLATFORM_ANDROID,
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
            'uri'      => Profile::get('region'),
            'endpoint' => '/login/advertising-id',
            'json'     => [
                // This UUID always seems to be the same
                'advertisingId'     => 'CDC61D75-5F00-4516-B6A5-F353F1C03179',
                'isTrackingEnabled' => 1,
            ]
        ]);
    
        return $this->post($req);
    }
    
    /**
     * FCM Token = Firebase Cloud Messaging token - Used for App Notifications
     * - https://firebase.google.com/docs/cloud-messaging
     *
     * Figured out via java: ffxiv/sight/e/o.java
     *
     * No response body for this request
     * @POST("login/fcm-token")
     */
    public function fcmToken()
    {
        $req = new CompanionRequest([
            'uri'      => Profile::get('region'),
            'endpoint' => '/login/fcm-token',
            'json'     => [
                'fcmToken' => ''
            ]
        ]);
    
        return $this->post($req);
    }
}
