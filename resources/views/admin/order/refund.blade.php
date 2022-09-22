@extends('layouts.app')

@section('content')
  @component('admin.components.basic-page', ['title' => 'Refund Meal Plan Order: ' . $order->transaction_id])

    <livewire:admin.order.order-refund :order="$order" />

  @endcomponent
@endsection
