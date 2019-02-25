<?php

namespace Companion\Api;

use Companion\Config\CompanionTokenManager;
use Companion\Http\Sight;
use Companion\Models\CompanionRequest;
use Companion\Models\Method;
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
        return $this->json(
            new CompanionRequest([
                'method'   => Method::POST,
                'uri'      => CompanionRequest::URI,
                'endpoint' => '/login/auth',
                'requestId' => ID::get(),
                'query'    => [
                    'token'      => CompanionTokenManager::getToken()->token,
                    'uid'        => CompanionTokenManager::getToken()->uid,
                    'request_id' => ID::get()
                ],
            ])
        );
    }
    
    /**
     * @GET("login/character")
     */
    public function getCharacter()
    {
        // log the character into the regional data center endpoint
        $res = $this->json(
            new CompanionRequest([
                'method'   => Method::GET,
                'uri'      => CompanionTokenManager::getToken()->region,
                'endpoint' => "/login/character",
            ])
        );

        // record character in token
        CompanionTokenManager::getToken()->character = $res->character->cid;
        CompanionTokenManager::getToken()->server = $res->character->world;
        CompanionTokenManager::saveTokens();
        
        return $res;
    }
    
    /**
     * @GET("login/characters")
     */
    public function getCharacters()
    {
        return $this->json(
            new CompanionRequest([
                'method'   => Method::GET,
                'uri'      => CompanionRequest::URI,
                'endpoint' => '/login/characters',
            ])
        );
    }
    
    /**
     * This will return the data center regional domain and log-ins this specific character
     *
     * @POST("login/characters/{characterId}")
     */
    public function loginCharacter(string $characterId = null)
    {
        // log the character into the base endpoint
        $res = $this->json(
            new CompanionRequest([
                'method'   => Method::POST,
                'uri'      => CompanionRequest::URI,
                'endpoint' => "/login/characters/{$characterId}",
                'json'     => [
                    // This is the language of your app
                    'appLocaleType' => 'EU'
                ]
            ])
        );
        
        CompanionTokenManager::getToken()->region = substr($res->region, 0, -1);
        CompanionTokenManager::saveTokens();
        
        // call get character on DC as this will log it in.
        $this->getCharacter();
    }
    
    public function getCharacterStatus()
    {
        return $this->json(
            new CompanionRequest([
                'method'   => Method::GET,
                'uri'      => CompanionTokenManager::getToken()->region,
                'endpoint' => '/character/login-status',
            ])
        );
    }
    
    /**
     * Get the uri region for the logged in character.
     * Sometimes returns blank... Unsure why
     *
     * @GET("login/region")
     */
    public function getRegion()
    {
        return $this->json(
            new CompanionRequest([
                'method'   => Method::GET,
                'uri'      => CompanionRequest::URI,
                'endpoint' => '/login/region',
            ])
        );
    }
    
    /**
     * refresh token (you get a new one)
     *
     * @POST("login/token")
     */
    public function postToken()
    {
        return $this->json(
            new CompanionRequest([
                'method'   => Method::POST,
                'uri'      => CompanionRequest::URI,
                'endpoint' => '/login/token',
                'json'     => [
                    // not sure if this has to be the same UID or a new one
                    // if it's a new one, need userId + salt
                    'uid'       => CompanionTokenManager::getToken()->uid,
                    'platform'  => Account::PLATFORM_ANDROID,
                ]
            ])
        );
    }
    
    /**
     * todo - investigate
     * @POST("login/advertising-id")
     */
    public function advertisingId()
    {
        return $this->json(
            new CompanionRequest([
                'method'   => Method::POST,
                'uri'      => CompanionTokenManager::getToken()->region,
                'endpoint' => '/login/advertising-id',
                'json'     => [
                    // This UUID always seems to be the same
                    'advertisingId'     => 'CDC61D75-5F00-4516-B6A5-F353F1C03179',
                    'isTrackingEnabled' => 1,
                ]
            ])
        );
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
        return $this->json(
            new CompanionRequest([
                'method'   => Method::POST,
                'uri'      => CompanionTokenManager::getToken()->region,
                'endpoint' => '/login/fcm-token',
                'json'     => [
                    'fcmToken' => ''
                ]
            ])
        );
    }
}
