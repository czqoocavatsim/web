@extends('layouts.error')


@section('error')
You have already applied for CZQO.
@stop

@section('message')
Please check the status of your application <a href="{{url('/dashboard/application/status')}}">here.</a>
@stop