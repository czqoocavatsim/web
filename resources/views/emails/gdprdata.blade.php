@extends('layouts.email')


@section('title')
GDPR Download Request
@stop

@section('content')
<strong>Dear {{$data['fname']}} {{$data['lname']}}</strong>
<p style="font-family: 'Open Sans', 'Segoe UI', 'Roboto', 'Verdana', 'Arial', sans-serif;">
    Listed below is all data held on you by the Gander Oceanic FIR in their database. This is pulled from the VATSIM SSO servers under the CZQO <a href="https://czqo.vatcan.ca/privacy">privacy policy.</a>
</p>
<pre>
    {{$data['json']}}
</pre>
<p style="font-family: 'Open Sans', 'Segoe UI', 'Roboto', 'Verdana', 'Arial', sans-serif;">
    Kind regards,<br/>
    Gander Oceanic FIR
    <a href="https://czqo.vatcan.ca" style="font-family: 'Open Sans', 'Segoe UI', 'Roboto', 'Verdana', 'Arial', sans-serif;">czqo.vatcan.ca</a>
</p>
@stop