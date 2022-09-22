<?php

namespace App\Helpers;

use Payeezy_Client;
use Payeezy_CreditCard;
use Payeezy_Error;
use Payeezy_Token;

class PaymentManager
{
    const TYPE_VISA = 'visa';
    const TYPE_MASTERCARD = 'mastercard';
    const TYPE_AMEX = 'american express';
    const TYPE_DINERS_CLUB = 'diners club';
    const TYPE_JCB = 'jcb';
    const TYPE_DISCOVER = 'discover';
    const TYPE_UNKONWN = 'unknown';

    private $name = '';
    private $amount = '';
    private $cardType = '';
    private $paymentToken = '';
    private $cardExpiry = '';
    private $merchantRef = '';

    private $client;

    function __construct($merchantToken)
    {
        $this->client = new Payeezy_Client();
        $this->client->setApiKey(config('services.payeezy.api_key'));
        $this->client->setApiSecret(config('services.payeezy.api_secret'));
        $this->client->setUrl(self::apiEndpoint());
        $this->client->setMerchantToken($merchantToken);
    }

    public function setName(string $name)
    {
        $this->name = $name;
        return $this;
    }

    public function setAmount($amount)
    {
        $this->amount = (string) intval($amount * 100);
        return $this;
    }

    public function setCardExpiry($month, $year)
    {
        $this->cardExpiry = str_pad($month, 2, '0', STR_PAD_LEFT) . substr($year, -2);
        return $this;
    }

    public function setCardType(string $cardType)
    {
        $this->cardType = $cardType;
        return $this;
    }

    public function setPaymentToken(string $paymentToken)
    {
        $this->paymentToken = $paymentToken;
        return $this;
    }

    public function purchase()
    {
        $transactionPayload = [
            'merchant_ref' => $this->merchantRef,
            'transaction_type' => 'purchase',
            'method' => 'token',
            'amount' => $this->amount,
            'currency_code' => 'USD',
            'token' => [
                'token_type' => 'FDToken',
                'token_data' => [
                    'type' => $this->cardType,
                    'value' => $this->paymentToken,
                    'cardholder_name' => $this->name,
                    'exp_date' => $this->cardExpiry,
                ],
            ],
        ];

        $response = [];
        try {
            $payeezyToken = new Payeezy_Token($this->client);
            $gatewayResponse = $payeezyToken->purchase($transactionPayload);

            $response = [
                'success' =>
                    isset($gatewayResponse->transaction_status) &&
                    $gatewayResponse->transaction_status === 'approved',
                'transaction_id' => $gatewayResponse->transaction_id ?? null,
                'transaction_tag' => $gatewayResponse->transaction_tag ?? null,
                'bank_resp_code' => $gatewayResponse->bank_resp_code ?? null,
                'bank_message' => $gatewayResponse->bank_message ?? null,
                'error_code' => $gatewayResponse->Error->messages[0]->code ?? null,
                'error_description' => $gatewayResponse->Error->messages[0]->description ?? null,
            ];
        } catch (Payeezy_Error $e) {
            $response = [
                'success' => false,
                'error_code' => $e->getCode(),
                'error_description' => $e->getMessage(),
            ];
        }
        return $response;
    }

    public function refund($transactionId, $transactionTag)
    {
        return $this->reverse($transactionId, $transactionTag, 'refund');
    }

    public function void($transactionId, $transactionTag)
    {
        return $this->reverse($transactionId, $transactionTag, 'void');
    }

    private function reverse($transactionId, $transactionTag, $transactionType)
    {
        $transactionPayload = [
            'transaction_tag' => $transactionTag,
            'transaction_type' => $transactionType,
            'method' => 'credit_card',
            'amount' => $this->amount,
            'currency_code' => 'USD',
        ];

        $payeezy = new Payeezy_CreditCard($this->client);

        $response = [];
        try {
            switch ($transactionType) {
                case 'void':
                    $gatewayResponse = $payeezy->void($transactionId, $transactionPayload);
                    break;

                case 'refund':
                    $gatewayResponse = $payeezy->refund($transactionId, $transactionPayload);
                    break;
            }

            $response = [
                'success' => $gatewayResponse->transaction_status === 'approved',
                'transaction_id' => $gatewayResponse->transaction_id ?? null,
                'bank_resp_code' => $gatewayResponse->bank_resp_code ?? null,
                'bank_message' => $gatewayResponse->bank_message ?? null,
                'error_code' => $gatewayResponse->Error->messages[0]->code ?? null,
                'error_description' => $gatewayResponse->Error->messages[0]->description ?? null,
            ];
        } catch (Payeezy_Error $e) {
            $response = [
                'success' => false,
                'error_code' => $e->getCode(),
                'error_description' => $e->getMessage(),
            ];
        }

        return $response;
    }

    public static function apiEndpoint()
    {
        return 'https://' .
            (config('services.payeezy.sandbox') ? 'api-cert' : 'api') .
            '.payeezy.com/v1/transactions';
    }
}
