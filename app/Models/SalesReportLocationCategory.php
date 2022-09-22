<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalesReportLocationCategory extends Model
{
    protected $fillable = [
        'sales_report_id',
        'sales_report_location_id',
        'category',
        'net_sales',
        'quantity',
    ];

    protected $casts = [
        'net_sales' => 'float',
        'quantity' => 'integer',
    ];

    public $timestamps = false;

    public function salesReportLocation()
    {
        return $this->belongsTo(SalesReportLocation::class);
    }
}
