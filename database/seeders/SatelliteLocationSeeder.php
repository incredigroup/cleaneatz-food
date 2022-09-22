<?php

namespace Database\Seeders;

use App\Models\SatelliteLocation;
use App\Models\StoreLocation;
use Illuminate\Database\Seeder;

class SatelliteLocationSeeder extends Seeder
{
    /**
     * Run the database seeders.
     *
     * @return void
     */
    public function run()
    {
        $satellites = collect([
            [
                'store_location_code' => '3547397',
                'name' => 'TruFit Gym Hope Mills (Cutoff is Midnight Saturday)',
                'address' => '2485 Hope Mills Rd.',
                'city' => 'Fayetteville',
                'state' => 'NC',
                'zip' => '28304',
                'is_approved' => 1,
            ],
            [
                'store_location_code' => '3547398',
                'name' => 'Triangle Crossfit Sunday NOON cut off MEMBERS ONLY pick up on Monday',
                'address' => '10113 Division Dr.',
                'city' => 'Raleigh',
                'state' => 'NC',
                'zip' => '27603',
                'is_approved' => 0,
            ],
        ]);
        $satellites->each(function($satellite) {
            $storeLocation = StoreLocation::where('code', $satellite['store_location_code'])->firstOrFail();
            $satellite['store_location_id'] = $storeLocation->id;
            unset($satellite['store_location_code']);

            SatelliteLocation::create($satellite);
        });
    }
}
