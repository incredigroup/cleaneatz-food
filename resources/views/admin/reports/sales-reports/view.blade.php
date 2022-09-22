@extends('layouts.app')

@section('content')
  @component('admin.components.basic-page', ['title' => "Sales Report for {$salesReport->starts_at->year} Week {$salesReport->period_num}"])
    @slot('actions')
      <div class="text-right">
        <div class="dropdown">
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
               href="{{ route('admin.sales-reports.export.excel', $salesReport->id ) }}"
            >
              Export to Excel
            </a>
            <a class="dropdown-item"
               href="{{ route('admin.sales-reports.export.ach', $salesReport->id ) }}"
            >
              Export ACH Report
            </a>
            <div class="dropdown-divider"></div>
            <form action="{{ route('admin.sales-reports.delete', $salesReport->id ) }}" method="POST">
              {{ method_field('DELETE') }}
              {{ csrf_field() }}
              <button type='submit' class="dropdown-item" value="delete">Delete Report</button>
            </form>
          </div>
        </div>
      </div>
    @endslot
    <div class="row mb-1">
      <div class="col-sm-3">
        <strong>Report Date:</strong>
      </div>
      <div>
        {{ $salesReport->report_date }}
      </div>
    </div>
    <div class="row mb-1">
      <div class="col-sm-3">
        <strong>Report Pull Date:</strong>
      </div>
      <div>
        {{ $salesReport->date_time_range_label }}
      </div>
    </div>
    <div class="row mt-4">
      <div class="col-sm-12">
        <div class="table-responsive">
          <table class="table table-striped table-bordered">
            <thead class="thead-dark">
            <tr>
              <th scope="col">Location</th>
              <th scope="col" class="text-right">Clover Sales</th>
              <th scope="col" class="text-right">Online Sales</th>
              <th scope="col" class="text-right">Total Sales</th>
              <th scope="col" class="text-right" colspan="2">Royalties</th>
              <th scope="col" class="text-right" colspan="2">Ad Fund</th>
              <th scope="col" class="text-right" colspan="2">Advisory</th>
            </tr>
            </thead>
            <tbody>
              @foreach($salesReportLocations as $salesReportLocation)
                <tr>
                  <td>
                    {{ $salesReportLocation->storeLocation->locale }}
                  </td>
                  <td class="text-right">
                    <span
                      class="with-tooltip"
                      style="cursor: pointer"
                      data-toggle="tooltip"
                      data-placement="top"
                      data-html="true"
                      title="Refunded: ${{ number_format($salesReportLocation->net_sales_refunds_pos, 2) }}<br>Non Revenue: ${{ number_format($salesReportLocation->non_revenue_pos, 2) }}"
                    >
                      ${{ number_format($salesReportLocation->net_sales_pos, 2) }}
                    </span>
                  </td>
                  <td class="text-right">
                    <span
                        class="with-tooltip"
                        style="cursor: pointer"
                        data-toggle="tooltip"
                        data-placement="top"
                        data-html="true"
                        title="Refunded: ${{ number_format($salesReportLocation->net_sales_refunds_online, 2) }}"
                    >
                      ${{ number_format($salesReportLocation->net_sales_online, 2) }}
                    </span>
                  </td>
                  <td class="text-right">
                    ${{ number_format($salesReportLocation->net_sales_pos + $salesReportLocation->net_sales_online, 2) }}
                  </td>
                  <td class="text-right">
                    {{ $salesReport->royalty_meta['fee_tier_amounts'][$salesReportLocation->royalty_tier - 1][0] }}%
                  </td>
                  <td class="text-right">
                    ${{ number_format($salesReportLocation->royalties_1, 2) }}
                  </td>
                  <td class="text-right">
                    {{ $salesReport->royalty_meta['fee_tier_amounts'][$salesReportLocation->royalty_tier - 1][1] }}%
                  </td>
                  <td class="text-right">
                    ${{ number_format($salesReportLocation->royalties_2, 2) }}
                  </td>
                  <td class="text-right">
                    {{ $salesReport->royalty_meta['fee_tier_amounts'][$salesReportLocation->royalty_tier - 1][2] }}%
                  </td>
                  <td class="text-right">
                    ${{ number_format($salesReportLocation->royalties_3, 2) }}
                  </td>
                </tr>
              @endforeach
            </tbody>
            <tfoot>
            <tr class="table-secondary">
              <td>
                <strong>Totals</strong>
              </td>
              <td class="text-right">
                <strong>${{ number_format($salesReportLocationTotals->net_sales_pos, 2) }}</strong>
              </td>
              <td class="text-right">
                <strong>${{ number_format($salesReportLocationTotals->net_sales_online, 2) }}</strong>
              </td>
              <td class="text-right">
                <strong>${{ number_format($salesReportLocationTotals->net_sales_pos + $salesReportLocationTotals->net_sales_online, 2) }}</strong>
              </td>
              <td class="text-right" colspan="2">
                <strong>${{ number_format($salesReportLocationTotals->royalties_1, 2) }}</strong>
              </td>
              <td class="text-right" colspan="2">
                <strong>${{ number_format($salesReportLocationTotals->royalties_2, 2) }}</strong>
              </td>
              <td class="text-right" colspan="2">
                <strong>${{ number_format($salesReportLocationTotals->royalties_3, 2) }}</strong>
              </td>
            </tr>
            </tfoot>
          </table>
        </div>
      </div>
    </div>
  @endcomponent
@endsection


@push('scripts')
  <script>
    $(function() {
      $('span.with-tooltip').tooltip();
    });
  </script>
@endpush
