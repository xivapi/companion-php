<?php

namespace Companion\Config;

/**
 * Companion Token Configuration
 */
class CompanionConfig
{
    const TOKEN_FILENAME = __DIR__ .'/tokens.json';
    
    /** @var Token */
    private static $token;
    /** @var string */
    private static $tokenFilename;
    /** @var bool */
    private static $async = false;
    
    /**
     * Initialize Configuration
     */
    public static function init($token): void
    {
        if (is_string($token)) {
            // try load an existing token
            if ($existing = self::loadTokens($token)) {
                self::$token = Token::build($existing);
                return;
            }

            // create a new token
            self::$token = new Token($token);
            return;
        }
        
        // build from existing token provided
        self::$token = Token::build($token);
    }
    
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
        
        
        foreach ($tokens as $i => $token) {
            // has it expired?
            if (self::hasTokenExpired($token)) {
                unset($tokens->{$i});
            }
        }

        if ($tokens && $tokenName) {
            return $tokens->{$tokenName} ?? null;
        }
        
        return $tokens;
    }
    
    /**
     * States if a token has expired or not
     */
    public static function hasTokenExpired($token)
    {
        return $token->created < (time() - (60 * 60 * CompanionSight::get('TOKEN_EXPIRY_HRS')));
    }
    
    /**
     * Get the current token
     */
    public static function getToken()
    {
        return self::$token;
    }
    
    public static function useAsync()
    {
        self::$async = true;
    }
    
    public static function isAsync(): bool
    {
        return self::$async;
    }
}
