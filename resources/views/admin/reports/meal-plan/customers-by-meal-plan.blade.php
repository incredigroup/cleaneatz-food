@extends('layouts.app')

@section('content')
  @component('admin.components.basic-page', ['title' => 'Customers by Menu'])
    @slot('actions')
      <div class="text-right">
        @include('admin.reports._meal-plan-selection', ['route' => 'admin.reports.meal-plan.customers-by-meal-plan'])
        <a href="{{ route('admin.reports.meal-plan.customers-by-meal-plan.export', ['mealPlanId' => $mealPlan->id]) }}"
          class="btn btn-primary">
          <i class="fa fa-download"></i> Export
        </a>
      </div>
    @endslot
    <table
      class="table table-striped table-bordered"
      id="meal-plan-customers-table">
      <thead>
      <tr>
        <th>Email</th>
        <th>First Name</th>
        <th>Last Name</th>
        <th>Location</th>
      </tr>
      </thead>
    </table>
  @endcomponent
@endsection
@push('scripts')
  <script>
    $(function() {
      $('#meal-plan-customers-table').DataTable({
        processing: true,
        serverSide: true,
        searching: false,
        ajax: {
          url: '{!! route('admin.reports.meal-plan.customers-by-meal-plan.data', ['mealPlanId' => $mealPlan->id]) !!}',
        },
        order: [[ 0, 'asc' ]],
        columns: [
          { data: 'email', name: 'email'},
          { data: 'first_name', name: 'first_name' },
          { data: 'last_name', name: 'last_name' },
          { data: 'city', name: 'city' },
        ]
      });
    });
  </script>
@endpush
