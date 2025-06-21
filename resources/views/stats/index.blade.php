@extends('layouts.primary', ['solidNavBar' => true])
@section('title', 'Statistics - ')
@section('description', 'Cool, calm and collected oceanic control services in the North Atlantic on VATSIM.')

@section('content')
<div class="container pt-5 pb-5">
    <div class="row">
        <div class="col-md-2 mb-3">
            <div class="list-group" style="position: sticky; top: 20px">
                <a href="#1" class="list-group-item list-group-item-action">{{\Carbon\Carbon::now()->format('F, Y')}} Stats</a>
                <a href="#2" class="list-group-item list-group-item-action">Controller Stats</a>
                <a href="#3" class="list-group-item list-group-item-action">Pilot Stats</a>
                <a href="#4" class="list-group-item list-group-item-action">Aircraft Stats</a>
                <a href="#5" class="list-group-item list-group-item-action">Airport Stats</a>
            </div>
        </div>

        <div class="col-md-10 mb-3">
            {{-- This Month Statistics --}}
            <a id="1"><h2 class="font-weight-bold blue-text mb-1">{{\Carbon\Carbon::now()->format('F, Y')}} Statistics</h2></a>
            <p style="margin-top: 5px; margin-bottom: 10px;">See the current most active Controllers, Pilots & Airport Pairs within Gander, Shanwick & New York Oceanic for this month.</p>
                <div class="row">
                    {{-- Month Stats --}}
                    @include('partials.statistics.controller-month')
                </div>

            {{-- All Controller Statistics --}}
            <a id="2"><h2 class="font-weight-bold blue-text mt-2">Controller Statistics</h2></a>
            <p style="margin-top: 5px; margin-bottom: 10px;">See Controller statistics for this Month, Last Month and This Year</p>
                <div class="row">
                    {{-- Month Stats --}}
                    @include('partials.statistics.controller-month')

                    {{-- Last Month Stats --}}
                    @include('partials.statistics.controller-last-month')

                    {{-- Whole Year Stats --}}
                    @include('partials.statistics.controller-year')
                </div>

            {{-- All Pilot Statistics --}}
            <a id="3"><h2 class="font-weight-bold blue-text mt-2">Pilot Statistics</h2></a>

            {{-- All Aircraft Statistics --}}
            <a id="4"><h2 class="font-weight-bold blue-text mt-4">Aircraft Statistics</h2></a>


            {{-- All Airport Statistics --}}
            <a id="5"><h2 class="font-weight-bold blue-text mt-4">Airport Statistics</h2></a>
        </div>
    </div>
</div>

@endsection
