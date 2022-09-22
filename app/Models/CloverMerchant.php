<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CloverMerchant extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'store_location_id',
        'merchant_id',
        'name',
        'address',
        'city',
        'state',
        'zip',
        'owner_name',
        'owner_email',
        'access_token',
    ];

    public function storeLocation()
    {
        return $this->belongsTo(StoreLocation::class);
    }
}
