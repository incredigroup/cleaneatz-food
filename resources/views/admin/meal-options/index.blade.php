@extends('layouts.app')

@section('content')
  @component('admin.components.basic-page', ['title' => 'Meal Options'])
    <form method="post">
      @method('put')
      @csrf

      <div class="row mb-4">
        <div class="col-md-3">
          Upcoming Meals Message
        </div>
        <div class="col-md-9">
          <textarea
              class="form-control"
              rows="5"
              name="upcoming_meals_msg">{{ old('upcoming_meals_msg', $options['upcoming_meals_msg']) }}</textarea>
          <small class="form-text text-muted">
            This message is shown between meals.
          </small>
        </div>
      </div>

      <div class="row mb-4">
        <div class="col-md-3">
          New Meals Available Message
        </div>
        <div class="col-md-9">
          <textarea
            class="form-control"
            rows="5"
            name="meals_available_msg">{{ old('meals_available_msg', $options['meals_available_msg']) }}</textarea>
          <small class="form-text text-muted">
            This message is shown under the NEW MEALS. EVERY WEEK. headline when meals are available.
          </small>
        </div>
      </div>

      <div class="row mb-4">
        <div class="col-md-3">
          Meal Pause Message
        </div>
        <div class="col-md-9">
          <textarea
            class="form-control"
            rows="5"
            name="meal_plan_pause_msg">{{ old('meal_plan_pause_msg', $options['meal_plan_pause_msg']) }}</textarea>
          <small class="form-text text-muted">
            This message is shown when meal plan sales are temporarily paused. It should be removed when the pause
            is over.
          </small>
        </div>
      </div>

      <div class="row">
        <div class="col-md-9 offset-md-3">
          <button class="btn btn-primary" type="submit">Save</button>
        </div>
      </div>
    </form>
  @endcomponent
@endsection

