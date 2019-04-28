<?php

namespace Companion\Config;

/**
 * Contains all headers and other form data configuration
 */
class SightConfig
{
    const APP_VERSION               = '1.2.0';
    const APP_LOCALE_TYPE           = 'EU';
    
    const PLATFORM_IOS              = 1;
    const PLATFORM_ANDROID          = 2;
    const USER_AGENT                = 'ffxivcomapp-e/1.2.0.0 CFNetwork/978.0.7 Darwin/18.5.0';
    const USER_AGENT_2              = 'Mozilla/5.0 (iPhone; CPU iPhone OS 12_2 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) XIV-Companion for iPhone';
    const ACCEPT                    = '*/*';
    const ACCEPT_LANGUAGE           = 'en-gb';
    const ACCEPT_ENCODING           = 'br, gzip, deflate';
    const CONTENT_TYPE              = 'application/json;charset=utf-8';
    
    const ADVERTISING_ID            = 'CDC61D75-5F00-4516-B6A5-F353F1C03179';
    const ADVERTISING_ENABLED       = 1;
    
    // Firebase Cloud Messaging token, used for app notifications
    const FCM_TOKEN                 = '';
}
