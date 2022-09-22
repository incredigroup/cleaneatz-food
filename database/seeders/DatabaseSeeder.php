<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(StoreLocationSeeder::class);
        $this->call(SatelliteLocationSeeder::class);
        $this->call(MealSeeder::class);
        $this->call(MealPlanSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(PromoCodeSeeder::class);
    }
}
