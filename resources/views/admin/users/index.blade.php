@extends('layouts.app')

@section('content')
  @component('admin.components.basic-page', ['title' => 'View All Admin Users'])

    <ul class="nav nav-tabs mb-4">
      <li class="nav-item">
        <a class="nav-link {{ $role === 'admin' ? 'active' : '' }}"
          href="{{ route('admin.users.index') . '?role=admin' }}">
          Global Admins
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link {{ $role === 'store' ? 'active' : '' }}"
           href="{{ route('admin.users.index') . '?role=store' }}">
          Store Admins
        </a>
      </li>
    </ul>

    <table
      class="table table-striped table-bordered dt-responsive nowrap table-delete-action"
      id="users-table">
      <thead>
      <tr>
        <th>Username</th>
        <th>Email</th>
        <th>First Name</th>
        <th>Last Name</th>
        <th>Options</th>
      </tr>
      </thead>
    </table>

  @endcomponent
  @include('layouts/app/_delete-modal', ['item' => 'admin user'])
@endsection

@push('scripts')
<script>
    $(function() {
      $('#users-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
          url: '{!! route('admin.users.data', ['role' => $role]) !!}',
        },
        pageLength: 25,
        order: [[ 0, 'asc' ]],
        columns: [
          { data: 'username', name: 'username' },
          { data: 'email', name: 'email' },
          { data: 'first_name', name: 'first_name' },
          { data: 'last_name', name: 'last_name' },
          { data: 'actions', name: 'actions', 'orderable': false, width: '140px'  },
        ]
      });
    });
  </script>
@endpush
