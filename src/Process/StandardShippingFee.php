<?php

namespace ToBeAgile\Process;

class StandardShippingFee extends AbstractProcess
{

    public const FEE = 10;

    protected const NOT_APPLICABLE_CATEGORIES = [
        \ToBeAgile\Auction::CATEGORY_CAR,
        \ToBeAgile\Auction::CATEGORY_DOWNLOADABLE_SOFTWARE,
    ];

    protected function iShouldProcess(): bool
    {
        return $this->getAuction()->hasBids() === true &&
            in_array($this->getAuction()->getCategory(), self::NOT_APPLICABLE_CATEGORIES) === false;
    }

    protected function process(): void
    {
        $this->getAuction()->addBuyerAmount(self::FEE);
    }

}
