<?php

namespace App\Exports;

use App\Models\SalesReport;
use Illuminate\Support\Facades\Date;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SalesReportAchExport implements FromCollection, WithHeadings
{
    use Exportable;

    private SalesReport $salesReport;
    private $salesReportLocations;

    public function __construct(SalesReport $salesReport)
    {
        $this->salesReport = $salesReport;

        $this->salesReportLocations = $this->salesReport->salesReportLocations()
            ->with('storeLocation')->get();
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $effectiveDate = Date::now()->modify("next Thursday")->format('mdy');

        $export = $this->salesReportLocations->map(function ($salesReportLocation) use ($effectiveDate) {
            return [
                $salesReportLocation->storeLocation->locale,
                $salesReportLocation->storeLocation->ach_receiver_id,
                $salesReportLocation->storeLocation->ach_account_num,
                $salesReportLocation->storeLocation->ach_routing_num,
                round($salesReportLocation->royalties_1, 2),
                $effectiveDate,
            ];
        });

        return $export;
    }

    public function headings(): array
    {
        return [
            'Store Location',
            'Receiver ID',
            'Receiver Account Number',
            'Receiver Bank Code',
            'Receiver Amount',
            'Effective Date',
        ];
    }
}
