<?php

namespace App\Console\Commands;

use App\Models\CloverMerchant;
use App\Models\StoreLocation;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class FranchiseStreamMerchantImport

    extends Command
{
    protected $signature = 'franchisestream:merchants-import';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Loads Franchise Stream merchant data';

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
        $response = Http::withToken(config('services.franchisestream.api_token'))
            ->get(config('services.franchisestream.api_url') . '/clover-merchants');

        $franchiseStreamCloverMerchants = json_decode($response->getBody(), true);

        foreach ($franchiseStreamCloverMerchants as $franchiseStreamCloverMerchant) {
            $existing = CloverMerchant::where('merchant_id', $franchiseStreamCloverMerchant['merchant_id'])->first();

            if (!$existing) {
                $this->addCloverMerchant($franchiseStreamCloverMerchant);
            }
        }
    }

    private function addCloverMerchant($franchiseStreamCloverMerchant)
    {
        $storeLocations = StoreLocation::where('city', $franchiseStreamCloverMerchant['city'])
            ->where('state', $franchiseStreamCloverMerchant['state'])
            ->get();

        $franchiseStreamCloverMerchant['store_location_id'] = count($storeLocations) === 1 ?
            $storeLocations[0]->id : null;

        CloverMerchant::create($franchiseStreamCloverMerchant);
    }
}
