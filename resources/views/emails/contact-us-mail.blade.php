@component('mail::message')
<h1>Hi, </h1>
<p>A new Customer Trying to reach you. Please find below customer details :-</p>
<h1>Customer details :</h1>
@component('mail::panel')
    Name : {{$contactInfo->name}}<br>
    Email : {{$contactInfo->email}}<br>
    Phone : {{$contactInfo->phone}}<br>
    Message : {{$contactInfo->message}}<br>
@endcomponent

@endcomponent