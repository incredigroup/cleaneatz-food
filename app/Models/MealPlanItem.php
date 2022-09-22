<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MealPlanItem extends Model
{
    public $incrementing = true;

    public function meal()
    {
        return $this->belongsTo(Meal::class);
    }
}
