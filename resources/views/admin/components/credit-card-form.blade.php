<form id="payment-form" class="payment-form">

  {{ $slot }}

  <div class="row">
    <div class="form-group col-sm-12">
      <label for="cc-name" class="control-label">Name on Card  </label>
      <div class="form-control payment-fields disabled empty" id="cc-card" data-cc-name></div>
    </div>
  </div>

  <div class="row">
    <div class="form-group col-sm-12">
      <label for="cc-card" class="control-label">Card Number  </label>
      <div class="form-control payment-fields disabled empty" id="cc-card" data-cc-card></div>
    </div>
  </div>

  <div class="row">
    <div class="form-group col-sm-6">
      <label for="cc-exp" class="control-label">Exp Date</label>
      <div class="form-control payment-fields disabled empty" id="cc-exp" data-cc-exp></div>
    </div>
    <div class="form-group col-sm-6">
      <label for="cc-cvv" class="control-label">CVV</label>
      <div class="form-control payment-fields disabled empty" id="cc-cvv" data-cc-cvv></div>
    </div>
  </div>

  <button id="submit" class="btn-primary disabled-bkg" data-submit-btn disabled>
    {{-- Using Bootstrap 4 spinner -- need to change for Bootstrap 3 --}}
    <span class="loader spinner-border spinner-border-sm mr-1" style="display: none;"></span>
    {{ $buttonLabel ?? 'Save Card' }}
  </button>

</form>

@push('scripts')
  <script src="{{ App\Helpers\PaymentJs::libraryUrl() }}"></script>
  <script src="{{ config('app.url') }}/js/admin/payment.js"></script>
@endpush

