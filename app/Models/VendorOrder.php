<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VendorOrder extends Model
{
    protected $fillable = ['name', 'email', 'order_details', 'delivery_notes'];

    public function getOrderDetailsAttribute($value)
    {
        return json_decode($value);
    }

    public static function mealOptions()
    {
        return [
            'Baja Pineapple Chicken',
            'Big Boy in a Bowl',
            'Bourbon Beef',
            'Breakfast Hash',
            'Buffalo Chicken Mac & Cheese',
            'Caprese Chicken',
            'Cheeseburger Bowl',
            'Chimichurri Steak',
            'French Toast',
            'Grecian Beef',
            'Grilled Chicken',
            'Italian Turkey',
            'Keto Black & Bleu Prime Rib',
            'Keto Breakfast Taco',
            'Keto Cashew Chicken',
            'Keto Chicken Cacciatore',
            'Keto Clean Mac',
            'Keto Creole Chicken',
            'Keto Enchilada',
            'Keto Hibachi',
            'Lemon Pepper Chicken',
            'Power Breakfast',
            'Sticky Chicken',
            'Sweet N\' Sour Chicken',
            'Thai Basil Chicken',
            'The Arnold Steak Wrap',
            'Tortellini Alfredo',
        ];
    }
}
