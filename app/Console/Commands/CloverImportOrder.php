<?php

namespace App\Console\Commands;

use App\Helpers\API\Clover\CloverApi;
use App\Models\CloverOrder;
use App\Models\StoreLocation;
use App\Traits\SalesReports\ImportsFromClover;
use Illuminate\Console\Command;

class CloverImportOrder extends Command
{
    use ImportsFromClover;

    protected $signature = 'clover:import-order {storeLocationId} {cloverOrderId}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Loads a single Clover order via API';

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
        $storeLocation = StoreLocation::findOrFail($this->argument('storeLocationId'));

        $cloverMerchant = $storeLocation->cloverMerchant;

        if (!$cloverMerchant) {
            throw new \Exception('No Clover Merchant for location' . $storeLocation->name);
        }

        $cloverApi = (new CloverApi())
            ->setMerchantId($cloverMerchant->merchant_id)
            ->setToken($cloverMerchant->access_token);

        $cloverApiOrder = $cloverApi->merchantOrder($this->argument('cloverOrderId'));

        CloverOrder::fromCloverJson($cloverApiOrder, $storeLocation->id);
    }
}
