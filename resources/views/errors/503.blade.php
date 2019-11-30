@extends('layouts.error')

@section('title', '503 Service Unavailable')
@section('error', '503 - Maintenance')
@section('message')
<div class="mb-4 lead">Gander Oceanic FIR is currently offline for maintenance. If you need to reach us, join our <a href="https://discord.gg/MvPVAHP">Discord</a> or <a href="mailto:chief@czqo.vatcan.ca">email us.</a>
</div>
{{-- <a href="/" class="btn btn-link">Go home</a> --}}
@endsection
