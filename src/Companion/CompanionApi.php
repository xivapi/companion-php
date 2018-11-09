<?php

namespace Companion;

use Companion\Api\AddressBook;
use Companion\Api\ChatRoom;
use Companion\Api\Item;
use Companion\Api\Login;
use Companion\Api\Market;
use Companion\Api\Account;
use Companion\Api\Payments;
use Companion\Api\Report;
use Companion\Api\Schedule;
use Companion\Config\Profile;

class CompanionApi
{
    public function __construct(string $profile, string $savePath = Profile::CONFIG_FILE)
    {
        Profile::setSavePath($savePath);
        Profile::setProfile($profile);
        Profile::init();
    }
    
    public function Profile()
    {
        return new Profile();
    }
    
    public function Account()
    {
        return new Account();
    }
    
    public function AddressBook()
    {
        return new AddressBook();
    }

    public function ChatRooms()
    {
        return new ChatRoom();
    }

    public function Item()
    {
        return new Item();
    }

    public function Login()
    {
        return new Login();
    }

    public function Market()
    {
        return new Market();
    }

    public function Payments()
    {
        return new Payments();
    }

    public function Report()
    {
        return new Report();
    }

    public function Schedule()
    {
        return new Schedule();
    }
}
