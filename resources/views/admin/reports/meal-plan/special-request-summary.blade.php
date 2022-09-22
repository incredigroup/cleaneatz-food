@extends('layouts.app')

@section('content')
  @component('admin.components.basic-page', ['title' => 'Special Request Summary'])
    @slot('actions')
      <div class="text-right">
        @include('admin.reports._meal-plan-selection', ['route' => 'admin.reports.meal-plan.special-request-summary'])
      </div>
    @endslot
    <table
      class="table table-striped table-bordered">
      <thead>
      <tr>
        <th>Meal Name</th>
        <th class="text-right">Quantity</th>
        <th class="text-right">Amount</th>
        <th class="text-right">% of Total</th>
      </tr>
      </thead>
      <tbody>
      @foreach($optionTotals as $option)
        <tr>
          <td>{{ $option->name }}</td>
          <td class="text-right">{{ number_format($option->quantity) }}</td>
          <td class="text-right">${{ number_format($option->subtotal, 2) }}</td>
          <td class="text-right">
            @if ($totalSales > 0)
              {{ $totalSales > 0 ? number_format($option->subtotal / $totalSales * 100, 1) : 'n/a' }}%
            @else
              --
            @endif
          </td>
        </tr>
      @endforeach
      </tbody>
    </table>
  @endcomponent
@endsection
