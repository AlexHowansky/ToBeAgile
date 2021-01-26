<?php

namespace ToBeAgile\Process;

class CloseProcessFactory
{

    /**
     * The list of processes to run on a closing auction.
     *
     * @param \ToBeAgile\Auction $auction The auction to process.
     *
     * @return array<ProcessInterface>
     */
    public static function getProcesses(\ToBeAgile\Auction $auction): array
    {
        return [
            new StandardShippingFee($auction),
            new LuxuryCarTaxFee($auction),
            new CarShippingFee($auction),
            new SellerFee($auction),
            new CarLog($auction),
            new ExpensiveItemLog($auction),
            new NoBidNotifier($auction),
            new BidNotifier($auction),
        ];
    }

}
