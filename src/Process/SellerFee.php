<?php

namespace ToBeAgile\Process;

class SellerFee extends AbstractProcess
{

    const FEE_RATE = 0.02;

    protected function iShouldProcess(): bool
    {
        return $this->getAuction()->hasBids() === true;
    }

    protected function process()
    {
        $this->getAuction()->addSellerAmount($this->getAuction()->getHighestBid() * - self::FEE_RATE);
    }

}
