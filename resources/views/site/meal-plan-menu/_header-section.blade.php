<div class="gray-header-panel">
  <h1 class="d-none d-md-block">
    Meal Plans <span class="orange-bullet">&#8226;</span>
    New Menus Every Week
  </h1>
  <div class="d-block d-md-none">
    <h1>New Menus</h1>
    <h1>Every Week</h1>
  </div>
  Try our delicious meals made using fresh, seasonal ingredients with gluten-free, keto and
  high-protein options. Dine-in or order online for pickup.
</div>

<div class="meal-plans-hero">
  <img src="{{ asset('/img/meal-plans/logo-text-white-ds.png') }}" />

  <h1 class="text-white">
    Meal Plans
  </h1>
</div>

<div class="bg-color-light-gray ">
  <div class="container">
    <my-store-location :meal-plan-stores-only="true"
                       asset-path="{{ asset('') }}">
    </my-store-location>
  </div>
</div>

<div class="container">
  <div class="row mt-4">
    <div class="col-md-12 text-center">
      @error('no_meal_ordered')
        <div class="alert alert-danger"
             role="alert">
          <h3>
            At least one meal must be ordered in order to purchase an Add On Item.
          </h3>
        </div>
      @enderror
      @error('meal_plan_expired')
        <div class="alert alert-danger"
             role="alert">
          <h3>
            The meal plan you ordered is no longer available.<br>
            Please choose from the meal plans below.
          </h3>
        </div>
      @enderror

      {!! option('meals_available_msg') !!}<br>&nbsp;

    </div>
  </div>
</div>
