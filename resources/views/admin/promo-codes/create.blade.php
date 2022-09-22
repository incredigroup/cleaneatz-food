@extends('layouts.app')

@section('content')
  @component('admin.components.basic-page', ['title' => 'Create A Promo Code'])
    @component('admin.components.create-form', ['action' => route('admin.promo-codes.store')])
      @include('admin.promo-codes._form', ['model' => $promoCode])
    @endcomponent
  @endcomponent
@endsection
