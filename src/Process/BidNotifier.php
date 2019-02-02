<?php

namespace ToBeAgile\Process;

class BidNotifier extends AbstractProcess
{

    protected function iShouldProcess(): bool
    {
        return
            $this->getAuction()->hasBids() === true &&
            $this->getAuction()->getPostOffice() !== null;
    }

    protected function process()
    {
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
