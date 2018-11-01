<?php

namespace Companion\Config;

class SightConfig
{
    /** @var string */
    private $token;
    /** @var string */
    private $region;

    public function getToken(): string
    {
        return $this->token;
    }

    public function setToken(string $token): SightConfig
    {
        $this->token = $token;
        return $this;
    }

    public function getRegion(): string
    {
        return $this->region;
    }

    public function setRegion(string $region): SightConfig
    {
        $this->region = $region;
        return $this;
    }
}
