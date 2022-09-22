<form
  method="post"
  @if (empty($storeLocation))
    action="{{ route($routeName . '.destroy', $id) }}"
  @else
    action="{{ route($routeName . '.destroy', ['id' => $id, 'store_code' => $storeLocation->code]) }}"
  @endif
  class="form-delete">
  @method('delete')
  @csrf
  <button type="submit" class="btn btn-danger">Delete</button>
</form>