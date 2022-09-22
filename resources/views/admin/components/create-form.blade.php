<form method="post"
  action="{{ $action }}"
  class="disable-on-submit"
  @if(isset($file) && $file==true) enctype="multipart/form-data" @endif>
  @csrf
  {{ $slot }}
  <button class="btn btn-primary mt-4" type="submit">Save</button>
</form>
