<?php

namespace Companion\Api;

use Companion\Config\SightConfig;
use Companion\Http\Sight;
use Companion\Models\SightRequest;
use GuzzleHttp\Psr7\Response;
use Ramsey\Uuid\Uuid;
use phpseclib\Crypt\RSA;

class OAuth extends Sight
{
    const PLATFORM_IOS      = 1;
    const PLATFORM_ANDROID  = 2;
    const SQEX_AUTH_URI     = "https://secure.square-enix.com/oauth/oa/oauthauth";
    const SQEX_LOGIN_URI    = "https://secure.square-enix.com/oauth/oa/oauthlogin";
    const OAUTH_CALLBACK    = 'https://companion.finalfantasyxiv.com/api/0/auth/callback';
    
    private $userId;
    private $token;
    private $uri;
    
    public function login($username, $password)
    {
        // Generate a new user uuid
        $this->userId = Uuid::uuid4()->toString();
        
        // Get a token from SE
        $this->token = $this->getToken();
        SightConfig::save('token', $this->token->token);
        print_r($this->token);

        // Get OAuth URI
        $this->uri = $this->getOAuthUri();

        echo "\n";
        echo "Login at the following url: \n";
        echo self::SQEX_AUTH_URI .'?'. http_build_query($this->uri);
        echo "\nThen the token: {$this->token->token} will work";
        echo "\n";
    }
    
    public function getToken()
    {
        // encrypt user id
        $pem = SightConfig::getPemData();
    
        $rsa = new RSA();
        $rsa->loadKey($pem);
        $rsa->setEncryptionMode(RSA::ENCRYPTION_PKCS1);
        $uid = base64_encode($rsa->encrypt($this->userId));

        // send request
        $req = new SightRequest();
        $req->setMethod(self::METHOD_POST)
            ->setEndpoint('/login/token')
            ->setJson([
                'platform' => self::PLATFORM_ANDROID, // < THIS IS IMPORTANT
                'uid' => $uid
            ]);
        
        return $this->request($req);
    }
    
    public function getOAuthUri()
    {
        return [
            'client_id'     => 'ffxiv_comapp',
            'lang'          => 'en-us',
            'response_type' => 'code',
            'redirect_uri'  => $this->buildOAuthRedirectUri(),
        ];
    }
    
    public function buildOAuthRedirectUri()
    {
        $uid = $this->encryptUserId();
        SightConfig::save('uid', $uid);
        
        return self::OAUTH_CALLBACK .'?'. http_build_query([
            'token'      => $this->token->token,
            'uid'        => $uid,
            'request_id' => Uuid::uuid4()->toString()
        ]);
    }
    
    public function encryptUserId()
    {
        return bin2hex(
            hash_pbkdf2("sha1", $this->userId, $this->token->salt, 1000, 1024/8, true)
        );
    }
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    /**
     * Login to an account, this will register the token for usage.
     *
     * @param string $username
     * @param string $password
     * @throws \Exception
     */
    public function OLD__login(string $username, string $password)
    {
        $token = TokenGenerator::generate();
        $uid   = SightConfig::get()->uid;
        $rqid  = Uuid::uuid4()->toString();
        
        // request login form
        $query = [
            'client_id'     => 'ffxiv_comapp',
            'lang'          => 'en-gb',
            'response_type' => 'code',
            'redirect_uri'  => "https://companion.finalfantasyxiv.com/api/0/auth/callback?token={$token}&uid={$uid}&request_id={$rqid}"
        ];
        
        $req = new SightRequest();
        $req->setMethod(self::METHOD_GET)
            ->setEndpoint('/oauth/oa/oauthauth')
            ->setQuery($query)
            ->setSquareEnixDomain(true);
    
        /** @var Response $response */
        $response = $this->response($req);
        
        // grab the location for the next request
        $location = $response->getHeaders()['Location'][0];
        $location = str_ireplace('https://secure.square-enix.com', null, $location);
        
        // continue onto location server provides us
        $req = new SightRequest();
        $req->setMethod(self::METHOD_GET)
            ->setEndpoint($location)
            ->setQuery($query)
            ->setSquareEnixDomain(true);
        
        // grab the referer, will need it later on
        $referer = $req->getUri();
        
        // grab login form html
        $html = $this->request($req);

        // grab submit action
        preg_match('/(.*)action="(?P<action>[^"]+)">/', $html, $matches);
        $action = parse_url($matches['action']);
        $postEndpoint = $action['path'];
        parse_str($action['query'], $postQuery);
        
        // grab _STORED_ value
        preg_match('/(.*)name="_STORED_" value="(?P<stored>[^"]+)">/', $html, $matches);
        $stored = $matches['stored'];

        // build payload to submit form
        $formData = [
            '_STORED_' => '3df06e5f47798101ac62bce3c650e276ef93bf147f79411483c2d3fcd790cbb49ecb2d0243ff0f295717fd1c29c94a512a68ed86f8b11af045f5bfe419922d10ef0ea1798fa8b184b39170e7df9f3757e480023aa2c99a68aa9b2ce40554b9571c0d7f2f93cba8c9cdf40e3bd7237113e6',
            'sqexid'   => $username,
            'password' => urlencode($password),
        ];
        
        // build form submit request
        $req = new SightRequest();
        $req->setMethod(self::METHOD_POST)
            ->setEndpoint("/oauth/oa/{$postEndpoint}")
            ->setQuery($postQuery)
            ->setFormData($formData)
            ->setSquareEnixDomain(true)
            ->addHeader('Origin', 'https://secure.square-enix.com')
            ->addHeader('Host', 'secure.square-enix.com')
            ->addHeader('Accept', 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8')
            ->addHeader('User-Agent', 'Mozilla/5.0 (iPhone; CPU iPhone OS 12_0_1 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) XIV-Companion for iPhone')
            ->addHeader('Content-Type', 'application/x-www-form-urlencoded');
        
        /** @var Response $response */
        $this->debug = true;
        $response = $this->response($req);
        
        print_r($response->getHeaders());
        print_r((string)$response->getBody());
        
        die;

        /*
        // if 200, save the token
        if ($response->getStatusCode() == 200) {
            SightConfig::save('token', $token);
        }
        */
    }
}
