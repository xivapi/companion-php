<?php

namespace Companion\Config;

/**
 * Companion Sight API Configuration
 */
class CompanionSight
{
    // defaults
    private static $defaults = [
        // how long on the guzzle client before timing out
        'CLIENT_TIMEOUT'    => 30,
        // should the guzzle client verify the sight https cert
        'CLIENT_VERIFY'     => false,
        // should we keep looping to ensure a result from sight?
        'QUERY_LOOPED'      => true,
        // how many loops should sight perform?
        'QUERY_LOOP_COUNT'  => 5,
        // at what interval delays should sight perform, this is in micro-seconds
        'QUERY_DELAY_MS'    => 800 * 1000,
        // the estimated token expiry time, it should last 24 hours but SE are being inconsistent...
        'TOKEN_EXPIRY_HRS'  => 12,
        // a static request id
        'REQUEST_ID'        => null,
    ];

    /** @var array */
    private static $settings = [];
    /** @var bool */
    private static $async = false;
    
    /**
     * Set an option
     */
    public static function set($option, $value)
    {
        // multiply any query delays in milliseconds by 1000
        $value = $option === 'QUERY_DELAY_MS' ? ($value * 1000) : $value;
        
        self::$settings[$option] = $value;
    }
    
    /**
     * Get an option (or return default)
     */
    public static function get($option)
    {
        return self::$settings[$option] ?? (self::$defaults[$option] ?? false);
    }
    
    /**
     * Switch to async mode
     */
    public static function useAsync()
    {
        self::$async = true;
    }
    
    /**
     * State if in async mode or not
     */
    public static function isAsync(): bool
    {
        return self::$async;
    }
}
