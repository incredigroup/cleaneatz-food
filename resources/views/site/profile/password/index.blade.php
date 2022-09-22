@extends('layouts.site')

@section('content')
  @component('site.profile.profile-page')
    <form method="POST" action="{{ route('profile.password') }}" class="profile-form">
      @csrf

      <div class="form-group {{ $errors->has('password') ? ' has-error' : '' }}">
        <label for="password">New Password</label>
        <input
          id="password"
          type="password"
          class="form-control"
          name="password"
          required
          autofocus>
        @error('password')
        <span class="text-danger" role="alert">{{ $message }}</span>
        @enderror
      </div>

      <div class="form-group {{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
        <label for="password_confirmation">Confirm Password</label>
        <input
          id="password_confirmation"
          type="password"
          class="form-control"
          name="password_confirmation"
          required>
        @error('password_confirmation')
        <span class="text-danger" role="alert">{{ $message }}</span>
        @enderror
      </div>

      <input class="btn btn-primary" type="submit" value="Update My Password">

    </form>
  @endcomponent
@endsection
