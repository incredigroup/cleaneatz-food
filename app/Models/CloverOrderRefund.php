<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CloverOrderRefund extends Model
{
    protected $casts = [
        'refunded_at' => 'datetime',
    ];

    static function fromCloverJson($refundElement)
    {
        $cloverOrder = CloverOrder::where(
            'transaction_id',
            $refundElement['orderRef']['id'],
        )->first();

        if (!$cloverOrder) {
            return;
        }

        $cloverOrderRefund = new CloverOrderRefund();
        $cloverOrderRefund->clover_order_id = $cloverOrder->id;
        $cloverOrderRefund->refunded_at = date('Y-m-d H:i:s', $refundElement['createdTime'] / 1000);
        $cloverOrderRefund->transaction_id = $refundElement['id'];
        $cloverOrderRefund->refund_amount = $refundElement['amount'] / 100;
        $cloverOrderRefund->tax_amount = isset($refundElement['taxAmount'])
            ? $refundElement['taxAmount'] / 100
            : 0;
        $cloverOrderRefund->tip_amount = isset($refundElement['tipAmount'])
            ? $refundElement['tipAmount'] / 100
            : 0;
        $cloverOrderRefund->save();
    }
}
