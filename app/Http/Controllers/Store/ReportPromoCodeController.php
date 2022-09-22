<?php

namespace App\Http\Controllers\Store;

use App\Models\MealPlanOrder;
use App\Traits\BuildsReports;
use App\Traits\ParsesDates;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportPromoCodeController extends StoreBaseController
{
    use BuildsReports, ParsesDates;

    public function uses(Request $request)
    {
        $promoCodes = $this->usesQuery($request)->get();

        $params = $this->reportInputs(
            $request, $this->getDefaultSalesTrendsRange());
        $params['promoCodes'] = $promoCodes;

        return view('store.reports.promo-code.uses', $params);
    }

    private function usesQuery($request)
    {
        $select = [
            'meal_plan_orders.promo_code as promo_code',
            'sum(meal_plan_orders.promo_amount) as amount',
            'count(*) as num_orders'
        ];
        $query = DB::table('meal_plan_orders')
            ->where('meal_plan_orders.store_location_id', $this->currentStoreLocation($request)->id)
            ->where('meal_plan_orders.transaction_status', MealPlanOrder::STATUS_COMPLETED)
            ->whereNotNull(('meal_plan_orders.promo_code'))
            ->groupBy('meal_plan_orders.promo_code')
            ->orderBy('promo_code', 'asc')
            ->selectRaw(implode(',', $select));

        $this->bindQueryTimeframe($request, $query, $this->getDefaultSalesTrendsRange());

        return $query;
    }

    private function getDefaultSalesTrendsRange()
    {
        return [$this->startOfMonthGlobalDate(), $this->todayGlobalDate()];
    }
}
