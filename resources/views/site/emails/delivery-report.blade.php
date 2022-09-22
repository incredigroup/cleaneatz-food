@foreach($orders as $order)
Cart {{ $order->cart->id }} • Order {{ $order->transaction_id }} • Date Placed {{ $order->created_at->setTimezone('America/New_York')->format('m/d/Y g:i a') }}
@endforeach
