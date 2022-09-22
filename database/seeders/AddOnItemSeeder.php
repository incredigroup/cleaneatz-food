<?php

namespace Database\Seeders;

use App\Models\Meal;
use Illuminate\Database\Seeder;

class AddOnItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Meal::create([
            'name' => "Clean Eatz Barz",
            'description' => "Cookies N' Cream",
            'is_breakfast' => false,
            'is_add_on_item' => true,
            'group_name' => 'barz',
            'group_desc' => "Our CLean Eatz Barz make a great snack when you're on the go.",
            'price_override' => 3.09,
            'sort_order' => 50,
            'image_url' => 'barz.png'
        ]);

        Meal::create([
            'name' => 'Clean Eatz Barz',
            'description' => 'Trail Mix',
            'is_breakfast' => false,
            'is_add_on_item' => true,
            'group_name' => 'barz',
            'group_desc' => "Our CLean Eatz Barz make a great snack when you're on the go.",
            'price_override' => 3.09,
            'sort_order' => 70,
            'image_url' => 'barz.png'
        ]);
    }
}
