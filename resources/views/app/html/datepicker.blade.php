@php
  $inputName = $name ?? \Str::slug($label, '_')
@endphp
<label for="{{ $inputName }}">{{ $label }}</label>
<div class="input-group date" id="{{ $inputName }}_picker" data-target-input="nearest">
  <input
    type="text"
    class="form-control datetimepicker-input @error($inputName) is-invalid @enderror"
    id="{{ $inputName }}"
    name="{{ $inputName }}"
    placeholder="{{ $placeholder ?? $label }}"
    @if($includeTime ?? false)
      value="{{ old($inputName, empty($model[$inputName]) ? '' : $model[$inputName]->format('m/d/Y g:i a')) }}"
    @else
      value="{{ old($inputName, empty($model[$inputName]) ? '' : $model[$inputName]->format('m/d/Y')) }}"
    @endif
    data-target="#{{ $inputName }}_picker"/>

  <div class="input-group-append" data-target="#{{ $inputName }}_picker" data-toggle="datetimepicker">
    <span class="input-group-text"><i class="fa fa-calendar"></i></span>
  </div>
  @include('app.html.invalid-message', compact('inputName'))
</div>

@push('scripts')
  <script>
    $.fn.datetimepicker.Constructor.Default = $.extend({}, $.fn.datetimepicker.Constructor.Default, {
      icons: {
        time: 'fa fa-clock',
        date: 'fa fa-calendar',
        up: 'fa fa-arrow-up',
        down: 'fa fa-arrow-down',
        previous: 'fa fa-chevron-left',
        next: 'fa fa-chevron-right',
        today: 'fa fa-calendar-check-o',
        clear: 'fa fa-trash',
        close: 'fa fa-times'
      } });
    @if($includeTime ?? false)
      $('#{{ $inputName }}_picker').datetimepicker();
    @else
      $('#{{ $inputName }}_picker').datetimepicker({format: 'L'});
    @endif
  </script>
@endpush
