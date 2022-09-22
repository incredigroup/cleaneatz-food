<?php

namespace App\Exports;

use App\Models\SalesReport;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class SalesReportExport implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles, WithColumnFormatting, WithEvents
{
    use Exportable;

    private SalesReport $salesReport;
    private $salesReportLocations;
    private $salesReportLocationTotals;

    public function __construct(SalesReport $salesReport)
    {
        $this->salesReport = $salesReport;

        $locationsWithTotals = $this->salesReport->locationsWithTotals();

        $this->salesReportLocations = $locationsWithTotals['salesReportLocations'];
        $this->salesReportLocationTotals = $locationsWithTotals['salesReportLocationTotals'];
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $export = $this->salesReportLocations->map(function ($salesReportLocation) {
            return [
                $salesReportLocation->storeLocation->locale,
                $salesReportLocation->storeLocation->gateway_merchant_name,
                $salesReportLocation->storeLocation->ach_receiver_id,
                $salesReportLocation->net_sales_pos,
                $salesReportLocation->net_sales_online,
                round($salesReportLocation->net_sales_pos + $salesReportLocation->net_sales_online, 2),
                $salesReportLocation->royalties_1,
                $salesReportLocation->royalties_2,
                $salesReportLocation->royalties_3,
            ];
        });

        return $export;
    }

    public function headings(): array
    {
        return [
            ['Sales Report For Week: ' . $this->salesReport->period_num ],
            [''],
            ['Dates:'],
            [$this->salesReport->date_time_range_label],
            [''],
            [''],
            ['Location', 'Payeezy ID', 'Pinnacle ID', 'Clover Sales', 'Online Sales', 'Total Sales', 'Royalties', 'Ad Fund', 'Advisory'],
        ];
    }

    public function columnFormats(): array
    {
        return [
            'B' => NumberFormat::FORMAT_CURRENCY_USD_SIMPLE,
            'C' => NumberFormat::FORMAT_CURRENCY_USD_SIMPLE,
            'D' => NumberFormat::FORMAT_CURRENCY_USD_SIMPLE,
            'E' => NumberFormat::FORMAT_CURRENCY_USD_SIMPLE,
            'F' => NumberFormat::FORMAT_CURRENCY_USD_SIMPLE,
            'G' => NumberFormat::FORMAT_CURRENCY_USD_SIMPLE,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            7    => ['font' => ['bold' => true]],
            'A1' => ['font' => ['bold' => true]],
            'A3' => ['font' => ['bold' => true]],
            'A6' => ['font' => ['bold' => true]],
            $this->totalsRowNum() => ['font' => ['bold' => true]],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet;

                $this->setFooterCellValue($sheet, 'C', 'Totals', false);
                $this->setFooterCellValue($sheet, 'D', $this->salesReportLocationTotals->net_sales_pos);
                $this->setFooterCellValue($sheet, 'E', $this->salesReportLocationTotals->net_sales_online);
                $this->setFooterCellValue($sheet, 'F', round($this->salesReportLocationTotals->net_sales_pos +
                    $this->salesReportLocationTotals->net_sales_online, 2));
                $this->setFooterCellValue($sheet, 'G', $this->salesReportLocationTotals->royalties_1);
                $this->setFooterCellValue($sheet, 'H', $this->salesReportLocationTotals->royalties_2);
                $this->setFooterCellValue($sheet, 'I', $this->salesReportLocationTotals->royalties_3);
          },
        ];
    }

    private function setFooterCellValue($sheet, $col, $value, $isCurrency = true)
    {
        $totalsRowNum  = $this->totalsRowNum();
        $cell = $col . $totalsRowNum;

        $sheet->setCellValue($cell, $value);
        $sheet->getStyle($cell)->getFont()->setBold(true);

        if ($isCurrency) {
            $sheet->getStyle($cell)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_CURRENCY_USD_SIMPLE);
        }
    }

    private function totalsRowNum()
    {
        return $this->salesReportLocations->count() + 10;
    }
}
