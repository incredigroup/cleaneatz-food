<section id="admin-header-buffer">
  <div>
    &nbsp;
  </div>
</section>

<div class="container">
  <div class="row">
    <div class="col-md-12">

      <h1>My Account</h1>

      @if (Auth::user()->isCustomer())
        <ul class="profile-nav nav nav-pills">
          <li class="{{ \Request::route()->getName() === 'profile.index' ? 'active' : '' }}">
            <a href="{{ route('profile.index') }}">General</a>
          </li>
          <li class="{{ \Request::route()->getName() === 'profile.billing' ? 'active' : '' }}">
            <a href="{{ route('profile.billing') }}">Billing Info</a>
          </li>
          <li class="{{ \Request::route()->getName() === 'profile.password' ? 'active' : '' }}">
            <a href="{{ route('profile.password') }}">Change Password</a>
          </li>
          <li class="{{ \Request::route()->getName() === 'profile.orders' ? 'active' : '' }}">
            <a href="{{ route('profile.orders') }}">Order History</a>
          </li>
        </ul>
      @endif

      @if (session('status'))
        <div class="alert alert-success"
             role="alert">
          {{ session('status') }}
        </div>
      @endif

      {{ $slot }}

    </div>
  </div>
</div>
