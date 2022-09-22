<?php

namespace Database\Seeders;

use App\{Models\PromoCode, Models\StoreLocation};
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class PromoCodeSeeder extends Seeder
{
    /**
     * Run the database seeders.
     *
     * @return void
     */
    public function run()
    {
        $store1 = StoreLocation::where('location', '=', 'Racine')->first();

        $store1->promoCodes()->saveMany([
            new PromoCode([
                'name' => '1.5 off',
                'code' => '1.5off',
                'discount_amount' => 1.5,
                'start_on' => Carbon::now(),
                'end_on' => Carbon::now()->addYear(1)
            ]),

            new PromoCode([
                'name' => '1/2 off',
                'code' => '1/2off',
                'discount_percent' => 50,
                'start_on' => Carbon::now(),
                'end_on' => Carbon::now()->addYear(1)
            ]),

            new PromoCode([
                'name' => 'expired',
                'code' => 'expired',
                'discount_amount' => 1.5,
                'start_on' => Carbon::now()->subDay(1),
                'end_on' => Carbon::now()->addYear(1)
            ]),

        ]);

        $store1 = StoreLocation::where('location', '=', 'South End')->first();

        $store1->promoCodes()->saveMany([
            new PromoCode([
                'name' => '1.5 off',
                'code' => '1.5off',
                'discount_amount' => 1.5,
                'start_on' => Carbon::now(),
                'end_on' => Carbon::now()->addYear(1)
            ]),

            new PromoCode([
                'name' => '1/2 off',
                'code' => '1/2off',
                'discount_percent' => 50,
                'start_on' => Carbon::now(),
                'end_on' => Carbon::now()->addYear(1)
            ]),

            new PromoCode([
                'name' => 'expired',
                'code' => 'expired',
                'discount_amount' => 1.5,
                'start_on' => Carbon::now()->subDay(1),
                'end_on' => Carbon::now()->addYear(1)
            ]),

        ]);
    }
}
