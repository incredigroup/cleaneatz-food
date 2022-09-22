@extends('layouts.app')

@section('content')
  @component('admin.components.basic-page', ['title' => 'Store Admin Options'])
    @foreach ($storeLocations->groupBy('state') as $k => $v)
      <h1>{{ $k }}</h1>
      <div class="row">
        @foreach($v as $storeLocation)
          <div class="col-md-3">
            <div class="card mb-3">
              <div class="card-body text-center">
                <h5 class="card-title">{{ $storeLocation->locale }}</h5>
                <hr />
                <ul class="list-unstyled">
                  <li class="mb-2">
                    <a href="{{ route('store.orders.index', ['store_code' => $storeLocation->code]) }}">
                      View Store Orders
                    </a>
                  </li>
                  <li class="mb-2">
                    <a href="{{ route('store.satellite-locations.create', ['store_code' => $storeLocation->code]) }}">
                      Create Satellite
                    </a>
                  </li>
                  <li class="mb-2">
                    <a href="{{ route('store.satellite-locations.index', ['store_code' => $storeLocation->code]) }}">
                      All Satellites
                    </a>
                  </li>
                  <li class="mb-2">
                    <a href="{{ route('store.promo-codes.index', ['store_code' => $storeLocation->code]) }}">
                      Promo Codes
                    </a>
                  </li>
                  <li class="mb-2">
                    <a href="{{ route('store.dashboard.index', ['store_code' => $storeLocation->code]) }}">
                      Dashboard
                    </a>
                  </li>
                </ul>
              </div>
            </div>
          </div>
        @endforeach
      </div>
    @endforeach
  @endcomponent
@endsection
