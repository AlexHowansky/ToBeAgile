<?php

namespace ToBeAgile\Notifier;

class NotifierFactory
{

    public static function getNotifier(\ToBeAgile\Auction $auction) : NotifierInterface
    {
        return $auction->hasBids() === true ? new Bids($auction) : new NoBids($auction);
    }

}
