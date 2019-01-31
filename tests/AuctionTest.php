<?php

namespace ToBeAgileTest;

class AuctionTest extends \PHPUnit\Framework\TestCase
{

    protected $user;
    
    protected $auction;
    
    protected $postOffice;

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
        
        $this->postOffice = new \ToBeAgile\PostOffice();
        $this->auction->setPostOffice($this->postOffice);
    }

    /**
     * @expectedException \Exception
     */
    public function testCreateAuctionNotLoggedIn()
    {
        $startPrice = 1;
        $itemDescription = "";
        $startTime = time() + 3600;
        $endTime = time() + 5000;
        $this->user->logout();
        new \ToBeAgile\Auction($this->user, $startPrice, $itemDescription, $startTime, $endTime);
    }

     /**
     * @expectedException \Exception
     */
    public function testCreateAuctionNotSeller()
    {
        $startPrice = 1;
        $itemDescription = "";
        $startTime = time() + 3600;
        $endTime = time() + 5000;
        $this->user->setSeller(false);
        new \ToBeAgile\Auction($this->user, $startPrice, $itemDescription, $startTime, $endTime);
    }
    
    /**
     * @expectedException \Exception
     */
    public function testCreateAuctionStartInPast()
    {
        $startPrice = 1;
        $itemDescription = "";
        $startTime = time() - 3600;
        $endTime = 0;
        new \ToBeAgile\Auction($this->user, $startPrice, $itemDescription, $startTime, $endTime);
    }
    
    /**
     * @expectedException \Exception
     */
    public function testCreateAuctionStartAfterEnd()
    {
        $startPrice = 1;
        $itemDescription = "";
        $startTime = time() + 3600;
        $endTime = time();
        new \ToBeAgile\Auction($this->user, $startPrice, $itemDescription, $startTime, $endTime);
    }
    
    public function testCreateGoodAuction()
    {
        $startPrice = 1.23;
        $itemDescription = "garbage can";
        $startTime = time() + 3600;
        $endTime = time() + 3600 * 2;
        $auction = new \ToBeAgile\Auction($this->user, $startPrice, $itemDescription, $startTime, $endTime);
        $this->assertSame($this->user->getUserName(), $auction->getUser()->getUserName());
        $this->assertSame($startPrice, $auction->getStartingPrice());
        $this->assertSame($itemDescription, $auction->getItemDescription());
        $this->assertSame($startTime, $auction->getStartTime());
        $this->assertSame($endTime, $auction->getEndTime());
        $this->assertTrue($auction->getStatus() === \ToBeAgile\Auction::STATUS_NEW);
    }
    
    public function testAuctionOpen()
    {
        $startPrice = 1.23;
        $itemDescription = "garbage can";
        $startTime = time() + 3600;
        $endTime = time() + 3600 * 2;
        $auction = new \ToBeAgile\Auction($this->user, $startPrice, $itemDescription, $startTime, $endTime);
        $this->assertFalse($auction->isOpen());
        $auction->onStart();
        $this->assertTrue($auction->isOpen());
        $auction->onClose();
        $this->assertFalse($auction->isOpen());
    }
    
    /**
     * @expectedException \Exception
     */
    public function testBidUserNotLoggedIn()
    {
        $bid = 0;
        $this->user->logout();
        $this->auction->bid($this->user, $bid);
    }
    
    /**
     * @expectedException \Exception
     */
    public function testBidAuctionNotStarted()
    {
        $bid = 0;
        $this->auction->bid($this->user, $bid);
    }
    
    /**
     * @expectedException \Exception
     */
    public function testBidNotHigest()
    {
        $bid = 0;
        $this->auction->onStart();
        $this->auction->bid($this->user, $bid);
    }
    
    public function testGoodBid()
    {
        $bid = 1.23;
        $this->auction->onStart();
        $this->auction->bid($this->user, $bid);
        $this->assertSame($bid, $this->auction->getHighestBid());
        $this->assertInstanceOf('\ToBeAgile\User', $this->auction->getHighestBidder());
        $this->assertSame($this->user->getUserName(), $this->auction->getHighestBidder()->getUserName());
        $bid = 1.50;
        $this->auction->bid($this->user, $bid);
        $this->assertSame($bid, $this->auction->getHighestBid());
    }
    
    /**
     * @expectedException \Exception
     */
    public function testNoHighestBid()
    {
        $this->auction->getHighestBid();
    }
    
    /**
     * @expectedException \Exception
     */
    public function testNoBidders()
    {
        $this->auction->getHighestBidder();
    }
    
    /**
     * @expectedException \Exception
     */
    public function testBidLessThanStart()
    {
        $bid = 1;
        $this->auction->onStart();
        $this->auction->bid($this->user, $bid);
    }
    
    /**
     * @expectedException \Exception
     */
    public function testCantOpenAlreadyOpened()
    {
        $this->auction->onStart();
        $this->assertTrue($this->auction->getStatus() === \ToBeAgile\Auction::STATUS_OPEN);
        $this->auction->onStart();
    }
    
    /**
     * @expectedException \Exception
     */
    public function testCantCloseAlreadyClosed()
    {
        $this->auction->onStart();
        $this->auction->onClose();
        $this->auction->onClose();
    }
    
    /**
     * @expectedException \Exception
     */
    public function testCantCloseNew()
    {
        $this->auction->onClose();
    }
    
    /**
     * @expectedException \Exception
     */
    public function testCantReopen()
    {
        $this->auction->onStart();
        $this->auction->onClose();
        $this->auction->onStart();
    }
    
    public function testCloseNoBidder()
    {
        $this->assertFalse($this->postOffice->doesLogContain($this->auction->getUser()->getUserEmail(), $this->auction->getNoBidsMessage()));
        $this->auction->onStart();
        $this->auction->onClose();
        $this->assertTrue($this->postOffice->doesLogContain($this->auction->getUser()->getUserEmail(), $this->auction->getNoBidsMessage()));
    }
    
    public function testCloseBidder()
    {
        $this->auction->onStart();
        $this->auction->bid($this->user, 3);
        $this->auction->onClose();
        $this->assertTrue($this->postOffice->doesLogContain($this->auction->getUser()->getUserEmail(), $this->auction->getSoldMessage()));
        $this->assertTrue($this->postOffice->doesLogContain($this->auction->getHighestBidder()->getUserEmail(), $this->auction->getWonMessage()));
    }

}
