<?php

namespace ToBeAgile\Process;

class ExpensiveItemLog extends AbstractProcess
{

    const FILENAME = 'expensive.log';

    const MESSAGE = '%s sold an expensive item valued at %.02f to %s';

    const THRESHOLD = 10000;

    protected function iShouldProcess(): bool
    {
        return
            $this->getAuction()->hasBids() === true &&
            $this->getAuction()->getLogger() !== null &&
            $this->getAuction()->getHighestBid() > self::THRESHOLD;
    }

    protected function process()
    {
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
