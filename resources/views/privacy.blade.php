@extends('layouts.master')

@section('navbarprim')

    @parent

@stop

@section('content')
<div class="container" style="margin-top: 20px">
    <iframe style="width: 100%; height: 100%;" src="{{url('cdn/PrivacyPolicy9Dec2018.pdf')}}"></iframe>
</div>
@stop
