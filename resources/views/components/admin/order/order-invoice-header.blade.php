<div class="row">
  <div class="col-md-4">
    <span class="text-muted">Order ID: </span>{{ $order->transaction_id }}<br />
    <span class="text-muted">Status: </span>{{ Str::title($order->transaction_status) }}<br />
    <span class="text-muted">Order At: </span>{{ $order->local_order_date_time }} EST<br />
    <span class="text-muted">Picked Up At: </span>{{ $order->local_cleared_date ?? '--' }}<br />
    <span class="text-muted">Location: </span>{{ $order->store->locale }}<br />
  </div>
  <div class="col-md-4">
    <div class="text-muted">Customer</div>
    {{ $order->name }}<br />
    {{ $order->email }}<br />
  </div>
  <div class="col-md-4">
    <div class="text-muted">Payment Method</div>
    @if ($order->payment_type == \App\Models\MealPlanOrder::PAYMENT_TYPE_ONLINE)
    {{ Str::title($order->card_brand) }} ****{{ $order->card_last_4 }}<br/>
    Exp: {{ $order->card_exp_month }} / {{ $order->card_exp_year }}
    @else
      In-Store
    @endif
  </div>
</div>

