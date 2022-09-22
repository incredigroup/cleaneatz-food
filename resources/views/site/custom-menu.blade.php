@extends('layouts.site')

@section('content')

<div id="app" class="PrintFriendly">
  <section class="page-banner banner">
    <div class="container">
      <div class="row">
        <div class="col-md-12 text-center">
          <div class="banner-content">
            <h1 class="top">
              Clean Eatz Custom Meal Plans
            </h1>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section class="white-banner banner">
    <div class="container">
      <div style="color:#fff">
        <my-store-location asset-path="{{ asset('') }}"></my-store-location>
      </div>

      <custom-order-form
        :proteins="{{ json_encode($proteins) }}"
        :protein-portions="{{ json_encode($proteinPortions) }}"
        :carbs="{{ json_encode($carbs) }}"
        :carb-portions="{{ json_encode($carbPortions) }}"
        :vegetables="{{ json_encode($vegetables) }}"
        :vegetables2="{{ json_encode($vegetables_2) }}"
        :vegetables3="{{ json_encode($vegetables_3) }}"
        :sauces="{{ json_encode($sauces) }}"
        :base-price="{{ $basePrice }}">
      </custom-order-form>
    </div>

  </section>
</div>
@endsection
