<?php

namespace ToBeAgile\Process;

class NoBidNotifier extends AbstractProcess
{

    public function process()
    {
        if (
            $this->getAuction()->hasBids() === true ||
            $this->getAuction()->getPostOffice() === null
        ) {
            return;
        }
        $this->getAuction()->getPostOffice()->sendEmail(
            $this->getAuction()->getUser()->getUserEmail(),
            $this->getAuction()->getNoBidsMessage()
        );
    }

}
