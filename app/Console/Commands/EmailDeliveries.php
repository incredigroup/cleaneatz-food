<?php

namespace App\Console\Commands;

use App\{Models\MealPlan, Models\MealPlanOrder, Models\StoreLocation};
use App\Mail\DeliveryReport;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Laracsv\Export;

class EmailDeliveries extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:deliveries';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sends weekly delivery report';

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
        $deliveryStores = StoreLocation::enabled()
            ->where('delivers', 1)
            ->get();

        foreach ($deliveryStores as $storeLocation) {
            $this->info('Delivery Email sent for ' . $storeLocation->locale);
            $this->emailForStore($storeLocation->id);
        }
    }

    public function emailForStore($storeId)
    {
        $currentMealPlan = MealPlan::current()->first();

        if ($currentMealPlan === null) {
            $currentMealPlan = MealPlan::orderBy('expires_on', 'desc')->first();
        }

        $orders = MealPlanOrder::forMealPlan($currentMealPlan->id, true)
            ->live()
            ->where('meal_plan_orders.store_location_id', '=', $storeId)
            ->orderBy('created_at')
            ->get();

        if ($orders->isEmpty()) {
            return;
        }

        $csvExporter = new Export();

        $csvExporter->beforeEach(function ($order) {
            $order->delivery_date = Carbon::now()->format('m/d/Y');
            $order->location = $order->store->locale;

            // order delivery instructions
            $cartId = $order->meal_plan_cart_id;
            $transactionId = $order->transaction_id;
            $order->special_delivery_instructions = "Cart $cartId /// Order $transactionId";

            // order details
            $order->details = '';
            foreach ($order->items as $item) {
                $order->details .= $item->meal_name . ' (' . $item->quantity . 'x), ';
            }

            $order->details .=
                ' /// ' . $order->store->locale . ' /// Meal Plan Total: ' . $order->total;
        });

        $csvExporter->build($orders, [
            'delivery_date',
            'name',
            'address',
            'address2',
            'city',
            'state',
            'zip',
            'phone',
            'special_delivery_instructions',
            'location',
            'details',
        ]);

        $writer = $csvExporter->getWriter();
        $csv = $writer->getContent();

        $to = explode(',', env('DELIVERY_EMAILS_TO'));
        $cc = explode(',', env('DELIVERY_EMAILS_CC'));

        Mail::to($to)
            ->cc($cc)
            ->send(new DeliveryReport($orders, $csv));
    }
}
