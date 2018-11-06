<?php

namespace Companion\Api;

use Companion\Config\Profile;
use Companion\Http\Cookies;
use Companion\Http\Pem;
use Companion\Http\Sight;
use Companion\Models\CompanionRequest;
use Companion\Utils\PBKDF2;
use Companion\Utils\ID;
use phpseclib\Crypt\RSA;

/**
 * APK Java class: common/a/g.java
 * Based on libpompom implementation: https://github.com/Minoost/libpompom-sharp
 * - https://github.com/Minoost/libpompom-sharp/blob/master/libpompom.sharp/Api/Session.cs
 * - https://github.com/Minoost/libpompom-sharp/blob/master/libpompom.sharp/Models/Response/Session.cs
 */
class Account extends Sight
{
    const PLATFORM_IOS      = 1;
    const PLATFORM_ANDROID  = 2;
    
    /** @var string */
    private $loginUri;
    
    /**
     * Login to an Square-Enix FFXIV Account
     */
    public function login(string $username, string $password)
    {
        // some clean up
        Cookies::clear();
        ID::refresh();
        
        // generate a new token and build login uri
        $this->getLoginUrl();
        
        // attempt to auto-login
        $this->autoLoginToProfileAccount($username, $password);
    
        // authenticate
        if ((new Login())->postAuth()->status !== 200) {
            throw new \Exception('Token status could not be validated');
        } else {
            echo "All good!\n\n";
        }
    }
    
    /**
     * Login to the character that is registered to this config profile
     */
    public function getLoginUrl()
    {
        // Generate a new user uuid
        Profile::set('userId', ID::uuid());
        
        // Get a token from SE
        $response = $this->getToken();
        Profile::set('token', $response->token);
        Profile::set('salt', $response->salt);
        
        // Get OAuth URI
        $this->loginUri = $this->buildLoginUri();
        return $this->loginUri;
    }
    
    /**
     * Get a new valid token + salt from SE
     * @POST("/login/token")
     */
    public function getToken()
    {
        $rsa = new RSA();
        $rsa->loadKey(Pem::get());
        $rsa->setEncryptionMode(RSA::ENCRYPTION_PKCS1);
        $uid = base64_encode($rsa->encrypt(Profile::get('userId')));
        
        return $this->post(new CompanionRequest([
            'uri'       => CompanionRequest::URI,
            'endpoint'  => '/login/token',
            'requestId' => ID::get(),
            'json'      => [
                'platform'  => self::PLATFORM_ANDROID, // < THIS IS IMPORTANT
                'uid'       => $uid
            ]
        ]))->getJson();
    }
    
    /**
     * Automatically login to an account saved.
     * @throws \Exception
     */
    private function autoLoginToProfileAccount(string $username, string $password)
    {
        $html = $this->get(new CompanionRequest([
            'uri'       => $this->loginUri,
            'version'   => '',
            'requestId' => ID::get()
        ]))->getBody();
        
        // todo - convert to: https://github.com/xivapi/companion-php
        preg_match('/(.*)action="(?P<action>[^"]+)">/', $html, $matches);
        $action = trim($matches['action']);
        preg_match('/(.*)name="_STORED_" value="(?P<stored>[^"]+)">/', $html, $matches);
        $stored = trim($matches['stored']);
    
        // build payload to submit form
        $formData = [
            '_STORED_' => $stored,
            'sqexid'   => $username,
            'password' => $password,
        ];
        
        $res = $this->post(new CompanionRequest([
            'uri'       => CompanionRequest::URI_SE . "/oauth/oa/{$action}",
            'version'   => '',
            'requestId' => ID::get(),
            'form'      => $formData,
        ]));
        
        $html = $res->getBody();
    
        // todo - convert to: https://github.com/xivapi/companion-php
        preg_match('/(.*)action="(?P<action>[^"]+)">/', $html, $matches);
        $action = html_entity_decode($matches['action']);
        preg_match('/(.*)name="cis_sessid" type="hidden" value="(?P<cis_sessid>[^"]+)">/', $html, $matches);
        $cis_sessid = trim($matches['cis_sessid']);
        
        $formData = [
            'cis_sessid' => $cis_sessid,
            'provision'  => '', // ??? - Don't know what this is but doesn't seem to matter
            '_c'         => 1   // ??? - Don't know what this is but doesn't seem to matter
        ];
        
        // submit to companion to confirm cis_sessid
        $req = new CompanionRequest([
            'uri'       => $action,
            'form'      => $formData,
            'version'   => '',
            'requestId' => ID::get(),
            'return202' => true,
        ]);
        
        // this will be another form with some other bits that the app just forcefully submits via js
        if ($this->post($req)->getStatusCode() !== 202) {
            throw new \Exception('Login status could not be validated.');
        }
    }
    
    /**
     * Build the Login uri
     */
    private function buildLoginUri()
    {
        return CompanionRequest::SQEX_AUTH_URI .'?'. http_build_query([
            'client_id'     => 'ffxiv_comapp',
            'lang'          => 'en-us',
            'response_type' => 'code',
            'redirect_uri'  => $this->buildCompanionOAuthRedirectUri(),
        ]);
    }
    
    /**
     * Build the login redirect uri
     */
    private function buildCompanionOAuthRedirectUri()
    {
        $uid = PBKDF2::encrypt(
            Profile::get('userId'),
            Profile::get('salt')
        );
        
        Profile::set('uid', $uid);
        
        return CompanionRequest::OAUTH_CALLBACK .'?'. http_build_query([
                'token'      => Profile::get('token'),
                'uid'        => $uid,
                'request_id' => ID::get(),
        ]);
    }
}
