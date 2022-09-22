<?php

namespace App\Models;

use App\Traits\SalesReports\ImportsFromClover;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Builder;

/**
 * @mixin Builder
 */
class StoreLocation extends Model
{
    use SoftDeletes, ImportsFromClover;

    const STORE_TYPE_CAFE = 'cafe';
    const STORE_TYPE_EXPRESS = 'express';

    const STATUS_OPERATIONAL = 'operational';
    const STATUS_COMING_SOON = 'coming_soon';
    const STATUS_IN_DEVELOPMENT = 'in_development';

    protected $fillable = [
        'code',
        'city',
        'state',
        'location',
        'zip',
        'phone',
        'email',
        'store_type',
        'status',
        'hours_of_operation',
        'has_sunday_pickup',
        'is_enabled',
        'is_cafe_ordering_enabled',
        'is_meal_plan_ordering_enabled',
        'royalty_tier',
        'lat',
        'lng',
        'tax_rate',
    ];

    protected $hidden = [
        'id',
        'gateway_merchant_token',
        'gateway_merchant_transarmor_token',
        'gateway_merchant_id',
        'gateway_merchant_name',
        'ach_account_num',
        'ach_receiver_id',
        'ach_routing_num',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function getStateNameAttribute()
    {
        return Config::get('constants.states')[$this->state];
    }

    public function getLocaleAttribute()
    {
        return self::buildName($this->state, $this->city, $this->location);
    }

    public function satellites()
    {
        return $this->hasMany(SatelliteLocation::class);
    }

    public function cloverMerchant()
    {
        return $this->hasOne(CloverMerchant::class);
    }

    public function availableSatellites()
    {
        return $this->satellites()->where('is_approved', '=', true);
    }

    public function promoCodes()
    {
        return $this->hasMany(PromoCode::class);
    }

    public function scopeOfCode($query, $code)
    {
        return $query->where('code', $code);
    }

    public function scopeEnabled($query)
    {
        return $query->where('is_enabled', 1);
    }

    public function scopeOrderedByLocation($query)
    {
        $query
            ->orderBy('state')
            ->orderBy('city')
            ->orderBy('location');
    }

    public static function locationOptions()
    {
        return self::orderedByLocation()
            ->get()
            ->pluck('locale', 'id');
    }

    public static function buildNameFromObject($storeLocation)
    {
        return self::buildName(
            $storeLocation->state,
            $storeLocation->city,
            $storeLocation->location,
        );
    }

    public static function buildName($state, $city, $location)
    {
        $locale = "$city, $state";

        if (!empty($location)) {
            $locale .= " - $location";
        }

        return $locale;
    }
}
