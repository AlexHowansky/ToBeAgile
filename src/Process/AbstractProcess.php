<?php

namespace ToBeAgile\Process;

abstract class AbstractProcess implements ProcessInterface
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
