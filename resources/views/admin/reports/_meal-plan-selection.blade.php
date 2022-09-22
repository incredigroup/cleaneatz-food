<span
  class="dropdown">
  <button
    class="btn btn-primary dropdown-toggle"
    type="button"
    id="dropdownMenuButton"
    data-toggle="dropdown"
    aria-haspopup="true"
    aria-expanded="false">
    Menu {{ $mealPlan->name }}
  </button>
  <div
    class="dropdown-menu"
    aria-labelledby="dropdownMenuButton"
    style="height:auto;max-height: 400px;overflow-x: hidden;"
  >
    @foreach($mealPlans as $selectMealPlan)
      <a class="dropdown-item"
         href="{{ route($route, ['mealPlanId' => $selectMealPlan->id]) }}">
        Menu {{ $selectMealPlan->name }}
      </a>
    @endforeach
  </div>
</span>
