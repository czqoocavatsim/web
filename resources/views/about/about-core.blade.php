@extends('layouts.master')
@section('title', 'About CZQO Core - ')
@section('content')
<div class="container py-5">
    <div class="d-flex flex-row justify-content-center">
        <img src="https://cdn.ganderoceanic.com/resources/media/img/brand/sqr/ZQO_SQ_TSPBLUE.png" class="mr-3" style="width: 125px; height: 125px;" alt="">
        <div class="d-flex flex-column">
            <h1 class="heading blue-text font-weight-bold display-5">Gander Oceanic Core</h1>
            <p style="font-size: 1.4em;" class="mb-1">The website for VATSIM's<br>Gander Oceanic OCA</p>
            <p class="text-muted" style="font-size: 0.9em;">
                Version {{App\Models\Settings\CoreSettings::where('id', 1)->firstOrFail()->release}} ({{App\Models\Settings\CoreSettings::where('id', 1)->firstOrFail()->sys_build}})
            </p>
            <div class="d-flex flex-row align-items-center justify-content-between">
                <a href="https://github.com/czqoocavatsim" class="text-body d-flex flex-row align-items-center">
                    <i class="fab fa-2x fa-github mr-2"></i> GitHub
                </a>
                <a href="https://github.com/czqoocavatsim/czqo-core/releases" class="text-body d-flex flex-row align-items-center">
                    <i class="fas fa-history fa-2x mr-2"></i> Change Log
                </a>
                <a href="https://dev.ganderoceanic.com" class="text-body d-flex flex-row align-items-center">
                    <i class="fas fa-2x mr-2 fa-feather-alt"></i> Beta
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
