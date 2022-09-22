@extends('layouts.site')

@section('content')
<div class="container-fluid cart-header-banner">
          <div class="row">
            <div class="col-md-12 text-center">
              <h1 class="bottom">
                Order Complete!
              </h1>
            </div>
          </div>
        </div>
        <section class="cart-banner banner">
        <div class="container">
          <div class="row">
            <div data-method="app-container">
              <div id="pnlThankyou" data-item="Panel" style="display: block;min-height: 300px;">
                <h3>THANK YOU!</h3>
                <strong>Order #1338842</strong>
                <p>Your order is complete!</p>
                <p>
                  Details of your order, including pickup instructions, have been emailed to
                  <strong>  @json(Auth::check()?Auth::user()->email:"")</strong>.
                </p>

                <h5>Meal Plan Pick Up: Sunday, August 21st &amp; Monday, August 22nd</h5>

                              </div>
            </div>
          </div>


        </div>
      </section>
   

      

        <style>
        .cart-header-banner {
    background-color: #80bd5a;
    color: #fff;
    width: 100%;
    padding-top: 28px;
    padding-bottom: 15px;
}
.banner {
    padding: 30px 0;
    position: relative;
}
h3 {
  font-family: "BebasNeue",Verdana,Tahoma;
    text-transform: uppercase;
  font-weight: 800;
    font-size: 50px;
}
h5 {
  font-weight: 700;
  font-family: "BebasNeue",Verdana,Tahoma;
    text-transform: uppercase;
    font-size: 25px;
}
        </style>
@endsection