<?php

namespace ToBeAgile\Fee;

class SellerFee extends AbstractFee
{
    
    public function computeFee()
    {
        $this->getAuction()->addSellerAmount($this->getAuction()->getHighestBid() * -0.02);
    }
}
