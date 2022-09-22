<div class="form-row">
  <div class="col-md-12 mb-3">
    @input(['label' => 'Name', 'required' => true, 'model' => $meal])
    @checkbox(['name' => 'is_breakfast', 'label' => 'Is Breakfast', 'model' => $meal])
  </div>

</div>

<div class="col-md-12 mb-3">

</div>

<div class="form-row">
  <div class="col-md-12 mb-3">
    @textarea(['label' => 'Description', 'required' => true, 'model' => $meal])
  </div>
</div>

<div class="form-row">
  <div class="col-md-4 mb-3">
    @input(['label' => 'Calories', 'type' => 'number', 'required' => true, 'model' => $meal])
  </div>
</div>

<div class="form-row">
  <div class="col-md-4 mb-3">
    @input(['label' => 'Fat', 'type' => 'number', 'required' => true, 'after' => 'grams', 'model' => $meal])
  </div>

  <div class="col-md-4 mb-3">
    @input(['label' => 'Carbs', 'type' => 'number', 'after' => 'grams', 'model' => $meal])
  </div>

  <div class="col-md-4 mb-3">
    @input(['label' => 'Protein', 'type' => 'number', 'after' => 'grams', 'model' => $meal])
  </div>
</div>

@if (!$meal->image_url)
  <div class="row">
    <div class="col-sm-6">
      <label for="customFile">Meal Image</label>
      <div class="custom-file">
        <input type="file" class="custom-file-input @error('image') is-invalid @enderror" name="image" id="customFile">
        <label class="custom-file-label" for="customFile">Choose file</label>
        <div class="invalid-feedback">
          @error('image')
          {{ $message }}
          @enderror
        </div>
      </div>
    </div>
  </div>
@endif

@push('scripts')
  <script>
    bsCustomFileInput.init();
  </script>
@endpush
