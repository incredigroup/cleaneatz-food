<div class="container">
  <div class="row">
    <div class="col-md-12">
      <div class="d-flex align-items-end mb-5">
        <h1 class="my-0 flex-grow-1">
          {{ $title }}
          @isset($subtitle)
            <span class="text-secondary small"> - {{ $subtitle }}</span>
          @endisset
        </h1>
        @isset($actions)
          <div>
            {{ $actions }}
          </div>
        @endisset
      </div>
      {{ $slot }}
    </div>
  </div>
</div>
