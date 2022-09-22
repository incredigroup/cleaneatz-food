<section class="page-banner banner">
  <div class="container">
    <div class="row">
      <div class="col-md-12 text-center">
        <div class="banner-content">

          @if (isset($top))
          <h3 class="top">
            {{ $top }}
          </h3>
          @endif

          <h1 class="bottom">
            {{ $bottom }}
          </h1>
        </div>
      </div>
    </div>
  </div>
</section>