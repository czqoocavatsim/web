@extends('layouts.master')

@section('navbarprim')

    @parent

@stop
@section('title', 'Pilot Tools - ')
@section('content')
    <div class="container" style="margin-top: 20px;">
        <div class="row">
            <div class="col-md-4">
                @include('layouts.toolSidebar')
            </div>
            <div class="col-md-8">
                <h4>Please select a tool from the left</h4>
            </div>
        </div>
    </div>
@stop
