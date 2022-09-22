<form method="post"
  action="{{ $action }}"
  class="disable-on-submit"
  @if(isset($file) && $file==true) enctype="multipart/form-data" @endif>
  @method('put')
  @csrf
  {{ $slot }}
  <button class="btn btn-primary mt-4" type="submit">Update</button>
</form>
