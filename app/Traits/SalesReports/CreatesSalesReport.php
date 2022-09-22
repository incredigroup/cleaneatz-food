<?php

namespace App\Traits\SalesReports;

use App\Helpers\Calculators\CloverSalesDataCalculator;
use App\Helpers\Calculators\OnlineSalesDataCalculator;
use App\Helpers\Calculators\SalesDataCalculator;
use App\Models\RoyaltyMeta;
use App\Models\SalesReport;
use App\Models\SalesReportLocation;
use App\Models\SalesReportLocationCategory;
use App\Models\StoreLocation;

trait CreatesSalesReport
{
    public static function createReport(string $startOn): SalesReport
    {
        $cloverSalesDataCalculator = new CloverSalesDataCalculator($startOn);
        $onlineSalesDataCalculator = new OnlineSalesDataCalculator($startOn);

        $locationsWithOrders = $cloverSalesDataCalculator
            ->locationIds()
            ->merge($onlineSalesDataCalculator->locationIds())
            ->unique();

        $salesReport = SalesReport::create([
            'period_num' => $cloverSalesDataCalculator->endsAt->weekOfYear,
            'starts_at' => $cloverSalesDataCalculator->startsAt,
            'ends_at' => $cloverSalesDataCalculator->endsAt,
            'royalty_meta' => RoyaltyMeta::get(),
            'num_locations' => $locationsWithOrders->count(),
        ]);

        $locationsWithOrders->each(function ($storeLocationId) use (
            $salesReport,
            $cloverSalesDataCalculator,
            $onlineSalesDataCalculator,
        ) {
            $storeLocation = StoreLocation::withTrashed()->find($storeLocationId);

            $salesReportLocation = self::createLocationRecordsFor(
                $salesReport,
                $storeLocation,
                $cloverSalesDataCalculator,
                $onlineSalesDataCalculator,
            );

            self::createLocationCategoryRecordsFor(
                $salesReport,
                $salesReportLocation,
                $cloverSalesDataCalculator,
            );

            self::createLocationCategoryRecordsFor(
                $salesReport,
                $salesReportLocation,
                $onlineSalesDataCalculator,
                true,
            );
        });

        $salesReport->recalc();

        return $salesReport;
    }

    private static function createLocationRecordsFor(
        SalesReport $salesReport,
        StoreLocation $storeLocation,
        CloverSalesDataCalculator $cloverSalesDataCalculator,
        OnlineSalesDataCalculator $onlineSalesDataCalculator,
    ): SalesReportLocation {
        $cloverTotals = $cloverSalesDataCalculator->totals($storeLocation->id);
        $onlineTotals = $onlineSalesDataCalculator->totals($storeLocation->id);

        $netSales = $cloverTotals->net_sales + $onlineTotals->net_sales;

        $royalties = $salesReport->calculateRoyaltiesFor(
            $netSales,
            1,
            $storeLocation->royalty_tier,
        );
        $adFund = $salesReport->calculateRoyaltiesFor($netSales, 2, $storeLocation->royalty_tier);
        $advisoryFee = $salesReport->calculateRoyaltiesFor(
            $royalties,
            3,
            $storeLocation->royalty_tier,
        );

        $salesReportLocation = $salesReport->salesReportLocations()->save(
            new SalesReportLocation([
                'store_location_id' => $storeLocation->id,
                'net_sales_pos' => $cloverTotals->net_sales,
                'tips_pos' => $cloverTotals->tips,
                'discounts_pos' => $cloverTotals->discounts,
                'sales_tax_pos' => $cloverTotals->sales_tax,
                'non_revenue_pos' => $cloverTotals->non_revenue,
                'net_sales_refunds_pos' => $cloverTotals->net_sales_refunds,
                'tips_refunds_pos' => $cloverTotals->tips_refunds,
                'sales_tax_refunds_pos' => $cloverTotals->sales_tax_refunds,
                'net_sales_online' => $onlineTotals->net_sales,
                'tips_online' => $onlineTotals->tips,
                'discounts_online' => $onlineTotals->discounts,
                'sales_tax_online' => $onlineTotals->sales_tax,
                'net_sales_refunds_online' => $onlineTotals->net_sales_refunds,
                'royalty_tier' => $storeLocation->royalty_tier,
                'royalties_1' => $royalties,
                'royalties_2' => $adFund,
                'royalties_3' => $advisoryFee,
            ]),
        );

        return $salesReportLocation;
    }

    private static function createLocationCategoryRecordsFor(
        SalesReport $salesReport,
        SalesReportLocation $salesReportLocation,
        SalesDataCalculator $salesDataCalculator,
        $updateIfExists = false,
    ): void {
        $totals = $salesDataCalculator->categoryTotals($salesReportLocation->store_location_id);

        $totals->each(function ($totalsForCategory) use (
            $salesReport,
            $salesReportLocation,
            $updateIfExists,
        ) {
            if (
                $updateIfExists &&
                self::updateCategoryIfExists($salesReport, $salesReportLocation, $totalsForCategory)
            ) {
                return true;
            }

            $salesReport->salesReportLocationCategories()->save(
                new SalesReportLocationCategory([
                    'sales_report_id' => $salesReport->id,
                    'sales_report_location_id' => $salesReportLocation->id,
                    'category' => $totalsForCategory->category,
                    'net_sales' => $totalsForCategory->net_sales,
                    'quantity' => 0,
                ]),
            );
        });
    }

    private static function updateCategoryIfExists(
        SalesReport $salesReport,
        SalesReportLocation $salesReportLocation,
        $totalsForCategory,
    ) {
        $existing = SalesReportLocationCategory::where('sales_report_id', $salesReport->id)
            ->where('sales_report_location_id', $salesReportLocation->id)
            ->where('category', $totalsForCategory->category)
            ->first();

        if ($existing) {
            $existing->update([
                'net_sales' => $existing->net_sales + $totalsForCategory->net_sales,
                'quantity' => 0,
            ]);

            return true;
        }

        return false;
    }
}
