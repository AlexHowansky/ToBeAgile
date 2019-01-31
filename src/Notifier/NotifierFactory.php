<?php

namespace ToBeAgile\Notifier;

class NotifierFactory
{
    public static function getNotifier(\ToBeAgile\Auction $auction) : NotifierInterface
    {
        if ($auction->hasBids() === true) {
            return new Bids($auction);
        } else {
            return new NoBids($auction);
        }
    }
}