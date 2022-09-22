@extends('layouts.app')

@section('content')
  @component('admin.components.basic-page', ['title' => 'Global Promo Code Uses'])
    <table
      class="table table-striped table-bordered"
      id="promo-code-uses-table">
      <thead>
      <tr>
        <th>Name</th>
        <th>Code</th>
        <th>Total Discount</th>
        <th>Total Uses</th>
      </tr>
      </thead>
    </table>
  @endcomponent
@endsection
@push('scripts')
  <script>
    $(function() {
      $('#promo-code-uses-table').DataTable({
        processing: true,
        serverSide: true,
        searching: false,
        ajax: {
          url: '{!! route('admin.reports.promo-code.promo-code-uses.data') !!}',
        },
        pageLength: 25,
        order: [[ 0, 'asc' ]],
        columns: [
          { data: 'name', name: 'name' },
          { data: 'code', name: 'code' },
          { data: 'amount', name: 'amount', class: 'text-right' },
          { data: 'num_orders', name: 'num_orders', class: 'text-right' },
        ]
      });
    });
  </script>
@endpush
