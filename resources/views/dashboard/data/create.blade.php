@extends('layouts.master')

@section('navbarprim')

    @parent

@stop

@section('content')
<div class="container" style="margin-top: 20px;">
    <h2>GDPR Download Request</h2><hr>
    <a role="button" href="{{url('/dashboard/data/download')}}" class="btn btn-primary" >Download Your Data</a>
</div>
@stop