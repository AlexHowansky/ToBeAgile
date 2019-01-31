<?php

namespace ToBeAgileTest;

class NotifierOld extends \PHPUnit\Framework\TestCase
{

    protected $user;

    protected $auction;

    public function setUp()
    {
        $firstName = 'Grover';
        $lastName = 'Spielmann';
        $userEmail = 'grover@sesame.com';
        $userName = 'SuperGrover';
        $password = 'DerthNader';
        $this->user = (new \ToBeAgile\Users())->register($firstName, $lastName, $userEmail, $userName, $password);
        $this->user->login();
        $this->user->setSeller(true);
        
        $startPrice = 1.23;
        $itemDescription = "garbage can";
        $startTime = time() + 3600;
        $endTime = time() + 3600 * 2;
        $this->auction = new \ToBeAgile\Auction($this->user, $startPrice, $itemDescription, $startTime, $endTime);
    }

    public function testNotifierNoBids()
    {
        $this->auction->onStart();
        $this->auction->onClose();
        $this->assertInstanceOf('\ToBeAgile\Notifier\NoBids', \ToBeAgile\Notifier\NotifierFactory::getNotifier($this->auction));
    }
    
    public function testNotifierBids()
    {
        $this->auction->onStart();
        $this->auction->bid($this->user, 5);
        $this->auction->onClose();
        $this->assertInstanceOf('\ToBeAgile\Notifier\Bids', \ToBeAgile\Notifier\NotifierFactory::getNotifier($this->auction));
    }
    
}
