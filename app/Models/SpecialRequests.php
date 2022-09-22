<?php

namespace App\Models;

class SpecialRequests
{
    public $isLowCarb;
    public $isExtaProtein;

    function __construct($json)
    {
        $requests = json_decode($json);

        $this->isLowCarb = $this->isSet($requests, 'LowCarb');
        $this->isExtaProtein = $this->isSet($requests, 'ExtraProtein');
    }

    private function isSet($requests, $key)
    {
        return isset($requests->$key) && $requests->$key;
    }
}
