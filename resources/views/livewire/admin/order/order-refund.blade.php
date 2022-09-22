<div>

  <x-admin.order.order-invoice-header :order="$this->order"></x-admin.order.order-invoice-header>

  <hr class="my-3" />

  <div class="card my-4 pr-3">
    <div class="d-flex">
      <table>
        <tr>
          <th class="p-3 text-right">
            Charge For
          </th>
          <th class="p-3 text-right">
            Current
          </th>
          <th class="p-3 text-center">
            Refund
          </th>
          <th class="p-3 text-right">
            New Total
          </th>
        </tr>
        @foreach($fields as $k=>$v)
          <tr>
            <td class="p-3 text-right">
              @if ($k == 'tax')
                {{ number_format($salesTaxRate, 2) }}%
              @endif
              {{ $v }}:
            </td>
            <td class="p-3 text-right">${{ number_format($originalAmounts[$k], 2) }}</td>
            <td class="d-flex align-items-center py-3">
              <span class="pr-3"><strong>-</strong></span>
              <div class="input-group flex-nowrap">
                <div class="input-group-prepend">
                  <div class="input-group-text">$</div>
                </div>
                <input
                    style="max-width: 100px; min-width: 100px"
                    wire:model="refundAmounts.{{ $k }}"
                    wire:change="refundAmountChanged"
                    type="number"
                    step=".01"
                    class="form-control text-right"
                    {{ ($k == 'tax') ? 'readonly' : ''  }}
                >
              </div>
              <span class="pl-3"><strong>=</strong></span>
            </td>
            <td class="p-3 text-right">
              ${{ number_format(floatval($originalAmounts[$k]) - floatval($refundAmounts[$k]), 2) }}
            </td>
          </tr>
        @endforeach

        <tr>
          <td colspan="1"></td>
          <td class="p-3 pl-0 text-right font-weight-bold">
            ${{ number_format($this->originalTotal, 2) }}
          </td>
          <td class="text-right font-weight-bold text-nowrap">
            -
            <span style="display:inline-block; width: 160px;padding-right:40px">${{ number_format($this->refundTotal, 2) }}</span> =
          </td>
          <td class="p-3 pl-0 text-right font-weight-bold">
            ${{ number_format($this->adjustedTotal, 2) }}
          </td>
        </tr>

      </table>
    </div>
  </div>

  <form class="disable-on-submit" method="post">
    @csrf
    <input type="hidden" name="total_refund" value="{{ $this->refundTotal }}">
    <input type="hidden" name="net_refund" value="{{ $refundAmounts['net_refund'] }}">
    <input type="hidden" name="tip_amount" value="{{ $refundAmounts['tip_amount'] }}">
    <input type="hidden" name="satellite_fee" value="{{ $refundAmounts['satellite_fee'] }}">
    <input type="hidden" name="tax" value="{{ $refundAmounts['tax'] }}">
    <div class="form-group">
      <label for="notes">Internal Notes (optional, not visible to customer)</label>
      <textarea name="notes" class="form-control" id="notes" rows="2"></textarea>
    </div>
    <div class="form-check  mb-5">
      <input class="form-check-input" type="checkbox" name="notify_customer" value="1" checked>
      <label class="form-check-label">
        Send notification email to customer
      </label>
    </div>

    <div class="d-flex">
      <a
        href="{{ route('admin.orders.show', $order->id) }}"
        class="btn btn-link mr-3">
        Cancel
      </a>
      <button
        type="submit"
        class="btn btn-primary"
      >
        Refund ${{ number_format($this->refundTotal, 2) }}
      </button>
    </div>
  </form>

</div>

