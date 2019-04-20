@extends('layouts.email')


@section('title')
    <b>IMPORATNT:</b> GDPR REMOVAL REQUEST
@stop

@section('to')

    <strong>Dear {{$data['adminName']}},</strong>
@stop

@section('content')
    <b>A GDPR removal request has been submitted. This requires action ASAP.</b><br/>
    User <a href="//czqo.vatcan.ca/dashboard/users/{{$data['requestUser']}}"> {{\App\User::find($data['requestUser'])->fname}} {{\App\User::find($data['requestUser'])->lname}} {{\App\User::find($data['requestUser'])->id}} </a> has requested the removal of their data under the General Data Protection Regulation (GDPR) law of the EU. This requires urgent action under VATSIM privacy regulations. The request removal method is a {{$data['method']}} delete.
@stop

@section('end')
    <b>Gander Oceanic Core</b>
@stop