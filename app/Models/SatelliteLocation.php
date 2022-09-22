<?php

namespace App\Models;

use App\Traits\HasStateOptions;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class SatelliteLocation extends Model
{
    use SoftDeletes, HasStateOptions;

    protected $fillable = ['name', 'fee', 'address', 'city', 'state', 'zip'];
    protected $casts = ['fee' => 'float'];
    protected $orderBy = 'name';

    public function storeLocation(): BelongsTo
    {
        return $this->belongsTo(StoreLocation::class);
    }

    public function scopeApproved($query)
    {
        return $query->where('is_approved', true);
    }

    public function scopeUnapproved($query)
    {
        return $query->where('is_approved', false);
    }

    public function saveWithGuardedFields($input)
    {
        $this->store_location_id = $input['store_location_id'];
        $this->is_approved = isset($input['is_approved']);
        $this->save();
    }
}
