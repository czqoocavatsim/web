@extends('pilots.layout')
@section('title', 'Concorde Tracks - ')
@section('description', 'Legacy Concorde NAT Tracks')
@section('pilot-content')
<div class="container py-4">
<script src="{{asset('js/leaflet.latlng.js')}}"></script>
            <h1 class="font-weight-bold blue-text mb-3">Concorde NAT Tracks</h1>
            <p>Concorde stopped flying in 2003, but its North Atlantic tracks are still part of our procedures. Concorde add-ons are popular on VATSIM, and we still handle supersonic flights the way the real controllers did.</p>
            <h2 class="h4 font-weight-bold blue-text mt-4">What are the Concorde tracks?</h2>
            <ul>
                <li>There are four <strong>fixed, legacy tracks</strong>: <strong>NAT SM</strong>, <strong>NAT SN</strong>, <strong>NAT SO</strong> and <strong>NAT SP</strong>. Unlike the daily NAT tracks, they do not change from day to day, so they are always available and are not published in a daily message.</li>
                <li>Concorde flew too high and too fast to share the daily tracks. It cruise-climbed from about FL470 to FL600 at roughly Mach 2, so these tracks are kept apart from other traffic, which stays at or below FL430.</li>
                <li><strong>SM</strong> is for westbound flights only, <strong>SN</strong> is for eastbound flights only, <strong>SO</strong> is an overflow track used as coordinated by ATC, and <strong>SP</strong> is a two-way route to the Caribbean.</li>
                <li>The points are on intermediate latitudes (degrees and minutes), so each point is named after its track and longitude, for example <em>SM20W</em> is on Track SM at 20 West.</li>
                <li>Concorde does not need a Mach number in its oceanic clearance, as it flies a standard speed schedule.</li>
            </ul>
            <p>Controllers can find the full rules in Annex B of the Gander Oceanic SOPs. Pilots do not need a daily track message: request your oceanic clearance as normal and ask for the track you want.</p>
            <div style="height: 400px;" id="map"></div>
            <p class="mt-2"><span style="color:#1c5fc9;font-weight:bold;">Blue</span> = westbound (SM), <span style="color:#c92d1c;font-weight:bold;">red</span> = eastbound (SN), <span style="color:#6c3fb5;font-weight:bold;">purple</span> = either direction as coordinated (SO, SP).</p>
            <table id="natTrackTable" class="table table-responsive table-striped">
                <thead>
                <tr>
                    <th scope="col">Track</th>
                    <th scope="col">Points</th>
                    <th scope="col">Use</th>
                    <th scope="col">Levels</th>
                </tr>
                </thead>
                <tbody id="tableBody">
                </tbody>
            </table>
            <p>For more information about Concorde tracks, check out this page: <a href="https://www.heritageconcorde.com/air-traffic-control-of-concorde-" target="_blank" rel="noopener">Heritage Concorde: air traffic control of Concorde</a>.</p>
            <p class="mt-3 text-muted">Special thank you to Christian Kovanen 1379372 for the boundaries and fixes.</p>
        <script>
            (async function () {
                // Fixed legacy tracks (Gander Oceanic SOPs, Annex B 1.1.2). Points are [name, degrees, minutes N, degrees, minutes W].
                const TRACKS = [
                    {id: 'SM', label: 1, colour: '#1c5fc9', use: 'Westbound only', pts: [['SM15W',50,41,15,0],['SM20W',50,50,20,0],['SM30W',50,30,30,0],['SM40W',49,16,40,0],['SM50W',47,3,50,0],['SM53W',46,10,53,0],['SM60W',44,14,60,0],['SM65W',42,46,65,0],['SM67W',42,0,67,0]]},
                    {id: 'SN', label: 6, colour: '#c92d1c', use: 'Eastbound only', pts: [['SN67W',40,25,67,0],['SN65W',41,40,65,0],['SN60W',43,7,60,0],['SN52W',45,10,52,30],['SN50W',45,54,50,0],['SN40W',48,10,40,0],['SN30W',49,26,30,0],['SN20W',49,49,20,0],['SN15W',49,41,15,0]]},
                    {id: 'SO', label: 4, colour: '#6c3fb5', use: 'Overflow, as coordinated by ATC', pts: [['SO15W',48,40,15,0],['SO20W',48,48,20,0],['SO30W',48,22,30,0],['SO40W',47,4,40,0],['SO50W',44,45,50,0],['SO52W',44,10,52,0],['SO60W',42,0,60,0]]},
                    {id: 'SP', label: 2, colour: '#6c3fb5', use: 'Caribbean, either direction', pts: [['SP15W',48,49,14,43],['SP20W',46,49,20,0],['SP45N',45,0,23,53],['SP30W',41,36,30,0],['SP40W',34,22,40,0]]}
                ];
                const map = L.map('map', { minZoom: 4, maxZoom: 7 }).setView([46, -35], 4);
                await addNatBaseMap(map);
                const table = $('#natTrackTable tbody');
                TRACKS.forEach(t => {
                    const route = t.pts.map(p => ({name: p[0], latitude: p[1] + p[2] / 60, longitude: -(p[3] + p[4] / 60)}));
                    route.forEach(point => createMapTrackPointMarker(point, t, map));
                    new L.Polyline(route.map(p => [p.latitude, p.longitude]), {color: t.colour, weight: 2, opacity: 1}).addTo(map);
                    const mid = route[t.label];
                    L.marker([mid.latitude, mid.longitude], {opacity: 0}).addTo(map).bindTooltip('NAT ' + t.id, {permanent: true, direction: 'top', className: 'concorde-label'});
                    const row = $('<tr></tr>');
                    row.append($('<td></td>').append($('<strong></strong>').text('NAT ' + t.id)));
                    row.append($('<td></td>').text(route.map(p => p.name).join(', ')));
                    row.append($('<td></td>').text(t.use));
                    row.append($('<td></td>').text('FL470-600 cruise climb'));
                    table.append(row);
                });
                createMapPointsBoundaries(map);
            })();
        </script>
</div>
<style>
    .leaflet-tooltip {
    position: absolute;
    padding: 6px;
    background: none !important;
    border: none !important;
    border-radius: none !important;
    color: #222;
    white-space: nowrap;
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
    pointer-events: none;
    box-shadow: none !important;
    }
    .leaflet-tooltip-top:before, .leaflet-tooltip-bottom:before, .leaflet-tooltip-left:before, .leaflet-tooltip-right:before {
    position: absolute;
    pointer-events: none;
    border: none !important;
    background: transparent;
    content: "";
    }
</style>
@endsection
