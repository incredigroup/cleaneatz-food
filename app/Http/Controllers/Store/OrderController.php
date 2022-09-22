<?php

namespace App\Http\Controllers\Store;

use App\{Models\MealPlan, Models\MealPlanOrder, Models\SatelliteLocation, Models\StoreLocation};
use Carbon\Carbon;
use Illuminate\Http\Request;

class OrderController extends StoreBaseController
{
    private $selectedMenu = null;

    public function __construct(Request $request)
    {
        $this->selectedMenu = $request->get('menu', null);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $store = StoreLocation::ofCode($request->route('store_code'))->firstOrFail();
        $orders = $this->currentOrders($store->id)->get();
        $currentMealPlan = $this->currentMealPlan();
        $previousMealPlans = $this->previousMealPlans();

        return view(
            'store.orders.index',
            compact('orders', 'store', 'previousMealPlans', 'currentMealPlan'),
        );
    }

    public function delivery(Request $request)
    {
        $store = StoreLocation::ofCode($request->route('store_code'))->firstOrFail();
        $orders = $this->currentOrders($store->id, true)->get();

        return view('store.orders.delivery', compact('orders', 'store'));
    }

    public function satellite(Request $request)
    {
        $store = StoreLocation::ofCode($request->route('store_code'))->firstOrFail();
        $satellite = SatelliteLocation::findOrFail($request->route('satellite_id'));
        $currentMealPlan = $this->currentMealPlan();
        $previousMealPlans = $this->previousMealPlans();

        $orders = $this->currentOrders($store->id)
            ->forSatellite($satellite->id)
            ->get();

        return view(
            'store.orders.satellite',
            compact('store', 'satellite', 'orders', 'previousMealPlans', 'currentMealPlan'),
        );
    }

    public function custom(Request $request)
    {
        $store = StoreLocation::ofCode($request->route('store_code'))->firstOrFail();

        $orders = MealPlanOrder::forStore($store->id)
            ->join(
                'meal_plan_carts',
                'meal_plan_carts.id',
                '=',
                'meal_plan_orders.meal_plan_cart_id',
            )
            ->where('meal_plan_carts.is_custom', '=', true)
            ->orderBy('meal_plan_carts.created_at', 'desc')
            ->select('meal_plan_orders.*')
            ->get();

        ray($orders);
        return view('store.orders.custom', compact('store', 'orders'));
    }

    private function currentMealPlan()
    {
        if ($this->selectedMenu === null) {
            $currentMealPlan = MealPlan::current()->first();
        } else {
            $currentMealPlan = MealPlan::findOrFail($this->selectedMenu);
        }

        if ($currentMealPlan === null) {
            $currentMealPlan = MealPlan::where('available_on', '<', Carbon::now())
                ->orderBy('expires_on', 'desc')
                ->first();
        }

        return $currentMealPlan;
    }

    private function currentOrders($storeId, $delivery = null)
    {
        $currentMealPlan = $this->currentMealPlan();
        return MealPlanOrder::forMealPlan($currentMealPlan->id, $delivery)
            ->forStore($storeId)
            ->live();
    }

    private function previousMealPlans()
    {
        return MealPlan::where('available_on', '<', Carbon::now())
            ->orderBy('expires_on', 'desc')
            ->limit(3)
            ->get()
            ->pluck('name', 'id')
            ->map(function ($item) {
                return "Menu: $item";
            });
    }
}
