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

    protected float $buyerAmount = 0;

    protected string $category;

    protected int $endTime;

    protected float $highestBid = 0;

    protected ?User $highestBidder = null;

    protected string $itemDescription;

    protected ?Logger $logger = null;

    protected ?PostOffice $postOffice = null;

    protected float $sellerAmount = 0;

    protected float $startPrice;

    protected int $startTime;

    protected int $status = self::STATUS_NEW;

    protected User $user;

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

    public function addBuyerAmount(float $amount): void
    {
        $this->buyerAmount += $amount;
    }

    public function addSellerAmount(float $amount): void
    {
        $this->sellerAmount += $amount;
    }

    public function bid(User $user, float $bid): void
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

    public function getBuyerAmount(): float
    {
        return $this->buyerAmount;
    }

    public function getCategory(): string
    {
        return $this->category;
    }

    public function getEndTime(): int
    {
        return $this->endTime;
    }

    public function getHighestBid(): float
    {
        if ($this->highestBid === 0.0) {
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

    public function getLogger(): ?Logger
    {
        return $this->logger;
    }

    public function getNoBidsMessage(): string
    {
        return sprintf('Sorry, your auction for %s did not have any bidders.', $this->getItemDescription());
    }

    public function getPostOffice(): ?PostOffice
    {
        return $this->postOffice;
    }

    public function getSellerAmount(): float
    {
        return $this->sellerAmount;
    }

    public function getSoldMessage(): string
    {
        return sprintf(
            'Your %s auction sold to bidder %s for %0.2f monetary units.',
            $this->getItemDescription(),
            $this->getHighestBidder()->getUserEmail(),
            $this->getHighestBid()
        );
    }

    public function getStartingPrice(): float
    {
        return $this->startPrice;
    }

    public function getStartTime(): int
    {
        return $this->startTime;
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function getWonMessage(): string
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

    public function onClose(): void
    {
        if ($this->status !== self::STATUS_OPEN) {
            throw new \Exception('Only open auctions may be closed.');
        }
        $this->status = self::STATUS_CLOSED;
        $this->runCloseProcesses();
    }

    public function onStart(): void
    {
        if ($this->status !== self::STATUS_NEW) {
            throw new \Exception('Only new auctions may be started.');
        }
        $this->status = self::STATUS_OPEN;
    }

    protected function runCloseProcesses(): void
    {
        $this->sellerAmount = $this->hasBids() === true ? $this->getHighestBid() : 0;
        $this->buyerAmount = $this->hasBids() === true ? $this->getHighestBid() : 0;
        foreach (\ToBeAgile\Process\CloseProcessFactory::getProcesses($this) as $process) {
            $process->invoke();
        }
    }

    public function setLogger(Logger $logger): void
    {
        $this->logger = $logger;
    }

    public function setPostOffice(PostOffice $postOffice): void
    {
        $this->postOffice = $postOffice;
    }

}
