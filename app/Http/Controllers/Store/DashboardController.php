<?php

namespace App\Http\Controllers\Store;

use App\Models\MealPlanOrder;
use App\Traits\BuildsReports;
use App\Traits\ParsesDates;
use Illuminate\Support\Facades\DB;

class DashboardController extends StoreBaseController
{
    use BuildsReports, ParsesDates;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($storeCode)
    {
        $startOnUtc = $this->startOfDayGlobalDateTime('first day of this month');
        $endOnUtc = $this->endOfDayGlobalDateTime('last day of this month');

        $monthlySales = DB::table('meal_plan_orders')
            ->join('store_locations', 'meal_plan_orders.store_location_id', '=', 'store_locations.id')
            ->where('store_locations.code', $storeCode)
            ->where('meal_plan_orders.created_at', '>=', $startOnUtc)
            ->where('meal_plan_orders.created_at', '<=', $endOnUtc)
            ->where('meal_plan_orders.transaction_status', MealPlanOrder::STATUS_COMPLETED)
            ->selectRaw('sum(total) as sales')
            ->value('sales');

        $promoCodes = DB::table('meal_plan_orders')
            ->join('store_locations', 'meal_plan_orders.store_location_id', '=', 'store_locations.id')
            ->where('store_locations.code', $storeCode)
            ->where('meal_plan_orders.created_at', '>=', $startOnUtc)
            ->where('meal_plan_orders.created_at', '<=', $endOnUtc)
            ->where('meal_plan_orders.transaction_status', MealPlanOrder::STATUS_COMPLETED)
            ->whereNotNull(('meal_plan_orders.promo_code'))
            ->groupBy('meal_plan_orders.promo_code')
            ->orderBy('uses', 'desc')
            ->limit(3)
            ->selectRaw('promo_code, count(*) as uses')
            ->get();

        $customers = DB::table('meal_plan_orders')
            ->join('store_locations', 'meal_plan_orders.store_location_id', '=', 'store_locations.id')
            ->where('store_locations.code', $storeCode)
            ->where('meal_plan_orders.created_at', '>=', $startOnUtc)
            ->where('meal_plan_orders.created_at', '<=', $endOnUtc)
            ->where('meal_plan_orders.transaction_status', MealPlanOrder::STATUS_COMPLETED)
            ->groupBy('meal_plan_orders.last_name')
            ->groupBy('meal_plan_orders.first_name')
            ->groupBy('meal_plan_orders.email')
            ->orderBy('num_purchases', 'desc')
            ->limit(3)
            ->selectRaw('first_name, last_name, count(*) as num_purchases')
            ->get();

        return view('store.dashboard.index', compact('monthlySales', 'promoCodes', 'customers'));
    }

    public function salesReporting($storeCode, $startOn, $endOn)
    {
        $startOnUtc = $this->startOfDayGlobalDateTime($startOn);
        $endOnUtc = $this->endOfDayGlobalDateTime($endOn);

        $query = DB::table('meal_plan_orders')
            ->join('store_locations', 'meal_plan_orders.store_location_id', '=', 'store_locations.id')
            ->where('store_locations.code', $storeCode)
            ->where('meal_plan_orders.created_at', '>=', $startOnUtc)
            ->where('meal_plan_orders.created_at', '<=', $endOnUtc)
            ->where('meal_plan_orders.transaction_status', MealPlanOrder::STATUS_COMPLETED);

        $overall = $query
            ->selectRaw('count(*) as num_orders, sum(total) as sales, sum(tip_amount) as tips')
            ->first();

        $prepaid = $query
            ->where('meal_plan_orders.payment_type', 'online')
            ->selectRaw('sum(tax) as taxes')
            ->first();

        $results = [
            'num_orders' => $overall->num_orders,
            'sales' => $overall->sales,
            'tips' => $overall->tips,
            'taxes' => $prepaid->taxes,
        ];

        return response()->json($results, 200, [], JSON_NUMERIC_CHECK);
    }
}
