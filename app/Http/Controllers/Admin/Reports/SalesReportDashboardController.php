<?php

namespace App\Http\Controllers\Admin\Reports;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;

class SalesReportDashboardController extends Controller
{
    public function categories(Request $request)
    {
        $storeLocationId = $request->get('storeLocationId');

        return view('admin.reports.sales-report-dashboard.categories', compact('storeLocationId'));
    }

    public function categoriesData()
    {
        $select = ['sales_report_location_categories.category'];
        $categories = DB::table('sales_reports')
            ->join(
                'sales_report_location_categories',
                'sales_report_location_categories.sales_report_id',
                '=',
                'sales_reports.id',
            )
            ->orderBy('sales_report_location_categories.category')
            ->groupBy('sales_report_location_categories.category')
            ->selectRaw(implode(',', $select))
            ->pluck('category');

        return response()->json($categories);
    }

    public function chartData(Request $request)
    {
        $startAt = Date::parse('2021-01-01')
            ->setTimezone('UTC')
            ->startOfDay();
        $endAt = Date::now()->setTimezone('UTC');

        $select = [
            'sales_reports.id',
            'sales_reports.starts_at',
            'sum(sales_report_location_categories.net_sales) as net_sales',
        ];
        $salesTotalsQuery = DB::table('sales_reports')
            ->join(
                'sales_report_locations',
                'sales_reports.id',
                '=',
                'sales_report_locations.sales_report_id',
            )
            ->join(
                'sales_report_location_categories',
                'sales_report_locations.id',
                '=',
                'sales_report_location_categories.sales_report_location_id',
            )
            ->where('sales_reports.starts_at', '>=', $startAt)
            ->where('sales_reports.starts_at', '<=', $endAt)
            ->orderBy('sales_reports.starts_at')
            ->groupBy('sales_reports.id')
            ->selectRaw(implode(',', $select));

        if ($request->has('storeLocationId')) {
            $salesTotalsQuery = $salesTotalsQuery->where(
                'sales_report_locations.store_location_id',
                $request->get('storeLocationId'),
            );
        }

        if ($request->has('categories')) {
            $salesTotalsQuery = $salesTotalsQuery->whereIn(
                'sales_report_location_categories.category',
                explode('|', $request->get('categories')),
            );
        }

        $salesTotals = $salesTotalsQuery->get();

        return response()->json($salesTotals, 200, [], JSON_NUMERIC_CHECK);
    }
}
