<?php

namespace Companion\Http;

use GuzzleHttp\Cookie\FileCookieJar;
use GuzzleHttp\Cookie\SetCookie;

class CookiesJar extends FileCookieJar
{
    public function setCookie(SetCookie $cookie)
    {
        // do not set the expiry above 6 hours on the same day.
        $cookie->setDomain('secure.square-enix.com');
        $cookie->setExpires(time() + (60*60*24*6));
        $cookie->setPath('/');
        $cookie->setSecure(true);
        
        return parent::setCookie($cookie);
    }
}
