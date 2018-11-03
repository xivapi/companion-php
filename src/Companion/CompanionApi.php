<?php

namespace Companion;

use Companion\Api\AddressBook;
use Companion\Api\ChatRooms;
use Companion\Api\Item;
use Companion\Api\Login;
use Companion\Api\Market;
use Companion\Api\OAuth;
use Companion\Api\Payments;
use Companion\Api\Points;
use Companion\Api\Report;
use Companion\Api\Schedule;

class CompanionApi
{
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
        return new ChatRooms();
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

    public function points()
    {
        return new Points();
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
