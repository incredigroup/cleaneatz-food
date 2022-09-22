<?php

namespace App\Http\Controllers\Admin\Reports;

use App\Http\Controllers\Controller;
use App\Models\MealPlan;
use App\Models\MealPlanOrder;
use App\Models\StoreLocation;
use App\Traits\BuildsReports;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Laracsv\Export;

class MealPlanController extends Controller
{
    use BuildsReports;

    public function mealPlanTally(Request $request)
    {
        return view(
            'admin.reports.meal-plan.meal-plan-tally',
            $this->recentMealPlanSelection($request),
        );
    }

    public function mealPlanTallyData(Request $request)
    {
        return datatables()
            ->of($this->getMealPlanTallyQuery($request->get('mealPlanId')))
            ->editColumn('city', function ($row) {
                return empty($row->city)
                    ? ''
                    : StoreLocation::buildName($row->state, $row->city, $row->location);
            })
            ->editColumn('quantity', function ($row) {
                return empty($row->quantity) ? '' : number_format($row->quantity);
            })
            ->editColumn('subtotal', function ($row) {
                return empty($row->subtotal) ? '' : '$' . number_format($row->subtotal, 2);
            })
            ->toJson();
    }

    public function mealPlanTallyExport(Request $request)
    {
        $report = $this->getMealPlanTallyQuery($request->get('mealPlanId'))
            ->orderBy('state')
            ->orderBy('city')
            ->orderBy('location')
            ->orderBy('meal_name')
            ->get();

        $csvExporter = new Export();
        $csvExporter->beforeEach(function ($row) {
            $row->city = StoreLocation::buildName($row->state, $row->city, $row->location);
            $row->subtotal = round($row->subtotal, 2);
        });
        $csvExporter
            ->build($report, [
                'city' => 'LocationName',
                'meal_name' => 'MealName',
                'quantity' => 'MealsSold',
                'subtotal' => 'Subtotal',
            ])
            ->download('CE_MenuReportExport.csv');
    }

    public function getMealPlanTallyQuery($mealPlanId)
    {
        $select = [
            'store_locations.city',
            'store_locations.state',
            'store_locations.location',
            'meal_plan_cart_items.meal_name',
            'sum(meal_plan_cart_items.quantity) as quantity',
            'sum(meal_plan_cart_items.quantity * meal_plan_cart_items.cost) as subtotal',
        ];
        return DB::table('meal_plan_orders')
            ->join(
                'meal_plan_carts',
                'meal_plan_carts.id',
                '=',
                'meal_plan_orders.meal_plan_cart_id',
            )
            ->join(
                'meal_plan_cart_items',
                'meal_plan_carts.id',
                '=',
                'meal_plan_cart_items.meal_plan_cart_id',
            )
            ->join(
                'store_locations',
                'store_locations.id',
                '=',
                'meal_plan_carts.store_location_id',
            )
            ->where('meal_plan_carts.meal_plan_id', $mealPlanId)
            ->where('meal_plan_orders.transaction_status', MealPlanOrder::STATUS_COMPLETED)
            ->groupBy('meal_plan_carts.store_location_id')
            ->groupBy('meal_plan_cart_items.meal_name')
            ->selectRaw(implode(',', $select));
    }

    public function mealPlanTallySummary(Request $request)
    {
        $data = $this->recentMealPlanSelection($request);

        $select = [
            'meal_plan_cart_items.meal_name',
            'sum(meal_plan_cart_items.quantity) as quantity',
            'sum(meal_plan_cart_items.quantity * meal_plan_cart_items.cost) as subtotal',
        ];
        $data['mealTotals'] = DB::table('meal_plan_orders')
            ->join(
                'meal_plan_carts',
                'meal_plan_carts.id',
                '=',
                'meal_plan_orders.meal_plan_cart_id',
            )
            ->join(
                'meal_plan_cart_items',
                'meal_plan_carts.id',
                '=',
                'meal_plan_cart_items.meal_plan_cart_id',
            )
            ->where('meal_plan_cart_items.meal_name', '!=', 'Custom Meal')
            ->where('meal_plan_carts.meal_plan_id', $data['mealPlan']['id'])
            ->where('meal_plan_orders.transaction_status', MealPlanOrder::STATUS_COMPLETED)
            ->groupBy('meal_plan_cart_items.meal_name')
            ->selectRaw(implode(',', $select))
            ->get();

        $data['totalSales'] = $data['mealTotals']->sum('subtotal');

        return view('admin.reports.meal-plan.meal-plan-tally-summary', $data);
    }

