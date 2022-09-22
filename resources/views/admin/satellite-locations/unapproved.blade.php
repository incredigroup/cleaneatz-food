@extends('layouts.app')

@section('content')
  @component('admin.components.basic-page', ['title' => 'Approve Satellite Locations'])
    @if (count($satelliteLocations))
    <table
      class="table table-striped table-bordered"
      id="approve-satellite-table">
      <thead>
      <tr>
        <th>Store</th>
        <th>City</th>
        <th>State</th>
        <th>Zip</th>
        <th>Address</th>
        <th>Name</th>
        <th>Options</th>
      </tr>
      </thead>
      <tbody>
        @foreach ($satelliteLocations as $satelliteLocation)
          <tr>
            <td>
              {{ $satelliteLocation->storeLocation->wp_store_location->name }}<br>
              {{ $satelliteLocation->storeLocation->wp_store_location->email }}<br>
              {{ $satelliteLocation->storeLocation->wp_store_location->phone }}<br>
            </td>
            <td>{{ $satelliteLocation->city }}</td>
            <td>{{ $satelliteLocation->state }}</td>
            <td>{{ $satelliteLocation->zip }}</td>
            <td>{{ $satelliteLocation->address }}</td>
            <td>{{ $satelliteLocation->name }}</td>
            <td>
              <form method="post" action="{{ route('admin.satellite-locations.approve', $satelliteLocation->id) }}">
                @csrf
                <button type="submit" class="btn btn-success">Approve</button>
              </form>
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
    @else
      You do not have any satellite locations waiting for approval.
    @endif
  @endcomponent
@endsection

