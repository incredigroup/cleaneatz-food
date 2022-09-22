<?php

namespace App\Http\Controllers\Admin\Reports;

use App\Http\Controllers\Controller;
use App\Models\MealPlanOrder;
use Illuminate\Support\Facades\DB;

class PromoCodeController extends Controller
{
    public function promoCodeUses()
    {
        return view('admin.reports.promo-code.promo-code-uses');
    }

    public function promoCodeUsesData()
    {
        return datatables()
            ->of($this->promoCodeUsesQuery())
            ->editColumn('amount', function ($row) {
                return empty($row->amount) ?
                    '' : '$' . number_format($row->amount, 2);
            })
            ->editColumn('num_orders', function ($row) {
                return empty($row->num_orders) ?
                    '' : number_format($row->num_orders);
            })
            ->toJson();
    }

    public function promoCodeUsesQuery()
    {
        $select = [
            'promo_codes.name',
            'promo_codes.code',
            'sum(meal_plan_orders.promo_amount) as amount',
            'count(*) as num_orders'
        ];
        return DB::table('meal_plan_orders')
            ->join('meal_plan_carts', 'meal_plan_carts.id', '=', 'meal_plan_orders.meal_plan_cart_id')
            ->join('promo_codes', 'promo_codes.id', '=', 'meal_plan_carts.promo_code_id')
            ->where('meal_plan_orders.transaction_status', MealPlanOrder::STATUS_COMPLETED)
            ->whereNull('promo_codes.store_location_id')
            ->groupBy('promo_codes.id')
            ->selectRaw(implode(',', $select));
    }
}
