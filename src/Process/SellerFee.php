<?php

namespace ToBeAgile\Process;

class SellerFee extends AbstractProcess
{

    const STANDARD_FEE_RATE = 0.02;

    const PREFERRED_FEE_RATE = 0.01;

    protected function iShouldProcess(): bool
    {
        return $this->getAuction()->hasBids() === true;
    }

    protected function process()
    {
        $rate = $this->getAuction()->getUser()->isPreferredSeller() === true
            ? self::PREFERRED_FEE_RATE
            : self::STANDARD_FEE_RATE;
        $this->getAuction()->addSellerAmount(- $this->getAuction()->getHighestBid() * $rate);
    }

}
