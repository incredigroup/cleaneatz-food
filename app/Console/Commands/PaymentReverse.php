<?php

namespace App\Console\Commands;

use App\Models\MealPlanOrder;
use Illuminate\Console\Command;

class PaymentReverse extends Command
{
    protected $signature = 'payment:reverse {mealPlanOrderId}';

    /**
     * The console command description.
     * Ex: php artisan payment:reverse 381559
     *
     * @var string
     */
    protected $description = 'Void or refund a live order';

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
        $mealPlanOrder = MealPlanOrder::findOrFail($this->argument('mealPlanOrderId'));
        $response = $mealPlanOrder->cancelOrRefund();

        $this->info(print_r($response, true));
    }
}
