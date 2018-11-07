<?php

namespace Companion\Config;

class Profile
{
    const CONFIG_FILE = __DIR__ .'/profile.json';
    
    /** @var string */
    public static $name;
    /** @var array */
    public static $config;
    /** @var string */
    public static $savePath = self::CONFIG_FILE;
    
    /**
     * Initialize profile
     */
    public static function init()
    {
        // if no config, create one from dist
        if (!file_exists(self::$savePath)) {
            self::set('created', date('Y-m-d H:i:s'));
        }
        
        self::load();
    }
    
    /**
     * Set the config profile for this instance, this is useful if you
     * want to log into multiple characters and save multiple tokens
     */
    public static function setProfile(string $name)
    {
        self::$name = $name;
    }
    
    /**
     * Get a config value
     */
    public static function get(string $field)
    {
        return self::$config[self::$name][$field] ?? null;
    }
    
    /**
     * Load the config
     */
    public static function load()
    {
        self::$config = json_decode(file_get_contents(self::$savePath), true);
    }
    
    /**
     * Save/update a new field onto the config
     */
    public static function set($field, $value): void
    {
        self::$config[self::$name][$field] = $value;
        file_put_contents(self::$savePath, json_encode(self::$config, JSON_PRETTY_PRINT));
    }
    
    // --- Non static methods ---
    
    public function getUserId()
    {
        return Profile::get('userId');
    }
    
    public function getToken()
    {
        return Profile::get('token');
    }
    
    public function setToken(string $token)
    {
        Profile::set('token', $token);
    }
    
    public function getSalt()
    {
        return Profile::get('salt');
    }
    
    public function getEncryptedId()
    {
        return Profile::get('uid');
    }
    
    public function getRegion()
    {
        return Profile::get('region');
    }
    
    public function setSavePath($path)
    {
        Profile::$savePath = $path;
        Profile::init();
    }
}
