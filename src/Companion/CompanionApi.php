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
use Companion\Api\Token;
use Companion\Config\CompanionSight;
use Companion\Http\Sight;

class CompanionApi
{
    private $classInstances = [];
    
    /**
     * Provide either:
     * - a stdClass of an existing token
     * - a string of for a name for a new token
     */
    public function __construct($token = null)
    {
        if ($token) {
            $this->Token()->set($token);
        }
    }
    
    /**
     * Provides nicer access to static token methods
     */
    public function Token(): Token
    {
        return $this->getClass(Token::class);
    }
    
    /**
     * Switch to async mode
     */
    public function useAsync(): self
    {
        CompanionSight::useAsync();
        return $this;
    }
    
    /**
     * --------------------------------------------------------------------------
     * Sight API
     * --------------------------------------------------------------------------
     */
    
    public function Account(): Account
    {
        return $this->getClass(Account::class);
    }
    
    public function AddressBook(): AddressBook
    {
        return $this->getClass(AddressBook::class);
    }

    public function ChatRoom(): ChatRoom
    {
        return $this->getClass(ChatRoom::class);
    }

    public function Item(): Item
    {
        return $this->getClass(Item::class);
    }

    public function Login(): Login
    {
        return $this->getClass(Login::class);
    }

    public function Market(): Market
    {
        return $this->getClass(Market::class);
    }

    public function Payments(): Payments
    {
        return $this->getClass(Payments::class);
    }

    public function Report(): Report
    {
        return $this->getClass(Report::class);
    }

    public function Schedule(): Schedule
    {
        return $this->getClass(Schedule::class);
    }

    public function Sight(): Sight
    {
        return $this->getClass(Sight::class);
    }
    
    /**
     * Either returns an existing initialized class or a new one
     */
    private function getClass(string $className)
    {
        if (isset($this->classInstances[$className])) {
            return $this->classInstances[$className];
        }
        
        $class = new $className();
        $this->classInstances[$className] = $class;
        
        return $class;
    }
}
