<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderRefundMail extends Mailable
{
    use Queueable, SerializesModels;

    public $mealPlanRefund;

    public function __construct($mealPlanRefund)
    {
        $this->mealPlanRefund = $mealPlanRefund;
    }

    public function build()
    {
        return $this->subject('Clean Eatz meal plan refund processed')->markdown(
            'site.emails.order-refund',
        );
    }
}
