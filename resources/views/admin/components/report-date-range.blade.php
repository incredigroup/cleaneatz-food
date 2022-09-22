<div class="date-range-container">
  <div id="date-range">
    <i class="fa fa-calendar"></i>
    <span></span>
  </div>
  @isset($exportUrl)
  <a href="{{ $exportUrl }}" class="btn btn-primary">
    <i class="fa fa-download"></i> Export
  </a>
  @endisset
</div>
