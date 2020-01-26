@extends('layouts.email')
@section('message-content')
An unauthorised connection on a Gander monitored position was detected at time {{Carbon\Carbon::now()}}.
<br/>
<ul>
    <li>Callsign: {{$oc['callsign']}}</li>
    <li>CID: {{$oc['cid']}}</li>
</ul>
@endsection
