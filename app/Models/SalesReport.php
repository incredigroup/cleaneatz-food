<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class SalesReport extends Model
{
    protected $fillable = [
        'period_num',
        'starts_at',
        'ends_at',
        'net_sales_pos',
        'net_sales_online',
        'royalties',
        'tips_pos',
        'discounts_pos',
        'sales_tax_pos',
        'tips_online',
        'discounts_online',
        'sales_tax_online',
        'royalty_meta',
        'num_locations',
    ];

    protected $dates = ['starts_at', 'ends_at'];

    protected $casts = [
        'net_sales_pos' => 'float',
        'net_sales_online' => 'float',
        'royalties' => 'float',
        'tips_pos' => 'float',
        'discounts_pos' => 'float',
        'sales_tax_pos' => 'float',
        'tips_online' => 'float',
        'discounts_online' => 'float',
        'sales_tax_online' => 'float',
        'royalty_meta' => 'array',
        'num_locations' => 'integer',
    ];

    public static $timezone = 'America/New_York';

    protected static function booted()
    {
        static::deleting(function ($salesReport) {
            $salesReport->salesReportLocations()->delete();
            $salesReport->salesReportLocationCategories()->delete();
        });
    }

    public function salesReportLocations()
    {
        return $this->hasMany(SalesReportLocation::class);
    }

    public function salesReportLocationCategories()
    {
        return $this->hasMany(SalesReportLocationCategory::class);
    }

    public function getReportDateAttribute($value)
    {
        return $this->created_at->setTimezone(self::$timezone)->toFormattedDateString();
    }

    public function getDateRangeLabelAttribute($value)
    {
        return $this->starts_at->setTimezone(self::$timezone)->toFormattedDateString() .
            ' - ' .
            $this->ends_at->setTimezone(self::$timezone)->toFormattedDateString();
    }

    public function getDateTimeRangeLabelAttribute()
    {
        return $this->starts_at->setTimezone(SalesReport::$timezone)->format('M j, Y h:i:s A') .
            ' - ' .
            $this->ends_at->setTimezone(SalesReport::$timezone)->format('M j, Y h:i:s A');
    }

    public function getNetSalesAttribute($value)
    {
        return $this->net_sales_pos + $this->net_sales_online;
    }

    public function recalc()
    {
        $select = [
            'sum(net_sales_pos) as net_sales_pos',
            'sum(net_sales_online) as net_sales_online',
            'count(*) as num_locations',
            'sum(royalties_1) as royalties',
        ];

        $totals = DB::table('sales_report_locations')
            ->where('sales_report_id', $this->id)
            ->selectRaw(implode(',', $select))
            ->first();

        $this->update((array) $totals);
    }

    public function calculateRoyaltiesFor(float $netSales, int $feeType, int $feeTier): float
    {
        $royaltyMeta = $this->royalty_meta['fee_tier_amounts'];

        if (!isset($royaltyMeta[$feeTier - 1][$feeType - 1])) {
            return 0;
        }

        $rate = $royaltyMeta[$feeTier - 1][$feeType - 1];

        return round($netSales * ($rate / 100), 2);
    }

    public function locationsWithTotals($sortBy = 'tier')
    {
        $salesReportLocations = $this->salesReportLocations()
            ->with([
                'storeLocation' => function ($query) {
                    $query->withTrashed();
                },
            ])
            ->get()
            ->sortBy([
                fn($a, $b) => $a->royalty_tier <=> $b->royalty_tier,
                fn($a, $b) => $a->storeLocation->state <=> $b->storeLocation->state,
                fn($a, $b) => $a->storeLocation->city <=> $b->storeLocation->city,
            ]);

        $salesReportLocationTotals = $salesReportLocations->pipe(function ($collection) {
            return (object) [
                'net_sales_pos' => $collection->sum('net_sales_pos'),
                'net_sales_online' => $collection->sum('net_sales_online'),
                'royalties_1' => $collection->sum('royalties_1'),
                'royalties_2' => $collection->sum('royalties_2'),
                'royalties_3' => $collection->sum('royalties_3'),
            ];
        });

        return compact('salesReportLocations', 'salesReportLocationTotals');
    }
}
