<?php

namespace Companion\Http;

class Pem
{
    const PEM_FILE = __DIR__ . '/public-key.pem';
    
    /**
     * Get the companion app public key pem file
     */
    public static function get()
    {
        return trim(
            file_get_contents(self::PEM_FILE)
        );
    }
}
