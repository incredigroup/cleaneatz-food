@extends('layouts.app')

@section('content')
  @component('admin.components.basic-page', ['title' => 'Orders By Satellite Location'])
    @slot('actions')
      <div class="text-right">
        @include('admin.reports._meal-plan-selection', ['route' => 'admin.reports.orders.by-satellite-location'])
      </div>
    @endslot
    <table
      class="table table-striped table-bordered">
      <thead>
      <tr>
        <th>Store</th>
        <th>Satellite</th>
        <th class="text-right">Orders</th>
        <th class="text-right">Amount</th>
      </tr>
      </thead>
      <tbody>
      @foreach($satelliteLocations as $satelliteLocation)
        <tr>
          <td>{{ \App\Models\StoreLocation::buildNameFromObject($satelliteLocation) }}</td>
          <td>{{ $satelliteLocation->satellite_location_name }}</td>
          <td class="text-right">{{ number_format($satelliteLocation->num_orders) }}</td>
          <td class="text-right">${{ number_format($satelliteLocation->subtotal, 2) }}</td>
        </tr>
      @endforeach
      </tbody>
    </table>
  @endcomponent
@endsection
