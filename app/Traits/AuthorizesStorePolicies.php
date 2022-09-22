<?php

namespace App\Traits;

trait AuthorizesStorePolicies
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorizeResource($resource)
    {
        if (request()->user()->isAdmin()) {
            return true;
        }

        return $resource->store_location_id === request()->get('store_location')->id;
    }
}
