@extends('layouts.app')

@section('content')
  @component('admin.components.basic-page', ['title' => 'View All Satellites'])
    <table
      class="table table-striped table-bordered dt-responsive table-delete-action"
      id="satellite-table">
      <thead>
      <tr>
        <th>Parent</th>
        <th>Name</th>
        <th>Address</th>
        <th>City</th>
        <th>State</th>
        <th>Zip</th>
        <th>Options</th>
      </tr>
      </thead>
    </table>
  @endcomponent
  @include('layouts/app/_delete-modal', ['item' => 'satellite location'])
@endsection

@push('scripts')
  <script>
    $(function() {
      $('#satellite-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
          url: '{!! route('admin.satellite-locations.data') !!}',
        },
        columns: [
          { data: 'store_location.city', name: 'storeLocation.city' },
          { data: 'name', name: 'name' },
          { data: 'address', name: 'address' },
          { data: 'city', name: 'city' },
          { data: 'state', name: 'state' },
          { data: 'zip', name: 'zip' },
          { data: 'actions', name: 'actions', 'orderable': false },
        ]
      });
    });
  </script>
@endpush
