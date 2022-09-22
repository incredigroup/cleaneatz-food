<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\PaymentManager;
use App\Http\Controllers\Controller;
use App\Models\PaymentToken;
use App\Models\StoreLocation;
use Illuminate\Http\Request;

class PayeezySetupController extends Controller
{
    public function index(Request $request)
    {
        $storeLocationId = $request->get('storeLocationId', null);
        $storeLocations = StoreLocation::orderedByLocation()->get();

        return view('admin.payeezy-setup.index', compact('storeLocations', 'storeLocationId'));
     }

     public function placeOrder(Request $request)
     {
         $paymentToken = PaymentToken::ofClientToken($request->get('client_token'))->firstOrFail();
         $storeLocation = StoreLocation::find($paymentToken->store_location_id);

         $paymentManager = new PaymentManager($storeLocation->gateway_merchant_token);

         $response = $paymentManager->setName($paymentToken->card_name)
             ->setAmount($request->get('amount'))
             ->setCardExpiry($paymentToken->card_exp_month, $paymentToken->card_exp_year)
             ->setCardType($paymentToken->card_brand)
             ->setPaymentToken($paymentToken->card_token)
             ->purchase();

         $message = (print_r($response, true));

         return redirect()->route('admin.payeezy-setup.index')
             ->with('status', $message);
     }
}
