<?php

namespace Companion\Config;

use Companion\Http\SightCookieJar;
use GuzzleHttp\Cookie\FileCookieJar;
use GuzzleHttp\Cookie\SetCookie;

class SightConfig
{
    const CONFIG_FILE = __DIR__ .'/config.json';
    const PEM_FILE    = __DIR__ .'/public-key.pem';
    
    /** @var string */
    private static $profile;
    /** @var \stdClass */
    private static $config;
    /** @var FileCookieJar */
    private static $cookieJar;
    
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
        echo "Config profile set to: {$profile}\n\n";
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
    
    
    
    
    // todo - move these to their own class under http
    
    public static function clearCookies()
    {
        $profile = self::$profile;
        @unlink(__DIR__ ."/cookies/{$profile}");
    }
    
    public static function getCookies(): SightCookieJar
    {
        if (!is_dir(__DIR__ .'/cookies')) {
            mkdir(__DIR__ .'/cookies', 0777, true);
        }
        
        if (self::$cookieJar === null) {
            $profile = self::$profile;
            self::$cookieJar = new SightCookieJar(__DIR__ ."/cookies/{$profile}");
        }
        
        return self::$cookieJar;
    }
    
    public static function addCookie($name, $value)
    {
        $profile = self::$profile;
        $cookie = new SetCookie([ $name => $value ]);
        
        $jar = self::getCookies();
        $jar->setCookie($cookie);
        $jar->save(__DIR__ ."/cookies/{$profile}");
        
        self::$cookieJar = $jar;
    }
}
