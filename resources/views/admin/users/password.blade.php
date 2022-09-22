@extends('layouts.app')

@section('content')
  @component('admin.components.basic-page', ['title' => 'Edit an Admin User'])
    <ul class="nav nav-tabs mb-4">
      <li class="nav-item">
        <a class="nav-link"
           href="{{ route('admin.users.edit', ['user' => $user]) }}">
          Edit User Details
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link active">Change Password</a>
      </li>
    </ul>
    @component('admin.components.edit-form', ['action' => route('admin.users.password.update', $user)])
      @include('admin.users._password_form', ['model' => $user])
    @endcomponent
  @endcomponent
@endsection
