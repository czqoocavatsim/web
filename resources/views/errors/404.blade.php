@extends('layouts.error')


@section('error')
Error 404 - Page Not Found
@endsection

@section('message')
<div class="mb-4 lead">We have dispatched our helpless webmaster to investigate. If you believe this is an error, please <a href="{{url('/dashboard/tickets')}}">contact us via the ticket system</a> or <a href="mailto:info@czqo.vatcan.ca">via email.</a>
</div>
<a href="/" class="btn btn-link">Go home</a>
@endsection
