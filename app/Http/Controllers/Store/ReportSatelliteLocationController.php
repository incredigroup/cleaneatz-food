<?php

namespace App\Http\Controllers\Store;

use App\Models\MealPlanOrder;
use App\Models\SpecialRequests;
use App\Models\StoreLocation;
use App\Traits\BuildsReports;
use App\Traits\ParsesDates;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Laracsv\Export;

class ReportSatelliteLocationController extends StoreBaseController
{
    use BuildsReports, ParsesDates;

    public function orders(Request $request)
    {
        return view('store.reports.satellite-location.orders', $this->reportInputs(
            $request, $this->getDefaultSatellitesRange()
        ));
    }

    public function ordersData(Request $request)
    {
        return datatables()->of($this->ordersQuery($request))
            ->editColumn('created_at', function ($salesTrend) {
                return $this->toFormattedLocalDateTime($salesTrend->created_at);
            })
            ->editColumn('total', function ($salesTrend) {
                return '$' . number_format($salesTrend->total, 2);
            })
            ->toJson();
    }

    public function ordersExport(Request $request)
    {
        $salesTrends = $this->ordersQuery($request)
            ->orderBy('created_at', 'desc')
            ->get();

        $csvExporter = new Export();
        $csvExporter->beforeEach(function ($order) {
            $order->store_location_name = StoreLocation::buildName(
                $order->store_location_state, $order->store_location_city,$order->store_location_location
            );
            $order->is_cleared = $order->cleared_at !== null ? 1 : 0;
            $order->cleared_at = $order->cleared_at !== null ?
                $this->toFormattedGlobalDateTime($order->cleared_at) : null;
            $order->local_cleared_at = $order->cleared_at !== null ?
                $this->toFormattedLocalDateTime($order->cleared_at) : null;
            $order->created_at = $this->toFormattedGlobalDateTime($order->created_at);
            $order->local_created_at = $this->toFormattedLocalDateTime($order->created_at);

            $order->is_satellite = 1;
            $order->ship_type = '';
            $order->ship_address = '';
            $order->gift_card_amount = 0;

            $specialRequests = new SpecialRequests($order->special_requests);
            $order->is_low_carb = $specialRequests->isLowCarb ? 1 : 0;
            $order->is_extra_protein = $specialRequests->isExtaProtein ? 1 : 0;
        });
        $csvExporter->build($salesTrends, [
            'id'=>'Id',
            'cart_id' => 'CartID',
            'store_location_code' => 'Location',
            'store_location_name' => 'Location Name',
            'is_cleared' => 'Cleared',
            'transaction_id' => 'OrderID',
            'cart_id' => 'CartKey',
            'first_name' => 'BFName',
            'last_name' => 'BLName',
            'email' => 'BEmail',
            'phone' => 'BDayPhone',
            'created_at' => 'DateOrderPlaced',
            'card_brand' => 'PaidWith',
            'total' => 'Total',
            'is_satellite' => 'Satellite',
            'satellite_location_id' => 'SatelliteID',
            'satellite_location_name' => 'SatelliteName',
            'cleared_at' => 'ClearedTime',
            'promo_amount' => 'Discount',
            'promo_code' => 'PromoCode',
            'tip_amount' => 'TipAmount',
            'ship_type' => 'ShipType',
            'ship_address' => 'ShipAddress',
            'gift_card_amount' => 'GiftCardAmount',
            'local_created_at' => 'localTime',
            'local_cleared_at' => 'clearedTime',
            'cart_item_quantity' => 'Qty',
            'cart_item_name' => 'MealName',
            'is_extra_protein' => 'ExtraProtein',
            'is_low_carb' => 'LowCarb',
            'id' => 'FullOrderId',
        ])->download('CE_SatelliteOrdersExport.csv');
    }

    private function ordersQuery($request)
    {
        $select = [
            'meal_plan_orders.*',
            'meal_plan_carts.id as cart_id',
            'meal_plan_carts.special_requests as special_requests',
            'meal_plan_cart_items.meal_name as cart_item_name',
            'meal_plan_cart_items.quantity as cart_item_quantity',
            'store_locations.code as store_location_code',
            'store_locations.state as store_location_state',
            'store_locations.city as store_location_city',
            'store_locations.location as store_location_location',
            'satellite_locations.name as satellite_location_name'
        ];
        $query = DB::table('meal_plan_orders')
            ->join('store_locations', 'meal_plan_orders.store_location_id', '=', 'store_locations.id')
            ->join('satellite_locations', 'meal_plan_orders.satellite_location_id', '=', 'satellite_locations.id')
            ->join('meal_plan_carts', 'meal_plan_orders.meal_plan_cart_id', '=', 'meal_plan_carts.id')
            ->join('meal_plan_cart_items', 'meal_plan_carts.id', '=', 'meal_plan_cart_items.meal_plan_cart_id')
            ->where('meal_plan_orders.store_location_id', $this->currentStoreLocation($request)->id)
            ->whereNotNull('meal_plan_orders.satellite_location_id')
            ->where('meal_plan_orders.transaction_status', MealPlanOrder::STATUS_COMPLETED)
            ->selectRaw(implode(",", $select));

        $this->bindQueryTimeframe($request, $query, $this->getDefaultSatellitesRange(), 'meal_plan_orders.created_at');

        return $query;
    }

    public function totals(Request $request)
    {
        $select = [
            'satellite_locations.name',
            'count(*) as num_orders',
            'sum(meal_plan_orders.subtotal) as subtotal'
        ];
        $query = DB::table('meal_plan_orders')
            ->join('satellite_locations', 'meal_plan_orders.satellite_location_id', '=', 'satellite_locations.id')
            ->where('meal_plan_orders.store_location_id', $this->currentStoreLocation($request)->id)
            ->whereNotNull('meal_plan_orders.satellite_location_id')
            ->where('meal_plan_orders.transaction_status', MealPlanOrder::STATUS_COMPLETED)
            ->groupBy('satellite_locations.id')
            ->orderBy('name')
            ->selectRaw(implode(",", $select));

        $this->bindQueryTimeframe($request, $query, $this->getDefaultSatellitesRange(), 'meal_plan_orders.created_at');

        $data =  $this->reportInputs($request, $this->getDefaultSatellitesRange());
        $data['satelliteLocations'] = $query->get();

        return view('store.reports.satellite-location.totals', $data);
    }

    private function getDefaultSatellitesRange()
    {
        return [$this->startOfMonthGlobalDate(), $this->todayGlobalDate()];
    }
}
