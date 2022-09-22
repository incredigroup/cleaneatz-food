<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PaymentMethod extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'store_location_id',
        'user_id',
        'card_name',
        'card_last_4',
        'card_exp_month',
        'card_exp_year',
        'card_brand',
        'card_token',
    ];

    public function scopeOfClientToken($query, $clientToken)
    {
        return $query->where('client_token', $clientToken);
    }
}
