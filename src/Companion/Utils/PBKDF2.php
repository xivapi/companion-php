<?php

namespace Companion\Utils;

class PBKDF2
{
    /**
     * Square-Enix Companion App KBKDF2 Encrypt implementation
     */
    public static function encrypt($string, $salt)
    {
        return bin2hex(
            hash_pbkdf2("sha1", $string, $salt, 1000, 128, true)
        );
    }
}
