@extends('layouts.app')

@section('content')
  @component('admin.components.basic-page', ['title' => 'Meal Plan Order: ' . $order->transaction_id])
    @slot('actions')
      @if ($order->payment_type === 'online' && count($order->refunds) === 0)
        <div class="text-right">
          <div class="dropdown show">
            <a class="btn btn-primary dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
               data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              Options
            </a>
            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuLink">
              <a class="dropdown-item" href="{{ route('admin.orders.refund', $order->id) }}">Issue Refund</a>
            </div>
          </div>
        </div>
      @endif
    @endslot

    <x-admin.order.order-invoice :order="$order">
    </x-admin.order.order-invoice>

  @endcomponent
@endsection
