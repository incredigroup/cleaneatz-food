@php
  $inputName = $name ?? \Str::slug($label, '_')
@endphp

<div class="form-group">
  <label for="{{ $inputName }}">{{ $label }}</label>
    @if (isset($before) || isset($after))<div class="input-group">@endif

    @if (isset($before))
      <div class="input-group-prepend">
        <span class="input-group-text">{{ $before }}</span>
      </div>
    @endif

    <input
      type="{{ $type ?? 'text' }}"
      class="form-control @error($inputName) is-invalid @enderror"
      id="{{ $inputName }}"
      name="{{ $inputName }}"
      placeholder="{{ $placeholder ?? $label }}"
      value="{{ old($inputName, $model[$inputName]) }}"
      {!! isset($step) ? 'step="' . $step . '"' : '' !!}
      {{ isset($required) ? 'required' : ''}}
    >
    @if (isset($after))
      <div class="input-group-append">
        <span class="input-group-text">{{ $after }}</span>
      </div>
    @endif

    @include('app.html.invalid-message', compact('inputName'))

    @if (isset($before) || isset($after))</div>@endif
    @if (isset($hint))<small id="emailHelp" class="form-text text-muted">{{ $hint }}</small>@endif
</div>
