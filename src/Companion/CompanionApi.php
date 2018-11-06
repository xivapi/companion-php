<?php

namespace Companion;

use Companion\Api\AddressBook;
use Companion\Api\ChatRoom;
use Companion\Api\Item;
use Companion\Api\Login;
use Companion\Api\Market;
use Companion\Api\OAuth;
use Companion\Api\Payments;
use Companion\Api\Report;
use Companion\Api\Schedule;
use Companion\Config\SightConfig;

class CompanionApi
{
    public function __construct(string $profile)
    {
        SightConfig::setProfile($profile);
    }
    
    public function oAuth()
    {
        return new OAuth();
    }
    
    public function addressBook()
    {
        return new AddressBook();
    }

    public function chatRooms()
    {
        return new ChatRoom();
    }

    public function item()
    {
        return new Item();
    }

    public function login()
    {
        return new Login();
    }

    public function market()
    {
        return new Market();
    }

    public function payments()
    {
        return new Payments();
    }

    public function report()
    {
        return new Report();
    }

    public function schedule()
    {
        return new Schedule();
    }
}
