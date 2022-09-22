<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentToken extends Model
{
    protected $fillable = [
        'store_location_id',
        'client_token',
        'nonce',
        'card_name',
        'card_last_4',
        'card_exp_month',
        'card_exp_year',
        'card_brand',
        'card_token',
        'error',
    ];

    public function scopeOfClientToken($query, $clientToken)
    {
        return $query->where('client_token', $clientToken);
    }

    public function storeForUser($userId)
    {
        PaymentMethod::updateOrCreate(
            [
                'card_token' => $this->card_token,
            ],
            [
                'store_location_id' => $this->store_location_id,
                'user_id' => $userId,
                'card_name' => $this->card_name,
                'card_last_4' => $this->card_last_4,
                'card_exp_month' => $this->card_exp_month,
                'card_exp_year' => $this->card_exp_year,
                'card_brand' => $this->card_brand,
                'card_token' => $this->card_token,
            ],
        );
    }
}
