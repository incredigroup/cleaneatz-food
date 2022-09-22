<div class="row mb-4">
  <div class="col-md-3">
    {{ \Str::snakeToTitle($option) }}
  </div>
  <div class="col-md-9">
    <textarea class="form-control" rows="5" name="{{ $option }}">{{ old($option, $customOptions[$option]) }}</textarea>
  </div>
</div>
