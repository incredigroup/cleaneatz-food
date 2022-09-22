 
<div class="edit-cart">
  <a class="text-white"
     href="{{ $editCartUrl }}">Edit Cart</a>
</div>

<div id="pnlRightBox">
  @foreach ($cart->items as $item)
    <div class="row">
      @if ($item->meal->image_url)
        <div class="col-sm-3">
          <img src="{{ asset('img/meals/' . $item->meal->image_url) }}"
               class="rb-pic"
               style="max-width: 60px; margin-bottom: 5px;">
        </div>
      @endif
      <div class="{{ $item->meal->image_url ? 'col-sm-6' : 'col-sm-9' }}">
        {{ $item->meal->display_name }}
        @if ($item->quantity > 1)
          (x{{ $item->quantity }})
        @endif
        @if ($cart->getSpecialRequestsForMeal($item->meal))
          <br>
          <small> * {{ $cart->getSpecialRequestsForMeal($item->meal) }}</small>
        @endif
      </div>
      <div class="col-sm-3"
           style="text-align:right">
        <strong>${{ number_format($item->lineTotal(), 2) }}</strong>
      </div>
    </div>
  @endforeach
  <hr class="hr-legacy">
  <div class="row">
    @if ($cart->promoCode()->exists())
      <form method="POST"
            action="{{ route('site.shopping-cart.removeCoupon') }}">
        @csrf
        @method('DELETE')
        <div class="col-sm-7 col-md-9">
          Coupon Code: <span class="bold">{{ $cart->promoCode->code }}</span>
          <button class="btn-link"
                  style="color:#fff; text-decoration:underline"
                  type="submit">(remove)</button>
        </div>
        <div class="col-sm-5 col-md-3 bold text-right">
          <strong>${{ number_format($cart->discount_amount, 2) }}</strong>
        </div>
      </form>
    @else
      <form method="POST"
            action="{{ route('site.shopping-cart.coupon') }}">
        @csrf
        <input type="hidden"
               name="store-location"
               value="{{ $cart->storeLocation->code }}">
        <div class="row">
          <div class="col-sm-7 col-md-9">
            <input type="text"
                   placeholder="Coupon Code"
                   name="promo-code"
                   class="form-control mt-1"
                   style="text-transform: uppercase;">
          </div>
          <div class="col-sm-5 col-md-3">
            <input type="submit"
                   value="Apply"
                   class="btn btn-lg btn-orange w-100">
          </div>
        </div>
      </form>
    @endif
  </div>
  <hr class="hr-legacy">
  <cart-order-totals :subtotal='@json($cart->sub_total)'
                     :delivery-fee='@json($cart->delivery_fee)'
                     :satellite-fee='@json($cart->satellite_fee)'
                     :discount='@json($cart->discount_amount)'
                     :tax='@json($cart->total_tax)'
                     :total='@json($cart->order_total)'>
  </cart-order-totals>


  

</div>
 