@extends('layouts.site')

@section('content')
  @component('site.profile.profile-page')
    <form method="POST" action="{{ route('profile.index') }}" class="profile-form">
      @csrf

      <div class="form-group {{ $errors->has('first_name') ? ' has-error' : '' }}">
        <label for="first_name">First Name</label>
        <input
          id="first_name"
          type="text"
          class="form-control"
          name="first_name"
          value="{{ old('first_name', $user->first_name) }}"
          required
          autofocus>
        @error('first_name')
        <span class="text-danger" role="alert">{{ $message }}</span>
        @enderror
      </div>

      <div class="form-group {{ $errors->has('last_name') ? ' has-error' : '' }}">
        <label for="last_name">Last Name</label>
        <input
          id="last_name"
          type="text"
          class="form-control"
          name="last_name"
          value="{{ old('last_name', $user->last_name) }}"
          required>
        @error('last_name')
        <span class="text-danger" role="alert">{{ $message }}</span>
        @enderror
      </div>

      <div class="form-group {{ $errors->has('email') ? ' has-error' : '' }}">
        <label for="email">Email</label>
        <input
          id="email"
          type="email"
          class="form-control"
          name="email"
          value="{{ old('email', $user->email) }}"
          required>
        @error('email')
        <span class="text-danger" role="alert">{{ $message }}</span>
        @enderror
      </div>

      <input class="btn btn-primary" type="submit" value="Update My Information">

    </form>
  @endcomponent
@endsection
