@php
  $inputName = $name ?? \Str::slug($label, '_')
@endphp
<div class="form-group">
  @isset($label)
    <label for="{{ $inputName }}">{{ $label }}</label>
  @endisset

  <select
    class="form-control  @error($inputName) is-invalid @enderror"
    id="{{ $inputName }}"
    name="{{ $inputName }}"
  >
    <option value="">
      @isset($emptyLabel)
        {{ $emptyLabel }}
      @else
        Select a {{ $label ?? '' }}...
      @endisset
    </option>
    @foreach ($options as $k=>$v)
      <option
        value="{{ $k }}"
        {{ old($inputName, $model[$inputName]) == $k ? 'selected' : '' }}>
        {{ $v }}
      </option>
    @endforeach
  </select>
  @if (isset($hint))<small id="emailHelp" class="form-text text-muted">{{ $hint }}</small>@endif
  @include('app.html.invalid-message', compact('inputName'))
</div>
