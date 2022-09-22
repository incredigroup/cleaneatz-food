@if (empty($storeLocation))
  <a href="{{ route($routeName . '.edit', $id) }}" class="btn btn-primary">Edit</a>
@else
  <a href="{{ route($routeName . '.edit', ['id' => $id, 'store_code' => $storeLocation->code]) }}" class="btn btn-primary">Edit</a>
@endif
