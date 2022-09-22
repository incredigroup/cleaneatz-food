<?php

namespace App\Traits\SalesReports;

use App\Helpers\Calculators\CloverSalesDataCalculator;
use App\Models\CloverOrder;
use App\Models\SalesReport;
use Exception;
use Illuminate\Support\Facades\Date;

trait ListsPendingSalesReportPeriods
{
    public static function pendingSalesReportPeriods()
    {
        try {
            $lastPossibleStartDate = self::lastPossibleStartDate();
            $firstPossibleStartDate = self::firstPossibleStartDate($lastPossibleStartDate);

            $startDateOptions = [];
            $startOfWeek = $lastPossibleStartDate;
            while ($startOfWeek->greaterThanOrEqualTo($firstPossibleStartDate)) {
                $startDateOptions[] = $startOfWeek;
                $startOfWeek = $startOfWeek->subWeek();
            }

            $dateOptions = collect($startDateOptions)->map(function ($startDateOption) {
                $localStartDate = $startDateOption;
                $localEndDate = $startDateOption->addDays(7)->subSecond();

                return [
                    'startOn' => $localStartDate->toDateString(),
                    'endOn' => $localEndDate->toDateString(),
                    'label' =>
                        $localStartDate->toFormattedDateString() .
                        ' - ' .
                        $localEndDate->toFormattedDateString(),
                ];
            });

            return $dateOptions;
        } catch (Exception $e) {
            return [];
        }
    }

    private static function lastPossibleStartDate()
    {
        $mostRecentCloverOrder = CloverOrder::firstOrder();

        if (!$mostRecentCloverOrder) {
            throw new Exception('No Clover data is available.');
        }

        $startOfWeek = Date::now()
            ->setTimezone(SalesReport::$timezone)
            ->startOfWeek(CloverSalesDataCalculator::$weekStartDayNum)
            ->setTime(CloverSalesDataCalculator::$weekStartDayHour, 0, 0);

        while (
            $startOfWeek->greaterThan(
                $mostRecentCloverOrder->order_created_at->setTimezone(SalesReport::$timezone),
            )
        ) {
            $startOfWeek = $startOfWeek->subWeek();
        }

        return $startOfWeek;
    }

    private static function firstPossibleStartDate($startOfWeek)
    {
        $firstCloverOrder = CloverOrder::firstOrder(false);
        if (!$firstCloverOrder) {
            throw new Exception('No Clover data is available.');
        }

        $mostRecentSalesReport = SalesReport::orderBy('id', 'desc')->first();

        // The earliest start of week for which we have Clover orders and have not yet created a sales report...
        while (
            $startOfWeek->greaterThan(
                $firstCloverOrder->order_created_at->setTimezone(SalesReport::$timezone),
            ) &&
            (!$mostRecentSalesReport ||
                $startOfWeek->greaterThan(
                    $mostRecentSalesReport->ends_at->setTimezone(SalesReport::$timezone),
                ))
        ) {
            $startOfWeek = $startOfWeek->subWeek();
        }

        return $startOfWeek->addWeek();
    }
}
