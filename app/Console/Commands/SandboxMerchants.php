<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class SandboxMerchants extends Command
{
    protected $signature = 'payeezy:sandbox-merchants';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Resets all store locations to use Payeezy sandbox tokens for merchants';

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
        if (!\App::environment(['local', 'staging'])) {
            $this->error('This command can only be run locally or on dev!');
            return;
        }

        $token = env('PAYEEZY_SANDBOX_MERCHANT_TOKEN', false);
        $transArmorToken = env('PAYEEZY_SANDBOX_MERCHANT_TRANSARMOR_TOKEN', false);

        if (!$token || !$transArmorToken) {
            $this->error('Add PAYEEZY_SANDBOX_MERCHANT_TOKEN and PAYEEZY_SANDBOX_MERCHANT_TRANSARMOR_TOKEN to .env file');
            return;
        }

        DB::table('store_locations')
            ->update([
                    'gateway_merchant_token' => $token,
                    'gateway_merchant_transarmor_token' => $transArmorToken,
                ]
            );

        $this->info("Merchants have been set to sandbox token $token and TransArmor token $transArmorToken");
    }
}
