<?php

namespace ToBeAgile\Process;

class CarShippingFee extends AbstractProcess
{

    public const FEE = 1000;

    protected function iShouldProcess(): bool
    {
        return $this->getAuction()->hasBids() === true &&
            $this->getAuction()->getCategory() === \ToBeAgile\Auction::CATEGORY_CAR;
    }

    protected function process(): void
    {
        $this->getAuction()->addBuyerAmount(self::FEE);
    }

}
