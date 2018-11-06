<?php

namespace Companion\Api;

use Companion\Config\SightConfig;
use Companion\Http\Sight;
use Companion\Models\CompanionRequest;
use Companion\Utils\PBKDF2;
use Companion\Utils\RequestId;
use phpseclib\Crypt\RSA;

/**
 * APK Java class: common/a/g.java
 * Based on libpompom implementation: https://github.com/Minoost/libpompom-sharp
 * - https://github.com/Minoost/libpompom-sharp/blob/master/libpompom.sharp/Api/Session.cs
 * - https://github.com/Minoost/libpompom-sharp/blob/master/libpompom.sharp/Models/Response/Session.cs
 */
class OAuth extends Sight
{
    const PLATFORM_IOS      = 1;
    const PLATFORM_ANDROID  = 2;
    
    private $userId;
    private $token;
    private $uri;
    
    /**
     * Login to the character that is registered to this config profile
     */
    public function login()
    {
        SightConfig::clearCookies();
        RequestId::refresh();
        
        // Generate a new user uuid
        $this->userId = RequestId::generate();
        SightConfig::save('userId', $this->userId);
        
        // Get a token from SE
        $this->token = $this->getToken();
        SightConfig::save('token', $this->token->token);
        SightConfig::save('salt', $this->token->salt);
        echo "Token: {$this->token->token}\n";
        
        // Get OAuth URI
        $this->uri = $this->getOAuthUri();
        echo "Login form URI: {$this->uri}\n";
        echo "";
        
        // attempt to auto-login
        $this->autoLogin();
        
        // authenticate
        echo "Checking token authentication\n";
        $login = new Login();
        $res = $login->postAuth();
        if ($res->status === 200) {
            echo "Token status good!\n";
        } else {
            throw new \Exception('Token status could not be validated');
        }
    }
    
    public function autoLogin()
    {
        $username = SightConfig::get('username');
        $password = SightConfig::get('password');

        if (!$username || !$password) {
            throw new \Exception("Username and/or Password not set in profile config.");
        }
        
        $html = $this->get(new CompanionRequest([
            'uri'       => $this->uri,
            'version'   => '',
            'requestId' => RequestId::get()
        ]))->getBody();
        
        // grab submit action
        echo "Parsing login form for _STORED_ CSRF token ...\n";
        preg_match('/(.*)action="(?P<action>[^"]+)">/', $html, $matches);
        $action = trim($matches['action']);
        
        // grab _STORED_ value
        preg_match('/(.*)name="_STORED_" value="(?P<stored>[^"]+)">/', $html, $matches);
        $stored = trim($matches['stored']);
    
        // build payload to submit form
        $formData = [
            '_STORED_' => $stored,
            'sqexid'   => $username,
            'password' => $password,
        ];
        
        echo "Submitting login details ...\n";
        $res = $this->post(new CompanionRequest([
            'uri'       => CompanionRequest::URI_SE . "/oauth/oa/{$action}",
            'version'   => '',
            'requestId' => RequestId::get(),
            'form'      => $formData,
        ]));
        
        if ($res->getStatusCode() !== 200) {
            die("!!! SE IS DOWN AGAIN, FFS");
        }
        
        $html = $res->getBody();
        
        preg_match('/(.*)action="(?P<action>[^"]+)">/', $html, $matches);
        $action = html_entity_decode($matches['action']);
        preg_match('/(.*)name="cis_sessid" type="hidden" value="(?P<cis_sessid>[^"]+)">/', $html, $matches);
        $cis_sessid = trim($matches['cis_sessid']);
        echo "POST: {$action}\n";
        
        $formData = [
            'cis_sessid' => $cis_sessid,
            'provision'  => '', // ??? - Don't know what this is but doesn't seem to matter
            '_c'         => 1   // ??? - Don't know what this is but doesn't seem to matter
        ];
        
        echo "Confirming authentication with companion api on uri: $action\n";
        
        // submit to companion to confirm cis_sessid
        $req = new CompanionRequest([
            'uri'       => $action,
            'form'      => $formData,
            'version'   => '',
            'requestId' => RequestId::get(),
            'return202' => true,
        ]);
        
        // this will be another form with some other bits that the app just forcefully submits via js
        if ($this->post($req)->getStatusCode() == 202) {
            echo "Login confirmed, woop. Now onto the good stuff.\n\n";
        } else {
            throw new \Exception('Login status could not be validated.');
        }
    }
    
    /**
     * Get a valid token + salt from SE
     * @POST("/login/token")
     */
    public function getToken()
    {
        // encrypt user id
        $pem = SightConfig::getPemData();
    
        $rsa = new RSA();
        $rsa->loadKey($pem);
        $rsa->setEncryptionMode(RSA::ENCRYPTION_PKCS1);
        $uid = base64_encode($rsa->encrypt($this->userId));
        
        $req = new CompanionRequest([
            'uri'       => CompanionRequest::URI,
            'endpoint'  => '/login/token',
            'requestId' => RequestId::get(),
            'json'      => [
                'platform'  => self::PLATFORM_ANDROID, // < THIS IS IMPORTANT
                'uid'       => $uid
            ]
        ]);
        
        return $this->post($req)->getJson();
    }
    
    /**
     * Build the Login uri
     */
    public function getOAuthUri()
    {
        return CompanionRequest::SQEX_AUTH_URI .'?'. http_build_query([
            'client_id'     => 'ffxiv_comapp',
            'lang'          => 'en-us',
            'response_type' => 'code',
            'redirect_uri'  => $this->buildOAuthRedirectUri(),
        ]);
    }
    
    /**
     * Build the login redirect uri
     */
    public function buildOAuthRedirectUri()
    {
        $uid = PBKDF2::encrypt($this->userId, $this->token->salt);
        SightConfig::save('uid', $uid);
        
        return CompanionRequest::OAUTH_CALLBACK .'?'. http_build_query([
            'token'      => $this->token->token,
            'uid'        => $uid,
            'request_id' => RequestId::get(),
        ]);
    }
}
