<?php

namespace Companion\Api;

use Companion\Config\SightConfig;
use Companion\Http\Sight;
use Companion\Models\SightRequest;
use Ramsey\Uuid\Uuid;

class Login extends Sight
{
    /**
     * Get the active character for this account
     */
    public function characters(string $id = null)
    {
        $req = new SightRequest();
        $req->setMethod(self::METHOD_GET)
            ->setEndpoint('/login/characters');
        
        if ($id) {
            $req->setMethod(self::METHOD_POST)
                ->setEndpoint("/login/characters/{$id}")
                ->setJson([
                    'appLocaleType' => 'EU'
                ]);
                
        }
        
        return $this->request($req);
    }
    
    public function token(string $uid)
    {
        $payload = [
            'platform' => 2,
            'uid' => $uid
        ];

        $req = new SightRequest();
        $req->setMethod(self::METHOD_POST)
            ->setEndpoint('/login/token')
            ->setJson($payload);

        return $this->request($req);
    }

    /**
     * Not sure what this is
     */
    public function fcmToken()
    {
        // look more into this
        $payload = [
            'fcmToken' => '[COMPANION_APP_FCM]'
        ];

        $req = new SightRequest();
        $req->setMethod(self::METHOD_POST)
            ->setRegion(self::REGION_EU) // this needs to be part of token config
            ->setEndpoint('/login/fcm-token')
            ->setJson($payload);

        return $this->request($req);
    }
}
