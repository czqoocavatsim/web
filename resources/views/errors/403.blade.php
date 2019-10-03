@extends('layouts.error')


@section('error')
Error 403 - Forbidden
@stop

@section('message')
{{$exception->getMessage()}}<br/> If you believe this is an error, please <a href="{{url('/dashboard/tickets')}}">contact us via the ticket system</a> or <a href="mailto:info@czqo.vatcan.ca">via email.</a>
@stop