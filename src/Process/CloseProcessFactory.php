<?php

namespace ToBeAgile\Process;

class CloseProcessFactory
{

    public static function getProcesses(\ToBeAgile\Auction $auction): array
    {
        return [
            new StandardShippingFee($auction),
            new LuxuryCarTaxFee($auction),
            new CarShippingFee($auction),
            new SellerFee($auction),
            new CarLog($auction),
            new ExpensiveItemLog($auction),
        ];
    }

}
