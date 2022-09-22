@extends('layouts.site')

@section('content')
  <div class="menu {{ $category->slug }}">
    <div class="gray-header-panel">
      <h1>{{ $category->title }}</h1>
      <p class="d-none d-md-block">
        Try our delicious meals made using fresh, seasonal ingredients with gluten-free, keto and
        high-protein options. Dine-in or order online for pickup.
      </p>
    </div>

    <div class="menu-hero">
      <p class="d-block d-md-none fw-bold p-3 text-white">
        Try our delicious meals made using fresh, seasonal ingredients with gluten-free, keto and
        high-protein options. Dine-in or order online for pickup.
      </p>
    </div>

    <div class="container menu-items py-4 py-md-5">
      @foreach ($menuItems->chunk(3) as $menuItemsRow)
        <div class="row mb-md-4 mb-1">
          @foreach ($menuItemsRow as $menuItem)
            <div class="col-12 col-md-4">
              <div class="d-flex flex-md-column align-items-center flex-row">
                <img src="https://via.placeholder.com/150"
                     alt="{{ $menuItem->title }}"
                     class="m-3" />
                <div>
                  <h2>{{ $menuItem->title }}</h2>
                  @if (!empty($menuItem->calories))
                    <div class="calories">(Cals {{ $menuItem->calories }})</div>
                    {!! $menuItem->content !!}
                  @endif
                </div>
              </div>
            </div>
          @endforeach
        </div>
      @endforeach

      @include('.site.cafe-menu._menu-footer')

    </div>
  </div>
@endsection
