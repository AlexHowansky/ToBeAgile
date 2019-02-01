<?php

namespace ToBeAgile\Process;

interface ProcessInterface
{

    public function __construct(\ToBeAgile\Auction $auction);

    public function process();

}
