@extends('layouts.app')

@section('content')
  @component('admin.components.basic-page', ['title' => 'Tips Per Store By Date', 'subtitle' => 'Past 7 Days'])
    <div class="row">
      <div class="col-sm-12">
        <table class="table">
          <thead>
            <tr>
              <th scope="col">Store</th>
              <th scope="col">Date</th>
              <th scope="col">Total</th>
            </tr>
          </thead>
          <tbody>
            @foreach($ordersByStore as $ordersForStore)
              @foreach($ordersForStore as $day => $orders)
                <tr>
                  <td>{{ $orders[0]->store->locale }}</td>
                  <td>{{ $day }}</td>
                  <td>${{ number_format($orders->sum('tip_amount'), 2) }}</td>
                </tr>
              @endforeach
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  @endcomponent
@endsection
