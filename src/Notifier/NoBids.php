<?php

namespace ToBeAgile\Notifier;

class NoBids extends AbstractNotifier
{

    public function notify()
    {
        $this->getAuction()->getPostOffice()->sendEmail(
            $this->getAuction()->getUser()->getUserEmail(),
            $this->getAuction()->getNoBidsMessage()
        );
    }

}
