<?php

namespace Companion\Config;

use Ramsey\Uuid\Uuid;

class SightToken
{
    /** @var string */
    public $id;
    /** @var string */
    public $name;
    /** @var string */
    public $character;
    /** @var string */
    public $server;
    /** @var string */
    public $uid;
    /** @var string */
    public $userId;
    /** @var string */
    public $token;
    /** @var string */
    public $salt;
    /** @var string */
    public $region;
    /** @var int */
    public $created;
    /** @var int */
    public $updated;
    
    public function __construct(string $name = null)
    {
        $this->id           = Uuid::uuid4()->toString();
        $this->name         = $name;
        $this->created      = time();
    }
    
    public function toArray(): array
    {
        return json_decode(json_encode($this), true);
    }

    public static function build(\stdClass $existing)
    {
        $obj             = new SightToken();
        $obj->id         = $existing->id ?? Uuid::uuid4()->toString();
        $obj->name       = $existing->name ?? null;
        $obj->character  = $existing->character ?? null;
        $obj->server     = $existing->server ?? null;
        $obj->uid        = $existing->uid ?? null;
        $obj->userId     = $existing->userId ?? null;
        $obj->token      = $existing->token ?? null;
        $obj->salt       = $existing->salt ?? null;
        $obj->region     = $existing->region ?? null;
        $obj->created    = $existing->created ?? null;
        $obj->updated    = $existing->updated ?? null;
        return $obj;
    }
}
