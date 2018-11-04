<?php

namespace Companion\Models;

use Companion\Config\SightConfig;
use GuzzleHttp\RequestOptions;
use Ramsey\Uuid\Uuid;

class CompanionRequest
{
    const SQEX_AUTH_URI     = "https://secure.square-enix.com/oauth/oa/oauthauth";
    const SQEX_LOGIN_URI    = "https://secure.square-enix.com/oauth/oa/oauthlogin";
    const OAUTH_CALLBACK    = 'https://companion.finalfantasyxiv.com/api/0/auth/callback';
    
    const URI     = 'https://companion.finalfantasyxiv.com';
    const URI_SE  = 'https://secure.square-enix.com';
    const VERSION = '/sight-v060/sight';
    
    public $uri;
    public $endpoint;
    public $version;
    public $redirect  = true;
    public $return202 = false;
    public $headers   = [];
    public $json      = [];
    public $form      = [];
    public $query     = [];
    public $cookies   = [];
    
    public function __construct(array $config)
    {
        $config            = (Object)$config;
        $this->uri         = $config->uri;
        $this->version     = $config->version ?? self::VERSION;
        $this->endpoint    = $config->endpoint;
        $this->json        = $config->json ?? [];
        $this->form        = $config->form ?? [];
        $this->query       = $config->query ?? [];
        $this->cookies     = $config->cookies ?? [];
        $this->redirect    = $config->redirect ?? $this->redirect;
        $this->return202   = $config->return202 ?? $this->return202;
    
        $this->headers['Accept']          = '*/*';
        $this->headers['Accept-Language'] = 'en-gb';
        $this->headers['domain-type']     = 'global';
        $this->headers['User-Agent']      = 'ffxivcomapp-e/1.0.3.0 CFNetwork/974.2.1 Darwin/18.0.0';
        $this->headers['request-id']      = $config->requestId ?? strtoupper(Uuid::uuid4()->toString());
        $this->headers['token']           = SightConfig::get('token');
        $this->headers                    = array_merge($this->headers, $config->headers ?? []);
    }
    
    public function getUri()
    {
        return $this->uri . $this->version . $this->endpoint;
    }
    
    public function getOptions()
    {
        return [
            RequestOptions::HEADERS         => $this->headers,
            RequestOptions::JSON            => $this->json,
            RequestOptions::FORM_PARAMS     => $this->form,
            RequestOptions::QUERY           => $this->query,
            RequestOptions::ALLOW_REDIRECTS => $this->redirect,
            RequestOptions::COOKIES         => $this->cookies,
        ];
    }
}
