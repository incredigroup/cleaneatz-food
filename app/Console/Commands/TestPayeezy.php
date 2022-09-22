<?php

namespace App\Console\Commands;

use App\Helpers\PaymentManager;
use App\Models\StoreLocation;
use Illuminate\Console\Command;

class TestPayeezy extends Command
{
    /**
     * The name and signature of the console command.
     *
     * Usage:
     * payeezy:test
     * payeezy:test --response=declined
     * payeezy:test --response=error
     *
     * @var string
     */
    protected $signature = 'payeezy:test {--response=approved : approved, declined, error}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a test sandbox Payeezy transaction';

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
        $storeLocation = StoreLocation::ofCode('3547285')->first();

        $paymentManager = new PaymentManager($storeLocation->gateway_merchant_token);

        $amount = null;
        switch ($this->option('response')) {
            case 'declined':
                $amount = 5521.00;
                break;

            case 'error':
                $amount = 5000.42;
                break;

            default:
                $amount = 12.00;
        }

        $response = $paymentManager->setName('John Smith')
            ->setAmount($amount)
            ->setCardExpiry('3', '2022')
            ->setCardType(PaymentManager::TYPE_VISA)
            ->setPaymentToken('9495846215171111')
            ->purchase();

        $this->info(print_r($response, true));
    }
}
