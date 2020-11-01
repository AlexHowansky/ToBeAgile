<?php

namespace ToBeAgile\Process;

abstract class AbstractProcess implements ProcessInterface
{

    protected \ToBeAgile\Auction $auction;

    public function __construct(\ToBeAgile\Auction $auction)
    {
        $this->auction = $auction;
    }

    protected function getAuction(): \ToBeAgile\Auction
    {
        return $this->auction;
    }

    public function invoke(): void
    {
        if ($this->iShouldProcess() === true) {
            $this->process();
        }
    }

    abstract protected function iShouldProcess(): bool;

    abstract protected function process(): void;

}
