<?php

namespace ToBeAgile\Process;

class ExpensiveItemLog extends AbstractProcess
{

    protected const FILENAME = 'expensive.log';

    protected const MESSAGE = '%s sold an expensive item valued at %.02f to %s';

    public const THRESHOLD = 10000;

    protected function iShouldProcess(): bool
    {
        return $this->getAuction()->hasBids() === true &&
            $this->getAuction()->getHighestBid() > self::THRESHOLD;
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
