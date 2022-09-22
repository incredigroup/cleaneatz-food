@extends('layouts.app')

@section('content')
  @component('admin.components.basic-page', ['title' => 'Create A Satellite Location'])
    @component('admin.components.create-form', ['action' => route('admin.satellite-locations.store')])
      @include('admin.satellite-locations._form', ['model' => $satelliteLocation])
    @endcomponent
  @endcomponent
@endsection
