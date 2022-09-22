@extends('layouts.app')

@section('content')
  @component('admin.components.basic-page', ['title' => 'Edit A Menu'])
    @component('admin.components.edit-form', ['action' => route('admin.meal-plans.update', $mealPlan)])
      @include('admin.meal-plans._form', ['model' => $mealPlan])
    @endcomponent
  @endcomponent
@endsection
