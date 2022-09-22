<div class="breadcrumbs nav-links d-none d-md-block">
  <div class="container">
    @if (isset($page) && $page->slug !== 'home')
      <a class="nav-link d-inline p-0"
         href="/">Home</a> <span class="px-1">&gt;</span> {{ $page->title }}
    @endif
    @isset($breadcrumbs)
      <a class="nav-link d-inline p-0"
         href="/">Home</a>
      @foreach ($breadcrumbs as $breadcrumb)
        @if ($loop->last)
          <span class="px-1">&gt;</span> {{ $breadcrumb->title }}
        @else
          <span class="px-1">&gt;</span> <a class="nav-link d-inline p-0"
             href="{{ $breadcrumb->url }}">
            {{ $breadcrumb->title }}</a>
        @endif
      @endforeach
    @endisset
  </div>
</div>
