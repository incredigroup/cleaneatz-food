@extends('layouts.site')

@section('content')
  @component('components.page-banner', ['top' => 'Sign Up', 'bottom' => 'And Order'])
  @endcomponent

  @component('components.green-banner')
    <div class="signin-form-wrap">
      <h1 class="text-center mb-3">Register</h1>

      <form method="POST"
            action="{{ route('register') }}">
        @csrf

        <div class="mb-3">
          <div class="form-floating">
            <input id="first_name"
                   class="form-control @error('first_name') is-invalid @enderror"
                   name="first_name"
                   value="{{ old('first_name') }}"
                   required
                   placeholder="First Name"
                   autofocus>
            <label for="first_name">First Name</label>
            @error('first_name')
              <span class="invalid-feedback"
                    role="alert">
                <strong>{{ $message }}</strong>
              </span>
            @enderror
          </div>
        </div>

        <div class="mb-3">
          <div class="form-floating">
            <input id="last_name"
                   class="form-control @error('last_name') is-invalid @enderror"
                   name="last_name"
                   value="{{ old('last_name') }}"
                   required
                   placeholder="Last Name">
            <label for="last_name">Last Name</label>
            @error('last_name')
              <span class="invalid-feedback"
                    role="alert">
                <strong>{{ $message }}</strong>
              </span>
            @enderror
          </div>
        </div>

        <div class="mb-3">
          <div class="form-floating">
            <input id="email"
                   type="email"
                   class="form-control @error('email') is-invalid @enderror"
                   name="email"
                   value="{{ old('email') }}"
                   required
                   autocomplete="email"
                   placeholder="Email Address">
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

        <div class="row mb-0">
          <div class="col-md-6 offset-md-4">
            <button type="submit"
                    class="btn btn-legacy">
              {{ __('Register') }}
            </button>
          </div>
        </div>
      </form>

    </div>
  @endcomponent

  @component('components.img-banner')
    <h2 class="mt-5">
      Already Have an Account?
    </h2>
    <a class="btn btn-legacy"
       href="{{ route('login') }}"
       title="Log In To for Clean Eatz">
      Login Here
    </a>
  @endcomponent
@endsection
