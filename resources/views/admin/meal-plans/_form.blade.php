<div class="form-row">
  <div class="col-md-12 mb-3">
    <select id="meal-count" class="form-control">
      @for ($i = 1; $i <= 12; $i++)
        <option {{ $i == $numberOfMeals ? 'selected' : '' }} value="{{ $i }}">
          {{ $i }} Meal Menu
        </option>
      @endfor
    </select>
  </div>
</div>

<div class="form-row">
  <div class="col-md-12 mb-3">
    @input(['label' => 'Name', 'required' => true, 'model' => $mealPlan])
  </div>
</div>

<div class="form-row">
  <div class="col-md-6">
    @datepicker(['name' => 'available_on', 'includeTime' => true, 'required' => true, 'label' => 'Start Date/Time', 'model' => $mealPlan])
  </div>
  <div class="col-md-6">
    @datepicker(['name' => 'expires_on', 'includeTime' => true, 'required' => true, 'label' => 'End Date/Time', 'model' => $mealPlan])
  </div>
</div>
<div class="form-row">
  @for ($i = 0; $i < $numberOfMeals; $i++)
    <div class="col-md-4" style="margin-bottom:15px;margin-top:25px">
      <div class="input-group">
        <select class="form-control" name="meal_id[]">
          <option value="">Meal {{ $i + 1 }}...</option>
          @foreach ($meals as $id => $name)
            @php $mealId = isset($mealPlan->items[$i]) ? $mealPlan->items[$i]->id : null @endphp
            <option value="{{ $id }}"
              {{ old('meal_id[$i]', $mealId) == $id ? 'selected' : '' }}
            >
              {{ $name }}
            </option>
          @endforeach
        </select>
      </div>
    </div>
  @endfor
</div>

@push('scripts')
  <script>
    $('#meal-count').change(function () {
      const url = new URL(window.location.href);
      url.searchParams.set('meal-count', this.value);
      window.location.href = url.href;
    })
  </script>
@endpush
