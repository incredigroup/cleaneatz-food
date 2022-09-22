<?php

namespace App\Http\Controllers\Api;

use App\{Models\MealPlan, Models\MealPlanOrder, Models\StoreLocation};
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function menus(Request $request)
    {
        if (!$this->isAuthorized($request)) {
            return abort(401);
        }

        return MealPlan::betweenDates($request->get('end'), $request->get('start'))->get();
    }

    public function mealPlanStats(Request $request)
    {
        if (!$this->isAuthorized($request)) {
            return abort(401);
        }

        $store = StoreLocation::ofCode($request->route('store_code'))->firstOrFail();
        $orders = MealPlanOrder::forMealPlan($request->get('mealPlanId'))
            ->forStore($store->id)
            ->live()
            ->get();

        $stats = [];
        $orders->each(function ($order) use (&$stats) {
            $order->items->each(function ($item) use (&$stats, $order) {
                if (isset($stats[$item->meal_id])) {
                    $stats[$item->meal_id]['count'] += $item->quantity;
                } else {
                    $stats[$item->meal_id]['count'] = $item->quantity;
                    $stats[$item->meal_id]['extra_protien'] = 0;
                    $stats[$item->meal_id]['low_carb'] = 0;
                    $stats[$item->meal_id]['id'] = $item->meal_id;
                }

                $stats[$item->meal_id]['name'] = $item->meal_name;

                if ($order->made_special_request) {
                    if ($order->hasSpecialRequest('ExtraProtein')) {
                        $stats[$item->meal_id]['extra_protien']++;
                    }

                    if ($order->hasSpecialRequest('LowCarb')) {
                        $stats[$item->meal_id]['low_carb']++;
                    }
                }
            });
        });

        return array_values($stats);
    }

    private function isAuthorized($request)
    {
        if ($request->user()->isAdmin()) {
            return true;
        }

        if ($request->user()->isStore()) {
            return intval($request->route('store_code')) === $request->user()->storeLocation->code;
        }

        return false;
    }
}
