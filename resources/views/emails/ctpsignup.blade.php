@extends('layouts.email')

@section('title')
    <b>CTP Eastbound 2019 Signup</b>
@stop

@section('to')
@stop

@section('content')
    {{\App\User::whereId($signup->user_id)->firstOrFail()->fullName('FLC')}} has signed-up for CTP Eastbound 2019.<br/>
    Availability: {{$signup->availability}}<br/>
    Times: {{$signup->times}}
@stop

@section('end')
    <b>Gander Oceanic Core</b>
@stop
