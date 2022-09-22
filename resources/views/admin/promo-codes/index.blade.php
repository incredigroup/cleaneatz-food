@extends('layouts.app')

@section('content')
  @component('admin.components.basic-page', ['title' => 'View All Promo Codes'])
    <table
      class="table table-striped table-bordered dt-responsive nowrap table-delete-action"
      id="promo-codes-table">
      <thead>
      <tr>
        <th>Parent</th>
        <th>Code</th>
        <th>Amount</th>
        <th>Percent</th>
        <th>Min Meals Req</th>
        <th>Start On</th>
        <th>End On</th>
        <th>Options</th>
      </tr>
      </thead>
    </table>
  @endcomponent
  @include('layouts/app/_delete-modal', ['item' => 'promo code'])
@endsection

@push('scripts')
<script>
    $(function() {
      $('#promo-codes-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
          url: '{!! route('admin.promo-codes.data') !!}',
        },
        order: [[ 0, 'asc' ]],
        columns: [
          { data: 'store_location.city', name: 'storeLocation.city' },
          { data: 'code', name: 'code' },
          { data: 'discount_amount', name: 'discount_amount' },
          { data: 'discount_percent', name: 'discount_percent' },
          { data: 'min_meals_required', name: 'min_meals_required' },
          { data: 'start_on', name: 'start_on' },
          { data: 'end_on', name: 'end_on' },
          { data: 'actions', name: 'actions', 'orderable': false, width: '140px'  },
          { data: 'match_type', name: 'match_type', visible: false },
        ]
      });
    });
  </script>
@endpush
