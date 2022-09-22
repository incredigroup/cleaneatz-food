<div class="row nav-row">
  <div class="col-md-12">
    <div class="card">
      <div class="card-body">
        <h4 class="card-title">{{ $title }}</h4>
        <p class="card-text">{{ $slot }}</p>
        @if (isset($route) || isset($url))
          <a href="{{ isset($route) ? route($route) : $url }}" class="btn btn-primary">View Page</a>
        @else
          <a href="#" disabled class="btn btn-primary disabled">Todo</a>
        @endif
      </div>
    </div>
  </div>
</div>
