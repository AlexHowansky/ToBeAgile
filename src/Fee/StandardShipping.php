<?php

namespace ToBeAgile\Fee;

class StandardShipping extends AbstractFee
{

    const FEE = 10;

    const NOT_APPLICABLE_CATEGORIES = [
        \ToBeAgile\Auction::CATEGORY_CAR,
        \ToBeAgile\Auction::CATEGORY_DOWNLOADABLE_SOFTWARE,
    ];

    public function computeFee()
    {
        if (!in_array($this->getAuction()->getCategory(), self::NOT_APPLICABLE_CATEGORIES)) {
            $this->getAuction()->addBuyerAmount(self::FEE);
        }
    }

}
