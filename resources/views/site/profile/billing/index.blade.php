@extends('layouts.site')

@section('content')
  @component('site.profile.profile-page')
    <form method="POST" action="{{ route('profile.billing') }}" class="profile-form">
      @csrf

      <div class="form-group {{ $errors->has('billing_first_name') ? ' has-error' : '' }}">
        <label for="first_name">Billing First Name</label>
        <input
          id="first_name"
          type="text"
          class="form-control"
          name="billing_first_name"
          value="{{ old('billing_first_name', $user->billing_first_name) }}"
          autofocus>
        @error('billing_first_name')
        <span class="text-danger" role="alert">{{ $message }}</span>
        @enderror
      </div>

      <div class="form-group {{ $errors->has('billing_last_name') ? ' has-error' : '' }}">
        <label for="last_name">Billing Last Name</label>
        <input
          id="last_name"
          type="text"
          class="form-control"
          name="billing_last_name"
          value="{{ old('billing_last_name', $user->billing_last_name) }}">
        @error('billing_last_name')
        <span class="text-danger" role="alert">{{ $message }}</span>
        @enderror
      </div>

      <div class="form-group {{ $errors->has('billing_email') ? ' has-error' : '' }}">
        <label for="email">Billing Email</label>
        <input
          id="email"
          type="email"
          class="form-control"
          name="billing_email"
          value="{{ old('billing_email', $user->billing_email) }}">
        @error('billing_email')
        <span class="text-danger" role="alert">{{ $message }}</span>
        @enderror
      </div>

      <div class="form-group {{ $errors->has('billing_phone') ? ' has-error' : '' }}">
        <label for="phone">Billing Phone</label>
        <input
          id="phone"
          type="text"
          class="form-control"
          name="billing_phone"
          value="{{ old('billing_phone', $user->billing_phone) }}">
        @error('billing_phone')
        <span class="text-danger" role="alert">{{ $message }}</span>
        @enderror
      </div>

      <div class="form-group {{ $errors->has('billing_address') ? ' has-error' : '' }}">
        <label for="address">Address</label>
        <input
          id="address"
          type="text"
          class="form-control"
          name="billing_address"
          value="{{ old('billing_address', $user->billing_address) }}">
        @error('billing_address')
        <span class="text-danger" role="alert">{{ $message }}</span>
        @enderror
      </div>

      <div class="form-group {{ $errors->has('billing_city') ? ' has-error' : '' }}">
        <label for="city">City</label>
        <input
          id="city"
          type="text"
          class="form-control"
          name="billing_city"
          value="{{ old('billing_city', $user->billing_city) }}">
        @error('billing_city')
        <span class="text-danger" role="alert">{{ $message }}</span>
        @enderror
      </div>

      <div class="form-group {{ $errors->has('billing_state') ? ' has-error' : '' }}">
        <label for="state">State</label>

        <select
          id="state"
          class="form-control"
          name="billing_state"
          value="{{ old('billing_state', $user->billing_state) }}">
          <option value="">Pick a state...</option>
          @foreach (\App\Models\User::stateOptions() as $k=>$v)
            <option
              value="{{ $k }}"
              {{ old('billing_state', $user->billing_state) === $k ? 'selected' : '' }}>
              {{ $v }}
            </option>
          @endforeach
        </select>
        @error('billing_state')
        <span class="text-danger" role="alert">{{ $message }}</span>
        @enderror
      </div>

      <div class="form-group {{ $errors->has('billing_zip') ? ' has-error' : '' }}">
        <label for="zip">Zip</label>
        <input
          id="zip"
          type="text"
          class="form-control"
          name="billing_zip"
          value="{{ old('billing_zip', $user->billing_zip) }}">
        @error('billing_zip')
        <span class="text-danger" role="alert">{{ $message }}</span>
        @enderror
      </div>

      <input class="btn btn-primary" type="submit" value="Update My Billing Info">

    </form>
  @endcomponent
@endsection
