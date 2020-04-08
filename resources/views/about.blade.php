@extends('layouts.master')
@section('title', 'About CZQO Core - ')
@section('content')
<div class="container d-flex py-5 flex-column align-items-center justify-content-center">
<img src="https://resources.ganderoceanic.com/pr/brand/square/ZQO_SQUARE_TRANSPARENTBLUE.png" class="img-fluid" style="width: 125px;" alt="">
<h1 class="heading font-weight-bold display-5">Gander Oceanic Core</h1>
<h4>Release {{App\Models\Settings\CoreSettings::where('id', 1)->firstOrFail()->release}} ({{App\Models\Settings\CoreSettings::where('id', 1)->firstOrFail()->sys_build}})</h4>
<br>
<h5 class="text-center">Developed by <a href="https://github.com/lieseldownes24">Liesel Downes</a> in Adelaide, Australia<br/>and <a href="https://github.com/andrewogden1678">Andrew Ogden</a> in Melbourne, Australia.</h5>
</div>
@endsection
