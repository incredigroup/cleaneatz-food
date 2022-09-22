<?php

namespace App\Models;

use App\Traits\ParsesDates;
use Illuminate\Database\Eloquent\Model;

class MealPlanRefund extends Model
{
    use ParsesDates;

    protected $fillable = [
        'user_id',
        'meal_plan_order_id',
        'total_refund',
        'net_refund',
        'tax',
        'tip_amount',
        'satellite_fee',
        'notes',
        'transaction_id',
    ];

    public function order()
    {
        return $this->belongsTo(MealPlanOrder::class, 'meal_plan_order_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getLocalRefundDateTimeAttribute()
    {
        return $this->toFormattedLocalDateTime($this->created_at);
    }

    public static function fullRefundFor($order)
    {
        return new MealPlanRefund([
            'user_id' => Auth::user()->id ?? null,
            'meal_plan_order_id' => $order->id,
            'total_refund' => $order->total,
            'net_refund' => $order->discounted_subtotal,
            'tax' => $order->tax,
            'tip_amount' => $order->tip_amount,
            'satellite_fee' => $order->satellite_fee,
        ]);
    }
}
