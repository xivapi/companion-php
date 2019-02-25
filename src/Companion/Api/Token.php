<?php

namespace Companion\Api;

use Companion\Config\CompanionTokenManager;
use Companion\Config\SightToken as SightToken;
use Companion\Exceptions\TokenExpiredException;

class Token
{
    /**
     * Set the token to use for any request
     * (can be replaced in the same API instance for async calls)
     */
    public function set($token): self
    {
        CompanionTokenManager::setToken($token);
        
        // check it hasn't expired
        if (CompanionTokenManager::hasTokenExpired(CompanionTokenManager::getToken())) {
            throw new TokenExpiredException();
        }
        
        return $this;
    }
    
    /**
     * Get the current in-use token
     */
    public function get(): SightToken
    {
        return CompanionTokenManager::getToken();
    }
    
    /**
     * Save our current token
     */
    public function save(): self
    {
        CompanionTokenManager::saveTokens();
        return $this;
    }
    
    /**
     * Load our current token, or a specified token
     */
    public function load(string $tokenName = null)
    {
        return CompanionTokenManager::loadTokens($tokenName);
    }
    
    /**
     * Check if a token has expired
     */
    public function hasExpired($token): bool
    {
        return CompanionTokenManager::hasTokenExpired($token);
    }
}
