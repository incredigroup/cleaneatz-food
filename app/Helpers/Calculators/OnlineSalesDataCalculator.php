<?php

namespace App\Helpers\Calculators;

use App\Models\MealPlanOrder;
use App\Models\SalesReport;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;

class OnlineSalesDataCalculator implements SalesDataCalculator
{
    public CarbonImmutable $startsAt;
    public CarbonImmutable $endsAt;

    public static int $weekStartDayNum = CarbonImmutable::MONDAY;
    public static int $weekStartDayHour = 0;

    public static string $category = 'WEEKLY MEAL PLANS';

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
        $locationsWithOrders = DB::table('meal_plan_orders')
            ->join(
                'store_locations',
                'meal_plan_orders.store_location_id',
                '=',
                'store_locations.id',
            )
            ->where('meal_plan_orders.created_at', '>=', $this->startsAt)
            ->where('meal_plan_orders.created_at', '<=', $this->endsAt)
            ->select('store_locations.id')
            ->distinct()
            ->pluck('id');

        return $locationsWithOrders;
    }

    public function totals(int $storeLocationId)
    {
        $select = [
            'sum(meal_plan_orders.subtotal - meal_plan_orders.promo_amount + meal_plan_orders.satellite_fee) as net_sales',
            'sum(meal_plan_orders.tip_amount) as tips',
            'sum(meal_plan_orders.promo_amount) as discounts',
            'sum(meal_plan_orders.tax) as sales_tax',
            'sum(meal_plan_orders.satellite_fee) as satellite_fee',
        ];

        $totals = MealPlanOrder::where('meal_plan_orders.store_location_id', $storeLocationId)
            ->where('meal_plan_orders.created_at', '>=', $this->startsAt->format('Y-m-d H:i:s'))
            ->where('meal_plan_orders.created_at', '<=', $this->endsAt->format('Y-m-d H:i:s'))
            ->paidOnline()
            ->selectRaw(implode(',', $select))
            ->first();

        $refunds = $this->refunds($storeLocationId);

        $totals->net_sales = $totals->net_sales ?: 0;
        $totals->tips = $totals->tips ?: 0;
        $totals->discounts = $totals->discounts ?: 0;
        $totals->sales_tax = $totals->sales_tax ?: 0;
        $totals->satellite_fee = $totals->satellite_fee ?: 0;
        $totals->net_sales_refunds = $refunds->net_sales_amount;
        $totals->tips_refunds = $refunds->tips_amount;
        $totals->sales_tax_refunds = $refunds->sales_tax_amount;

        $totals->net_sales = $totals->net_sales - $totals->net_sales_refunds;

        return $totals;
    }

    public function refunds(int $storeLocationId)
    {
        $select = [
            'sum(meal_plan_refunds.total_refund) as refund_amount',
            'sum(meal_plan_refunds.tax) as sales_tax_amount',
            'sum(meal_plan_refunds.tip_amount) as tips_amount',
            'sum(meal_plan_refunds.tip_amount) as tips_amount',
        ];

        $refundTotals = DB::table('meal_plan_refunds')
            ->join(
                'meal_plan_orders',
                'meal_plan_refunds.meal_plan_order_id',
                'meal_plan_orders.id',
            )
            ->where('meal_plan_orders.store_location_id', $storeLocationId)
            ->where('meal_plan_refunds.created_at', '>=', $this->startsAt)
            ->where('meal_plan_refunds.created_at', '<=', $this->endsAt)
            ->selectRaw(implode(',', $select))
            ->first();

        $refundTotals->refund_amount = $refundTotals->refund_amount ?: 0;
        $refundTotals->sales_tax_amount = $refundTotals->sales_tax_amount ?: 0;
        $refundTotals->tips_amount = $refundTotals->tips_amount ?: 0;
        $refundTotals->net_sales_amount =
            $refundTotals->refund_amount -
            $refundTotals->sales_tax_amount -
            $refundTotals->tips_amount;

        return $refundTotals;
    }

    public function categoryTotals(int $storeLocationId)
    {
        $totals = $this->totals($storeLocationId);

        return collect([
            (object) [
                'category' => self::$category,
                'net_sales' => $totals->net_sales,
                'quantity' => 0,
            ],
        ]);
    }
}
