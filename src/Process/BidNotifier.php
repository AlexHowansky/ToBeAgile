<?php

namespace ToBeAgile\Process;

class BidNotifier extends AbstractProcess
{

    protected function iShouldProcess(): bool
    {
        return $this->getAuction()->hasBids() === true;
    }

    protected function process(): void
    {
        if ($this->getAuction()->getPostOffice() === null) {
            return;
        }
        $this->getAuction()->getPostOffice()->sendEmail(
            $this->getAuction()->getUser()->getUserEmail(),
            $this->getAuction()->getSoldMessage()
        );
        $this->getAuction()->getPostOffice()->sendEmail(
            $this->getAuction()->getHighestBidder()->getUserEmail(),
            $this->getAuction()->getWonMessage()
        );
    }

}