    public function specialRequestSummary(Request $request)
    {
        $data = $this->recentMealPlanSelection($request);

        $select = [
            'sum(meal_plan_cart_items.quantity) as quantity',
            'sum(meal_plan_cart_items.quantity * meal_plan_cart_items.cost) as subtotal',
        ];

        $data['optionTotals'] = [];
        foreach (MealPlan::$requestOptions as $specialRequestOption) {
            $optionTotals = (array) $this->buildSpecialRequestTotals(
                $data['mealPlan']['id'],
                $specialRequestOption,
            );
            $optionTotals['name'] = Str::of($specialRequestOption)
                ->snake()
                ->replace('_', ' ')
                ->title();
            $data['optionTotals'][] = (object) $optionTotals;
        }

        $data['totalSales'] = $this->buildSpecialRequestTotals($data['mealPlan']['id'])->subtotal;

        return view('admin.reports.meal-plan.special-request-summary', $data);
    }

    private function buildSpecialRequestTotals($mealPlanId, $specialRequest = null)
    {
        $select = [
            'sum(meal_plan_cart_items.quantity) as quantity',
            'sum(meal_plan_cart_items.quantity * meal_plan_cart_items.cost) as subtotal',
        ];

        $query = DB::table('meal_plan_orders')
            ->join(
                'meal_plan_carts',
                'meal_plan_carts.id',
                '=',
                'meal_plan_orders.meal_plan_cart_id',
            )
            ->join(
                'meal_plan_cart_items',
                'meal_plan_carts.id',
                '=',
                'meal_plan_cart_items.meal_plan_cart_id',
            )
            ->where('meal_plan_cart_items.meal_name', '!=', 'Custom Meal')
            ->where('meal_plan_carts.meal_plan_id', $mealPlanId)
            ->where('meal_plan_orders.transaction_status', MealPlanOrder::STATUS_COMPLETED)
            ->selectRaw(implode(',', $select));

        if ($specialRequest) {
            $query->where('meal_plan_carts.special_requests->' . $specialRequest, 'true');
        }

        return $query->first();
    }

    public function customersByMealPlan(Request $request)
    {
        return view(
            'admin.reports.meal-plan.customers-by-meal-plan',
            $this->recentMealPlanSelection($request),
        );
    }

    public function customersByMealPlanData(Request $request)
    {
        return datatables()
            ->of($this->getMealPlanCustomersQuery($request->get('mealPlanId')))
            ->editColumn('city', function ($row) {
                return empty($row->city)
                    ? ''
                    : StoreLocation::buildName($row->state, $row->city, $row->location);
            })
            ->toJson();
    }

    public function customersByMealPlanExport(Request $request)
    {
        $customers = $this->getMealPlanCustomersQuery($request->get('mealPlanId'))
            ->orderBy('email')
            ->get();

        $csvExporter = new Export();
        $csvExporter->beforeEach(function ($user) {
            $user->city = empty($user->city)
                ? ''
                : StoreLocation::buildName($user->state, $user->city, $user->location);
        });

        $csvExporter
            ->build($customers, ['email', 'first_name', 'last_name', 'city'])
            ->download('CE_Customers_Export.csv');
    }

    public function getMealPlanCustomersQuery($mealPlanId)
    {
        return DB::table('meal_plan_orders')
            ->join(
                'meal_plan_carts',
                'meal_plan_orders.meal_plan_cart_id',
                '=',
                'meal_plan_carts.id',
            )
            ->join(
                'store_locations',
                'store_locations.id',
                '=',
                'meal_plan_carts.store_location_id',
            )
            ->where('meal_plan_carts.meal_plan_id', $mealPlanId)
            ->where('meal_plan_orders.transaction_status', MealPlanOrder::STATUS_COMPLETED)
            ->distinct()
            ->select(
                'meal_plan_orders.first_name',
                'meal_plan_orders.last_name',
                'meal_plan_orders.email',
                'store_locations.state',
                'store_locations.city',
                'store_locations.location',
            );
    }
}
