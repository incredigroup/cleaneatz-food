<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;

class MealPlan extends Model
{
    use SoftDeletes;

    protected $dates = ['available_on', 'expires_on'];
    protected $fillable = ['available_on', 'expires_on', 'name'];

    public static $requestOptions = ['LowCarb', 'ExtraProtein'];

    public function items()
    {
        return $this->belongsToMany('App\Models\Meal', 'meal_plan_items')
            ->withPivot('id')
            ->withTimestamps();
    }

    public function scopeCurrent($query)
    {
        $now = Carbon::now();
        return $this->betweenDates($now, $now);
    }

    public function scopeUpcoming($query)
    {
        return $query->where('available_on', '>', Carbon::now())->orderBy('available_on', 'asc');
    }

    public function scopeBetweenDates($query, $start, $end)
    {
        return $query->where('available_on', '<=', $start)->where('expires_on', '>=', $end);
    }

    public static function pricePerMeal(
        int $items,
        array $specialRequests = null,
        Meal $meal = null,
    ): float {
        if ($items < 10) {
            $costPerItem = 8.2;
        } elseif ($items < 15) {
            $costPerItem = 7.3;
        } elseif ($items < 21) {
            $costPerItem = 7;
        } else {
            $costPerItem = 6.523809523809524;
        }

        $extraProtein =
            isset($specialRequests['ExtraProtein']) && $specialRequests['ExtraProtein'] === true;

        if ($extraProtein) {
            $costPerItem += 1.5;
        }

        return $costPerItem;
    }
}
