<?php

namespace Companion\Config;

/**
 * Companion Sight API Configuration
 */
class CompanionSight
{
    // defaults
    private static $defaults = [
        'CLIENT_TIMEOUT'    => 30,
        'CLIENT_VERIFY'     => false,
        'QUERY_LOOPED'      => true,
        'QUERY_LOOP_COUNT'  => 5,
        'QUERY_DELAY_MS'    => 800 * 1000,
        'TOKEN_EXPIRY_HRS'  => 18,
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
