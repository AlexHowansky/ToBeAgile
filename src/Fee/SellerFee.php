<?php

namespace ToBeAgile\Fee;

class SellerFee extends AbstractFee
{

    const FEE_RATE = 0.02;
    
    public function computeFee()
    {
        $this->getAuction()->addSellerAmount($this->getAuction()->getHighestBid() * - self::FEE_RATE);
    }

}
