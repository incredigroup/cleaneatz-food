<?php

namespace App\Http\Controllers\Admin\Reports;

use App\Exports\SalesReportAchExport;
use App\Exports\SalesReportExport;
use App\Http\Controllers\Controller;
use App\Models\CloverOrder;
use App\Models\SalesReport;
use App\Traits\SalesReports\CreatesSalesReport;
use App\Traits\SalesReports\ListsPendingSalesReportPeriods;
use Illuminate\Support\Facades\Gate;

class SalesReportController extends Controller
{
    use ListsPendingSalesReportPeriods, CreatesSalesReport;

    public function index()
    {
        Gate::authorize('view-sales-reports');

        $lastCloverOrderAt = CloverOrder::firstOrder()
            ->order_created_at
            ->endOfDay()
            ->setTimezone(SalesReport::$timezone)
            ->format('M j, Y h:i:s A') . ' EST';

        return view('admin.reports.sales-reports.index', [
            'salesReports' => SalesReport::orderBy('starts_at', 'desc')->limit(20)->get(),
            'weekOptions' => self::pendingSalesReportPeriods(),
            'lastCloverOrderAt' => $lastCloverOrderAt,
        ]);
    }

    public function view(SalesReport $salesReport)
    {
        Gate::authorize('view-sales-reports');

        $params = array_merge(compact('salesReport'), $salesReport->locationsWithTotals());

        return view('admin.reports.sales-reports.view', $params);
    }

    public function create($startOn)
    {
        Gate::authorize('view-sales-reports');

        $salesReport = self::createReport($startOn);

        return redirect()->route('admin.sales-reports.view', [$salesReport]);
    }

    public function exportExcel(SalesReport $salesReport)
    {
        Gate::authorize('view-sales-reports');

        return (new SalesReportExport($salesReport))->download('sales-report.xlsx');
    }

    public function exportACH(SalesReport $salesReport)
    {
        Gate::authorize('view-sales-reports');

        return (new SalesReportAchExport($salesReport))->download("Royalties Rock {$salesReport->period_num}.csv");
    }

    public function delete(SalesReport $salesReport)
    {
        Gate::authorize('view-sales-reports');

        $salesReport->delete();

        return redirect()->route('admin.reports.sales-reports');
    }
}
