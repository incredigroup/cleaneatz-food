@extends('layouts.site')

@section('content')
  @component('components.page-banner', ['top' => 'Login', 'bottom' => 'To Order'])
  @endcomponent

  @component('components.green-banner')
    <div class="signin-form-wrap">
      <h1 class="text-center mb-3">Sign In</h1>

      <form method="POST"
            action="{{ route('login') }}">
        @csrf

        <div class="mb-3">
          <div class="form-floating">
            <input id="username"
                   class="form-control @error('username') is-invalid @enderror"
                   name="username"
                   value="{{ old('username') }}"
                   required
                   placeholder="Email Address"
                   autofocus>
            <label for="username">Email Address</label>
            @error('username')
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
                   autocomplete="current-password">
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
          <div class="form-check">
            <input class="form-check-input"
                   type="checkbox"
                   name="remember"
                   id="remember"
                   {{ old('remember') ? 'checked' : '' }}>

            <label class="form-check-label"
                   for="remember">
              {{ __('Remember Me') }}
            </label>
          </div>
        </div>

        <div class="text-center">
          <button type="submit"
                  class="btn btn-legacy">
            Sign In
          </button>

          @if (Route::has('password.request'))
            <a class="d-block mt-4"
               href="{{ route('password.request') }}">
              {{ __('Forgot Your Password?') }}
            </a>
          @endif
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
