<?php

namespace ToBeAgile\Process;

class SellerFee extends AbstractProcess
{

    const FEE_RATE = 0.02;

    public function process()
    {
        if ($this->getAuction()->hasBids() === false) {
            return;
        }
        $this->getAuction()->addSellerAmount($this->getAuction()->getHighestBid() * - self::FEE_RATE);
    }

}
