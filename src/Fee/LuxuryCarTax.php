<?php

namespace ToBeAgile\Fee;

class LuxuryCarTax extends AbstractFee
{

    const THRESHOLD = 50000;

    const TAX_RATE = 0.04;

    public function computeFee()
    {
        if (
            $this->getAuction()->getCategory() === \ToBeAgile\Auction::CATEGORY_CAR &&
            $this->getAuction()->getHighestBid() > self::THRESHOLD
        ) {
            $this->getAuction()->addBuyerAmount($this->getAuction()->getHighestBid() * self::TAX_RATE);
        }
    }

}

