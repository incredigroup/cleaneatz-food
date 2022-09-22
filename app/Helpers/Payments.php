<?php

namespace App\Helpers;
use CardDetect;
use \Illuminate\Support\Str;

/**
 * @todo DELETE this class after conversion to tokenized payments
 */
class Payments
{
    const TYPE_VISA = 'visa';
    const TYPE_MASTERCARD = 'mastercard';
    const TYPE_AMEX = 'american express';
    const TYPE_DINERS_CLUB = 'diners club';
    const TYPE_JCB = 'jcb';
    const TYPE_DISCOVER = 'discover';
    const TYPE_UNKONWN = 'unknown';

    protected $payeezy;

    private $name = '';
    private $amount = '';
    private $type = '';
    private $cardNumber = '';
    private $cardType = '';
    private $cardCvv = '';
    private $cardExpiry = '';
    private $merchantRef = '';

    function __construct($merchantToken)
    {
        $this->payeezy = new Payeezy;
        $this->payeezy->setApiKey(config('services.payeezy.api_key'));
        $this->payeezy->setApiSecret(config('services.payeezy.api_secret'));
        $this->payeezy->setUrl(self::apiEndpoint());
        $this->payeezy->setMerchantToken($merchantToken);
    }

    public function setName(string $name)
    {
        $this->name = self::sanitizeInput($name);
        return $this;
    }

    public function setAmount($amount)
    {
        $this->amount = (string)intval($amount * 100);
        return $this;
    }

    public function setCardNumber(string $number)
    {
        $this->cardNumber = self::sanitizeInput($number);

        $detector = new CardDetect\Detector();
        switch ($detector->detect($number)) {
            case 'Amex':
                $this->cardType = self::TYPE_AMEX;
                break;

            case 'Visa':
                $this->cardType = self::TYPE_VISA;
                break;

            case 'MasterCard':
                $this->cardType = self::TYPE_MASTERCARD;
                break;

            case 'DinersClub':
                $this->cardType = self::TYPE_DINERS_CLUB;
                break;

            case 'Discover':
                $this->cardType = self::TYPE_DISCOVER;
                break;

            case 'JCB':
                $this->cardType = self::TYPE_JCB;
                break;

            default:
                $this->cardType = self::TYPE_UNKONWN;
        }

        return $this;
    }

    public function setCardCvv($cvv)
    {
        $this->cardCvv = self::sanitizeInput($cvv);
        return $this;
    }

    public function setCardExpiry($month, $year)
    {
        $month = self::sanitizeInput($month);
        $year = self::sanitizeInput($year);

        $this->cardExpiry = str_pad($month, 2, '0', STR_PAD_LEFT) . substr($year, -2);

        return $this;
    }

    public function getCardType()
    {
        return Str::title($this->cardType);
    }

    public function getCardLastFour()
    {
        return substr($this->cardNumber, -4);
    }

    public function purchase()
    {
        $transactionPayload = [
            'amount'=> $this->amount,
            'card_number' => $this->cardNumber,
            'card_type' => $this->cardType,
            'card_holder_name' => $this->name,
            'card_cvv' => $this->cardCvv,
            'card_expiry' => $this->cardExpiry,
            'merchant_ref' => '',
            'currency_code' => 'USD',
            'method'=> 'credit_card',
        ];

        $gatewayResponse = json_decode($this->payeezy->purchase($transactionPayload));

        $response = [
            'success' => isset($gatewayResponse->transaction_status) && $gatewayResponse->transaction_status === 'approved',
            'transaction_id' => $gatewayResponse->transaction_id ?? null,
            'transaction_tag' => $gatewayResponse->transaction_tag ?? null,
            'bank_resp_code' => $gatewayResponse->bank_resp_code ?? null,
            'bank_message' => $gatewayResponse->bank_message ?? null,
            'error_code' => $gatewayResponse->Error->messages[0]->code ?? null,
            'error_description' => $gatewayResponse->Error->messages[0]->description ?? null,
        ];

        return $response;
    }

    public function void($mealPlanOrder)
    {
        return $this->reverse($mealPlanOrder, 'void', $mealPlanOrder->total);
    }

    public function refund($mealPlanOrder, $amount = null)
    {
        return $this->reverse($mealPlanOrder, 'refund', $amount ?? $mealPlanOrder->total);
    }

    public function reverse($mealPlanOrder, $transactionType, $amount)
    {
        $this->setAmount($amount);
        $transactionPayload = [
            'transaction_id' => $mealPlanOrder->transaction_id,
            'transaction_tag' => $mealPlanOrder->transaction_tag,
            'transaction_type' => $transactionType,
            'method'=> 'credit_card',
            'amount'=> $this->amount,
            'currency_code' => 'USD',
        ];

        $gatewayResponse = [];
        switch ($transactionType) {
            case 'void':
                $gatewayResponse = json_decode($this->payeezy->void($transactionPayload));
                break;

            case 'refund':
                $gatewayResponse = json_decode($this->payeezy->refund($transactionPayload));
                break;
        }

        $response = [
            'success' => $gatewayResponse->transaction_status === 'approved',
            'bank_resp_code' => $gatewayResponse->bank_resp_code ?? null,
            'bank_message' => $gatewayResponse->bank_message ?? null,
            'error_code' => $gatewayResponse->Error->messages[0]->code ?? null,
            'error_description' => $gatewayResponse->Error->messages[0]->description ?? null,
        ];

        return $response;
    }

    public static function sanitizeInput($data)
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);

        return strval($data);
    }

    public static function apiEndpoint()
    {
        return 'https://'
            . (config('services.payeezy.sandbox') ? 'api-cert' : 'api')
            . '.payeezy.com/v1/transactions';
    }
}
