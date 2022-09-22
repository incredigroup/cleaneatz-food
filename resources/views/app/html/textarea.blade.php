@php
  $inputName = $name ?? \Str::slug($label, '_')
@endphp
<label for="{{ $inputName }}">{{ $label }}</label>
<textarea
  class="form-control @error($inputName) is-invalid @enderror"
  id="{{ $inputName }}"
  name="{{ $inputName }}"
  rows="{{ $rows ?? 3 }}"
  placeholder="{{ $label }}"
  {{ isset($required) ? 'required' : ''}}
>
{{ old($inputName, $model[$inputName]) }}
</textarea>
@include('app.html.invalid-message', compact('inputName'))

