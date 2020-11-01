<?php

namespace ToBeAgile\Process;

class NoBidNotifier extends AbstractProcess
{

    protected function iShouldProcess(): bool
    {
        return $this->getAuction()->hasBids() === false;
    }

    protected function process(): void
    {
        if ($this->getAuction()->getPostOffice() === null) {
            return;
        }
        $this->getAuction()->getPostOffice()->sendEmail(
            $this->getAuction()->getUser()->getUserEmail(),
            $this->getAuction()->getNoBidsMessage()
        );
    }

}
