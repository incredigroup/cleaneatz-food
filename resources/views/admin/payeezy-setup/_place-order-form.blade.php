<form id="place-order-form" method="post" action="{{ route('admin.payeezy-setup.place-order') }}" style="display: none">
  @csrf

  <div class="form-group">
    <label>Client Token</label>
    <input type="text" class="form-control" name="client_token" readonly>
  </div>
  <div class="form-group">
    <label>Amount</label>
    <div class="input-group">
      <div class="input-group-prepend">
        <span class="input-group-text">$</span>
      </div>
      <input type="text" class="form-control" name="amount" value=".10" readonly>
    </div>
  </div>
  <button type="submit" class="btn btn-primary mt-3">Place Order</button>
</form>
