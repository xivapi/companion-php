<?php

namespace Companion\Api;

use Companion\Http\Sight;
use Companion\Models\SightRequest;

class Login extends Sight
{
    /**
     * Get a new 24 hour token using a login UID payload
     */
    public function refreshToken($uid)
    {
        $payload = [
            'platform' => 1,
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

    /**
     * Login to the active character for this account
     */
    public function loginCharacter()
    {
        $req = new SightRequest();
        $req->setMethod(self::METHOD_GET)
            ->setRegion(self::REGION_EU) // this needs to be part of token config
            ->setEndpoint('/login/character');

        return $this->request($req);
    }
}
