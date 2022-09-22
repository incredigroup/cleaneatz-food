<?php

namespace App\Console\Commands;

use App\Mail\CustomOrderNotification;
use App\Mail\OrderComplete;
use App\Models\MealPlanOrder;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

/**
 * Example:
 * php artisan email:test gary@curlytech.com custom-order-notification 355234
 * php artisan email:test gary@curlytech.com order-invoice 851122
 */
class TestEmail extends Command
{
    /**
     * The name and signature of the console command.
     * @var string
     */
    protected $signature = 'email:test {recipient} {type : order-invoice, custom-order-notification} {id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create and send a test email';

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
        switch ($this->argument('type')) {
            case 'order-invoice':
                $this->sendOrderInvoiceEmail();
                break;

            case 'custom-order-notification':
                $this->sendCustomOrderNotificationEmail();
                break;
        }
    }

    private function sendOrderInvoiceEmail()
    {
        $order = MealPlanOrder::findOrFail($this->argument('id'));

        Mail::to($this->argument('recipient'))->queue(new OrderComplete($order));
    }

    private function sendCustomOrderNotificationEmail()
    {
        $order = MealPlanOrder::findOrFail($this->argument('id'));

        Mail::to($this->argument('recipient'))->queue(new CustomOrderNotification($order));
    }
}
