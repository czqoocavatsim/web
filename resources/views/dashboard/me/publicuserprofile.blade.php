@extends('layouts.master')

@section('navbarprim')
    @parent
@stop

@section('content')
    <div class="container" style="margin-top: 20px;">
        <h2>{{$user->fullName('FLC')}}</h2>

    </div>
@stop