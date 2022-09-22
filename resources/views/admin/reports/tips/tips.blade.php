@extends('layouts.app')

@section('content')
  @component('admin.components.basic-page', ['title' => 'All Tips For All Stores', 'subtitle' => 'Past 14 Days'])
    <div class="row">
      <div class="col-sm-12">
        <table class="table">
          <thead>
            <tr>
              <th scope="col">Date</th>
              <th scope="col">Total</th>
            </tr>
          </thead>
          <tbody>
            @foreach($tips as $date => $tip)
              <tr>
                <td>{{ $date }}</td>
                <td>${{ number_format($tip, 2) }}</td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  @endcomponent
@endsection
