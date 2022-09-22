@php
  $inputName = $name ?? \Str::slug($label, '_')
@endphp
<div class="form-group">
  <label for="{{ $inputName }}">{{ $label }}</label>
  <input
    type="password"
    class="form-control @error($inputName) is-invalid @enderror"
    id="{{ $inputName }}"
    name="{{ $inputName }}"
    placeholder="{{ $placeholder ?? $label }}"
    {{ isset($required) ? 'required' : ''}}
  >
  @include('app.html.invalid-message', compact('inputName'))
</div>

