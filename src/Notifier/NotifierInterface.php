<?php

namespace ToBeAgile\Notifier;

interface NotifierInterface
{

    public function __construct(\ToBeAgile\Auction $auction);

    public function notify();

}
