<div class="form-row">
  <div class="col-md-12 mb-3">
    @radio(['name' => 'role', 'label' => 'Admin Type', 'values'=>['store' => 'Store Admin', 'admin'
    => 'Global Admin'],
    'model' => $user])
  </div>
</div>

<div id="store_location_id_row"
     style="display: none">
  <div class="form-row">
    <div class="col-md-12 mb-3">
      @select(['label' => 'Location', 'name' => 'store_location_id', 'model' => $user, 'options' =>
      App\Models\StoreLocation::locationOptions()])
    </div>
  </div>
</div>

<div class="form-row">
  <div class="col-md-6 mb-3">
    @input(['label' => 'First Name', 'required' => true, 'model' => $user])
  </div>
  <div class="col-md-6 mb-3">
    @input(['label' => 'Last Name', 'required' => true, 'model' => $user])
  </div>
</div>

<div class="form-row">
  <div class="col-md-12 mb-3">
    @input(['label' => 'Email', 'required' => true, 'model' => $user])
  </div>
</div>

<div class="form-row">
  <div class="col-md-12 mb-3">
    @input(['label' => 'Username', 'required' => true, 'model' => $user])
  </div>
</div>

@push('scripts')
  <script>
    $(document).ready(function() {
      function toggleStoreVisibility(role) {
        $("#store_location_id_row").toggle(role === 'store');
      }

      toggleStoreVisibility($("input[name='role']:checked").val());
      $('input[type=radio][name=role]').on('change', function() {
        toggleStoreVisibility($(this).val());
      });
    });
  </script>
@endpush
