<?php

namespace App\Http\Controllers\Admin\Reports;

use App\Http\Controllers\Controller;
use App\Models\MealPlanOrder;
use Carbon\Carbon;

class TipsController extends Controller
{
    public function tips()
    {
        $orders = $this->pastOrders(14);
        $tips = $orders->groupBy('order_date')->map->sum('tip_amount');

        return view('admin.reports.tips.tips', compact('tips'));
    }

    public function tipsByStore()
    {
        $orders = $this->pastOrders(7);
        $ordersByStore = $orders->groupBy(['store_location', 'order_date']);

        return view('admin.reports.tips.tips-by-store', compact('ordersByStore'));
    }

    private function pastOrders($days)
    {
        $select = [
            'meal_plan_orders.*',
            "CONCAT(store_locations.city, ' ',store_locations.location, ' ', store_locations.state) AS store_location"
        ];
        return MealPlanOrder::where('meal_plan_orders.created_at', '>=', Carbon::now()
            ->subDays($days))
            ->join('store_locations', 'meal_plan_orders.store_location_id', '=', 'store_locations.id')
            ->where('meal_plan_orders.transaction_status', MealPlanOrder::STATUS_COMPLETED)
            ->orderBy('store_location')
            ->orderBy('meal_plan_orders.created_at', 'desc')
            ->selectRaw(implode(',', $select))
            ->get();
    }
}
