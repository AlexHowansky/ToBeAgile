<?php

namespace ToBeAgile\Process;

class LuxuryCarTaxFee extends AbstractProcess
{

    public const TAX_RATE = 0.04;

    public const THRESHOLD = 50000;

    protected function iShouldProcess(): bool
    {
        return $this->getAuction()->hasBids() === true &&
            $this->getAuction()->getCategory() === \ToBeAgile\Auction::CATEGORY_CAR &&
            $this->getAuction()->getHighestBid() > self::THRESHOLD;
    }

    protected function process(): void
    {
        $this->getAuction()->addBuyerAmount($this->getAuction()->getHighestBid() * self::TAX_RATE);
    }

}
