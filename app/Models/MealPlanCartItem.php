<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Scopes\CustomMealScope;

class MealPlanCartItem extends Model
{
    protected $fillable = [
        'meal_id',
        'cost',
        'quantity',
        'meal_name',
        'meta_data',
        'is_add_on_item',
        'group_name',
    ];

    protected $casts = [
        'is_add_on_item' => 'boolean',
    ];

    public function mealPlanItem()
    {
        return $this->belongsTo(MealPlanItem::class, 'meal_plan_item_id');
    }

    public function meal()
    {
        return $this->belongsTo(Meal::class)->withoutGlobalScope(CustomMealScope::class);
    }

    public function lineTotal()
    {
        return $this->quantity * $this->cost;
    }

    public function getCustomIngredientsAttribute()
    {
        return json_decode($this->meta_data)->ingredients;
    }

    public function getCommentsAttribute()
    {
        $metaData = json_decode($this->meta_data);
        if (property_exists($metaData, 'comments')) {
            return $metaData->comments;
        }

        return null;
    }
}
