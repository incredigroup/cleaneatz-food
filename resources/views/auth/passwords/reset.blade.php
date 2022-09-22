@extends('layouts.site')

@section('content')
  @component('components.page-banner', ['bottom' => 'Forgot Your Password?'])
  @endcomponent

  @component('components.green-banner')
    <div class="signin-form-wrap">
      <h1 class="text-center mb-3">Reset Password</h1>

      <form method="POST"
            action="{{ route('password.update') }}">
        @csrf

        <input type="hidden"
               name="token"
               value="{{ $token }}">

        <div class="mb-3">
          <div class="form-floating">
            <input id="email"
                   class="form-control @error('email') is-invalid @enderror"
                   name="email"
                   type="email"
                   value="{{ old('email') }}"
                   required
                   placeholder="Email Address"
                   autofocus>
            <label for="email">Email Address</label>
            @error('email')
              <span class="invalid-feedback"
                    role="alert">
                <strong>{{ $message }}</strong>
              </span>
            @enderror
          </div>
        </div>

        <div class="mb-3">
          <div class="form-floating">
            <input id="password"
                   type="password"
                   class="form-control @error('password') is-invalid @enderror"
                   name="password"
                   required
                   placeholder="Password"
                   autocomplete="new-password">
            <label for="password">Password</label>
            @error('password')
              <span class="invalid-feedback"
                    role="alert">
                <strong>{{ $message }}</strong>
              </span>
            @enderror
          </div>
        </div>

        <div class="mb-3">
          <div class="form-floating">
            <input id="password_confirmation"
                   type="password"
                   class="form-control"
                   name="password_confirmation"
                   required
                   placeholder="Confirm Password"
                   autocomplete="new-password">
            <label for="password_confirmation">Confirm Password</label>
          </div>
        </div>

        <div class="text-center">
          <button type="submit"
                  class="btn btn-legacy">
            Reset Password
          </button>

        </div>
      </form>

    </div>
  @endcomponent

  @component('components.img-banner')
    <h2 class="mt-5">
      Don't Have an Account?
    </h2>
    <a class="btn btn-legacy"
       href="route('register')"
       title="Sign Up for Clean Eatz">
      Sign Up Here
    </a>
  @endcomponent
@endsection
