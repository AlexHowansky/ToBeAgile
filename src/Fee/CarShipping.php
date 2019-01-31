<?php

namespace ToBeAgile\Fee;

class CarShipping extends AbstractFee
{

    public function computeFee()
    {
        if (
            $this->getAuction()->getCategory() === \ToBeAgile\Auction::CATEGORY_CAR
        ) {
            $this->getAuction()->addBuyerAmount(1000);
        }
    }
}