@extends('layouts.app')

@section('content')
  @component('admin.components.basic-page', ['title' => 'Sales Reports'])
    @slot('actions')
      <div class="d-flex justify-content-end align-items-center">
        Clover Captured Through {{ $lastCloverOrderAt }}
        <div class="dropdown ml-3">
          <button
            id="create-menu-btn"
            class="btn btn-primary dropdown-toggle"
            type="button"
            data-toggle="dropdown"
            aria-haspopup="true"
            aria-expanded="false">
            <i class="fa fa-plus mr-1"></i> Create Sales Report
          </button>
          <div id="week_selection" class="dropdown-menu" aria-labelledby="dropdownMenuButton">
            @foreach($weekOptions as $weekOption)
              <a class="dropdown-item"
                href="{{ route('admin.sales-reports.create', $weekOption['startOn'] ) }}"
              >
                {{ $weekOption['label'] }}
              </a>
            @endforeach
          </div>
        </div>
      </div>
    @endslot
    <div class="row">
      <div class="col-sm-12">
        <table class="table table-striped table-bordered">
          <thead>
          <tr>
            <th scope="col">Period</th>
            <th scope="col" class="text-right">Week</th>
            <th scope="col" class="text-right">Net Sales</th>
            <th scope="col" class="text-right">Royalties</th>
            <th scope="col" class="text-right">Locations</th>
          </tr>
          </thead>
          <tbody>
          @foreach($salesReports as $salesReport)
            <tr>
              <td>
                <a href="{{ route('admin.sales-reports.view', $salesReport->id ) }}">
                  {{ $salesReport->date_range_label }}
                </a>
              </td>
              <td class="text-right">
                {{ $salesReport->period_num }}
              </td>
              <td class="text-right">
                ${{ number_format($salesReport->net_sales, 2) }}
              </td>
              <td class="text-right">
                ${{ number_format($salesReport->royalties, 2) }}
              </td>
              <td class="text-right">
                {{ number_format($salesReport->num_locations) }}
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
    $(function () {
      $("#week_selection a").on("click", function () {
        $("#create-menu-btn").prop("disabled", true);
        $("#create-menu-btn").html(
          '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Building Report...'
        );
      });
    });
  </script>
@endpush


