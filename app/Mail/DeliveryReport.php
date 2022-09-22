<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Carbon\Carbon;

class DeliveryReport extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($orders, $csv)
    {
        $this->orders = $orders;
        $this->csv = $csv;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        if ($this->orders->count() === 0) {
            return;
        }

        $city = $this->orders[0]->store->city;
        $date = Carbon::now()->format('Y-m-d');

        $fileName = "$date-$city-Delivery-Orders.csv";

        return $this->text('site.emails.delivery-report', ['orders' => $this->orders])
            ->attachData($this->csv, $fileName, [
                    'mime' => 'text/csv',
                ]);
    }
}
