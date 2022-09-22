@extends('layouts.site')

@section('content')
  <section id="main">

    <div class="gray-header-panel">
      <h1>Order Complete!</h1>
    </div>

    <div class="container">
      <div class="row py-4">
        <div style="min-height: 500px;">
          <h3>THANK YOU!</h3>
          <strong>Order #{{ $order->transaction_id }}</strong>
          <p>Your order is complete!</p>
          <p>
            Details of your order, including pickup instructions, have been emailed to
            <strong>{{ $order->email }}</strong>.
          </p>

          <h5>{{ $order->pickupDateDescription()['descr'] }}:
            {{ $order->pickupDateDescription()['dates'] }}</h5>

          @if ($promptToRegister)
            @include(
                'site.online-order.order-complete._register'
            )
          @endif
        </div>
      </div>
    </div>
  </section>
@endsection

@push('scripts')
  @if (!empty($purchaseScript))
    {!! $purchaseScript !!}
  @endif
@endpush
