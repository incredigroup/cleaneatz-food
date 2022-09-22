@extends('layouts.app')

@section('content')
  @component('admin.components.basic-page', ['title' => 'Edit A Meal'])
    @component('admin.components.edit-form', ['file' => true, 'action' => route('admin.meals.update', $meal)])
      @include('admin.meals._form', ['model' => $meal])
    @endcomponent

    @if ($meal->image_url)
      <div class="row" style="margin-top:30px">
        <div class="col-sm-6">
          <form method="POST" action="{{ route('admin.meals.delete-image', $meal) }}">
            @method('delete')
            @csrf
            <div style="margin-bottom:20px">
              <label>Current Image</label><br>
              <img class="admin-image-upload" src="{{ asset('img/meals/' . $meal->image_url) }}">
            </div>
            <button type="submit" class="btn btn-danger">Delete Image</button>
          </form>
        </div>
      </div>
    @endif
  @endcomponent
@endsection
