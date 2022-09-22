<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;

class StoreBaseController extends Controller
{
    protected $storeLocation = null;

    protected function currentStoreLocation($request)
    {
        return $request->request->get('store_location');
    }

    protected function currentStoreAttribute($request)
    {
        return ['store_code' => $this->currentStoreLocation($request)->code];
    }

    protected function addCurrentStoreAttribute($request, $attributes)
    {
        return array_merge($attributes, $this->currentStoreAttribute($request));
    }
}

