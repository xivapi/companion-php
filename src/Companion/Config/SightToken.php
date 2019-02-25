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
    
    public function __construct(string $name = null)
    {
        $this->id      = Uuid::uuid4()->toString();
        $this->name    = $name;
        $this->created = time();
    }
    
    public function toArray(): array
    {
        return json_decode(json_encode($this), true);
    }

    public static function build(\stdClass $existing)
    {
        $obj            = new SightToken();
        $obj->id        = $existing->id;
        $obj->name      = $existing->name;
        $obj->character = $existing->character;
        $obj->server    = $existing->server;
        $obj->uid       = $existing->uid;
        $obj->userId    = $existing->userId;
        $obj->token     = $existing->token;
        $obj->salt      = $existing->salt;
        $obj->region    = $existing->region;
        $obj->created   = $existing->created;
        return $obj;
    }
}
