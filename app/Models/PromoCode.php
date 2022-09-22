<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PromoCode extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'store_location_id',
        'name',
        'code',
        'discount_amount',
        'discount_percent',
        'start_on',
        'end_on',
        'min_meals_required',
        'match_type',
        'is_approved',
    ];

    protected $dates = ['start_on', 'end_on'];
    protected $casts = ['discount_amount' => 'float'];

    const MATCH_TYPE_EQUALS = 'equals';
    const MATCH_TYPE_STARTS_WITH = 'starts_with';

    public function storeLocation()
    {
        return $this->belongsTo(StoreLocation::class);
    }

    public function scopeActive($query)
    {
        $now = Carbon::now('EST')->toDateString();

        return $query
            ->where(function ($query) use ($now) {
                $query->whereNull('start_on')->orWhere('start_on', '<=', $now);
            })
            ->where(function ($query) use ($now) {
                $query->whereNull('end_on')->orWhere('end_on', '>=', $now);
            })
            ->where('is_approved', true);
    }

    public function scopeUnapproved($query)
    {
        return $query->where('is_approved', false);
    }

    public function isGlobal()
    {
        return $this->store_location_id === null;
    }

    public function getDiscountTextAttribute()
    {
        if ($this->discount_amount) {
            return '$' . number_format($this->discount_amount);
        }

        return $this->discount_percent . '%';
    }

    public function getTimeframeTextAttribute()
    {
        if (empty($this->start_on) && empty($this->end_on)) {
            return '';
        }

        return $this->start_on?->format('m/d/Y') . ' - ' . $this->end_on?->format('m/d/Y');
    }

    public static function matchTypeOptions()
    {
        return [
            'equals' => 'Code Matches Exactly',
            'starts_with' => 'Code Starts With',
        ];
    }
}
