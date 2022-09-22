@isset($storeLocation)
  <input type="hidden"
         name="store_location_id"
         value="{{ $storeLocation->id }}">
@else
  <div class="form-row">
    <div class="col-md-12 mb-3">
      @select(['label' => 'Parent Location', 'name' => 'store_location_id', 'model' =>
      $satelliteLocation, 'options' =>
      App\Models\StoreLocation::locationOptions()])
    </div>
  </div>
@endisset

<div class="form-row">
  <div class="col-md-8 mb-3">
    @input(['label' => 'Satellite Name', 'name'=>'name', 'required' => true, 'model' =>
    $satelliteLocation])
  </div>

  <div class="col-md-4 mb-3">
    @currency(['label' => 'Fee', 'placeholder' => 'Convenience Fee', 'model' => $satelliteLocation])
  </div>
</div>

<div class="form-row">
  <div class="col-md-12 mb-3">
    @input(['label' => 'Address', 'required' => true, 'model' => $satelliteLocation])
  </div>
</div>

<div class="form-row">
  <div class="col-md-4 mb-3">
    @input(['label' => 'City', 'required' => true, 'model' => $satelliteLocation])
  </div>

  <div class="col-md-4 mb-3">
    @select(['label' => 'State', 'model' => $satelliteLocation, 'options' =>
    App\SatelliteLocation::stateOptions()])
  </div>

  <div class="col-md-4 mb-3">
    @input(['label' => 'Zip', 'required' => true, 'model' => $satelliteLocation])
  </div>
</div>

@if (!isset($storeLocation))
  <div class="form-row">
    <div class="col-md-12 mb-3">
      @checkbox(['name' => 'is_approved', 'label' => 'Has Been Approved', 'model' =>
      $satelliteLocation])
    </div>
  </div>
@endif
