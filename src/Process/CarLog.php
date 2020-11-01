<?php

namespace ToBeAgile\Process;

class CarLog extends AbstractProcess
{

    const FILENAME = 'car.log';

    const MESSAGE = '%s sold a car valued at %.02f to %s';

    protected function iShouldProcess(): bool
    {
        return
            $this->getAuction()->hasBids() === true &&
            $this->getAuction()->getCategory() === \ToBeAgile\Auction::CATEGORY_CAR;
    }

    protected function process(): void
    {
        if ($this->getAuction()->getLogger() === null) {
            return;
        }
        $this->getAuction()->getLogger()->log(
            self::FILENAME,
            sprintf(
                self::MESSAGE,
                $this->getAuction()->getUser()->getUserName(),
                $this->getAuction()->getHighestBid(),
                $this->getAuction()->getHighestBidder()->getUserName()
            )
        );
    }

}
