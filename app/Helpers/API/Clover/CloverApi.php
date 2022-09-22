<?php

namespace App\Helpers\API\Clover;

use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Http;

class CloverApi
{
    public $merchantId;
    public $token;
    public $pageSize = 100;

    const DEFAULT_ORDER_EXPAND = [
        'lineItems',
        'lineItems.item.categories',
        'discounts',
        'payments',
    ];

    public static function getDomain()
    {
        return config('services.clover.sandbox')
            ? 'https://sandbox.dev.clover.com/v3'
            : 'https://api.clover.com/v3';
    }

    public function setMerchantId($merchantId)
    {
        $this->merchantId = $merchantId;
        return $this;
    }

    public function setToken($token)
    {
        $this->token = $token;
        return $this;
    }

    public function merchant($expand = [])
    {
        return $this->retrieveResource('/merchants/' . $this->merchantId, [], $expand);
    }

    public function merchantAddress()
    {
        return $this->retrieveResource('/merchants/' . $this->merchantId . '/address');
    }

    public function merchantOrders($dateStr, $pageNum = 0, $expand)
    {
        $params = [
            'filter=modifiedTime>=' . $this->startOfDay($dateStr),
            'filter=modifiedTime<=' . $this->endOfDay($dateStr),
            'limit=' . $this->pageSize,
            'offset=' . $pageNum * $this->pageSize,
        ];

        return $this->retrieveResource(
            '/merchants/' . $this->merchantId . '/orders',
            $params,
            $expand,
        );
    }

    public function merchantRefunds($dateStr, $pageNum = 0)
    {
        $params = [
            'filter=clientCreatedTime>=' . $this->startOfDay($dateStr),
            'filter=clientCreatedTime<=' . $this->endOfDay($dateStr),
            'limit=' . $this->pageSize,
            'offset=' . $pageNum * $this->pageSize,
        ];

        return $this->retrieveResource('/merchants/' . $this->merchantId . '/refunds', $params);
    }

    public function merchantOrder($cloverOrderId, $expand = self::DEFAULT_ORDER_EXPAND)
    {
        return $this->retrieveResource(
            '/merchants/' . $this->merchantId . '/orders/' . $cloverOrderId,
            [],
            $expand,
        );
    }

    public function startOfDay(string $date)
    {
        return Date::parse($date, 'UTC')->startOfDay()->timestamp * 1000;
    }

    public function endOfDay(string $date)
    {
        return Date::parse($date, 'UTC')->endOfDay()->timestamp * 1000;
    }

    private function retrieveResource($resource, $params = [], $expand = [])
    {
        if (count($expand) > 0) {
            $params[] = 'expand=' . implode(',', $expand);
        }

        $endpointUrl = self::getDomain() . $resource;

        if (count($params) > 0) {
            $endpointUrl = $endpointUrl . '?' . implode('&', $params);
        }

        $response = Http::withToken($this->token)->get($endpointUrl);

        if ($response->successful()) {
            return $response->json();
        }

        $response->throw();
    }
}
