<?php

namespace ToBeAgile\Process;

class StandardShippingFee extends AbstractProcess
{

    const FEE = 10;

    const NOT_APPLICABLE_CATEGORIES = [
        \ToBeAgile\Auction::CATEGORY_CAR,
        \ToBeAgile\Auction::CATEGORY_DOWNLOADABLE_SOFTWARE,
    ];

    public function process()
    {
        if (in_array($this->getAuction()->getCategory(), self::NOT_APPLICABLE_CATEGORIES) === false) {
            $this->getAuction()->addBuyerAmount(self::FEE);
        }
    }

}