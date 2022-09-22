@component('mail::message')
# Promo code approval request

You've received a request to approve a promo code.

@component('mail::button', ['url' => config('app.url') . '/admin/promo-codes/unapproved'])
  Approve Promo Code
@endcomponent

@endcomponent
