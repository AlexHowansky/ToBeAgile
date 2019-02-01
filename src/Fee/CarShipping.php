<?php

namespace ToBeAgile\Fee;

class CarShipping extends AbstractFee
{

    const FEE = 1000;

    public function computeFee()
    {
        if ($this->getAuction()->getCategory() === \ToBeAgile\Auction::CATEGORY_CAR) {
            $this->getAuction()->addBuyerAmount(self::FEE);
        }
    }

}
