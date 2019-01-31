<?php

namespace ToBeAgile;

class Auction
{
    
    const STATUS_NEW = 0;
    const STATUS_OPEN = 1;
    const STATUS_CLOSED = 2;
    
    protected $user;
    
    protected $startPrice;
    
    protected $itemDescription;
    
    protected $startTime;
    
    protected $endTime;
    
    protected $status = self::STATUS_NEW;
    
    protected $highestBidder = null;
    
    protected $highestBid = null;
    
    protected $postOffice = null;

    public function __construct(User $user, float $startPrice, string $itemDescription, int $startTime, int $endTime)
    {
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
    }
    
    public function getUser(): User
    {
        return $this->user;
    }

    public function getStartingPrice(): float
    {
        return $this->startPrice;
    }
    
    public function getItemDescription(): string
    {
        return $this->itemDescription;
    }
    
    public function getStartTime(): int
    {
        return $this->startTime;
    }
    
    public function getEndTime(): int
    {
        return $this->endTime;
    }
    
    public function isOpen(): bool
    {
        return $this->status == self::STATUS_OPEN;
    }
    
    public function onStart()
    {
        if ($this->status !== self::STATUS_NEW) {
            throw new \Exception('Only new auctions may be started.');
        }
        $this->status = self::STATUS_OPEN;
    }
    
    public function onClose()
    {
        if ($this->status !== self::STATUS_OPEN) {
            throw new \Exception('Only open auctions may be closed.');
        }
        $this->status = self::STATUS_CLOSED;
        if ($this->postOffice == null) {
             return;
        }
        (\ToBeAgile\Notifier\NotifierFactory::getNotifier($this))->notify();
    }
    
    public function bid(User $user, float $bid)
    {
        if ($this->isOpen() === false) {
            throw new \Exception('Auction is not open.');
        }
        if ($user->isLoggedIn() === false) {
            throw new \Exception('User is not logged in.');
        }
        if ($this->hasBids()) {
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
    
    public function hasBids(): bool
    {
        return $this->highestBidder !== null;
    }
    
    public function getHighestBidder(): User {
        if ($this->highestBidder === null) {
             throw new \Exception('No bids yet.');
        }
        return $this->highestBidder;
    }
    
    public function getHighestBid(): float {
        if ($this->highestBid === null) {
             throw new \Exception('No bids yet.');
        }
        return $this->highestBid;
    }
    
    public function getStatus(): int
    {
        return $this->status;
    }
    
    public function setPostOffice(PostOffice $postOffice)
    {
        $this->postOffice = $postOffice;
    }
    
    public function getPostOffice(): PostOffice
    {
        return $this->postOffice;
    }
    
    public function getNoBidsMessage()
    {
        return 'Sorry, your auction for ' . $this->getItemDescription() . ' did not have any bidders.';
    }
    
    public function getSoldMessage()
    {
        return 'Your ' . $this->getItemDescription() . ' auction sold to bidder ' . $this->getHighestBidder()->getUserEmail() . ' for ' . $this->getHighestBid() . ' monies.';
    }
    
    public function getWonMessage()
    {
        return 'Congratulations! You won an auction for a ' . $this->getItemDescription() . ' from ' . $this->getUser()->getUserEmail() . ' for ' . $this->getHighestBid() . ' monies.';    
    }
    
}
