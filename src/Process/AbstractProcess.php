<?php

namespace ToBeAgile\Process;

abstract class AbstractProcess implements ProcessInterface
{

    protected $auction;

    public function __construct(\ToBeAgile\Auction $auction)
    {
        $this->auction = $auction;
    }

    protected function getAuction(): \ToBeAgile\Auction
    {
        return $this->auction;
    }

    abstract protected function iShouldProcess(): bool;

    public function invoke()
    {
        if ($this->iShouldProcess() === true) {
            $this->process();
        }
    }

    abstract protected function process();

}
