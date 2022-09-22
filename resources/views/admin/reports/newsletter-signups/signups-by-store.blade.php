@extends('layouts.app')

@section('content')
  @component('admin.components.basic-page', ['title' => 'Mailing List Signups'])
    @slot('actions')
      <div class="d-flex justify-content-between">
        <ul class="nav nav-pills mr-3">
          <li class="nav-item">
            <a class="nav-link {{ $audience == null ? 'active' : '' }}"
              href="{{ route('admin.reports.signups.signups-by-store') }}">
              Meal Plan
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link {{ $audience == 'wcl' ? 'active' : '' }}"
               href="{{ route('admin.reports.signups.signups-by-store', ['audience' => 'wcl']) }}">
              WCL 2022
            </a>
          </li>
        </ul>
        <div>
          @component('admin.components.report-date-range')])
          @endcomponent
        </div>
      </div>
    @endslot
    <div class="row">
      <div class="col-sm-12">
        <table class="table table-striped table-bordered">
          <thead>
            <tr>
              <th scope="col">Store</th>
              <th scope="col" class="text-right">Total</th>
            </tr>
          </thead>
          <tbody>
            @foreach($signupsByStore as $storeSignups)
              <tr>
                <td>{{ $storeSignups->store_location }}</td>
                <td class="text-right">{{ $storeSignups->total }}</td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  @endcomponent
@endsection

@push('scripts')
  <script>
    $(function() {
      activateDateRangeControls('{!! url()->current() !!}', '{{ $defaultStart }}', '{{ $defaultEnd }}');
    });
  </script>
@endpush
