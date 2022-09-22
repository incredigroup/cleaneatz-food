<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalesReportLocation extends Model
{
    protected $fillable = [
        'sales_report_id',
        'store_location_id',
        'net_sales_pos',
        'tips_pos',
        'discounts_pos',
        'sales_tax_pos',
        'non_revenue_pos',
        'net_sales_refunds_pos',
        'tips_refunds_pos',
        'sales_tax_refunds_pos',
        'net_sales_online',
        'tips_online',
        'discounts_online',
        'sales_tax_online',
        'net_sales_refunds_online',
        'royalty_tier',
        'royalties_1',
        'royalties_2',
        'royalties_3',
    ];

    protected $casts = [
        'net_sales_pos' => 'float',
        'tips_pos' => 'float',
        'discounts_pos' => 'float',
        'sales_tax_pos' => 'float',
        'non_revenue_pos' => 'float',
        'net_sales_refunds_pos' => 'float',
        'tips_refunds_pos' => 'float',
        'sales_tax_refunds_pos' => 'float',
        'net_sales_online' => 'float',
        'tips_online' => 'float',
        'discounts_online' => 'float',
        'sales_tax_online' => 'float',
        'net_sales_refunds_online' => 'float',
        'royalties_1' => 'float',
        'royalty_tier' => 'integer',
        'royalties_2' => 'float',
        'royalties_3' => 'float',
    ];

    public $timestamps = false;

    public function storeLocation()
    {
        return $this->belongsTo(StoreLocation::class);
    }
}
