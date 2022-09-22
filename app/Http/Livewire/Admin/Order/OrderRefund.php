<?php

namespace App\Http\Livewire\Admin\Order;

use Livewire\Component;

class OrderRefund extends Component
{
    public $order;

    public $originalAmounts;
    public $refundAmounts;
    public $salesTaxRate;

    public $fields = [
        'net_refund' => 'Meals',
        'tip_amount' => 'Tips',
        'satellite_fee' => 'Satellite Fee',
        'tax' => 'Sales Tax',
    ];

    public function mount()
    {
        $this->originalAmounts = [
            'net_refund' => $this->order->discounted_subtotal,
            'tip_amount' => $this->order->tip_amount,
            'satellite_fee' => $this->order->satellite_fee,
            'tax' => $this->order->tax,
        ];

        $this->refundAmounts = $this->originalAmounts;

        $this->formatRefundAmountsAsCurrency();

        $this->salesTaxRate = round($this->order->tax / $this->order->discounted_subtotal, 2) * 100;
    }

    public function render()
    {
        return view('livewire.admin.order.order-refund');
    }

    public function getOriginalTotalProperty()
    {
        return collect($this->originalAmounts)
            ->values()
            ->map(fn($value) => floatval($value))
            ->sum();
    }

    public function getRefundTotalProperty()
    {
        return collect($this->refundAmounts)
            ->values()
            ->map(fn($value) => floatval($value))
            ->sum();
    }

    public function getAdjustedTotalProperty()
    {
        return $this->getOriginalTotalProperty() - $this->getRefundTotalProperty();
    }

    public function refundAmountChanged()
    {
        collect($this->fields)
            ->keys()
            ->each(
                fn($field) => ($this->refundAmounts[$field] = max(
                    (float) min($this->originalAmounts[$field], $this->refundAmounts[$field]),
                    0,
                )),
            );

        $this->refundAmounts['tax'] =
            $this->refundAmounts['net_refund'] * ($this->salesTaxRate / 100);

        $this->formatRefundAmountsAsCurrency();
    }

    private function formatRefundAmountsAsCurrency()
    {
        collect($this->fields)
            ->keys()
            ->each(
                fn($field) => ($this->refundAmounts[$field] = number_format(
                    $this->refundAmounts[$field],
                    2,
                )),
            );
    }
}
