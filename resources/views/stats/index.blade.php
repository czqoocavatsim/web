@extends('layouts.primary', ['solidNavBar' => true])
@section('title', 'Statistics Hub - ')
@section('description', 'Cool, calm and collected oceanic control services in the North Atlantic on VATSIM.')

@section('content')
<div class="container pt-5 pb-5">
    <div class="row">
        <div class="col-md-2 mb-3">
            <div class="list-group" style="position: sticky; top: 20px">
                <a href="#1" class="list-group-item list-group-item-action">This Month Stats</a>
                <a href="#2" class="list-group-item list-group-item-action">Controller Stats</a>
                <a href="#3" class="list-group-item list-group-item-action">Pilot Stats</a>
                <a href="#4" class="list-group-item list-group-item-action">Aircraft Stats</a>
                <a href="#5" class="list-group-item list-group-item-action">Airport Stats</a>
            </div>
        </div>

        <div class="col-md-10 mb-3">
            <h1 class="font-weight-bold blue-text mb-1">Statistics Hub - Gander Oceanic</h1>
            <p style="margin-top: 5px; margin-bottom: 10px;" class="mb-3">All statistics are caclulated once hourly.</p>

            {{-- This Month Statistics --}}
            <a id="1"><h2 class="font-weight-bold blue-text mb-1">This Month Statistics</h2></a>
            <p style="margin-top: 5px; margin-bottom: 10px;">See the current most active Controllers, Pilots & Airport Pairs within Gander, Shanwick & New York Oceanic for this month.</p>
                <div class="row">
                    {{-- Controller Stats --}}
                    @include('partials.statistics.controller-month')

                    {{-- Pilot Stats --}}
                    @include('partials.statistics.pilot-month')

                    {{-- Airport Pair Stats --}}
                    @include('partials.statistics.airport-pairs')
                </div>

            {{-- All Controller Statistics --}}
            <a id="2"><h2 class="font-weight-bold blue-text mt-2">Controller Statistics</h2></a>
            <p style="margin-top: 5px; margin-bottom: 10px;">See Controller statistics for this Month, Last Month and this year.</p>
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
            <p style="margin-top: 5px; margin-bottom: 10px;">See top pilot flights for this month, last month and the entire year.</p>
            <div class="row">
                {{-- Airline Month Stats --}}
                @include('partials.statistics.pilot-month')

                {{-- Type Month Stats --}}
                @include('partials.statistics.pilot-last-month')

                {{-- Level Month Stats --}}
                @include('partials.statistics.pilot-year')
            </div>

            {{-- All Aircraft Statistics --}}
            <a id="4"><h2 class="font-weight-bold blue-text mt-4">Aircraft Statistics</h2></a>
            <p style="margin-top: 5px; margin-bottom: 10px;">See Pilot Airline, Aircraft Type and Flight Level statistics for this month and the entire year. All statistics are updated once per hour.</p>
            <div class="row">
                {{-- Airline Month Stats --}}
                @include('partials.statistics.aircraft-airline')

                {{-- Type Month Stats --}}
                @include('partials.statistics.aircraft-type')

                {{-- Level Month Stats --}}
                @include('partials.statistics.aircraft-level')

                {{-- Airline Year Stats --}}
                @include('partials.statistics.aircraft-airline-year')

                {{-- Type Year Stats --}}
                @include('partials.statistics.aircraft-type-year')

                {{-- Level Year Stats --}}
                @include('partials.statistics.aircraft-level-year')
            </div>

            {{-- All Airport Statistics --}}
            <a id="5"><h2 class="font-weight-bold blue-text mt-4">Airport Statistics</h2></a>
            <p style="margin-top: 5px; margin-bottom: 10px;">Monthly & Yearly Departure, Arrival and Airport Pair Statistics. All statistics are updated once per hour.</p>
            <div class="row">
                {{-- Airport DEP Month Stats --}}
                @include('partials.statistics.airport-departure')

                {{-- Airport ARR Month Stats --}}
                @include('partials.statistics.airport-arrival')

                {{-- Airport Pair Month Stats --}}
                @include('partials.statistics.airport-pairs')

                {{-- Airport DEP Year Stats --}}
                @include('partials.statistics.airport-departure-year')

                {{-- Airport ARR Year Stats --}}
                @include('partials.statistics.airport-arrival-year')

                {{-- Airport Pair Year Stats --}}
                @include('partials.statistics.airport-pairs-year')
            </div>
        </div>
    </div>
</div>

@endsection
