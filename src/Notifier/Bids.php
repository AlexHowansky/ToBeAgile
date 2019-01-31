<?php

namespace ToBeAgile\Notifier;

class Bids extends AbstractNotifier
{

    public function notify()
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