@extends('layouts.site')

@section('content')
  <div class="about">
    <div class="row ce-panel about-hero">
      <div class="col-12 col-md-6">
        <h1 class="color-ce-orange">Who We Are {{Auth::check()?Auth::user()->email:""}}</h1>
        <p>
          We are heart-driven to Change Live through clean, balanced food, thought-provoking
          education and motivational support that inspires results. We know personal wellness is
          not a one-size-fits-all, so we craft nutritional products and fitness opportunities that
          meet the needs of every type of lifestyle.
        </p>
      </div>
      <div class="col-12 col-md-6 order-first order-md-last">
      </div>
    </div>
    <div class="row ce-panel gray-panel">
      <div class="col-12 col-md-6">
        <h1>We Lead<br>By Example</h1>
        <p>
          Actions speak louder than words, and our individual follow-through is the greatest value we
          bring to the mission. We offer our strengths to help drive the bigger goal, treat everyone
          with dignity, and never stop learning from those around us.
        </p>
      </div>
      <div class="col-12 col-md-6 col-image col-image-curve-left">
        <img src="/img/about/lead-by-example.jpg">
        </p>
      </div>
    </div>
    <div class="row ce-panel orange-panel">
      <div class="col-12 col-md-6  col-image col-image-curve-right">
        <img src="/img/about/build-confidence.jpg">
        </p>
      </div>
      <div class="col-12 col-md-6 order-first order-md-last">
        <h1>We Build<br>Confidence</h1>
        <p>
          Clean Eatz is commited on guiding all people to better lifestyle choices, providing access
          to innovative nutritional options, engaging education tools, and inspired motivational
          support.
        </p>
      </div>
    </div>
    <div class="row ce-panel">
      <div class="col-12 col-md-6">
        <h1 class="color-ce-orange">We Change Livez</h1>
        <p>
          This is our rally point – our unified focus. Success will come if we put every ounce of
          energy into people over earnings. Helping others on their healthy lifestyle journey defines
          our existence…everything else is just the result of that commitment.
        </p>
      </div>
      <div class="col-12 col-md-6  col-image">
        <img src="/img/about/we-change-livez.jpg">
        </p>
      </div>
    </div>
  </div>
@endsection
