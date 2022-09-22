@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row">
    <div class="col-md-12">
      <h1>Admin Options</h1>
      <hr>
    </div>
  </div>

  @include('admin.home._meals-and-menus')
  @include('admin.home._locations-and-orders')
  @include('admin.home._admin-users')
</div>
@endsection
