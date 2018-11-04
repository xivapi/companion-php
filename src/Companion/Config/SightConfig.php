<?php

namespace Companion\Config;

class SightConfig
{
    const CONFIG_FILE   = __DIR__ .'/config.json';
    const PEM_FILE      = __DIR__ .'/public-key.pem';
    
    /** @var string */
    private static $profile;
    /** @var \stdClass */
    private static $config;
    
    private static function init()
    {
        // if no config, create one from dist
        if (!file_exists(self::CONFIG_FILE)) {
            copy(self::CONFIG_FILE .".dist", self::CONFIG_FILE);
        }
        
        self::$config = json_decode(
            file_get_contents(
                self::CONFIG_FILE
            )
        );
    }
    
    /**
     * Set the config profile for this instance, this is useful if you
     * want to log into multiple characters and save multiple tokens
     */
    public static function setProfile(string $profile)
    {
        self::$profile = $profile;
        self::init();
    }
    
    /**
     * Get config

     * @param string|null $field
     * @return null|\stdClass|string
     */
    public static function get(string $field = null)
    {
        self::init();
        
        if ($field) {
            return self::$config->{self::$profile}->{$field} ?? null;
        }
        
        return self::$config;
    }
    
    /**
     * Save/update a new field onto the config
     */
    public static function save($field, $value): void
    {
        self::init();
        
        // create profile entry
        if (!isset(self::$config->{self::$profile})) {
            self::$config->{self::$profile} = (Object)[];
        }
        
        self::$config->{self::$profile}->{$field} = $value;
        
        file_put_contents(
            self::CONFIG_FILE,
            json_encode(self::$config, JSON_PRETTY_PRINT)
        );
    }
    
    /**
     * get the public key file data
     */
    public static function getPemData(): string
    {
        self::init();
        return trim(
            file_get_contents(self::PEM_FILE)
        );
    }
}
