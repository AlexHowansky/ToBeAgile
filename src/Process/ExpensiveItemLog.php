<?php

namespace ToBeAgile\Process;

class ExpensiveItemLog extends AbstractProcess
{

    const FILENAME = "expensive.log";

    const THRESHOLD = 10000;

    public function process()
    {
        if (
            $this->getAuction()->getLogger() === null ||
            $this->getAuction()->getHighestBid() <= self::THRESHOLD
        ) {
            return;
        }
        $message = sprintf("%s sold an expensive item valued at %.02f to %s", 
            $this->getAuction()->getUser()->getUserName(),
            $this->getAuction()->getHighestBid(),
            $this->getAuction()->getHighestBidder()->getUserName()
        );
        $this->getAuction()->getLogger()->log(self::FILENAME, $message);  
    }
}
