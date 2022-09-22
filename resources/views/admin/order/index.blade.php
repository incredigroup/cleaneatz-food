@extends('layouts.app')

@section('content')
  @component('admin.components.basic-page', ['title' => 'Meal Plan Orders'])
    @slot('actions')
      <div class="d-flex justify-content-end align-items-center">
        <form class="form-inline">
          <select class="form-control" name="storeLocationId">
            <option value="">All Locations</option>
            @foreach (\App\Models\StoreLocation::locationOptions() as $k=>$v)
              <option value="{{ $k }}" {{ $k == $storeLocationId ? 'selected' : '' }}>
                {{ $v }}
              </option>
            @endforeach
          </select>
          <button type="submit" class="btn btn-primary ml-3">Select</button>
        </form>
      </div>
    @endslot

    <div class="livewire-table">
      <livewire:admin.order.orders-table :store-location-id="$storeLocationId" />
    </div>

  @endcomponent

@endsection

@push('scripts')

@endpush
