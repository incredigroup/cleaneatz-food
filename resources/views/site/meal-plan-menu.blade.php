@extends('layouts.site')

@section('content')
  <div id="app"
       class="meal-plans">
    @if ($mealPlan)
      @include('site.meal-plan-menu._header-section')
      @include('site.meal-plan-menu._meal-plan-items', [
          'mealPlan' => $mealPlan,
          'addOnItems' => $addOnItems,
      ])
    @else
      @include(
          'site.meal-plan-menu._no-meal-plan',
          compact('nextMealPlan')
      )
    @endif

    @include('site.meal-plan-menu._pricing-block')
  </div>

  @include('site.meal-plan-menu._join-meal-plan-form')
  @include('site.meal-plan-menu._heating-instructions')
@endsection

@push('scripts')
  <script>
    fbq('track', 'ViewContent');
  </script>
  <script src="{{ asset('/js/newsletter-signup.js') }}"></script>
  <script src="https://www.google.com/recaptcha/api.js"></script>
@endpush