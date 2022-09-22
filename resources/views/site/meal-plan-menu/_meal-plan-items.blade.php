<div id="meal-plan-form">
  <meal-plan-order-form asset-path="{{ asset('') }}"
                        meal-plan-id="{{ $mealPlan->id }}"
                        meal-plan-items="{{ $mealPlan->items->toJson() }}"
                        add-on-items="{{ $addOnItems }}"
                        form-data="{{ json_encode($formData) }}">
  </meal-plan-order-form>
</div>
