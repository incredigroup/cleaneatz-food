@component('mail::message')
# CUSTOMER INVOICE
### YOUR TRANSACTION HAS BEEN PROCESSED. THANK YOU!

#### Transaction ID: {{ $order->transaction_id }}

@if ($order->satellite()->exists())
  ** Satellite pickup: **<br>
  {{ $order->satellite->name }}<br>
  {{ $order->satellite->address }}<br>
  {{ $order->satellite->city }}, {{ $order->satellite->state }} {{ $order->satellite->zip }}
@endif

<div class="banner gray-banner">Order Details</div>

@component('mail::table')
| Meal | Quantity |
| ----------- | ----------|
@foreach ($order->mealPlanItems as $item)
| {{ $item->meal->display_name }} | {{ $item->quantity }} |
@endforeach
@endcomponent

@if ($order->addOnItems->count() > 0)
@component('mail::table')
| Add On Items | Quantity |
| ----------- | ---------- |
@foreach ($order->addOnItems as $item)
| {{ $item->meal->display_name }} | {{ $item->quantity }} |
@endforeach
@endcomponent
@endif

  <div class="text-center">
    <strong>{{ $order->store->locale }}</strong><br>
    {{ $order->store->address }}<br>
    {{ $order->store->phone }}<br><br>
    {!! nl2br($order->store->hours_of_operation) !!}<br><br>
  </div>

  <div class="text-center banner">
    {{ $order->pickupDateDescription()['descr'] }}:<br>
    {{ $order->pickupDateDescription()['dates'] }}<br><br>
  </div>

  <div class="panel text-right no-border">
    Subtotal: ${{ number_format($order->subtotal, 2) }}<br>
    @if ($order->tax > 0)
      Tax: ${{ number_format($order->tax, 2) }}<br>
    @endif
    @if ($order->delivery_fee > 0)
      Delivery: ${{ number_format($order->delivery_fee, 2) }}<br>
    @endif

    @if ($order->satellite_fee > 0)
      Satellite Fee: ${{ number_format($order->satellite_fee, 2) }}<br>
    @endif

    @if ($order->tip_amount > 0)
      Tip: ${{ number_format($order->tip_amount, 2) }}<br>
    @endif
    @if ($order->promo_amount > 0)
      Discount {{ $order->promo_code }}: (${{ number_format($order->promo_amount, 2) }})<br>
    @endif

  </div>

  <div class="banner orange-banner text-right">
    Total ${{ number_format($order->total, 2) }}
  </div>

  <table style="width:100%">
    @if ($order->cart->hasSpecialRequests())
      <tr>
        <td colspan="2">
          <div class="banner gray-banner meta">
            You order contains these special requests:
            <strong>{{ $order->cart->special_requests_display }}</strong>
          </div>
        </td>
      </tr>
    @endif
    <tr>
      <td width="50%">
        <div class="banner gray-banner meta">
          <strong>Date</strong><br>
          {{ $order->order_date }}
        </div>
      </td>
      <td width="50%">
        <div class="banner gray-banner meta">
          <strong>Payment Method</strong><br>
          {{ $order->payment_method }}
        </div>
      </td>
    </tr>
  </table>
@endcomponent
