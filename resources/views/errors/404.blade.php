@extends('layouts.error')


@section('error')
Error 404 - Page Not Found
@stop

@section('message')
We have dispatched our helpless webmaster to investigate. If you believe this is an error, please <a href="{{url('/dashboard/tickets')}}">contact us via the ticket system</a> or <a href="mailto:info@czqo.vatcan.ca">via email.</a>
@stop