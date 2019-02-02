<?php

namespace ToBeAgile\Process;

class NoBidNotifier extends AbstractProcess
{

    protected function iShouldProcess(): bool
    {
        return
            $this->getAuction()->hasBids() === false &&
            $this->getAuction()->getPostOffice() !== null;
    }

    protected function process()
    {
        $this->getAuction()->getPostOffice()->sendEmail(
            $this->getAuction()->getUser()->getUserEmail(),
            $this->getAuction()->getNoBidsMessage()
        );
    }

}
