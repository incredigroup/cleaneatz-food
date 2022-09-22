<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SatelliteLocation;
use App\Traits\RendersTableButtons;
use Illuminate\Http\Request;

class SatelliteLocationController extends Controller
{
    use RendersTableButtons;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.satellite-locations.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $satelliteLocation = new SatelliteLocation();
        return view('admin.satellite-locations.create', compact('satelliteLocation'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->validate($this->rules());

        $satelliteLocation = new SatelliteLocation();
        $satelliteLocation->fill($input);
        $satelliteLocation->saveWithGuardedFields($input);

        return redirect()
            ->route('admin.satellite-locations.index')
            ->with('status', 'Satellite location has been created.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\SatelliteLocation  $satelliteLocation
     * @return \Illuminate\Http\Response
     */
    public function show(SatelliteLocation $satelliteLocation)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\SatelliteLocation  $satelliteLocation
     * @return \Illuminate\Http\Response
     */
    public function edit(SatelliteLocation $satelliteLocation)
    {
        return view('admin.satellite-locations.edit', compact('satelliteLocation'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SatelliteLocation  $satelliteLocation
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SatelliteLocation $satelliteLocation)
    {
        $input = $request->validate($this->rules());

        $satelliteLocation->fill($input);
        $satelliteLocation->saveWithGuardedFields($input);

        return redirect()
            ->route('admin.satellite-locations.index')
            ->with('status', 'Satellite location has been updated.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SatelliteLocation  $satelliteLocation
     * @return \Illuminate\Http\Response
     */
    public function destroy(SatelliteLocation $satelliteLocation)
    {
        $satelliteLocation->delete();

        return redirect()
            ->route('admin.satellite-locations.index')
            ->with('status', 'Satellite location has been deleted.');
    }

    public function unapproved()
    {
        $satelliteLocations = SatelliteLocation::unapproved();

        return view('admin.satellite-locations.unapproved', compact('satelliteLocations'));
    }

    public function approve($id)
    {
        $satelliteLocation = SatelliteLocation::findOrFail($id);
        $satelliteLocation->is_approved = true;
        $satelliteLocation->save();

        return redirect()
            ->route('admin.satellite-locations.unapproved')
            ->with('status', 'Satellite location has been approved.');
    }

    public function data()
    {
        return datatables()
            ->of(SatelliteLocation::with('storeLocation')->select('satellite_locations.*'))
            ->editColumn('store_location.city', function ($satellite) {
                return $satellite->storeLocation->locale;
            })
            ->addColumn('actions', function ($satellite) {
                return $this->renderEditDeleteButtons('admin.satellite-locations', $satellite->id);
            })
            ->rawColumns(['actions'])
            ->toJson();
    }

    public function rules()
    {
        return [
            'store_location_id' => 'required|integer',
            'name' => 'required|string|max:255',
            'fee' => 'required|numeric',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'zip' => 'required|string|max:10',
            'is_approved' => 'boolean',
        ];
    }
}
