<?php

namespace App\Console\Commands;

use App\Helpers\Payments;
use App\Models\StoreLocation;
use Illuminate\Console\Command;

class TestPayeezyMerchant extends Command
{
    protected $signature = 'payeezy:merchant-test {storeLocationId} {ccNum} {expMonth} {expYear} {cvv}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a live Payeezy transaction for a merchant';

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

        $payments = new Payments($storeLocation->gateway_merchant_token);

        $amount = $this->argument('storeLocationId') / 100;

        $response = $payments->setName('John Smith')
            ->setAmount($amount)
            ->setCardCvv($this->argument('cvv'))
            ->setCardExpiry($this->argument('expMonth'), $this->argument('expYear'))
            ->setCardNumber($this->argument('ccNum'))
            ->purchase();

        $this->info(print_r($response, true));
    }
}
