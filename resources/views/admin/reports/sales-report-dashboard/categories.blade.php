@extends('layouts.app')

@section('content')
  @component('admin.components.basic-page', ['title' => 'Sales Report Dashboard: Categories'])
    @slot('actions')
      <div class="d-flex justify-content-end align-items-center">
        <form class="form-inline mb-5">
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

    <div class="row">
      <div class="col-sm-12">
        <sales-by-category store-location-id="{{ $storeLocationId }}"></sales-by-category>
      </div>
    </div>

  @endcomponent
@endsection
