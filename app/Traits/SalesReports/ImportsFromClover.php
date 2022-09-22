<?php
namespace App\Traits\SalesReports;

use App\Helpers\API\Clover\CloverApi;
use App\Models\CloverOrder;
use App\Models\CloverOrderRefund;
use Illuminate\Support\Facades\Date;

trait ImportsFromClover
{
    /**
     * Imports recent orders and refunds from Clover up to yesterday.
     */
    public function importFromClover(int $maxDaysBack = 30)
    {
        $cloverMerchant = $this->cloverMerchant;

        if (!$cloverMerchant) {
            throw new \Exception('No Clover Merchant for location' . $this->name);
        }

        $cloverApi = (new CloverApi())
            ->setMerchantId($cloverMerchant->merchant_id)
            ->setToken($cloverMerchant->access_token);

        $lastCloverOrderAt = CloverOrder::lastOrderCreatedAt($this->id);

        $ordersOn = $lastCloverOrderAt
            ? $lastCloverOrderAt->startOfDay()->addDay()
            : Date::now()
                ->startOfDay()
                ->subDays($maxDaysBack);

        while ($ordersOn->isBefore(Date::now()->startOfDay())) {
            $this->importDayOfOrders($cloverApi, $ordersOn->format('Y-m-d'));
            $this->importDayOfRefunds($cloverApi, $ordersOn->format('Y-m-d'));

            $ordersOn = $ordersOn->addDay();
        }
    }

    private function importDayOfOrders(CloverApi $cloverApi, string $dateStr): void
    {
        $expand = ['lineItems', 'lineItems.item.categories', 'discounts', 'payments'];
        $page = 0;
        do {
            $cloverApiOrders = $cloverApi->merchantOrders($dateStr, $page++, $expand)['elements'];
            foreach ($cloverApiOrders as $cloverApiOrder) {
                CloverOrder::fromCloverJson($cloverApiOrder, $this->id);
            }
            sleep(1);
        } while (count($cloverApiOrders) > 0);
    }

    private function importDayOfRefunds(CloverApi $cloverApi, string $dateStr): void
    {
        $page = 0;
        do {
            $cloverApiRefunds = $cloverApi->merchantRefunds($dateStr, $page++)['elements'];
            foreach ($cloverApiRefunds as $cloverApiRefund) {
                CloverOrderRefund::fromCloverJson($cloverApiRefund);
            }
            sleep(1);
        } while (count($cloverApiRefunds) > 0);
    }
}
