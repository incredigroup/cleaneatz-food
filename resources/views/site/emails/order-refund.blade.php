@component('mail::message')
  # CUSTOMER REFUND NOTIFICATION
  ### A refund has been processed for your meal plan order

  #### Transaction ID: {{ $mealPlanRefund->order->transaction_id }}
  #### Refund Amount: ${{ number_format($mealPlanRefund->total_refund, 2) }}

@endcomponent
