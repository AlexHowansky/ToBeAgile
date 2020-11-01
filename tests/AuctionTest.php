<?php

namespace ToBeAgileTest;

class AuctionTest extends \PHPUnit\Framework\TestCase
{

    protected $user;

    protected $auction;

    protected $postOffice;

    public function setUp(): void
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
        $this->auction = new \ToBeAgile\Auction($this->user, $startPrice, $itemDescription, $startTime, $endTime, \ToBeAgile\Auction::CATEGORY_OTHER);

        $this->postOffice = new \ToBeAgile\PostOffice();
        $this->auction->setPostOffice($this->postOffice);
        
        $this->logger = new \ToBeAgile\Logger();
        $this->auction->setLogger($this->logger);

    }

    public function testCreateAuctionNotLoggedIn()
    {
        $this->expectException(\Exception::class);
        $startPrice = 1;
        $itemDescription = "";
        $startTime = time() + 3600;
        $endTime = time() + 5000;
        $this->user->logout();
        new \ToBeAgile\Auction($this->user, $startPrice, $itemDescription, $startTime, $endTime, \ToBeAgile\Auction::CATEGORY_OTHER);
    }

    public function testCreateAuctionNotSeller()
    {
        $this->expectException(\Exception::class);
        $startPrice = 1;
        $itemDescription = "";
        $startTime = time() + 3600;
        $endTime = time() + 5000;
        $this->user->setSeller(false);
        new \ToBeAgile\Auction($this->user, $startPrice, $itemDescription, $startTime, $endTime, \ToBeAgile\Auction::CATEGORY_OTHER);
    }

    public function testCreateAuctionStartInPast()
    {
        $this->expectException(\Exception::class);
        $startPrice = 1;
        $itemDescription = "";
        $startTime = time() - 3600;
        $endTime = 0;
        new \ToBeAgile\Auction($this->user, $startPrice, $itemDescription, $startTime, $endTime, \ToBeAgile\Auction::CATEGORY_OTHER);
    }

    public function testCreateAuctionStartAfterEnd()
    {
        $this->expectException(\Exception::class);
        $startPrice = 1;
        $itemDescription = "";
        $startTime = time() + 3600;
        $endTime = time();
        new \ToBeAgile\Auction($this->user, $startPrice, $itemDescription, $startTime, $endTime, \ToBeAgile\Auction::CATEGORY_OTHER);
    }

    public function testCreateGoodAuction()
    {
        $startPrice = 1.23;
        $itemDescription = "garbage can";
        $startTime = time() + 3600;
        $endTime = time() + 3600 * 2;
        $auction = new \ToBeAgile\Auction($this->user, $startPrice, $itemDescription, $startTime, $endTime, \ToBeAgile\Auction::CATEGORY_OTHER);
        $this->assertSame($this->user->getUserName(), $auction->getUser()->getUserName());
        $this->assertSame($startPrice, $auction->getStartingPrice());
        $this->assertSame($itemDescription, $auction->getItemDescription());
        $this->assertSame($startTime, $auction->getStartTime());
        $this->assertSame($endTime, $auction->getEndTime());
        $this->assertTrue($auction->getStatus() === \ToBeAgile\Auction::STATUS_NEW);
        $this->assertSame(\ToBeAgile\Auction::CATEGORY_OTHER, $auction->getCategory());
    }

    public function testAuctionOpen()
    {
        $startPrice = 1.23;
        $itemDescription = "garbage can";
        $startTime = time() + 3600;
        $endTime = time() + 3600 * 2;
        $auction = new \ToBeAgile\Auction($this->user, $startPrice, $itemDescription, $startTime, $endTime, \ToBeAgile\Auction::CATEGORY_OTHER);
        $this->assertFalse($auction->isOpen());
        $auction->onStart();
        $this->assertTrue($auction->isOpen());
        $auction->onClose();
        $this->assertFalse($auction->isOpen());
    }

    public function testBidUserNotLoggedIn()
    {
        $this->expectException(\Exception::class);
        $this->user->logout();
        $this->auction->onStart();
        $this->auction->bid($this->user, 0);
    }

    public function testBidAuctionNotStarted()
    {
        $this->expectException(\Exception::class);
        $bid = 0;
        $this->auction->bid($this->user, $bid);
    }

    public function testBidNotHigest()
    {
        $this->expectException(\Exception::class);
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

    public function testNoHighestBid()
    {
        $this->expectException(\Exception::class);
        $this->auction->getHighestBid();
    }

    public function testNoBidders()
    {
        $this->expectException(\Exception::class);
        $this->auction->getHighestBidder();
    }

    public function testBidLessThanStart()
    {
        $this->expectException(\Exception::class);
        $bid = 1;
        $this->auction->onStart();
        $this->auction->bid($this->user, $bid);
    }

    public function testCantOpenAlreadyOpened()
    {
        $this->expectException(\Exception::class);
        $this->auction->onStart();
        $this->assertTrue($this->auction->getStatus() === \ToBeAgile\Auction::STATUS_OPEN);
        $this->auction->onStart();
    }

    public function testCantCloseAlreadyClosed()
    {
        $this->expectException(\Exception::class);
        $this->auction->onStart();
        $this->auction->onClose();
        $this->auction->onClose();
    }

    public function testCantCloseNew()
    {
        $this->expectException(\Exception::class);
        $this->auction->onClose();
    }

    public function testCantReopen()
    {
        $this->expectException(\Exception::class);
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
    
    public function testCloseAdjustPrice()
    {
        $this->auction->onStart();
        $this->auction->bid($this->user, 3);
        $this->auction->onClose();
        $this->assertEquals($this->auction->getHighestBid() * 0.98, $this->auction->getSellerAmount(), '', 0.01);
        $this->assertEquals($this->auction->getHighestBid() + 10, $this->auction->getBuyerAmount(), '', 0.01);
    }

    public function testCloseCarShipping()
    {
        $startPrice = \ToBeAgile\Process\LuxuryCarTaxFee::THRESHOLD;
        $itemDescription = 'Maserati';
        $startTime = time() + 3600;
        $endTime = time() + 3600 * 2;
        $auction = new \ToBeAgile\Auction($this->user, $startPrice, $itemDescription, $startTime, $endTime, \ToBeAgile\Auction::CATEGORY_CAR);
        $auction->onStart();
        $auction->bid($this->user, $startPrice);
        $auction->onClose();
        $this->assertEquals(
            $auction->getHighestBid() + \ToBeAgile\Process\CarShippingFee::FEE,
            $auction->getBuyerAmount(),
            '',
            0.01
        );
    }
    
    public function testLuxuryCar()
    {
        $startPrice = \ToBeAgile\Process\LuxuryCarTaxFee::THRESHOLD + 1;
        $itemDescription = 'Datsun';
        $startTime = time() + 3600;
        $endTime = time() + 3600 * 2;
        $auction = new \ToBeAgile\Auction($this->user, $startPrice, $itemDescription, $startTime, $endTime, \ToBeAgile\Auction::CATEGORY_CAR);
        $auction->onStart();
        $auction->bid($this->user, $startPrice);
        $auction->onClose();
        $this->assertEquals(
            $auction->getHighestBid() * (1 + \ToBeAgile\Process\LuxuryCarTaxFee::TAX_RATE) + \ToBeAgile\Process\CarShippingFee::FEE,
            $auction->getBuyerAmount(),
            '',
            0.01
        );
    }
    
    public function testLogCarSale()
    {
        $startPrice = \ToBeAgile\Process\LuxuryCarTaxFee::THRESHOLD;
        $itemDescription = 'Datsun';
        $startTime = time() + 3600;
        $endTime = time() + 3600 * 2;
        $auction = new \ToBeAgile\Auction($this->user, $startPrice, $itemDescription, $startTime, $endTime, \ToBeAgile\Auction::CATEGORY_CAR);
        $auction->setLogger($this->logger);
        $auction->onStart();
        $auction->bid($this->user, $startPrice);
        $fileName = 'car.log';
        $message = 'SuperGrover sold a car valued at 50000.00 to SuperGrover';
        $this->assertFalse($this->logger->findMessage($fileName, $message));
        $auction->onClose();
        $this->assertTrue($this->logger->findMessage($fileName, $message));
    }

    public function testExpensiveSale()
    {
        $startPrice = \ToBeAgile\Process\ExpensiveItemLog::THRESHOLD + 1;
        $itemDescription = 'widget';
        $startTime = time() + 3600;
        $endTime = time() + 3600 * 2;
        $auction = new \ToBeAgile\Auction($this->user, $startPrice, $itemDescription, $startTime, $endTime, \ToBeAgile\Auction::CATEGORY_OTHER);
        $auction->setLogger($this->logger);
        $auction->onStart();
        $auction->bid($this->user, $startPrice);
        $fileName = 'expensive.log';
        $message = 'SuperGrover sold an expensive item valued at 10001.00 to SuperGrover';
        $this->assertFalse($this->logger->findMessage($fileName, $message));
        $auction->onClose();
        $this->assertTrue($this->logger->findMessage($fileName, $message));
    }

    public function testPreferredSellerReducedRate()
    {
        $amount = 5;
        $this->user->setPreferredSeller(true);
        $this->auction->onStart();
        $this->auction->bid($this->user, $amount);
        $this->auction->onClose();
        $this->assertEquals($amount * (1 - \ToBeAgile\Process\SellerFee::PREFERRED_FEE_RATE), $this->auction->getSellerAmount(), '', 0.01);
    }

}
