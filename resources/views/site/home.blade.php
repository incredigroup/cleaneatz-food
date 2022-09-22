@extends('layouts.site')

@section('content')
  <div class="home">
    <img src="{{ asset('/img/home/hero.jpg') }}"
         class="w-100"
         alt="Clean Eatz Hero Image">

    <div class="white-panel welcome-panel">
      <div class="container">
        <h2>Welcome to Clean Eatz</h2>

        <p>
          Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut
          labore et dolore magna.Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do
          eiusmod tempor incididunt ut labore et dolore magna.Lorem ipsum dolor sit amet, consectetur
          adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna
        </p>

        <div class="row icons-medium mt-5">
          <div class="col-12 col-md">
            <img src="{{ asset('img/home/icon-cafe.png') }}"
                 alt="Cafe">
            <h2>Cafe</h2>
            <p>
              Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor
              incididunt ut labore et dolore magna aliqua.
            </p>
          </div>
          <div class="col-12 col-md">
            <img src="{{ asset('img/home/icon-snackz.png') }}"
                 alt="Snackz">
            <h2>Snackz</h2>
            <p>
              Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor
              incididunt ut labore et dolore magna aliqua.
            </p>
          </div>
          <div class="col-12 col-md">
            <img src="{{ asset('img/home/icon-smoothies.png') }}"
                 alt="Smoothies">
            <h2>Smoothies</h2>
            <p>
              Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor
              incididunt ut labore et dolore magna aliqua.
            </p>
          </div>

          <div class="col-12 col-md">
            <img src="{{ asset('img/home/icon-meal-plans.png') }}"
                 alt="Meal Plans">
            <h2>Meal Plans</h2>
            <p>
              Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor
              incididunt ut labore et dolore magna aliqua.
            </p>
          </div>

          <div class="col-12 col-md">
            <img src="{{ asset('img/home/icon-catering.png') }}"
                 alt="Catering">
            <h2>Catering</h2>
            <p>
              Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor
              incididunt ut labore et dolore magna aliqua.
            </p>
          </div>
        </div>

      </div>
    </div>
  </div>
@endsection
