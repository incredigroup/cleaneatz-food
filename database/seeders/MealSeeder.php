<?php

namespace Database\Seeders;

use App\Models\Meal;
use Illuminate\Database\Seeder;

class MealSeeder extends Seeder
{
    /**
     * Run the database seeders.
     *
     * @return void
     */
    public function run()
    {
        $meals = collect([
            [
                'name' => 'Bang Bang Shrimp',
                'image_url' => 'web_hzuzt1fta.jpg',
                'calories' => 319,
                'fat' => 7,
                'carbs' => 38,
                'protein' => 26,
                'description' => 'Grilled shrimp tossed in the low-fat Clean Eatz version of bang bang sauce. Served with Jasmine rice and grilled zucchini. Garnished with green onions and toasted coconut.',
            ],
            [
                'name' => 'Beef Nachos',
                'image_url' => 'web2_p897rtvl7.jpg',
                'calories' => 436,
                'fat' => 20,
                'carbs' => 40,
                'protein' => 24,
                'description' => "Sweet potato waffle fries covered in shredded BBQ beef and black beans and topped with Monterey Jack cheese and three jalapenos. Can't take the heat? Just remove the peppers because you do not want to miss this meal. No special requests available.",
            ],
            [
                'name' => 'Cheeseburger Bowl',
                'image_url' => 'web3_w8udw5s1a.jpg',
                'calories' => 398,
                'fat' => 14,
                'carbs' => 42,
                'protein' => 26,
                'description' => 'BBQ beef, diced tomatoes, pickles and shredded cheese served over brown rice.'
            ],
            [
                'name' => 'Honey Mustard Chicken',
                'image_url' => 'web4_gtu9fofbg.jpg',
                'calories' => 429,
                'fat' => 13,
                'carbs' => 40,
                'protein' => 38,
                'description' => 'Chicken tossed in honey mustard served with diced potatoes and broccoli.'
            ],
            [
                'name' => 'Steak & Egg Wrap',
                'image_url' => 'web5_s2lwv2qup.jpg',
                'calories' => 442,
                'fat' => 18,
                'carbs' => 46,
                'protein' => 24,
                'description' => 'Egg white omelettes, shredded beef and Monterey Jack cheese in a whole wheat tortilla.'
            ],
            [
                'name' => 'Verde Pork Pita',
                'image_url' => 'web6_2m6hot6vf.jpg',
                'calories' => 452,
                'fat' => 12,
                'carbs' => 46,
                'protein' => 40,
                'description' => 'Whole wheat pita topped with sliced pork, black beans, tomatillo salsa and sprinkled with cotija cheese, red onions and cilantro.'
            ],
        ]);

        $meals->each(function($meal) {
          Meal::create($meal);
        });
    }
}
