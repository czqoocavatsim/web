@extends('layouts.master')

@section('navbarprim')

    @parent

@stop

@section('content')
<div class="container" style="margin-top: 20px;">
        <div class="row">
            <div class="col-md-4">
                    @include('layouts.toolSidebar')
            </div>
            <div class="col-md-8">
                <h2>VATSIM Resources</h2>
                <br>
                <p>If you're completely new to flying on VATSIM, there are a range of in depth tutorials on the VATSIM homepage. Some of these are listed below:</p>
                <ul>
                    <li>
                        <a href="https://www.vatsim.net/pilots/getting-started" target="_blank">Getting Started</a>
                    </li>
                    <li>
                        <a href="https://www.vatsim.net/pilots/resources" target="_blank">Pilot Resources (charts, who's online)</a>
                    </li>
                    <li>
                        <a href="https://www.vatsim.net/pilots/software" target="_blank">Software</a>
                    </li>
                    <li>
                        <a href="https://www.vatsim.net/pilots/file-flightplan" target="_blank">Filing a Flightplan</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
@stop