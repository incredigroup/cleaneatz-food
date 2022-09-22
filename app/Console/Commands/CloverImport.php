<?php

namespace App\Console\Commands;

use App\Models\StoreLocation;
use App\Traits\SalesReports\ImportsFromClover;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CloverImport extends Command
{
    use ImportsFromClover;

    protected $signature = 'clover:import {storeLocationId?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Loads Clover data';

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
        if ($this->argument('storeLocationId')) {
            try {
                StoreLocation::findOrFail($this->argument('storeLocationId'))->importFromClover();
            } catch (Exception $e) {
                $msg = "Unable to import clover orders: {$e->getMessage()}";
                $this->error($msg);
                Log::error($msg);
            }
        } else {
            StoreLocation::join(
                'clover_merchants',
                'clover_merchants.store_location_id',
                'store_locations.id',
            )
                ->where('store_locations.is_enabled', true)
                ->whereNull('store_locations.deleted_at')
                ->whereNull('clover_merchants.deleted_at')
                ->where('store_locations.is_enabled', true)
                ->select('store_locations.*')
                ->orderBy('store_locations.id')
                ->get()
                ->each(function ($storeLocation) {
                    $this->info('Importing Clover orders for: ' . $storeLocation->locale);
                    try {
                        $storeLocation->importFromClover();
                    } catch (Exception $e) {
                        $msg = "Unable to import clover orders for {$storeLocation->locale}: {$e->getMessage()}";
                        $this->error($msg);
                        Log::error($msg);
                    }
                });
        }
    }
}
