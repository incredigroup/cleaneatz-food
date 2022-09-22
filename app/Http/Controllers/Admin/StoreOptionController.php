<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StoreLocation;

class StoreOptionController extends Controller
{
    public function index()
    {
        $storeLocations = StoreLocation::orderedByLocation()->get();

        return view('admin.store-options.index', compact('storeLocations'));
    }
}
