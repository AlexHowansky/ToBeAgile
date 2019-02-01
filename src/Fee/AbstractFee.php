<?php

namespace ToBeAgile\Fee;

abstract class AbstractFee implements FeeInterface 
{

    protected $auction;

    public function __construct(\ToBeAgile\Auction $auction)
    {
        $this->auction = $auction;
    }
    
    public function getAuction(): \ToBeAgile\Auction
    {
        return $this->auction;
    }

}
