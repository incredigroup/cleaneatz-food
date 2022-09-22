<h6>Contact Information</h6>
<input type="email" required="required" name="email" value="{{ old('email', Auth::check() ? Auth::user()->email : null) }}" placeholder="Email" class="form-control">
<input type="checkbox" id="chkNews" checked="checked">
<label for="chkNews">Receive email updates about orders</label>
