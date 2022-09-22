<?php

namespace App\Helpers\Calculators;

use App\Models\SalesReport;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;

class CloverSalesDataCalculator implements SalesDataCalculator
{
    public CarbonImmutable $startsAt;
    public CarbonImmutable $endsAt;

    public static int $weekStartDayNum = CarbonImmutable::MONDAY;
    public static int $weekStartDayHour = 0;

    public function __construct(string $startOn)
    {
        $this->startsAt = Date::createFromFormat('Y-m-d', $startOn)
            ->setTimezone(SalesReport::$timezone)
            ->setTime(self::$weekStartDayHour, 0, 0)
            ->setTimezone('UTC');

        $this->endsAt = $this->startsAt->addWeek()->subSecond();
    }

    public function locationIds()
    {
        $locationsWithOrders = DB::table('clover_orders')
            ->join('store_locations', 'clover_orders.store_location_id', '=', 'store_locations.id')
            ->where('order_created_at', '>=', $this->startsAt)
            ->where('order_created_at', '<=', $this->endsAt)
            ->select('store_locations.id')
            ->distinct()
            ->pluck('id');

        return $locationsWithOrders;
    }

    public function totals(int $storeLocationId)
    {
        $select = [
            'sum(net_sales_amount) as net_sales',
            'sum(tip_amount) as tips',
            'sum(discount_amount) as discounts',
            'sum(tax_amount) as sales_tax',
        ];

        $totals = DB::table('clover_orders')
            ->where('store_location_id', $storeLocationId)
            ->where('order_created_at', '>=', $this->startsAt)
            ->where('order_created_at', '<=', $this->endsAt)
            ->selectRaw(implode(',', $select))
            ->first();

        $refunds = $this->refunds($storeLocationId);

        $nonRevenue = $this->nonRevenue($storeLocationId);

        $totals->net_sales = $totals->net_sales ?: 0;
        $totals->tips = $totals->tips ?: 0;
        $totals->discounts = $totals->discounts ?: 0;
        $totals->sales_tax = $totals->sales_tax ?: 0;
        $totals->net_sales_refunds = $refunds->net_sales_amount;
        $totals->tips_refunds = $refunds->tips_amount;
        $totals->sales_tax_refunds = $refunds->sales_tax_amount;
        $totals->non_revenue = $nonRevenue;

        $totals->net_sales = $totals->net_sales - $totals->net_sales_refunds - $totals->non_revenue;

        return $totals;
    }

    public function refunds(int $storeLocationId)
    {
        $select = [
            'sum(clover_order_refunds.refund_amount) as refund_amount',
            'sum(clover_order_refunds.tax_amount) as tax_amount',
            'sum(clover_order_refunds.tip_amount) as tip_amount',
        ];

        $refundTotals = DB::table('clover_order_refunds')
            ->join('clover_orders', 'clover_order_refunds.clover_order_id', 'clover_orders.id')
            ->where('clover_orders.store_location_id', $storeLocationId)
            ->where('clover_order_refunds.refunded_at', '>=', $this->startsAt)
            ->where('clover_order_refunds.refunded_at', '<=', $this->endsAt)
            ->selectRaw(implode(',', $select))
            ->first();

        $refundTotals->refund_amount = $refundTotals->refund_amount ?: 0;
        $refundTotals->sales_tax_amount = $refundTotals->tax_amount ?: 0;
        $refundTotals->tips_amount = $refundTotals->tip_amount ?: 0;
        $refundTotals->net_sales_amount =
            $refundTotals->refund_amount - $refundTotals->tax_amount - $refundTotals->tip_amount;

        return $refundTotals;
    }

    public function nonRevenue(int $storeLocationId)
    {
        $select = ['sum(clover_order_items.line_item_total_amount) as non_revenue_amount'];

        $nonRevenueTotals = DB::table('clover_order_items')
            ->join('clover_orders', 'clover_order_items.clover_order_id', 'clover_orders.id')
            ->where('clover_order_items.is_revenue', false)
            ->where('clover_orders.store_location_id', $storeLocationId)
            ->where('clover_orders.order_created_at', '>=', $this->startsAt)
            ->where('clover_orders.order_created_at', '<=', $this->endsAt)
            ->selectRaw(implode(',', $select))
            ->first();

        return $nonRevenueTotals->non_revenue_amount ?: 0;
    }

    public function categoryTotals(int $storeLocationId)
    {
        $select = [
            'category',
            'sum(clover_order_items.line_item_total_amount) as net_sales',
            'sum(clover_order_items.quantity) as quantity',
        ];

        $totals = DB::table('clover_orders')
            ->join(
                'clover_order_items',
                'clover_orders.id',
                '=',
                'clover_order_items.clover_order_id',
            )
            ->where('clover_orders.store_location_id', $storeLocationId)
            ->where('order_created_at', '>=', $this->startsAt)
            ->where('order_created_at', '<=', $this->endsAt)
            ->groupBy('clover_order_items.category')
            ->selectRaw(implode(',', $select))
            ->get();

        return $totals;
    }
}
