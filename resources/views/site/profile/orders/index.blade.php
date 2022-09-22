@extends('layouts.site')

@section('content')
  @component('site.profile.profile-page')

    <table class="table table-bordered table-striped profile-table">
      <thead>
      <tr class="table-success">
        <th scope="col">ID</th>
        <th scope="col" class="nowrap">Ordered On</th>
        <th scope="col">Pickup Location</th>
        <th scope="col">Payment Type</th>
        <th scope="col" class="text-right">Order Total</th>
      </tr>
      </thead>
      <tbody>
      @foreach($orders as $order)
        <tr>
          <td>{{ $order->transaction_id }}</td>
          <td>{{ $order->local_order_date }}</td>
          <td>
            @if ($order->store)
              {{ $order->store->locale }}
            @endif
            @if ($order->satellite)
              - {{ $order->satellite->name }}
            @endif
          </td>
          <td>{{ $order->payment_type == 'online' ? 'Online' : 'In-Store' }}</td>
          <td class="text-right">${{ $order->total }}</td>
        </tr>
      @endforeach
      @if ($orders->isEmpty())
        <tr>
          <td colspan="5">You don't currently have any orders under this account.</td>
        </tr>
      @endif
      </tbody>
    </table>

  @endcomponent
@endsection
