@extends('layouts.master')

@section('navbarprim')

    @parent

@stop

@section('content')
<script src="{{asset('/js/markdown.min.js')}}"></script>
<div class="container" style="margin-top: 20px;">
    <h2>System Change Log</h2>
    <br class="my-2">
    <div id="content"></div>
    <script src="https://gist.github.com/lieselta/e3b35297975f20b56587198a7d2ac76e.js"></script>
</div>
@stop
