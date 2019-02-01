<?php

namespace ToBeAgile\Process;

class CarLog extends AbstractProcess
{

    const FILENAME = 'car.log';

    public function process()
    {
        if ($this->getAuction()->getLogger() === null) {
            return;
        }
        if ($this->getAuction()->getCategory() === \ToBeAgile\Auction::CATEGORY_CAR) {
            $message = sprintf(
                '%s sold a car valued at %.02f to %s',
                $this->getAuction()->getUser()->getUserName(),
                $this->getAuction()->getHighestBid(),
                $this->getAuction()->getHighestBidder()->getUserName()
            );
            $this->getAuction()->getLogger()->log(self::FILENAME, $message);
        }
    }

}
