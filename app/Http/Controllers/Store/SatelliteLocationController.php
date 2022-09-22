<?php

namespace App\Http\Controllers\Store;

use App\Http\Requests\StoreLocatioon;
use App\Http\Requests\StoreSatelliteLocation;
use App\Models\SatelliteLocation;
use App\Traits\RendersTableButtons;
use Illuminate\Http\Request;

class SatelliteLocationController extends StoreBaseController
{
    use RendersTableButtons;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $actionRoute = route('store.satellite-locations.create', $this->currentStoreAttribute($request));

        return view('store.satellite-locations.index', compact('actionRoute'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $satelliteLocation = new SatelliteLocation;

        $actionRoute = route('store.satellite-locations.store', $this->currentStoreAttribute($request));

        return view('store.satellite-locations.create', compact('satelliteLocation', 'actionRoute'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreSatelliteLocation $request)
    {
        $input = $request->validated();

        $satelliteLocation = new SatelliteLocation;
        $satelliteLocation->fill($input);
        $satelliteLocation->store_location_id = $this->currentStoreLocation($request)->id;
        $satelliteLocation->is_approved = false;
        $satelliteLocation->save();

        return redirect()->route('store.satellite-locations.index', $this->currentStoreAttribute($request))
            ->with('status', 'Satellite location has been created.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, $storeCode, $id)
    {
        $satelliteLocation = SatelliteLocation::findOrFail($id);
        $this->authorize('view', $satelliteLocation);

        $actionRoute = route(
            'store.satellite-locations.update',
            $this->addCurrentStoreAttribute($request, ['id' => $satelliteLocation->id]));

        return view('store.satellite-locations.edit', compact('satelliteLocation', 'actionRoute'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreSatelliteLocation $request, $storeCode, $id)
    {
        $satelliteLocation = SatelliteLocation::findOrFail($id);
        $this->authorize('update', $satelliteLocation);

        $input = $request->validated();

        $satelliteLocation->fill($input);
        $satelliteLocation->store_location_id = $this->currentStoreLocation($request)->id;
        $satelliteLocation->is_approved = false;
        $satelliteLocation->save();

        return redirect()->route('store.satellite-locations.index', $this->currentStoreAttribute($request))
            ->with('status', 'Satellite location has been updated -- changes must be re-approved.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\SatelliteLocation $satelliteLocation
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $storeCode, $id)
    {
        $satelliteLocation = SatelliteLocation::findOrFail($id);
        $this->authorize('delete', $satelliteLocation);

        $satelliteLocation->delete();

        return redirect()->route('store.satellite-locations.index', $this->currentStoreAttribute($request))
            ->with('status', 'Satellite location has been deleted.');
    }

    public function data(Request $request)
    {
        $storelocation =  $this->currentStoreLocation($request);
        return datatables()->of(
            SatelliteLocation::where('store_location_id', $storelocation->id)
        )
            ->editColumn('is_approved', function ($satellite) {
                return $satellite->is_approved ? 'Yes' : 'No';
            })
            ->rawColumns(['actions'])
            ->toJson();
    }
}
