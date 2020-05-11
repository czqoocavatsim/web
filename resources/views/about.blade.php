@extends('layouts.master')
@section('title', 'About CZQO Core - ')
@section('content')
<div class="container d-flex py-5 flex-column align-items-center justify-content-center">
<img src="https://resources.ganderoceanic.com/media/img/brand/sqr/ZQO_SQ_TSPBLUE.png" class="img-fluid" style="width: 125px;" alt="">
<h1 class="heading blue-text font-weight-bold display-5">Gander Oceanic Core</h1>
<h4>Release {{App\Models\Settings\CoreSettings::where('id', 1)->firstOrFail()->release}} ({{App\Models\Settings\CoreSettings::where('id', 1)->firstOrFail()->sys_build}})</h4>
<br>
<h5 class="text-center"><a href="https://github.com/gander-oceanic-fir-vatsim/czqo-core">View our GitHub</a></h5>
<h5 class="text-center"><a href="https://blog.ganderoceanic.com/gander-oceanic-core-update-log/">Site Update Log</a></h5>
<h5 class="text-center"><a href="https://canary.ganderoceanic.com/">Development ("Canary") Website</a></h5>
</div>
@endsection
