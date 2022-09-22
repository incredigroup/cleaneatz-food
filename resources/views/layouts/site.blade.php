<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="utf-8">
  <meta name="viewport"
        content="width=device-width, initial-scale=1">

  <!-- CSRF Token -->
  <meta name="csrf-token"
        content="{{ csrf_token() }}">

  <title>{{ config('app.name', 'Clean Eatz') }}</title>

  <!-- Scripts -->
  <script src="{{ asset('js/site.js') }}"
          defer></script>
  <script src="{{ asset('js/jquery-1.11.1.min.js') }}"></script>
  <script src="{{ App\Helpers\PaymentJs::libraryUrl() }}"></script>

  <!-- Fonts -->
  <link rel="dns-prefetch"
        href="//fonts.gstatic.com">
  <link href="https://fonts.googleapis.com/css?family=Nunito"
        rel="stylesheet">

  <!-- Styles -->
  <link href="{{ asset('css/site.css') }}"
        rel="stylesheet">
        <script src="https://secure.qorcommerce.io/payFrame/js/QorPaymentForm.js"></script>

</head>

<body>
  @include('layouts.site._breadcrumbs')

  @include('layouts.site._nav')

  <main>
    @yield('content')
  </main>

  @include('layouts.site._footer')
</body>
@stack('scripts')

</html>
