<?php

namespace App\Helpers;

use Exception;

class PaymentJs
{
    /**
     * @todo Laravelify this
     */
    public static function authorizeSession($storeLocation)
    {
        $nonce = time() * 1000 + rand();
        $timestamp = time() * 1000;
        $apiKey = config('services.payeezy.api_key');
        $secretKey = config('services.payeezy.payment_js_secret');
        $contentType = 'application/json';

        //merchant's gateway credentials
        $data = [
            'gateway' => 'PAYEEZY',
            'apiKey' => config('services.payeezy.api_key'),
            'apiSecret' => config('services.payeezy.api_secret'),
            'authToken' => $storeLocation->gateway_merchant_token,
            'transarmorToken' => $storeLocation->gateway_merchant_transarmor_token,
            'zeroDollarAuth' => false,
        ];

        $url = self::apiEndpoint() . '/merchant/authorize-session';

        $jsonPayload = json_encode($data);
        //message components
        $msg = $apiKey . $nonce . $timestamp . $jsonPayload;
        //message signature signed with ApiSecret and message
        $messageSignature = base64_encode(hash_hmac('sha256', $msg, $secretKey));

        //header array for auth request
        $headers = array(
            'Api-Key: ' . $apiKey,
            'Content-Type: ' . $contentType,
            'Content-Length: ' . strlen($jsonPayload),
            'Message-Signature: ' . $messageSignature,
            'Nonce: ' . $nonce,
            'Timestamp: ' . $timestamp
        );

        //cURL function to get the response
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonPayload);
        curl_setopt($ch, CURLOPT_HEADER, 1);
        $response = curl_exec($ch);
        $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        $header = [];
        $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        foreach (explode("\r\n", trim(substr($response, 0, $header_size))) as $row) {
            if (preg_match('/(.*?): (.*)/', $row, $matches)) {
                $header[$matches[1]] = $matches[2];
            }
        }
        
        //response headers
        $client_token = $header['Client-Token'];
        $responseNonce = $header['Nonce'];

        //response body
        $body = substr($response, $header_size);
        $publicKeyBase64 = substr($body, 20, -2); //extract publicKeyBase64 from the response body

        if ($http_status === 200) {
            if ($responseNonce == $nonce) {
                return [
                    'clientToken' => $client_token,
                    'nonce' => $nonce,
                    'publicKeyBase64' => $publicKeyBase64];
            } else {
                throw new Exception('nonce validation failed for nonce "' + $nonce + '"', 1);
            }
        } else {
            throw new Exception('received HTTP ' . $http_status, 1);
        }
    }

    public static function apiEndpoint()
    {
        return 'https://'
            . (config('services.payeezy.sandbox') ? 'cert' : 'prod')
            . '.api.firstdata.com/paymentjs/v2';
    }

    public static function libraryUrl()
    {
        return 'https://docs.paymentjs.firstdata.com/lib/'
            . (config('services.payeezy.sandbox') ? 'uat' : 'prod')
            . '/client-2.0.0.js';
    }
}
