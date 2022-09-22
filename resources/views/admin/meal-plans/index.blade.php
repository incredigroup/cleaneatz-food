@extends('layouts.app')

@section('content')
  @component('admin.components.basic-page', ['title' => 'View All Menus'])
    <table
      class="table table-striped table-bordered dt-responsive table-delete-action"
      id="meal-plans-table">
      <thead>
      <tr>
        <th>Name</th>
        <th>Start</th>
        <th>End</th>
        <th>Options</th>
      </tr>
      </thead>
    </table>
  @endcomponent
  @include('layouts/app/_delete-modal', ['item' => 'meal plan'])
@endsection

@push('scripts')
  <script>
    $(function() {
      $('#meal-plans-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
          url: '{!! route('admin.meal-plans.data') !!}',
        },
        order: [[ 2, 'desc' ]],
        columns: [
          { data: 'name', name: 'name' },
          { data: 'available_on', name: 'available_on' },
          { data: 'expires_on', name: 'expires_on' },
          { data: 'actions', name: 'actions', 'orderable': false, width: '140px' },
        ]
      });
    });
  </script>
@endpush
