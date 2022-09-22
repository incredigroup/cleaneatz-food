@extends('layouts.app')

@section('content')
  @component('admin.components.basic-page', ['title' => 'Edit an Admin User'])
    <ul class="nav nav-tabs mb-4">
      <li class="nav-item">
        <a class="nav-link active">Edit User Details</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="{{ route('admin.users.password.edit', ['user' => $user]) }}">Change Password</a>
      </li>
    </ul>
    @component('admin.components.edit-form', ['action' => route('admin.users.update', $user)])
      @include('admin.users._form', ['model' => $user])
    @endcomponent
  @endcomponent
@endsection
