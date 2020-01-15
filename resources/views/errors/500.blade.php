@extends('layouts.error')

@section('title', '500 Server Error')
@section('error', '500 Error')
@section('message')
<div class="mb-4 lead">Looks like something broke! Please report this error with the following data to the Web Team via email or a support ticket:<br/>
    <p class="border p-2 mt-3" style="font-family: monospace;">
        {{Request::url()}}<br/>
        {{Carbon\Carbon::now()}}
        {{$exception->getMessage()}}
    </p>
</div>
<a href="/" class="btn btn-link">Go home</a>
@endsection
