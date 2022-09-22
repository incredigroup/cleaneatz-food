@extends('layouts.app')

@section('content')
  @component('admin.components.basic-page', ['title' => 'View All Meals'])
    <table
      class="table table-striped table-bordered dt-responsive nowrap table-delete-action"
      id="meals-table">
      <thead>
      <tr>
        <th>Name</th>
        <th>Description</th>
        <th>Added On</th>
        <th>Options</th>
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
          url: '{!! route('admin.meals.data') !!}',
        },
        order: [[ 2, 'desc' ]],
        columns: [
          { data: 'name', name: 'name' },
          { data: 'description', name: 'description' },
          { data: 'created_at', name: 'created_at' },
          { data: 'actions', name: 'actions', 'orderable': false, width: '140px'  },
        ]
      });
    });
  </script>
@endpush
