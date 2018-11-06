<?php

namespace Companion\Http;

use GuzzleHttp\Cookie\FileCookieJar;
use GuzzleHttp\Cookie\SetCookie;

class Cookies
{
    const FILENAME = __DIR__.'/cookie_jar';
    
    /** @var FileCookieJar */
    private static $jar = null;
    
    public static function get(): CookiesJar
    {
        if (self::$jar === null) {
            self::$jar = new CookiesJar(self::FILENAME);
        }
        
        return self::$jar;
    }
    
    public static function add($name, $value)
    {
        $cookie = new SetCookie([ $name => $value ]);
        
        $jar = self::get();
        $jar->setCookie($cookie);
        $jar->save(self::FILENAME);
        
        self::$jar = $jar;
    }
    
    public static function clear()
    {
        @unlink(self::FILENAME);
        self::$jar = null;
    }
}
