@extends('layouts.app')

@section('content')
  @component('admin.components.basic-page', ['title' => 'Edit A Promo Code'])
    @component('admin.components.edit-form', ['action' => route('admin.promo-codes.update', $promoCode)])
      @include('admin.promo-codes._form', ['model' => $promoCode])
    @endcomponent
  @endcomponent
@endsection
