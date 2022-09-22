@extends('layouts.app')

@section('content')
  @component('admin.components.basic-page', ['title' => 'Payeezy Setup'])

    <form class="form-inline mb-5">
      <select class="form-control col-sm-6" name="storeLocationId">
        <option value="">Select a Location...</option>
        @foreach (\App\Models\StoreLocation::locationOptions() as $k=>$v)
          <option value="{{ $k }}" {{ $k == $storeLocationId ? 'selected' : '' }}>
            {{ $v }}
          </option>
        @endforeach
      </select>
      <button type="submit" class="btn btn-primary ml-3">Select</button>
    </form>

    @if ($storeLocationId)
      @component('admin.components.credit-card-form', ['buttonLabel' => 'Tokenize Card'])
        <input type="hidden" id="store_location_id" name="store_location_id" value="{{ $storeLocationId }}">
      @endcomponent

      @include('admin.payeezy-setup._place-order-form')
    @endif

  @endcomponent
@endsection

@push('scripts')
  <script>

    function onTokenizeSuccess(clientToken) {
      $('#payment-form').hide();
      $('#place-order-form').show();
      $('input[type=text][name=client_token]').val(clientToken);
    }

    function onTokenizeError(error) {
      alert('Tokenization request error: ' + error.message);
    }
  </script>
@endpush
