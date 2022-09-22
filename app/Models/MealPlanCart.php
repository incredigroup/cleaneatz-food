<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasSpecialRequests;

class MealPlanCart extends Model
{
    use HasSpecialRequests;

    protected $fillable = [
        'cookie_id',
        'meal_plan_id',
        'store_location_id',
        'special_requests',
        'satellite_location_id',
        'delivery',
        'delivery_address',
        'is_custom',
    ];

    public function items()
    {
        return $this->hasMany(MealPlanCartItem::class);
    }

    public function mealPlanItems()
    {
        return $this->hasMany(MealPlanCartItem::class)->where('group_name', '=', 'meal_plan');
    }

    public function addOnItems()
    {
        return $this->hasMany(MealPlanCartItem::class)->where('is_add_on_item', '=', true);
    }

    public function promoCode()
    {
        return $this->belongsTo(PromoCode::class);
    }

    public function storeLocation()
    {
        return $this->belongsTo(StoreLocation::class);
    }

    public function satelliteLocation()
    {
        return $this->belongsTo(SatelliteLocation::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getDiscountAmountAttribute()
    {
        if ($this->promoCode()->exists() === false) {
            return 0;
        }

        if ($this->promoCode->min_meals_required > $this->mealPlanItems->sum->quantity) {
            return 0;
        }

        if ($this->promoCode->discount_amount && floatval($this->promoCode->discount_amount) > 0) {
            return min($this->sub_total, $this->promoCode->discount_amount);
        }

        if ($this->promoCode->discount_percent) {
            return $this->sub_total * ($this->promoCode->discount_percent / 100);
        }

        return 0;
    }

    public function getSubTotalAttribute()
    {
        return $this->items->sum->lineTotal();
    }

    public function getTotalTaxAttribute()
    {
        return ($this->sub_total - $this->discount_amount) * ($this->storeLocation->tax_rate / 100);
    }

    public function getDeliveryFeeAttribute()
    {
        return $this->delivery ? MealPlanOrder::DELIVERY_FEE : 0;
    }

    public function getSatelliteFeeAttribute()
    {
        if ($this->satelliteLocation()->exists() === false) {
            return 0;
        }

        return $this->satelliteLocation->fee;
    }

    public function getOrderTotalAttribute()
    {
        return $this->sub_total +
            $this->delivery_fee +
            $this->satellite_fee +
            $this->total_tax -
            $this->discount_amount;
    }

    public function getSpecialRequestsForMeal(Meal $meal): string
    {
        if (!$this->special_requests) {
            return '';
        }

        if ($meal->is_add_on_item) {
            return '';
        }

        $specialRequests = json_decode($this->special_requests, true);

        if ($meal->is_breakfast) {
            if (isset($specialRequests['LowCarb'])) {
                unset($specialRequests['LowCarb']);
            }
        }

        return $this->specialRequestsToString($specialRequests);
    }

    public function getSpecialRequestsDisplayAttribute(): string
    {
        if (!$this->special_requests) {
            return '';
        }

        $specialRequests = json_decode($this->special_requests, true);
        return $this->specialRequestsToString($specialRequests);
    }

    public function hasSpecialRequests()
    {
        return !empty($this->special_requests) && $this->special_requests !== '[]';
    }

    private function specialRequestsToString(array $specialRequests): string
    {
        return collect(array_keys($specialRequests))
            ->map(function ($request) {
                if ($request === 'LowCarb') {
                    return 'Low Carb';
                }

                if ($request === 'ExtraProtein') {
                    return 'Extra Protein';
                }

                return str_replace('-', ' ', $request);
            })
            ->join(', ');
    }
}
