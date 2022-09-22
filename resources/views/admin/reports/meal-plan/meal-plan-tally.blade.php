@extends('layouts.app')

@section('content')
  @component('admin.components.basic-page', ['title' => 'Menu Tally'])
    @slot('actions')
      <div class="text-right">
        @include('admin.reports._meal-plan-selection', ['route' => 'admin.reports.meal-plan.meal-plan-tally'])
        <a href="{{ route('admin.reports.meal-plan.meal-plan-tally.export', ['mealPlanId' => $mealPlan->id]) }}"
          class="btn btn-primary">
          <i class="fa fa-download"></i> Export
        </a>
      </div>
    @endslot
    <table
      class="table table-striped table-bordered"
      id="meal-plan-tally-table">
      <thead>
      <tr>
        <th>Location Name</th>
        <th>Meal Name</th>
        <th>Meals Sold</th>
        <th>Subtotal</th>
      </tr>
      </thead>
    </table>
  @endcomponent
@endsection
@push('scripts')
  <script>
    $(function() {
      $('#meal-plan-tally-table').DataTable({
        processing: true,
        serverSide: true,
        searching: false,
        ajax: {
          url: '{!! route('admin.reports.meal-plan.meal-plan-tally.data', ['mealPlanId' => $mealPlan->id]) !!}',
        },
        pageLength: 25,
        order: [[ 0, 'asc' ]],
        columns: [
          { data: 'city', name: 'city'},
          { data: 'meal_name', name: 'meal_name' },
          { data: 'quantity', name: 'quantity', class: 'text-right' },
          { data: 'subtotal', name: 'subtotal', class: 'text-right' },
        ]
      });
    });
  </script>
@endpush
