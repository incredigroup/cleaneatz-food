<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Scopes\CustomMealScope;

class Meal extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'is_breakfast',
        'price_override',
        'group_name',
        'group_desc',
        'is_add_on_item',
        'meta_data',
        'image_url',
        'calories',
        'fat',
        'carbs',
        'protein',
        'points',
    ];

    const CUSTOM_MEAL_ID = 999999999;
    const CUSTOM_MEAL_BASE_PRICE = 7.0;

    public static $customOptions = [
        'protein',
        'protein_portion',
        'carbohydrate',
        'carbohydrate_portion',
        'vegetables',
        'vegetables_2',
        'vegetables_3',
        'sauce',
    ];

    protected $casts = [
        'is_add_on_item' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new CustomMealScope());
    }

    public static function getCustomOptions()
    {
        $options = option('custom_meal_options');

        return collect($options)->map(function ($option) {
            return collect($option)->map(function ($ingredient, $index) {
                $label = $ingredient['label'];

                if ($ingredient['cost'] > 0) {
                    $label .= ' + ' . sprintf("$%01.2f", $ingredient['cost']);
                }

                return [
                    'label' => $label,
                    'cost' => $ingredient['cost'],
                    'id' => $index,
                ];
            });
        });
    }

    public static function getAddOnItems()
    {
        return Meal::where('is_add_on_item', '=', true)
            ->get()
            ->groupBy('group_name');
    }

    public function getDisplayNameAttribute()
    {
        if (!$this->is_add_on_item) {
            return $this->name;
        }

        $hasVariants = Meal::where('group_name', '=', $this->group_name)
            ->where('id', '!=', $this->id)
            ->exists();

        if ($hasVariants === false) {
            return $this->name;
        }

        return $this->name . ' - ' . $this->description;
    }

    public static function calcCustomMealCost($meal)
    {
        $options = self::getCustomOptions();

        $protein = $options['protein']->first(function ($opt, $index) use ($meal) {
            return $index === $meal->ingredients->protein->id;
        });

        $proteinPortion = $options['protein_portion']->first(function ($opt, $index) use ($meal) {
            return $index === $meal->ingredients->proteinPortion->id;
        });

        $carb = $options['carbohydrate']->first(function ($opt, $index) use ($meal) {
            return $index === $meal->ingredients->carb->id;
        });

        $carbPortion = $options['carbohydrate_portion']->first(function ($opt, $index) use ($meal) {
            return $index === $meal->ingredients->carbPortion->id;
        });

        $vegetable = $options['vegetables']->first(function ($opt, $index) use ($meal) {
            return $index === $meal->ingredients->vegetable->id;
        });

        $vegetable2 = $options['vegetables_2']->first(function ($opt, $index) use ($meal) {
            return $index === $meal->ingredients->vegetable2->id;
        });

        $vegetable3 = $options['vegetables_3']->first(function ($opt, $index) use ($meal) {
            return $index === $meal->ingredients->vegetable3->id;
        });

        $sauce = $options['sauce']->first(function ($opt, $index) use ($meal) {
            return $index === $meal->ingredients->sauce->id;
        });

        return self::CUSTOM_MEAL_BASE_PRICE +
            $protein['cost'] +
            $proteinPortion['cost'] +
            $carb['cost'] +
            $carbPortion['cost'] +
            $vegetable['cost'] +
            $vegetable2['cost'] +
            $vegetable3['cost'] +
            $sauce['cost'];
    }
}
