<?php

namespace ToBeAgile\Notifier;

interface NotifierInterface
{

    public function notify();

    public function __construct(\ToBeAgile\Auction $auction);

}
