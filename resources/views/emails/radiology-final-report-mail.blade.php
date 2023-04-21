@component('mail::message')
<h1>Welcome {{$user->email}}</h1>
<p>This is radiology final mail..............</p>

@component('mail::panel')
{{$user->email}}
@endcomponent

@endcomponent