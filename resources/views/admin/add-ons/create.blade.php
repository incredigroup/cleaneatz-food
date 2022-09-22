@extends('layouts.app')

@section('content')
  @component('admin.components.basic-page', ['title' => 'Create Add-On Item'])
    @component('admin.components.create-form', ['file' => true, 'action' => route('admin.add-ons.store')])
      @include('admin.add-ons._form', ['model' => $meal, 'variants' => $variants])
    @endcomponent
  @endcomponent
@endsection
