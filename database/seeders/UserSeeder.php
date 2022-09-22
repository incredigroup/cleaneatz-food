<?php

namespace Database\Seeders;

use App\Models\StoreLocation;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeders.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'first_name' => 'John',
            'last_name' => 'User',
            'username' => 'user@dev.com',
            'email' => 'user@dev.com',
            'role' => 'user',
            'password' => Hash::make('password'),
            'api_token' => Str::random(60),
        ]);
        User::create([
            'first_name' => 'John',
            'last_name' => 'Store',
            'username' => 'store@dev.com',
            'email' => 'store@dev.com',
            'role' => 'store',
            'password' => Hash::make('password'),
            'api_token' => Str::random(60),
            'store_location_id' => StoreLocation::ofCode('3547285')->firstOrFail()->id,
        ]);
        $user = User::create([
            'first_name' => 'John',
            'last_name' => 'Admin',
            'username' => 'admin@dev.com',
            'email' => 'admin@dev.com',
            'role' => 'admin',
            'api_token' => Str::random(60),
            'password' => Hash::make('password'),
        ]);
    }
}
