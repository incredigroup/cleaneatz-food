 
      
@extends('layouts.site')

@section('content')
  <div class="gray-header-panel">
    <h1>Shopping Cart</h1>
  </div>

  <div class="meal-plan-cart container py-4"
       id="app">

    @if ($errors->any())
      <div class="alert alert-danger">
        @error('charge_failed')
          <div>
            Your order could not be processed.
          </div>
        @enderror
        <ul>
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <div class="row">
      <div data-method="app-container"
           class="pe-0">
        <div id="pnlBilling"
             data-item="Panel"
             style="display: block;">
          <table style="width: 100%;">
            <tr class="flex-row">
              <td style="width: 50%; padding-right: 20px;">
                <h3>Billing</h3>
              </td>
              <td class="d-none d-md-block"
                  style="width: 50%; padding-right: 20px;">
                <h3>{{ $cart->storeLocation->locale }}</h3>
              </td>
            </tr>
          </table>
          <table style="width: 100%;">
            <tbody>
              <tr class="flex-row">
                <td style="width: 50%; padding-right: 20px;">
                  <div class="row d-block d-md-none">
                    <div class="col-sm-5 ce-cart-rightbox">
                      @include(
                          'site.meal-plan-cart._order-details',
                          ['cart' => $cart]
                      )
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-12">
                      @if (!Auth::check())
                        @include(
                            'site.meal-plan-cart._login-form'
                        )
                        <hr class="hr-legacy">
                      @endif
    
                    
                      <cart-order-form form-action="{{ route('site.order.save') }}"
                                       default-email="{{ $defaultEmail }}"
                                       edit-cart-url="{{ $editCartUrl }}">

                        <cart-contact-info default-email="{{ $defaultEmail }}"
                                           :default-first-name='@json($defaultFirstName)'
                                           :default-last-name='@json($defaultLastName)'
                                           :is-logged-in='@json(Auth::check())'
                                           :form-errors="{{ $errors->toJson() }}">
                        </cart-contact-info>
                      

                        <cart-billing-info :default-payment-type='@json(old('payment_type', 'online'))'
                                           :default-name='@json($defaultName)'
                                           :is-logged-in='@json(Auth::check())'
                                           :stored-payment-methods='@json($storedPaymentMethods)'
                                           :store-location-id='@json($cart->storeLocation->id)'
                                           :satellite='@json($satellite)'
                                           :online-payments-only='@json($onlinePaymentsOnly)'
                                           :in-store-payments-only='@json($inStorePaymentsOnly)'
                                           :form-errors="{{ $errors->toJson() }}">
                        </cart-billing-info>
                       
                      </cart-order-form>
 
                    </div>
                  </div>
                </td>
                <td data-method="right-box"
                
                    class="ce-cart-rightbox d-none d-md-block">
                  @include(
                      'site.meal-plan-cart._order-details',
                      ['cart' => $cart]
                  )

                  <div style="display:none;">

                  
                  <support-total-amount
                     :total='@json($cart->order_total)'
                     :emailm='@json(Auth::check()?Auth::user()->email:"")'
                     >
  </support-total-amount>
  </div>
                </td>
              </tr>
            </tbody>
          </table>
                    
        </div>

</div>
<!-- <cart-order-totals :subtotal='@json($cart->sub_total)'
                     :delivery-fee='@json($cart->delivery_fee)'
                     :satellite-fee='@json($cart->satellite_fee)'
                     :discount='@json($cart->discount_amount)'
                     :tax='@json($cart->total_tax)'
                     :total='@json($cart->order_total)'>
  </cart-order-totals> -->

      </div>
    </div>
  </div>
	
@endsection
<script src="https://secure.qorcommerce.io/payFrame/js/QorPaymentForm.js"></script>

@if (Session::has('addedToCart'))
  @push('scripts')
    <script>
      fbq('track', 'AddToCart', {
        value: {{ Session::get('addedToCart')['subtotal'] }},
        currency: 'USD',
      });
    </script>
  @endpush
@endif
