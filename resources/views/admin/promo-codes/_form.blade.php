@isset($storeLocation)
  <input type="hidden"
         name="store_location_id"
         value="{{ $storeLocation->id }}">
@else
  <div class="form-row">
    <div class="col-md-12 mb-3">
      @select(['label' => 'Parent Location', 'name' => 'store_location_id', 'model' => $promoCode,
      'options' => App\Models\StoreLocation::locationOptions(), 'emptyLabel' => 'All Stores'])
    </div>
  </div>
@endisset

<div class="form-row">
  <div class="col-md-6 mb-3">
    @input(['label' => 'Name', 'required' => true, 'model' => $promoCode, 'hint' => 'This is the
    name of the promo code'])
  </div>
  <div class="col-md-6 mb-3">
    @input(['label' => 'Code', 'required' => true, 'model' => $promoCode, 'hint' => 'This is the
    code the customer will enter'])
  </div>
</div>

<p class="text-muted">Specify either a dollar amount or a percentage amount of the discount:</p>
<div class="form-row">
  <div class="col-md-6 mb-3">
    @currency(['label' => 'Discount Amount', 'model' => $promoCode])
  </div>
  <div class="col-md-6 mb-3">
    @input(['label' => 'Discount Percent', 'type' => 'number', 'after' => '%', 'model' =>
    $promoCode])
  </div>
</div>

<div class="form-row">
  <div class="col-md-6 mb-3">
    @input(['label' => 'Min Meals Required', 'model' => $promoCode, 'hint' => 'Optionally specify
    the minimum # of meals required for discount'])
  </div>
  <div class="col-md-6 mb-3">
    @if (!isset($storeLocation))
      @select(['label' => 'Match Type', 'model' => $promoCode, 'options' =>
      App\PromoCode::matchTypeOptions(), 'hint' => 'Use a Starts With match to capture info about
      the customer (e.g. employee #)'])
    @endif
  </div>
</div>

<div class="form-row">
  <div class="col-md-6 mb-3">
    @datepicker(['name' => 'start_on', 'label' => 'Start Date', 'model' => $promoCode])
  </div>
  <div class="col-md-6 mb-3">
    @datepicker(['name' => 'end_on', 'label' => 'End Date', 'model' => $promoCode])
  </div>
</div>
