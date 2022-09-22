@extends('layouts.app')

@section('content')
  @component('admin.components.basic-page', ['title' => 'Custom Meal Options'])
    <p class="mb-4">
      To edit the values available for each custom meal option, enter the options one per line.
      <strong>If there is an upcharge associated with the option, add it inline separated by a plus (no $).</strong>
    </p>

    <form method="post">
      @method('put')
      @csrf
      @foreach(\App\Models\Meal::$customOptions as $option)
        @include('admin.custom-meal-options._meal-option-form', [compact('option')])
      @endforeach
      <div class="row">
        <div class="col-md-9 offset-md-3">
          <button class="btn btn-primary" type="submit">Save</button>
        </div>
      </div>
    </form>
  @endcomponent
@endsection

