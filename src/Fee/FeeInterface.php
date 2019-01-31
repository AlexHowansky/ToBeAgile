<?php

namespace ToBeAgile\Fee;

interface FeeInterface
{

    public function __construct(\ToBeAgile\Auction $auction);

    public function computeFee();

}
