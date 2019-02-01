<?php

namespace ToBeAgile\Process;

class BidNotifier extends AbstractProcess
{

    public function process()
    {
        if (
            $this->getAuction()->hasBids() === false ||
            $this->getAuction()->getPostOffice() === null
        ) {
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
