<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Prunable;
use Illuminate\Support\Facades\Date;

class CloverOrder extends Model
{
    use Prunable;

    protected $casts = [
        'order_created_at' => 'datetime',
        'order_modified_at' => 'datetime',
        'payment_at' => 'datetime',
    ];

    protected static function booted()
    {
        static::deleting(function ($cloverOrder) {
            $cloverOrder->cloverOrderItems()->delete();
            $cloverOrder->cloverOrderRefunds()->delete();
        });
    }

    public function storeLocation()
    {
        return $this->belongsTo(StoreLocation::class);
    }

    public function cloverOrderItems()
    {
        return $this->hasMany(CloverOrderItem::class);
    }

    public function cloverOrderRefunds()
    {
        return $this->hasMany(CloverOrderRefund::class);
    }

    static function fromCloverJson($orderElement, $storeLocationId)
    {
        if ($orderElement['paymentState'] !== 'PAID') {
            return;
        }

        $existingOrder = CloverOrder::where('transaction_id', $orderElement['id'])->first();
        if ($existingOrder) {
            $existingOrder->delete();
        }

        $payments = collect($orderElement['payments']['elements']);

        $cloverOrder = new CloverOrder();
        $cloverOrder->transaction_id = $orderElement['id'];
        $cloverOrder->device_id = isset($orderElement['device']['id'])
            ? $orderElement['device']['id']
            : null;
        $cloverOrder->store_location_id = $storeLocationId;
        $cloverOrder->order_created_at = date('Y-m-d H:i:s', $orderElement['createdTime'] / 1000);
        $cloverOrder->order_modified_at = date('Y-m-d H:i:s', $orderElement['modifiedTime'] / 1000);
        $cloverOrder->payment_at = date('Y-m-d H:i:s', $payments[0]['createdTime'] / 1000);
        $cloverOrder->tax_amount = $payments->sum('taxAmount') / 100;
        $cloverOrder->tip_amount = $payments->sum('tipAmount') / 100;
        $cloverOrder->payment_amount = isset($orderElement['total'])
            ? $orderElement['total'] / 100 + $cloverOrder->tip_amount
            : 0;
        $cloverOrder->discount_amount = 0;
        $cloverOrder->net_sales_amount =
            $cloverOrder->payment_amount - $cloverOrder->tip_amount - $cloverOrder->tax_amount;

        $cloverOrder->save();

        $lineItemTotal = 0;
        $lineItemDiscount = 0;
        if (isset($orderElement['lineItems'])) {
            foreach ($orderElement['lineItems']['elements'] as $lineItem) {
                $cloverOrderItem = new CloverOrderItem();
                $cloverOrderItem->clover_order_id = $cloverOrder->id;
                $cloverOrderItem->name = trim($lineItem['name']);
                $cloverOrderItem->category =
                    isset($lineItem['item']) &&
                    count($lineItem['item']['categories']['elements']) > 0
                        ? trim($lineItem['item']['categories']['elements'][0]['name'])
                        : '';
                $cloverOrderItem->price = $lineItem['price'] / 100;
                $cloverOrderItem->quantity = 1;
                $cloverOrderItem->is_revenue = $lineItem['isRevenue'];
                $cloverOrderItem->discount_amount = self::calculateDiscount(
                    $lineItem,
                    $cloverOrderItem['price'],
                );
                $cloverOrderItem->line_item_total_amount =
                    $cloverOrderItem->price * $cloverOrderItem->quantity -
                    $cloverOrderItem->discount_amount;

                $lineItemDiscount += $cloverOrderItem->discount_amount;
                $lineItemTotal += $cloverOrderItem->line_item_total_amount;

                $cloverOrderItem->save();
            }
        }

        $cloverOrder->discount_amount = self::calculateDiscount($orderElement, $lineItemTotal);
        if ($lineItemDiscount > 0) {
            $cloverOrder->discount_amount += $lineItemDiscount;
        }

        if ($cloverOrder->discount_amount > 0) {
            $cloverOrder->save();
        }

        if (isset($orderElement['refunds'])) {
            CloverOrderRefund::fromCloverJson($cloverOrder, $orderElement['refunds']['elements']);
        }
    }

    public static function lastOrderCreatedAt($storeLocationId)
    {
        $lastOrder = CloverOrder::where('store_location_id', $storeLocationId)
            ->orderBy('order_created_at', 'desc')
            ->first();

        return !empty($lastOrder) ? $lastOrder->order_created_at : null;
    }

    private static function calculateDiscount($element, $amount)
    {
        if (!isset($element['discounts'])) {
            return 0;
        }

        $discount = $element['discounts']['elements'][0];
        if (isset($discount['percentage'])) {
            return round(($amount * $discount['percentage']) / 100, 2);
        }

        if (isset($discount['amount'])) {
            return abs($discount['amount'] / 100);
        }

        return 0;
    }

    public static function firstOrder(bool $desc = true)
    {
        return CloverOrder::orderBy('order_created_at', $desc ? 'desc' : 'asc')->first();
    }

    public function prunable()
    {
        return static::where('created_at', '<=', Date::now()->subMonths(3));
    }
}
