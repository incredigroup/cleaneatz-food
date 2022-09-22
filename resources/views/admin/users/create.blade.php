@extends('layouts.app')

@section('content')
  @component('admin.components.basic-page', ['title' => 'Create An Admin User'])
    @component('admin.components.create-form', ['action' => route('admin.users.store')])
      @include('admin.users._form', ['model' => $user])
      @include('admin.users._password_form', ['model' => $user])
    @endcomponent
  @endcomponent
@endsection
