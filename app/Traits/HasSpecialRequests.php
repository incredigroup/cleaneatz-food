<?php

namespace App\Traits;

trait HasSpecialRequests
{
    public function hasSpecialRequest($request) {
        if ($this->made_special_request === false) {
            return false;
        }

        $specialRequests = json_decode($this->special_requests, true);
        return isset($specialRequests[$request]) && $specialRequests[$request] === true;
    }

    public function getMadeSpecialRequestAttribute() {
        $specialRequests = json_decode($this->special_requests, true);

        if ($specialRequests === null || count($specialRequests) === 0) {
            return false;
        }

        return in_array(true, array_values($specialRequests));
    }
}
