@component('mail::message')
# CUSTOM ORDER NOTIFICATION
### YOU HAVE RECEIVED THE FOLLOWING CUSTOM ORDER

#### Transaction ID: {{ $order->transaction_id }}
#### Customer: {{ $order->first_name }} {{ $order->last_name }}

@foreach($order->items as $item)
<div class="banner gray-banner">Custom Order, Quantity: {{ $item->quantity }}</div>

@component('mail::table')
| Option               |  Selection
| -----------          |  ----------
| Protein              |  {{ $item->custom_ingredients->protein->label }}
| Protein Portion      |  {{ $item->custom_ingredients->proteinPortion->label }}
| Carbohydrate         |  {{ $item->custom_ingredients->carb->label }}
| Carbohydrate Portion |  {{ $item->custom_ingredients->carbPortion->label }}
| Vegetables           |  {{ $item->custom_ingredients->vegetable->label }}
| Vegetables 2         |  {{ $item->custom_ingredients->vegetable2->label }}
| Vegetables 3         |  {{ $item->custom_ingredients->vegetable3->label }}
| Sauce                |  {{ $item->custom_ingredients->sauce->label }}
@endcomponent

@if($item->comments)
<p>Comments: {{ $item->comments }}</p>
@endif

@endforeach

@endcomponent
