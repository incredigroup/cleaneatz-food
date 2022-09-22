<?php

namespace App\Models;

use App\Helpers\PaymentManager;
use App\Traits\ParsesDates;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasSpecialRequests;
use Spatie\Tags\HasTags;

/**
 * Notes:
 *
 * Subtotal does not include discounts -- use attribute net_sales for actual subtotal
 */
class MealPlanOrder extends Model
{
    use HasSpecialRequests, ParsesDates, HasTags;

    const STATUS_COMPLETED = 'completed';
    const STATUS_DECLINED = 'declined';
    const STATUS_CANCELED = 'canceled';
    const STATUS_REFUNDED = 'refunded';
    const STATUS_ERROR = 'error';

    const PAYMENT_TYPE_IN_STORE = 'in-store';
    const PAYMENT_TYPE_ONLINE = 'online';

    const DELIVERY_FEE = 6.99;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'order_type',
        'card_last_4',
        'card_exp_month',
        'card_exp_year',
        'payment_type',
        'tip_amount',
        'special_requests',
        'transaction_status',
    ];

    protected static function booted()
    {
        static::created(function ($mealPlanOrder) {
            if (!$mealPlanOrder->transaction_id) {
                $mealPlanOrder->transaction_id = $mealPlanOrder->id;
                $mealPlanOrder->save();
            }
        });
    }

    public function cart()
    {
        return $this->belongsTo(MealPlanCart::class, 'meal_plan_cart_id');
    }

    public function store()
    {
        return $this->belongsTo(StoreLocation::class, 'store_location_id');
    }

    public function satellite()
    {
        return $this->belongsTo(SatelliteLocation::class, 'satellite_location_id');
    }

    public function refunds()
    {
        return $this->hasMany(MealPlanRefund::class);
    }

    public function items()
    {
        return $this->cart->items();
    }

    public function mealPlanItems()
    {
        return $this->cart->mealPlanItems();
    }

    public function addOnItems()
    {
        return $this->cart->addOnItems();
    }

    public function getDiscountedSubtotalAttribute()
    {
        return round($this->subtotal - $this->promo_amount, 2);
    }

    public function getOrderDateAttribute()
    {
        return $this->created_at->format('m/d/Y');
    }

    public function getLocalOrderDateAttribute()
    {
        return $this->toFormattedLocalDate($this->created_at);
    }

    public function getOrderDateTimeAttribute()
    {
        return $this->created_at->format('m/d/Y g:i A');
    }

    public function getLocalOrderDateTimeAttribute()
    {
        return $this->toFormattedLocalDateTime($this->created_at);
    }

    public function getLocalClearedDateAttribute()
    {
        return $this->cleared_at !== null ? $this->toFormattedLocalDate($this->cleared_at) : null;
    }

    public function getLocalClearedDateTimeAttribute()
    {
        return $this->cleared_at !== null
            ? $this->toFormattedLocalDateTime($this->cleared_at)
            : null;
    }

    public function getNameAttribute()
    {
        return trim($this->first_name) . ' ' . trim($this->last_name);
    }

    public function getPaymentMethodAttribute()
    {
        return $this->payment_type === 'online' ? 'Credit Card' : 'In Store';
    }

    public function getPickUpDayAttribute()
    {
        return $this->made_special_request ? 'Tuesday' : 'Monday';
    }

    public function getTransactionTagAttribute()
    {
        $transactionDetails = json_decode($this->transaction_details, true);

        return $transactionDetails['transaction_tag'] ?: null;
    }

    public function pickupDateDescription()
    {
        $orderedAt = $this->toLocalDateTime($this->created_at);

        if ($orderedAt->dayOfWeek === Carbon::SUNDAY || $orderedAt->dayOfWeek === Carbon::MONDAY) {
            $orderedAt = $orderedAt->previous('Saturday')->startOfDay();
        }

        $sunday = $orderedAt->next('Sunday');

        if ($this->cart->is_custom) {
            return [
                'dates' => implode(' & ', [
                    $sunday->addDays(2)->format('l, F jS'),
                    $sunday->addDays(3)->format('l, F jS'),
                ]),
                'descr' => 'Custom Meal Plan Pick Up',
            ];
        }

        if ($this->cart->hasSpecialRequests()) {
            return [
                'dates' => $sunday->addDays(2)->format('l, F jS'),
                'descr' => 'Special Request Meal Plan Pick Up',
            ];
        }

        return [
            'dates' => !$this->store->has_sunday_pickup
                ? $sunday->addDays(1)->format('l, F jS')
                : implode(' & ', [
                    $sunday->format('l, F jS'),
                    $sunday->addDays(1)->format('l, F jS'),
                ]),
            'descr' => 'Meal Plan Pick Up',
        ];
    }

    public function scopeForMealPlan($query, $mealPlanId, $delivery = null)
    {
        $query = $query
            ->select(
                'meal_plan_orders.*',
                'meal_plan_carts.special_requests',
                'meal_plan_carts.delivery',
            )
            ->join(
                'meal_plan_carts',
                'meal_plan_carts.id',
                '=',
                'meal_plan_orders.meal_plan_cart_id',
            )
            ->where('meal_plan_carts.meal_plan_id', '=', $mealPlanId);

        if ($delivery !== null) {
            $query->where('meal_plan_carts.delivery', '=', $delivery);
        }

        return $query;
    }

    public function scopePaidOnline($query)
    {
        return $query
            ->where('meal_plan_orders.payment_type', self::PAYMENT_TYPE_ONLINE)
            ->whereIn('meal_plan_orders.transaction_status', [
                self::STATUS_COMPLETED,
                self::STATUS_REFUNDED,
            ]);
    }

    public function scopeLive($query)
    {
        return $query->where('meal_plan_orders.transaction_status', self::STATUS_COMPLETED);
    }

    public function scopeForSatellite($query, $satelliteLocationId)
    {
        return $query->where('meal_plan_orders.satellite_location_id', '=', $satelliteLocationId);
    }

    public function scopeForStore($query, $storeLocationId)
    {
        return $query->where('meal_plan_orders.store_location_id', '=', $storeLocationId);
    }

    /**
     * Cancels an in-store order, voids a same-day credit card charge, or fully refunds a charge that has batched.
     * Basically, fully reverses any order.
     */
    public function cancelOrRefund()
    {
        if ($this->payment_type == self::PAYMENT_TYPE_IN_STORE) {
            $this->update([
                'transaction_status' => self::STATUS_CANCELED,
            ]);
        } else {
            $this->toLocalDateTime($this->created_at)->diffInDays(Carbon::now()) == 0
                ? $this->voidCharge()
                : $this->refundCharge();
        }
    }

    /**
     * Voids a credit card charge that has not yet batched.
     */
    public function voidCharge()
    {
        if ($this->payment_type !== self::PAYMENT_TYPE_ONLINE) {
            throw new Exception('Cannot void an in-store payment');
        }

        $paymentManager = new PaymentManager($this->store->gateway_merchant_token);

        $response = $paymentManager->void($this->transaction_id, $this->transaction_tag);

        if (!$response['success']) {
            $errorMsg = "Error voiding order {$this->id} {$response['error_description']}";

            throw new Exception($errorMsg);
        }

        $this->update([
            'transaction_status' => self::STATUS_CANCELED,
        ]);
    }

    /**
     * Refunds a credit card charge.
     */
    public function refundCharge($mealPlanRefund = null)
    {
        if ($this->payment_type !== self::PAYMENT_TYPE_ONLINE) {
            throw new Exception('Cannot refund an in-store payment');
        }

        $mealPlanRefund = $mealPlanRefund ?? MealPlanRefund::fullRefundFor($this);

        $paymentManager = new PaymentManager($this->store->gateway_merchant_token);

        $response = $paymentManager
            ->setAmount($mealPlanRefund->total_refund)
            ->refund($this->transaction_id, $this->transaction_tag);

        if (!$response['success']) {
            $errorMsg = "Error refunding order {$this->id} {$response['error_description']}";

            throw new Exception($errorMsg);
        }

        $mealPlanRefund->transaction_id = $response['transaction_id'];
        $mealPlanRefund->save();

        if (abs($this->total - $mealPlanRefund->total_refund) < PHP_FLOAT_EPSILON) {
            $this->update([
                'transaction_status' => self::STATUS_REFUNDED,
            ]);
        }

        return $mealPlanRefund;
    }

    public function hasTag($tagToFind)
    {
        return $this->tags->contains(fn($tag) => $tag->name === $tagToFind);
    }
}
