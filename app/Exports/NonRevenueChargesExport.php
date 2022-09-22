<?php

namespace App\Exports;

use App\Models\StoreLocation;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class NonRevenueChargesExport implements
    FromCollection,
    WithMapping,
    WithColumnFormatting,
    WithHeadings,
    ShouldAutoSize
{
    protected $allStoreLocationTotals;

    public function __construct($allStoreLocationTotals)
    {
        $this->allStoreLocationTotals = $allStoreLocationTotals;
    }

    public function collection()
    {
        return $this->allStoreLocationTotals;
    }

    public function map($storeLocationTotals): array
    {
        return [
            StoreLocation::buildNameFromObject($storeLocationTotals),
            $storeLocationTotals->gateway_merchant_name,
            $storeLocationTotals->tip_amount,
            $storeLocationTotals->tax,
            $storeLocationTotals->total,
        ];
    }

    public function headings(): array
    {
        return ['Store', 'Payeezy ID', 'Tips', 'Sales Tax', 'Totals'];
    }

    public function columnFormats(): array
    {
        return [
            'C' => NumberFormat::FORMAT_CURRENCY_USD_SIMPLE,
            'D' => NumberFormat::FORMAT_CURRENCY_USD_SIMPLE,
            'E' => NumberFormat::FORMAT_CURRENCY_USD_SIMPLE,
        ];
    }
}
