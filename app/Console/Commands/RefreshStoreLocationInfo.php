<?php

namespace App\Console\Commands;

// use App\WPStoreLocation;
use App\Models\StoreLocation;
use Illuminate\Console\Command;

class RefreshStoreLocationInfo extends Command
{
    protected $signature = 'store-location:refresh';

    protected static $statusMap = [
        'Operational Store' => StoreLocation::STATUS_OPERATIONAL,
        'Coming Soon' => StoreLocation::STATUS_COMING_SOON,
        'In Development' => StoreLocation::STATUS_IN_DEVELOPMENT,
    ];

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Imports most recent store location data (address, hours, etc) from Wordpress';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        foreach (WPStoreLocation::allStores() as $wpStore) {
            if ($wpStore->status === WPStoreLocation::STATUS_IN_DEVELOPMENT) {
                continue;
            }

            $storeLocation = StoreLocation::ofCode($wpStore->post_id)->first();

            if ($storeLocation) {
                $storeLocation->update([
                    'zip' => $wpStore->zip,
                    'phone' => $wpStore->phone,
                    'email' => $wpStore->email,
                    'hours_of_operation' => $wpStore->hours_of_operation,
                    'has_sunday_pickup' => !$wpStore->no_sunday_pickup,
                    'lat' => $wpStore->lat,
                    'lng' => $wpStore->lng,
                    'status' => self::$statusMap[$wpStore->status],
                    'store_type' => strtolower($wpStore->store_type),
                ]);
            }

            $this->info("Updated {$storeLocation->locale}");
        }
    }
}
