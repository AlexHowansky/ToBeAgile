<?php

namespace ToBeAgile\Fee;

class FeeFactory
{

    public static function getFees(\ToBeAgile\Auction $auction): array
    {
        return [
            new StandardShipping($auction),
            new LuxuryCarTax($auction),
            new CarShipping($auction),
            new SellerFee($auction),
        ];
    }

}
