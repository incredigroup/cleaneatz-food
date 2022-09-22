@php
  $inputName = $name ?? \Str::slug($label, '_')
@endphp
<label for="{{ $inputName }}" class="mr-3">{{ $label }}</label>
<div class="form-check-inline">
  @foreach($values as $k=>$v)
  <label class="form-check-label mr-4">
    <input
      type="radio"
      class="form-check-input @error($inputName) is-invalid @enderror"
      id="{{ $inputName }}_{{ $k }}"
      name="{{ $inputName }}"
      value="{{ $k }}"
      {{ old($inputName, $model[$inputName]) === $k ? 'checked' : '' }}
    > {{ $v }}
  </label>
  @endforeach
</div>
