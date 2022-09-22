<div class="form-row">
  <div class="col-md-12 mb-3">
    @input(['label' => 'Name', 'required' => true, 'model' => $meal])
  </div>
</div>

<div class="form-row">
  <div class="col-md-12 mb-3">
    @textarea(['label' => 'Description', 'name' => 'group_desc', 'required' => true, 'model' => $meal])
  </div>
</div>

<div class="form-row">
  <div class="col-md-4 mb-3">
    @input(['label' => 'Price', 'name' => 'price_override', 'type' => 'text', 'required' => true, 'model' => $meal])
  </div>
</div>

@if (!$meal->image_url)
  <div class="form-row">
    <div class="col-sm-6 mb-3">
      <label for="customFile">Group Image</label>
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

<hr>
<h4>Variants <small class='text-muted'>(Optional)</small></h4>
<div class="form-row mb-3">
  <div class="col-md-4">
    <div class="form-group">
      <label for="variant1">Variant #1</label>
      <input type="text" name="{{ isset($variants[0]) ? 'variant[' . $variants[0]->id . ']': 'newVariant[]' }}" value="{{ isset($variants[0]) ? $variants[0]->description : '' }}" class="form-control" id="variant1" placeholder="Variant Name">
      <small class="form-text text-muted">(e.g. Cookies N Cream)</small>
    </div>
  </div>
  <div class="col-md-4">
    <div class="form-group">
      <label for="variant2">Variant #2</label>
      <input type="text" name="{{ isset($variants[1]) ? 'variant[' . $variants[1]->id . ']': 'newVariant[]' }}" value="{{ isset($variants[1]) ? $variants[1]->description : ''}}" class="form-control" id="variant2" placeholder="Variant Name">
    </div>
  </div>
  <div class="col-md-4">
    <div class="form-group">
      <label for="variant3">Variant #3</label>
      <input type="text" name="{{ isset($variants[2]) ? 'variant[' . $variants[2]->id . ']': 'newVariant[]' }}" value="{{ isset($variants[2]) ? $variants[2]->description : '' }}" class="form-control" id="variant3" placeholder="Variant Name">
    </div>
  </div>
  <div class="col-md-4">
    <div class="form-group">
      <label for="variant3">Variant #4</label>
      <input type="text" name="{{ isset($variants[3]) ? 'variant[' . $variants[3]->id . ']': 'newVariant[]' }}" value="{{ isset($variants[3]) ? $variants[3]->description : '' }}" class="form-control" id="variant3" placeholder="Variant Name">
    </div>
  </div>
  <div class="col-md-4">
    <div class="form-group">
      <label for="variant3">Variant #5</label>
      <input type="text" name="{{ isset($variants[4]) ? 'variant[' . $variants[4]->id . ']': 'newVariant[]' }}" value="{{ isset($variants[4]) ? $variants[4]->description : '' }}" class="form-control" id="variant3" placeholder="Variant Name">
    </div>
  </div>
  <div class="col-md-4">
    <div class="form-group">
      <label for="variant3">Variant #6</label>
      <input type="text" name="{{ isset($variants[5]) ? 'variant[' . $variants[5]->id . ']': 'newVariant[]' }}" value="{{ isset($variants[5]) ? $variants[5]->description : '' }}" class="form-control" id="variant3" placeholder="Variant Name">
    </div>
  </div>
</div>

@push('scripts')
  <script>
    bsCustomFileInput.init();
  </script>
@endpush
