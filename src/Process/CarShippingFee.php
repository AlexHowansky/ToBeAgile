<?php

namespace ToBeAgile\Process;

class CarShippingFee extends AbstractProcess
{

    const FEE = 1000;

    public function process()
    {
        if ($this->getAuction()->getCategory() === \ToBeAgile\Auction::CATEGORY_CAR) {
            $this->getAuction()->addBuyerAmount(self::FEE);
        }
    }

}
