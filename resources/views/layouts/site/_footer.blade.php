

<footer class="nav-links py-5">
  <div class="text-md-start container text-center">
    <div class="row">
      <div class="col-12 col-md">
        <img src="{{ asset('img/logos/logo.png') }}"
             class="logo"
             alt="Clean Eatz">
      </div>

      @foreach (collect(Statamic::tag('nav:footer'))->chunk(3) as $footerLinksColumn)
        <div class="col-12 col-md">
          <ul>
            @foreach ($footerLinksColumn as $link)
              <li><a class="nav-link p-0"
                   href="{{ $link['url'] }}">{{ $link['title'] }}</a></li>
            @endforeach
            @if ($loop->last)
              <div class="social-icons">
                <a href="https://www.facebook.com/CleanEatzLife/"
                   target="_blank">
                  <img src="{{ asset('img/social/facebook.png') }}"
                       alt="Facebook">
                </a>
                <a href="https://www.instagram.com/cleaneatzlife/"
                   target="_blank">
                  <img src="{{ asset('img/social/instagram.png') }}"
                       alt="Instagram">
                </a>
                <a href="https://www.youtube.com/channel/UCJRGrE-Xv4IMW_DbxSOTGGA"
                   target="_blank">
                  <img src="{{ asset('img/social/youtube.png') }}"
                       alt="Instagram">
                </a>
              </div>
            @endif
          </ul>
        </div>
      @endforeach
    </div>
    <div class="copyright text-md-end text-center">
      © {{ date('Y') }} Clean Eatz
    </div>
  </div>
</footer>
