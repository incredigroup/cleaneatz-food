@component('mail::message')
# Wordpress Store Configuration Error

Errors have been found when reconciling store data with Wordpress.

@foreach($errors as $error)
### {{ $error }}
@endforeach

@endcomponent
