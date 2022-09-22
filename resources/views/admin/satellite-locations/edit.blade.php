@extends('layouts.app')

@section('content')
  @component('admin.components.basic-page', ['title' => 'Edit A Satellite Location'])
    @component('admin.components.edit-form', ['action' => route('admin.satellite-locations.update', $satelliteLocation)])
      @include('admin.satellite-locations._form', ['model' => $satelliteLocation])
    @endcomponent
  @endcomponent
@endsection
