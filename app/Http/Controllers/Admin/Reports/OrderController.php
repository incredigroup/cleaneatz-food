<?php

namespace App\Http\Controllers\Admin\Reports;

use App\Exports\NonRevenueChargesExport;
use App\Http\Controllers\Controller;
use App\Models\MealPlanOrder;
use App\Models\MealPlanRefund;
use App\Traits\BuildsReports;
use App\Traits\ParsesDates;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class OrderController extends Controller
{
    use BuildsReports, ParsesDates;

    public function bySatelliteLocation(Request $request)
    {
        $data = $this->recentMealPlanSelection($request);

        $select = [
            'store_locations.state',
            'store_locations.city',
            'store_locations.location',
            'satellite_locations.name as satellite_location_name',
            'count(*) as num_orders, sum(meal_plan_orders.subtotal) as subtotal',
        ];
        $data['satelliteLocations'] = DB::table('meal_plan_orders')
            ->join(
                'meal_plan_carts',
                'meal_plan_carts.id',
                '=',
                'meal_plan_orders.meal_plan_cart_id',
            )
            ->join(
                'satellite_locations',
                'satellite_locations.id',
                '=',
                'meal_plan_carts.satellite_location_id',
            )
            ->join(
                'store_locations',
                'store_locations.id',
                '=',
                'meal_plan_carts.store_location_id',
            )
            ->where('meal_plan_carts.meal_plan_id', $data['mealPlan']['id'])
            ->where('meal_plan_orders.transaction_status', MealPlanOrder::STATUS_COMPLETED)
            ->groupBy(
                'store_locations.state',
                'store_locations.city',
                'store_locations.location',
                'satellite_locations.id',
            )
            ->orderBy('store_locations.state')
            ->orderBy('store_locations.city')
            ->orderBy('store_locations.location')
            ->orderBy('satellite_locations.name')
            ->selectRaw(implode(',', $select))
            ->get();

        return view('admin.reports.orders.by-satellite-location', $data);
    }

    public function nonRevenueCharges(Request $request)
    {
        $params = $this->reportInputs($request, $this->getDefaultSalesTrendsRange());

        $params['storeLocationTotals'] = $this->nonRevenueChargesFor($request);

        return view('admin.reports.orders.non-revenue-charges', $params);
    }

    public function nonRevenueChargesExport(Request $request)
    {
        $export = new NonRevenueChargesExport($this->nonRevenueChargesFor($request));

        $params = $this->reportInputs($request, $this->getDefaultSalesTrendsRange());

        $filename =
            'non-revenue-charges_' . $params['activeStart'] . '-' . $params['activeEnd'] . '.xlsx';
        return Excel::download($export, $filename);
    }

    private function nonRevenueChargesFor($request)
    {
        $select = [
            'store_locations.id as store_location_id',
            'store_locations.state',
            'store_locations.city',
            'store_locations.location',
            'store_locations.gateway_merchant_name',
            'sum(tip_amount) as tip_amount',
            'sum(tax) as tax',
        ];

        $query = MealPlanOrder::leftJoin(
            'store_locations',
            'meal_plan_orders.store_location_id',
            '=',
            'store_locations.id',
        )
            ->paidOnline()
            ->groupBy('meal_plan_orders.store_location_id')
            ->orderBy('store_locations.state', 'asc')
            ->orderBy('store_locations.city', 'asc')
            ->orderBy('store_locations.location', 'asc')
            ->selectRaw(implode(',', $select));

        $this->bindQueryTimeframe(
            $request,
            $query,
            $this->getDefaultSalesTrendsRange(),
            'meal_plan_orders.created_at',
        );

        $charges = $query->get();

        $refunds = $this->nonRevenueChargeRefundsFor($request);

        $charges->each(function ($charge) use ($refunds) {
            $refund = $refunds->firstWhere('store_location_id', $charge->store_location_id);

            if ($refund) {
                $charge['tip_amount'] -= $refund['tip_amount'];
                $charge['tax'] -= $refund['tax'];
                $charge['refund_total'] = $refund['tip_amount'] + $refund['tax'];
            }

            $charge['total'] = $charge['tip_amount'] + $charge['tax'];
        });

        return $charges;
    }

    private function nonRevenueChargeRefundsFor($request)
    {
        $select = [
            'meal_plan_orders.store_location_id',
            'sum(meal_plan_refunds.tip_amount) as tip_amount',
            'sum(meal_plan_refunds.tax) as tax',
        ];

        $query = MealPlanRefund::join(
            'meal_plan_orders',
            'meal_plan_refunds.meal_plan_order_id',
            '=',
            'meal_plan_orders.id',
        )
            ->groupBy('meal_plan_orders.store_location_id')
            ->selectRaw(implode(',', $select));

        $this->bindQueryTimeframe(
            $request,
            $query,
            $this->getDefaultSalesTrendsRange(),
            'meal_plan_refunds.created_at',
        );

        return $query->get();
    }

    private function getDefaultSalesTrendsRange()
    {
        return [$this->startOfMonthGlobalDate(), $this->todayGlobalDate()];
    }
}
