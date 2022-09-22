@extends('layouts.app')

@section('content')
  @component('admin.components.basic-page', ['title' => 'Create A Meal'])
    @component('admin.components.create-form', ['file' => true, 'action' => route('admin.meals.store')])
      @include('admin.meals._form', ['model' => $meal])
    @endcomponent
  @endcomponent
@endsection
