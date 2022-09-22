<hr class="my-4">

<h5>
  Create an Account for {{ $order->email }}
</h5>

<p>
  If you would like to create a customer account under to speed up future orders,
  you can do so now -- all you need to do is provide a password.
</p>

<div class="row mb-5">
  <div class="col-md-4">
    <form method="POST"
          action="{{ route('register') }}"
          id="register_form">
      @csrf

      <input type="hidden"
             name="first_name"
             value="{{ $order->first_name }}">
      <input type="hidden"
             name="last_name"
             value="{{ $order->last_name }}">
      <input type="hidden"
             name="email"
             value="{{ $order->email }}">

      <h5 class="text-left sm-header">
        Select Password
      </h5>

      <div class="mb-3">
        <div class="form-floating">
          <input id="password"
                 type="password"
                 class="form-control @error('password') is-invalid @enderror"
                 name="password"
                 required
                 autofocus
                 placeholder="Password"
                 autocomplete="new-password">
          <label for="password">Password</label>
          @error('password')
            <span class="invalid-feedback"
                  role="alert">
              <strong>{{ $message }}</strong>
            </span>
          @enderror
        </div>
      </div>

      <div class="mb-3">
        <div class="form-floating">
          <input id="password_confirmation"
                 type="password"
                 class="form-control"
                 name="password_confirmation"
                 required
                 placeholder="Confirm Password"
                 autocomplete="new-password">
          <label for="password_confirmation">Confirm Password</label>
        </div>
      </div>

      <div class="form-group text-center">
        <input name="sendto"
               type="hidden"
               value="/">
        <input class="btn btn-legacy"
               type="submit"
               value="Register">
      </div>

    </form>
  </div>
</div>

@push('scripts')
  <script>
    $(function() {
      $("#register_form").submit(function() {
        $(this).find(":submit").prop('disabled', true);
      });
    });
  </script>
@endpush
