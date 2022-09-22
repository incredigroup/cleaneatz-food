<nav class="navbar navbar-expand-md navbar-dark navbar-primary shadow-sm">
  <div class="container">
    <a class="navbar-brand"
       href="{{ url('/') }}">
      <img src="{{ asset('img/logos/logo-round-white.png') }}"
           alt="Clean Eatz">
    </a>
    <button class="navbar-toggler"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent"
            aria-expanded="false"
            aria-label="{{ __('Toggle navigation') }}">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse"
         id="navbarSupportedContent">
      <!-- Left Side Of Navbar -->
      <ul class="navbar-nav me-auto">

      </ul>

      <!-- Right Side Of Navbar -->
      <ul class="navbar-nav ms-auto">
        <!-- Authentication Links -->
        @foreach (Statamic::tag('nav:main_nav') as $page)
          <li class="nav-item">
            <a class="nav-link"
               href="{{ $page['url'] }}">{{ $page['title'] }}</a>
          </li>
        @endforeach
        @guest
          @if (Route::has('login'))
            <li class="nav-item">
              <a class="nav-link"
                 href="{{ route('login') }}">{{ __('Login') }}</a>
            </li>
          @endif

          @if (Route::has('register'))
            <li class="nav-item">
              <a class="nav-link"
                 href="{{ route('register') }}">Sign Up</a>
            </li>
          @endif
        @else
          <li class="nav-item dropdown">
            <a id="navbarDropdown"
               class="nav-link dropdown-toggle"
               href="#"
               role="button"
               data-bs-toggle="dropdown"
               aria-haspopup="true"
               aria-expanded="false"
               v-pre>
              {{ Auth::user()->first_name }}
            </a>

            <div class="dropdown-menu dropdown-menu-end"
                 aria-labelledby="navbarDropdown">
              <a class="dropdown-item"
                 href="{{ route('logout') }}"
                 onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                {{ __('Logout') }}
              </a>

              <form id="logout-form"
                    action="{{ route('logout') }}"
                    method="POST"
                    class="d-none">
                @csrf
              </form>
            </div>
          </li>
        @endguest
        <a href="{{ route('site.order') }}"
           class="btn btn-round btn-white ms-md-3">Order Online</a>
      </ul>
    </div>
  </div>
</nav>
<style>
  iframe#pay-formx {
    height: 31% !important;
}
iframe#pay-form {
	 height: 31% !important;
}
button#payment-submit-button {
    display: none !important;
}
form#order-form {
    margin-bottom: 20% !important;
}
</style>