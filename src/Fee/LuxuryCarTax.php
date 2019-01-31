<?php

namespace ToBeAgile\Fee;

class LuxuryCarTax extends AbstractFee
{

    public function computeFee()
    {
        if (
            $this->getAuction()->getCategory() === \ToBeAgile\Auction::CATEGORY_CAR &&
            $this->getAuction()->getHighestBid() > 50000
        ) {
            $this->getAuction()->addBuyerAmount($this->getAuction()->getHighestBid() * 0.04);
        }
    }

}

