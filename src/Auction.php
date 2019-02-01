<?php

namespace ToBeAgile;

class Auction
{

    const STATUS_NEW = 0;
    const STATUS_OPEN = 1;
    const STATUS_CLOSED = 2;

    const CATEGORY_DOWNLOADABLE_SOFTWARE = 'Downloadable Software';
    const CATEGORY_CAR = 'Car';
    const CATEGORY_OTHER = 'Other';

    protected $buyerAmount = 0;

    protected $category;

    protected $endTime;

    protected $highestBid = null;

    protected $highestBidder = null;

    protected $itemDescription;

    protected $postOffice = null;

    protected $sellerAmount = 0;

    protected $startPrice;

    protected $startTime;

    protected $status = self::STATUS_NEW;

    protected $user;

    public function __construct(
        User $user,
        float $startPrice,
        string $itemDescription,
        int $startTime,
        int $endTime,
        string $category
    ) {
        if ($user->isLoggedIn() === false) {
            throw new \Exception('User not logged in');
        }
        if ($user->isSeller() === false) {
            throw new \Exception('User must be a seller.');
        }
        if ($startTime <= time()) {
            throw new \Exception('Auction Start Time in the past.');
        }
        if ($startTime >= $endTime) {
            throw new \Exception('Auction End Time is before Start Time.');
        }
        $this->user = $user;
        $this->startPrice = $startPrice;
        $this->itemDescription = $itemDescription;
        $this->startTime = $startTime;
        $this->endTime = $endTime;
        $this->category = $category;
    }

    public function addBuyerAmount(float $amount)
    {
        $this->buyerAmount += $amount;
    }

    public function addSellerAmount(float $amount)
    {
        $this->sellerAmount += $amount;
    }

    public function bid(User $user, float $bid)
    {
        if ($this->isOpen() === false) {
            throw new \Exception('Auction is not open.');
        }
        if ($user->isLoggedIn() === false) {
            throw new \Exception('User is not logged in.');
        }
        if ($this->hasBids() === true) {
            if ($bid <= $this->highestBid) {
                throw new \Exception('Bid must be higher than existing bid ' . $this->highestBid);
            }
        } else {
            if ($bid < $this->startPrice) {
                throw new \Exception('Bid must be higher than starting bid ' . $this->startPrice);
            }
        }
        $this->highestBidder = $user;
        $this->highestBid = $bid;
    }

    public function getBuyerAmount()
    {
        return $this->buyerAmount;
    }

    public function getCategory()
    {
        return $this->category;
    }

    public function getEndTime(): int
    {
        return $this->endTime;
    }

    public function getHighestBid(): float
    {
        if ($this->highestBid === null) {
             throw new \Exception('No bids yet.');
        }
        return $this->highestBid;
    }

    public function getHighestBidder(): User
    {
        if ($this->highestBidder === null) {
             throw new \Exception('No bids yet.');
        }
        return $this->highestBidder;
    }

    public function getItemDescription(): string
    {
        return $this->itemDescription;
    }

    public function getNoBidsMessage()
    {
        return sprintf('Sorry, your auction for %s did not have any bidders.', $this->getItemDescription());
    }

    public function getPostOffice(): PostOffice
    {
        return $this->postOffice;
    }

    public function getSellerAmount()
    {
        return $this->sellerAmount;
    }

    public function getSoldMessage()
    {
        return sprintf(
            'Your %s auction sold to bidder %s for %0.2f monetary units.',
            $this->getItemDescription(),
            $this->getHighestBidder()->getUserEmail(),
            $this->getHighestBid()
        );
    }

    public function getStartTime(): int
    {
        return $this->startTime;
    }

    public function getStartingPrice(): float
    {
        return $this->startPrice;
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function getWonMessage()
    {
        return sprintf(
            'Congratulations! You won an auction for a %s from %s for %0.2f monetary units',
            $this->getItemDescription(),
            $this->getUser()->getUserEmail(),
            $this->getHighestBid()
        );
    }

    public function hasBids(): bool
    {
        return $this->highestBidder !== null;
    }

    public function isOpen(): bool
    {
        return $this->status == self::STATUS_OPEN;
    }

    public function onClose()
    {
        if ($this->status !== self::STATUS_OPEN) {
            throw new \Exception('Only open auctions may be closed.');
        }
        $this->status = self::STATUS_CLOSED;
        if ($this->postOffice instanceof PostOffice) {
            (\ToBeAgile\Notifier\NotifierFactory::getNotifier($this))->notify();
        }
        $this->runCloseProcesses();
    }

    public function onStart()
    {
        if ($this->status !== self::STATUS_NEW) {
            throw new \Exception('Only new auctions may be started.');
        }
        $this->status = self::STATUS_OPEN;
    }

    protected function runCloseProcesses()
    {
        if ($this->hasBids() === false) {
            return;
        }
        $this->sellerAmount = $this->getHighestBid();
        $this->buyerAmount = $this->getHighestBid();
        foreach (\ToBeAgile\Process\CloseProcessFactory::getProcesses($this) as $process) {
            $process->process();
        }
    }

    public function setPostOffice(PostOffice $postOffice)
    {
        $this->postOffice = $postOffice;
    }

}
