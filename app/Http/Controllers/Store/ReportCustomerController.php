<?php

namespace App\Http\Controllers\Store;

use App\Models\MealPlanOrder;
use App\Traits\BuildsReports;
use App\Traits\ParsesDates;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Laracsv\Export;

class ReportCustomerController extends StoreBaseController
{
    use BuildsReports, ParsesDates;

    public function salesTrends(Request $request)
    {
        return view('store.reports.customer.sales-trends', $this->reportInputs(
            $request, $this->getDefaultSalesTrendsRange())
        );
    }

    public function salesTrendsData(Request $request)
    {
        return datatables()->of($this->byCustomerQuery($request))
            ->editColumn('last_order_at', function ($salesTrend) {
                return $this->toFormattedLocalDate($salesTrend->last_order_at);
            })
            ->editColumn('num_purchases', function ($salesTrend) {
                return number_format($salesTrend->num_purchases);
            })
            ->editColumn('total_spend', function ($salesTrend) {
                return '$' . number_format($salesTrend->total_spend, 2);
            })
            ->toJson();
    }

    public function salesTrendsExport(Request $request)
    {
        $salesTrends = $this->byCustomerQuery($request)
            ->orderBy('total_spend', 'desc')
            ->get();

        $csvExporter = new Export();
        $csvExporter->beforeEach(function ($salesTrend) {
            $salesTrend->last_order_at = $this->toFormattedLocalDate($salesTrend->last_order_at);
        });
        $csvExporter->build($salesTrends, [
            'email',
            'first_name' => 'fname',
            'last_name' => 'lname',
            'last_order_at' => 'lastOrder',
            'num_purchases' => 'OrderCount',
            'total_spend' => 'orderTotals',
        ])->download('CE_Orders_Export.csv');
    }

    private function byCustomerQuery($request)
    {
        $query = DB::table('meal_plan_orders')
            ->where('meal_plan_orders.store_location_id', $this->currentStoreLocation($request)->id)
            ->where('meal_plan_orders.transaction_status', MealPlanOrder::STATUS_COMPLETED)
            ->groupBy('meal_plan_orders.last_name')
            ->groupBy('meal_plan_orders.first_name')
            ->groupBy('meal_plan_orders.email')
            ->selectRaw('first_name, last_name, email, max(created_at) as last_order_at, count(*) as num_purchases, sum(total) as total_spend');

        $this->bindQueryTimeframe($request, $query, $this->getDefaultSalesTrendsRange());

        return $query;
    }

    private function getDefaultSalesTrendsRange()
    {
        return [$this->startOfMonthGlobalDate(), $this->todayGlobalDate()];
    }
}
