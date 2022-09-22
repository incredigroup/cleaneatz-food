@extends('layouts.app')

@section('content')
  @component('admin.components.basic-page', ['title' => 'Create A Menu'])
    @component('admin.components.create-form', ['action' => route('admin.meal-plans.store')])
      @include('admin.meal-plans._form', ['model' => $mealPlan])
    @endcomponent
  @endcomponent
@endsection
