<?php

namespace Companion\Config;

/**
 * Companion Token Configuration
 */
class CompanionTokenManager
{
    const TOKEN_FILENAME = __DIR__ .'/tokens.json';
    
    /** @var SightToken */
    private static $token;
    /** @var string */
    private static $tokenFilename;
    
    /**
     * Set the token filename if you wish to save locally, otherwise it wont save
     */
    public static function setTokenFilename(string $tokenFilename)
    {
        self::$tokenFilename = $tokenFilename;
    
        // initialize token file so it always exists
        if (file_exists(self::$tokenFilename) === false) {
            file_put_contents(self::$tokenFilename, '{}');
        }
    }
    
    /**
     * Saves any tokens we have modified
     */
    public static function saveTokens()
    {
        if (self::$tokenFilename === null) {
            return;
        }
        
        self::$token->updated = time();
        
        $tokens = self::loadTokens();
        $tokens->{self::$token->name} = self::$token->toArray();
        $tokens = json_encode($tokens, JSON_PRETTY_PRINT);
        
        file_put_contents(self::$tokenFilename, $tokens);
    }
    
    /**
     * Load our existing tokens
     */
    public static function loadTokens(string $tokenName = null)
    {
        if (self::$tokenFilename === null) {
            return null;
        }
        
        $tokens = file_get_contents(self::$tokenFilename);
        $tokens = json_decode($tokens);

        if ($tokens && $tokenName) {
            return $tokens->{$tokenName} ?? null;
        }
        
        return $tokens;
    }
    
    /**
     * States if a token has expired or not via a provided timestamp, the reason
     * the library does not maintain a timestamp is because there are various stages when
     * a "confirmed login" is, such as getting a character, or manually logging in. It is
     * the developers job to maintain a logged in state based on their rules.
     */
    public static function hasExpired($timestamp)
    {
        return $timestamp < (time() - (60 * 60 * CompanionSight::get('TOKEN_EXPIRY_HRS')));
    }
    
    /**
     * Get the current token
     */
    public static function getToken()
    {
        return self::$token;
    }
    
    /**
     * Set the token to use
     */
    public static function setToken($token): void
    {
        
        if (is_string($token)) {
            // try load an existing token
            if ($existing = self::loadTokens($token)) {
                self::$token = SightToken::build($existing);
                return;
            }
        
            // create a new token
            self::$token = new SightToken($token);
            return;
        }
        
        // if this is already a sight token, use it.
        if (get_class($token) == SightToken::class) {
            self::$token = $token;
            return;
        }
    
        // build from existing token provided
        self::$token = SightToken::build($token);
    }
}
