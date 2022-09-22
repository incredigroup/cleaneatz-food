@extends('layouts.app')

@section('content')
  @component('admin.components.basic-page', ['title' => 'Approve Promo Codes'])
    @if (count($promoCodes))
    <table
      class="table table-striped table-bordered"
      id="approve-promo-codes-table">
      <thead>
      <tr>
        <th>Store</th>
        <th>Code</th>
        <th>Discount</th>
        <th>Timeframe</th>
      </tr>
      </thead>
      <tbody>
        @foreach ($promoCodes as $promoCode)
          <tr>
            <td>
              {{ $promoCode->storeLocation->locale }}<br>
            </td>
            <td>
              {{ $promoCode->code }}<br>
            </td>
            <td>
              {{ $promoCode->discountText }}<br>
            </td>
            <td>
              {{ $promoCode->timeframeText }}<br>
            </td>
            <td>
              <form method="post" action="{{ route('admin.promo-codes.approve', $promoCode->id) }}">
                @csrf
                <button type="submit" class="btn btn-success">Approve</button>
              </form>
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
    @else
      You do not have any promo codes waiting for approval.
    @endif
  @endcomponent
@endsection

