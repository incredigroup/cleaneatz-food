@extends('layouts.site')

@section('content')
  <div class="menu">
    <div class="gray-header-panel">
      <h1 class="d-none d-md-block">Clean Eatz Cafe <span class="orange-bullet">&#8226;</span> Healthy
        Diet Menu</h1>
      <div class="d-none d-md-block">
        Try our delicious meals made using fresh, seasonal ingredients with gluten-free, keto and
        high-protein options. Dine-in or order online for pickup.
      </div>
      <div class="d-block d-md-none">
        <h1>Clean Eatz Cafe</h1>
        <h2>Healthy Diet Menu</h2>
      </div>
    </div>

    <div class="menu-hero">
      <div class="d-block d-md-none p-3 text-white">
        Try our delicious meals made using fresh, seasonal ingredients with gluten-free, keto and
        high-protein options. Dine-in or order online for pickup.
      </div>
    </div>

    <div class="container menu-categories py-md-5 py-4">
      @foreach ($categories->chunk(5) as $categoriesRow)
        <div class="row mb-md-4 mb-1">
          @foreach ($categoriesRow as $category)
            <div class="col-12 col-md">
              <a href="{{ route('cafe-menu', ['categorySlug' => Str::slug($category)]) }}">
                <div class="d-flex flex-md-column align-items-center flex-row">
                  <img src="{{ asset('img/menu/categories/' . Str::slug($category) . '.png') }}"
                       alt="{{ $category }}"
                       class="m-3">
                  <h2 class="text-start text-md-center">{{ $category }}</h2>
                </div>
              </a>
            </div>
          @endforeach
        </div>
      @endforeach

      @include('.site.cafe-menu._menu-footer')

    </div>

  </div>
@endsection
