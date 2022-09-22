<?php

namespace App\Http\Controllers;

use App\Helpers\PaymentJs;
use App\Models\PaymentToken;
use App\Models\StoreLocation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PaymentJsController extends Controller
{
    public function authorizeSession($storeLocationId)
    {
        $storeLocation = StoreLocation::findOrFail($storeLocationId);

        $response = PaymentJs::authorizeSession($storeLocation);

        if ($response)
        {
            PaymentToken::create([
                'store_location_id' => $storeLocation->id,
                'client_token' => $response['clientToken'],
                'nonce' => $response['nonce'],
            ]);
            return response()->json($response);
        }
    }

    public function tokenizeWebhook(Request $request)
    {
        $clientToken = $request->header('Client-Token');
        $nonce = $request->header('Nonce');

        $data = $request->all();

        Log::debug("Payment.js webhook received for clientToken: $clientToken");

        $paymentToken = PaymentToken::ofClientToken($clientToken)->first();

        if ($paymentToken) {
            if ($data['error']) {
                $paymentToken->update([
                    'error' => isset($data['gatewayReason']) ? json_encode($data['gatewayReason']) : 'No reason provided',
                ]);

                $this->logAndAbort("Unable to tokenize payment for $clientToken");
            }

            if ($nonce != $paymentToken->nonce) {

                $this->logAndAbort("Nonce does not match for $clientToken");
            }

            $paymentToken->update([
                'card_name' => $data['card']['name'],
                'card_last_4' => $data['card']['last4'],
                'card_exp_month' => $data['card']['exp']['month'],
                'card_exp_year' => $data['card']['exp']['year'],
                'card_brand' => Str::title(str_replace('-', ' ', $data['card']['brand'])),
                'card_token' => $data['card']['token'],
            ]);

            return response('Successfully completed Payment.js webhook');

        } else {

            /**
             * If we get a webhook for an unrecognized clientToken, most likely due to testing from a local
             * dev environment, so log and ignore.
             */
            if (App::environment('staging')) {
                Log::debug("Received webhook for unidentified tokenization, assuming local testing\n"
                    . 'clientToken: ' . $clientToken . "\n"
                    . 'Nonce: ' . $nonce . "\n"
                    . 'Payload=' . print_r($request->all(), true));

                return response('Successfully completed Payment.js webhook');
            }

            $this->logAndAbort("Unable find tokenization request for $clientToken");
        }
    }

    private function logAndAbort($msg)
    {
        Log::error($msg);
        abort(403, 'Error tokenizing card.');
    }
}
