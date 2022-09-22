<h4>Refunds</h4>
@foreach ($refunds as $refund)
  <div>
    <span class="text-muted">Refund At: </span>{{ $refund->local_refund_date_time }} EST<br />
    <span class="text-muted">Refund Amount: </span>${{ number_format($refund->total_refund, 2) }}<br />
    <span class="text-muted">Refunded By: </span>{{ $refund->user->first_name }} {{ $refund->user->last_name }}<br />
    @if ($refund->notes)
    <span class="text-muted">Refunded By: </span>{{ $refund->notes }}<br />
      @endif
  </div>
@endforeach
