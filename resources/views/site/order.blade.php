@extends('layouts.site')

@section('content')
  <div class="gray-header-panel">
    <h1>Order Online</h1>
  </div>

  <section class="order-banner banner"
           id="main">
    <div id="app"
         class="container">
      <section>
        <my-store-location class="text-white"
                           default-store-code="{{ $defaultStoreCode }}"
                           asset-path="{{ asset('') }}"></my-store-location>
      </section>
      <order-options asset-path="{{ asset('') }}">
      </order-options>
    </div>
  </section>
@endsection
