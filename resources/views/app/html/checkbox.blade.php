@php
  $inputName = $name ?? \Str::slug($label, '_')
@endphp
<div class="form-check">
  <input
    type="checkbox"
    class="form-check-input @error($inputName) is-invalid @enderror"
    id="{{ $inputName }}"
    name="{{ $inputName }}"
    value="1"
    {{ old($inputName, $model[$inputName]) ? 'checked' : '' }}
  >
  <label class="form-check-label" for="{{ $inputName }}">
    {{ $label }}
  </label>

  @include('app.html.invalid-message', compact('inputName'))
</div>
