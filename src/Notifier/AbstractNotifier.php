<?php

namespace ToBeAgile\Notifier;

abstract class AbstractNotifier implements NotifierInterface
{

    protected $auction = null;

    public function __construct(\ToBeAgile\Auction $auction)
    {
        $this->auction = $auction;
    }

    public function getAuction(): \ToBeAgile\Auction
    {
        return $this->auction;
    }

}
