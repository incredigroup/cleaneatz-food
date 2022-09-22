<x-admin.order.order-invoice-header :order="$order"></x-admin.order.order-invoice-header>

<table class="table mt-3">
  <thead>
  <tr>
    <th scope="col">Meal Name</th>
    <th scope="col" class="text-right">Quantity</th>
    <th scope="col" class="text-right">Unit Cost</th>
  </tr>
  </thead>
  <tbody>
  @foreach ($order->items as $item)
    <tr>
      <td>{{ $item->meal_name }}</td>
      <td class="text-right">{{ $item->quantity }}</td>
      <td class="text-right">${{ number_format($item->cost, 2) }}</td>
    </tr>
  @endforeach
  </tbody>
  <tfoot>
  <tr>
    <td colspan="2" class="text-right">Subtotal</td>
    <td class="text-right">${{ number_format($order->subtotal, 2) }}</td>
  </tr>
  @if ($order->promo_amount > 0)
    <tr>
      <td colspan="2" class="text-right border-top-0">Discount</td>
      <td class="text-right border-top-0">(${{ number_format($order->promo_amount, 2) }})</td>
    </tr>
  @endif
  @if ($order->satellite_fee > 0)
    <tr>
      <td colspan="2" class="text-right border-top-0">Satellite Fee</td>
      <td class="text-right border-top-0">${{ number_format($order->satellite_fee, 2) }}</td>
    </tr>
  @endif
  <tr>
    <td colspan="2" class="text-right border-top-0">Sales Tax</td>
    <td class="text-right border-top-0">${{ number_format($order->tax, 2) }}</td>
  </tr>
  <tr>
    <td colspan="2" class="text-right border-top-0">Tip</td>
    <td class="text-right border-top-0">${{ number_format($order->tip_amount, 2) }}</td>
  </tr>
  <tr>
    <td colspan="2" class="text-right border-top-0"><strong>Total</strong></td>
    <td class="text-right border-top-0"><strong>${{ number_format($order->total, 2) }}</strong></td>
  </tr>
  </tfoot>
</table>

@if (count($order->refunds))
  <x-admin.order.order-invoice-refunds :refunds="$order->refunds"></x-admin.order.order-invoice-refunds>
@endif
