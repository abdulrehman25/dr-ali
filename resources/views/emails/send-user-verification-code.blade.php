@component('mail::message')
<h1>We have received your request to verify your account email</h1>
<p>You can use the following code to verify your account:</p>

@component('mail::panel')
{{ $code }}
@endcomponent

@endcomponent