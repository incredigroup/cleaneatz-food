<?php

namespace Database\Seeders;

use App\{Models\Meal, Models\MealPlan};
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class MealPlanSeeder extends Seeder
{
    /**
     * Run the database seeders.
     *
     * @return void
     */
    public function run()
    {
        $meals = Meal::all();

        // // expired
        $plan = MealPlan::create([
            'name' => Carbon::now()->subDay(1)->format('m-d'),
            'available_on' => Carbon::now()->subWeek(1),
            'expires_on' => Carbon::now()->subDay(1),
        ]);

        $plan->items()->sync($meals);

        // current
        $plan = MealPlan::create([
            'name' => Carbon::now()->addWeek(1)->format('m-d'),
            'available_on' => Carbon::now()->toDate(),
            'expires_on' => Carbon::now()->addWeek(1),
        ]);

        $plan->items()->sync($meals);

        // future
        $plan = MealPlan::create([
            'name' => Carbon::now()->addWeek(2)->format('m-d'),
            'available_on' => Carbon::now()->addWeek(1),
            'expires_on' => Carbon::now()->addWeek(2),
        ]);

        $plan->items()->sync($meals);
    }
}
