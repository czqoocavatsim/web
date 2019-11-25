@extends('layouts.master')

@section('navbarprim')

    @parent

@stop

@section('content')
<div class="container" style="margin-top: 20px">
    <h3>Privacy Policy</h3>
    <i>Last updated 15 July 2019</i>
    <iframe style="border: none; margin-top: 10px; margin-bottom: 10px; width: 100%; height: 100vh;" src="{{asset('cdn/PrivacyPolicy15July2019.pdf')}}"></iframe>
    If the PDF is not displaying correctly, you can view it directly <a href="{{asset('cdn/PrivacyPolicy15July2019.pdf')}}">here.</a>
</div>
@stop
