@extends('layouts.app')

@section('content')
  @component('admin.components.basic-page', ['title' => 'Non-Revenue Charges'])
    @slot('actions')
      <div class="text-right d-flex">
        @component('admin.components.report-date-range')])
        @endcomponent
          <div class="ml-3">
            <button
                class="btn btn-primary dropdown-toggle"
                type="button"
                data-toggle="dropdown"
                aria-haspopup="true"
                aria-expanded="false">
              Options
            </button>
            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
              <a class="dropdown-item"
                 href="{{ route('admin.reports.orders.non-revenue-charges.export', ['start_date' => $routeParams['start_date'], 'end_date' => $routeParams['end_date']]) }}"
              >
                Export to Excel
              </a>
            </div>
          </div>
      </div>
    @endslot
    <div class="row">
      <div class="col-sm-12">
        <table class="table table-striped table-bordered">
          <thead>
          <tr>
            <th scope="col">Store</th>
            <th scope="col">Payeezy ID</th>
            <th scope="col" class="text-right">Tips</th>
            <th scope="col" class="text-right">Sales Tax</th>
            <th scope="col" class="text-right">Total</th>
          </tr>
          </thead>
          <tbody>
            @foreach($storeLocationTotals as $storeLocation)
              <tr>
                <td>{{ \App\Models\StoreLocation::buildNameFromObject($storeLocation) }}</td>
                <td>{{ $storeLocation->gateway_merchant_name }}</td>
                <td class="text-right">${{ number_format($storeLocation->tip_amount, 2) }}</td>
                <td class="text-right">${{ number_format($storeLocation->tax, 2) }}</td>
                <td class="text-right">
                  @if ($storeLocation->refund_total)
                    <span
                        class="with-tooltip"
                        style="cursor: pointer"
                        data-toggle="tooltip"
                        data-placement="top"
                        data-html="true"
                        title="Includes refunds of ${{ number_format($storeLocation->refund_total, 2) }}"
                    >
                      <i class="fa fa-info-circle mr-1"></i>
                    </span>

                  @endif
                  <strong>${{ number_format($storeLocation->total, 2) }}</strong>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  @endcomponent
@endsection

@push('scripts')
  <script>
    $(function() {
      activateDateRangeControls('{!! url()->current() !!}', '{{ $defaultStart }}', '{{ $defaultEnd }}');

      $('span.with-tooltip').tooltip();
    });
  </script>
@endpush
