@extends('layouts.app')

@section('content')
  @component('admin.components.basic-page', ['title' => 'Current Add-On Items'])
    <table
      class="table table-striped table-bordered dt-responsive nowrap table-delete-action"
      id="meals-table">
      <thead>
      <tr>
        <th>Name</th>
        <th>Description</th>
        <th>Price</th>
        <th>Actions</th>
      </tr>
      </thead>
    </table>
  @endcomponent
  @include('layouts/app/_delete-modal', ['item' => 'meal'])
@endsection

@push('scripts')
<script>
    $(function() {
      $('#meals-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
          url: '{!! route('admin.add-ons.data') !!}',
        },
        order: [[ 0, 'asc' ]],
        columns: [
          { data: 'name', name: 'name' },
          { data: 'group_desc', name: 'group_desc' },
          { data: 'price_override', name: 'price_override' },
          { data: 'actions', name: 'actions', 'orderable': false, width: '140px'  },
        ]
      });
    });
  </script>
@endpush
