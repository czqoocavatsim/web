@extends('layouts.primary', ['solidNavBar' => true])
@section('title', 'FDD - ')

@section('content')
    <style>
        :root {
            --blue-0: rgb(66, 125, 197);
            --blue-2: rgb(29, 57, 93);
            --blue-3: rgb(12, 24, 39);
            --blue-8: rgb(12, 24, 39);
            --blue-9: rgb(70, 130, 180);
            --blue-10: rgb(20, 41, 66);
            --yellow-100: rgb(255, 255, 150);
            --yellow-200: rgb(230, 230, 100);
        }

        body {
            background-color: var(--blue-2);
            color: white;
            font-family: 'Courier New', monospace;
            margin: 0;
            padding: 0;
            overflow: hidden;
        }

        ::-webkit-scrollbar {
            width: 14px;
            height: 14px;
        }

        ::-webkit-scrollbar-track {
            background-color: var(--blue-10);
            border: 2px solid var(--blue-3);
        }

        ::-webkit-scrollbar-thumb {
            background-color: var(--blue-2);
            border: 2px solid var(--blue-0);
        }

        .header-bar {
            background-color: var(--blue-10);
            border-bottom: 2px solid var(--blue-3);
            padding: 10px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .info-box {
            background-color: var(--blue-2);
            border: 2px solid;
            border-color: var(--blue-0) var(--blue-8) var(--blue-8) var(--blue-0);
            padding: 5px 15px;
            font-size: 1.1rem;
            min-width: 70px;
            text-align: center;
        }

        .filter-bar {
            background-color: var(--blue-3);
            padding: 5px 20px;
            display: flex;
            gap: 20px;
            align-items: center;
            font-size: 0.9rem;
        }

        .filter-bar label {
            display: flex;
            align-items: center;
            gap: 5px;
            cursor: pointer;
        }

        .filter-bar input[type="checkbox"] {
            width: 18px;
            height: 18px;
            cursor: pointer;
        }

        .btn {
            background-color: var(--blue-0);
            border: none;
            color: white;
            padding: 5px 15px;
            cursor: pointer;
            font-family: inherit;
        }

        .btn:hover {
            background-color: rgb(86, 145, 217);
        }

        .main-content {
            display: flex;
            height: calc(100vh - 100px);
        }

        .fdd-panel {
            flex: 3;
            border-right: 2px solid var(--blue-3);
            overflow-y: auto;
            background-color: var(--blue-10);
        }

        .side-panel {
            flex: 2;
            display: flex;
            flex-direction: column;
            background-color: var(--blue-2);
        }

        .track-header {
            background-color: rgba(80, 80, 80, 0.9);
            padding: 4px 10px;
            display: flex;
            font-size: 0.85rem;
            font-weight: bold;
        }

        .track-id {
            width: 80px;
        }

        .track-route {
            display: flex;
            flex: 1;
            flex-wrap: wrap;
        }

        .track-route span {
            width: 65px;
        }

        .flight-strip-east {
            background-color: var(--blue-9);
            color: white;
            font-weight: bold;
        }

        .flight-strip-east-dark {
            background-color: rgb(50, 100, 140);
        }

        .flight-strip-west {
            background-color: var(--yellow-100);
            color: black;
            font-weight: bold;
        }

        .flight-strip-west-dark {
            background-color: var(--yellow-200);
        }

        .flight-strip-container {
            border-bottom: 2px solid rgba(0, 0, 0, 0.3);
            margin-bottom: 2px;
        }

        .flight-strip {
            display: flex;
            align-items: center;
            padding: 4px 8px;
            font-size: 0.85rem;
            cursor: default;
            user-select: none;
        }

        .flight-strip:hover {
            border: 2px solid #22c55e;
            margin: -2px;
        }

        .flight-strip .callsign {
            width: 90px;
            font-weight: bold;
        }

        .flight-strip .type {
            width: 55px;
        }

        .flight-strip .airports {
            width: 100px;
        }

        .flight-strip .level {
            width: 50px;
            text-align: center;
        }

        .flight-strip .mach {
            width: 60px;
            text-align: center;
        }

        /* Route row with waypoints and ETAs */
        .flight-strip-route {
            display: flex;
            padding: 3px 8px 5px 8px;
            font-size: 0.75rem;
        }

        .route-row-east {
            background-color: rgba(40, 80, 130, 0.95);
            color: white;
        }

        .route-row-west {
            background-color: rgba(200, 180, 80, 0.95);
            color: #000;
        }

        .route-points {
            display: flex;
            flex: 1;
            flex-wrap: wrap;
            padding-left: 5px;
        }

        .route-point {
            display: flex;
            flex-direction: column;
            align-items: center;
            min-width: 70px;
            margin-right: 8px;
            padding: 2px 4px;
        }

        .wp-name {
            font-weight: bold;
            font-size: 0.8rem;
            color: inherit;
        }

        .wp-eta {
            font-size: 0.75rem;
        }

        .route-row-east .wp-name {
            color: white;
        }

        .route-row-east .wp-eta {
            color: #aaccff;
        }

        .route-row-west .wp-name {
            color: #000;
        }

        .route-row-west .wp-eta {
            color: #333;
        }

        /* Track header variations */
        .track-header-east {
            border-left: 4px solid var(--blue-9);
        }

        .track-header-west {
            border-left: 4px solid rgb(255, 255, 100);
        }

        .track-strip {
            border-bottom: 2px solid rgba(0, 0, 0, 0.3);
        }

        .track-strip-header,
        .track-strip-body {
            padding: 4px 10px;
            display: flex;
            font-size: 0.85rem;
        }

        .track-strip-header {
            background-color: rgba(0, 0, 0, 0.3);
        }

        .track-strip-body.east {
            background-color: rgba(70, 130, 180, 0.5);
        }

        .track-strip-body.west {
            background-color: rgba(200, 200, 100, 0.5);
        }

        .section-title {
            background-color: var(--blue-3);
            padding: 8px;
            font-weight: bold;
            border-bottom: 1px solid var(--blue-0);
        }

        #tracks-panel,
        #clearances-panel {
            overflow-y: auto;
            padding: 5px;
        }

        #tracks-panel {
            flex: 1;
            border-bottom: 2px solid var(--blue-3);
        }

        #clearances-panel {
            flex: 1;
        }

        .loading {
            text-align: center;
            padding: 40px;
            font-size: 1.3rem;
        }

        .no-data {
            text-align: center;
            padding: 30px;
            color: #888;
        }

        /* Modal styles */
        .modal-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.7);
            z-index: 1000;
            justify-content: center;
            align-items: center;
        }

        .modal-overlay.show {
            display: flex;
        }

        .modal-box {
            background: var(--blue-2);
            border: 2px solid var(--blue-0);
            padding: 20px;
            min-width: 300px;
            text-align: center;
        }

        .modal-title {
            font-size: 1.2rem;
            font-weight: bold;
            margin-bottom: 15px;
            color: #fff;
        }

        .modal-callsign {
            font-size: 1.5rem;
            font-weight: bold;
            color: #fbbf24;
            margin-bottom: 20px;
        }

        .modal-buttons {
            display: flex;
            gap: 10px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .modal-btn {
            padding: 8px 20px;
            border: none;
            cursor: pointer;
            font-size: 0.9rem;
            font-weight: bold;
        }

        .modal-btn-ack {
            background: #3b82f6;
            color: white;
        }

        .modal-btn-ack:hover {
            background: #2563eb;
        }

        .modal-btn-processed {
            background: #22c55e;
            color: white;
        }

        .modal-btn-processed:hover {
            background: #16a34a;
        }

        .modal-btn-remove {
            background: #ef4444;
            color: white;
        }

        .modal-btn-remove:hover {
            background: #dc2626;
        }

        .modal-btn-cancel {
            background: #6b7280;
            color: white;
        }

        .modal-btn-cancel:hover {
            background: #4b5563;
        }

        .confirm-text {
            margin-bottom: 15px;
            color: #fbbf24;
        }

        /* Clearance row states */
        .clearance-row {
            cursor: pointer;
            display: flex;
            padding: 4px 8px;
            font-size: 0.85rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.15);
            align-items: center;
        }

        .clearance-row span {
            padding-right: 8px;
        }

        .clearance-row .cs {
            width: 90px;
            font-weight: bold;
        }

        .clearance-row .fix {
            width: 70px;
        }

        .clearance-row .eta {
            width: 55px;
        }

        .clearance-row .level {
            width: 45px;
            text-align: center;
        }

        .clearance-row .mach {
            width: 50px;
            text-align: center;
        }

        .clearance-row .track {
            width: 35px;
            text-align: center;
        }

        .clearance-row .status {
            flex: 1;
            text-align: right;
            font-weight: bold;
            padding-right: 0;
        }

        .clearance-row:hover {
            background: rgba(255, 255, 255, 0.1);
        }

        .clearance-ack {
            background: rgba(59, 130, 246, 0.3) !important;
        }

        .clearance-processing {
            background: rgba(34, 197, 94, 0.5) !important;
            animation: fadeOut 5s forwards;
        }

        @keyframes fadeOut {
            0% {
                opacity: 1;
            }

            80% {
                opacity: 1;
            }

            100% {
                opacity: 0;
            }
        }

        .status-clr {
            color: #22c55e;
        }

        .status-pending {
            color: #fbbf24;
        }

        .status-nocrx {
            color: #ef4444;
        }

        @keyframes flashNew {

            0%,
            100% {
                background-color: transparent;
            }

            50% {
                background-color: rgba(251, 191, 36, 0.4);
            }
        }

        .clearance-new {
            animation: flashNew 1s ease-in-out 3;
        }
    </style>

    <div class="header-bar">
        <div style="display: flex; gap: 20px; align-items: center;">
            <span style="font-size: 1.1rem; font-weight: bold;">Gander Oceanic - Flight Data Display</span>
            <div class="info-box" id="time-display">--:--z</div>
            <div class="info-box" id="tmi-display">TMI ---</div>
        </div>
        <div style="display: flex; gap: 20px; align-items: center;">
            <div class="info-box" id="ac-count">-- AC</div>
            <div class="info-box" id="clx-count">-- RCL</div>
            <button class="btn" onclick="refreshAll()">↻ Refresh</button>
        </div>
    </div>

    <div class="filter-bar">
        <label><input type="checkbox" id="filter-east" checked> Eastbound</label>
        <label><input type="checkbox" id="filter-west" checked> Westbound</label>
        <label><input type="checkbox" id="filter-cleared" checked> Cleared</label>
        <label><input type="checkbox" id="filter-uncleared" checked> Uncleared</label>
        <label><input type="checkbox" id="filter-tracks" checked> On Track</label>
        <label><input type="checkbox" id="filter-random" checked> Random</label>
        <span style="margin-left: auto; color: #888;" id="last-update">Next refresh: 30s</span>
    </div>

    <div class="main-content">
        <div class="fdd-panel" id="fdd-panel">
            <div class="loading" id="loading">Loading flight data...</div>
        </div>

        <div class="side-panel">
            <div class="section-title">NAT Tracks</div>
            <div id="tracks-panel"></div>

            <div class="section-title">Pending RCL Requests</div>
            <div id="clearances-panel"></div>
        </div>
    </div>

    <!-- Modal for clearance actions -->
    <div class="modal-overlay" id="clearance-modal">
        <div class="modal-box">
            <div class="modal-title">Clearance Request</div>
            <div class="modal-callsign" id="modal-callsign">---</div>
            <div id="modal-content">
                <div class="modal-buttons">
                    <button class="modal-btn modal-btn-ack" onclick="ackClearance()">ACK</button>
                    <button class="modal-btn modal-btn-processed" onclick="processClearance()">PROCESSED</button>
                    <button class="modal-btn modal-btn-remove" onclick="showRemoveConfirm()">REMOVE</button>
                    <button class="modal-btn modal-btn-cancel" onclick="hideModal()">CANCEL</button>
                </div>
            </div>
            <div id="modal-confirm" style="display:none">
                <div class="confirm-text">Are you sure you have processed this RCL?</div>
                <div class="modal-buttons">
                    <button class="modal-btn modal-btn-remove" onclick="removeClearance()">YES - REMOVE</button>
                    <button class="modal-btn modal-btn-cancel" onclick="hideRemoveConfirm()">NO</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // =====================================================================
        // Gander Oceanic Flight Data Display
        // Version 1.0 - January 2026
        // =====================================================================

        // State variables
        let natTracks = new Map();
        let clearances = new Map();
        let previousClearances = new Set();
        let newClearances = new Set();
        let acknowledgedClearances = new Set();
        let processingClearances = new Set();
        let oceanicAircraft = [];
        let currentTMI = '';

        // Calculate TMI from Julian day (day of year)
        function calculateTMI() {
            const now = new Date();
            const startOfYear = new Date(Date.UTC(now.getUTCFullYear(), 0, 0));
            const diff = now - startOfYear;
            const oneDay = 1000 * 60 * 60 * 24;
            const dayOfYear = Math.floor(diff / oneDay);
            return String(dayOfYear).padStart(3, '0');
        }
        let selectedCallsign = null;
        let refreshCountdown = 30;

        // API endpoints
        const VATSIM_API = 'https://data.vatsim.net/v3/vatsim-data.json';
        const NATTRAK_TRACKS_API = 'https://nattrak.vatsim.net/api/tracks';
        const NATTRAK_RCL_API = 'https://nattrak.vatsim.net/api/plugins-rcl';

        // Oceanic boundaries
        const OCA_BOUNDS = {
            latMin: 40,
            latMax: 65,
            lonMin: -60,
            lonMax: -10
        };
        const EXTENDED_BOUNDS = {
            latMin: 35,
            latMax: 70,
            lonMin: -75,
            lonMax: 5
        };

        // NAT entry/exit fixes
        const NAT_FIXES = ['SUNOT', 'KESRI', 'DOGAL', 'BEXET', 'MALOT', 'GISTI', 'NEBIN', 'LIMRI', 'XETBO',
            'RAFIN', 'ELSIR', 'RESNO', 'VENER', 'CYMON', 'NICSO', 'ALLRY', 'OMOKO', 'JOOPY', 'JANJO',
            'BOBTU', 'PIKIL', 'ETIKI', 'SOMAX', 'BEDRA', 'NERTU', 'NIBOG', 'PASAS', 'KUGOS', 'DINIM', 'RELIC', 'SUPRY',
            'MUSAK'
        ];

        // =====================================================================
        // EET Parsing Functions
        // =====================================================================

        function parseEETFromRemarks(remarks, deptime) {
            if (!remarks) return new Map();

            const eetMap = new Map();

            const eetMatch = remarks.match(/EET\/([A-Z0-9\s\/]+?)(?:\s+[A-Z]{3}\/|$)/);
            if (!eetMatch) return eetMap;

            const eetSection = eetMatch[1];
            const pairs = eetSection.match(/([A-Z0-9\/]+?)(\d{4})/g);
            if (!pairs) return eetMap;

            let depHour = 0,
                depMin = 0;
            if (deptime && deptime.length >= 4) {
                depHour = parseInt(deptime.substring(0, 2)) || 0;
                depMin = parseInt(deptime.substring(2, 4)) || 0;
            }

            pairs.forEach(pair => {
                const match = pair.match(/([A-Z0-9\/]+?)(\d{4})$/);
                if (match) {
                    const waypoint = match[1];
                    const elapsed = match[2];

                    const elapsedHours = parseInt(elapsed.substring(0, 2)) || 0;
                    const elapsedMins = parseInt(elapsed.substring(2, 4)) || 0;

                    let etaHour = depHour + elapsedHours;
                    let etaMin = depMin + elapsedMins;

                    if (etaMin >= 60) {
                        etaHour += Math.floor(etaMin / 60);
                        etaMin = etaMin % 60;
                    }
                    etaHour = etaHour % 24;

                    const etaStr = String(etaHour).padStart(2, '0') + String(etaMin).padStart(2, '0');

                    eetMap.set(waypoint.toUpperCase(), etaStr);

                    if (waypoint.includes('/')) {
                        eetMap.set(waypoint.replace('/', '').toUpperCase(), etaStr);
                    }
                }
            });

            return eetMap;
        }

        function getWaypointETA(waypoint, eetMap, groundspeed, currentLon) {
            if (eetMap && eetMap.size > 0) {
                const wpUpper = waypoint.toUpperCase();

                if (eetMap.has(wpUpper)) {
                    const eta = eetMap.get(wpUpper);
                    return eta.substring(0, 2) + ':' + eta.substring(2, 4);
                }

                const coordMatch = wpUpper.match(/(\d{2})\/(\d{2})/);
                if (coordMatch) {
                    const searchVariants = [
                        `${coordMatch[1]}N0${coordMatch[2]}W`,
                        `${coordMatch[1]}N${coordMatch[2]}0W`,
                        `${coordMatch[1]}${coordMatch[2]}`,
                        `${coordMatch[1]}N0${coordMatch[2]}0W`
                    ];
                    for (const variant of searchVariants) {
                        if (eetMap.has(variant)) {
                            const eta = eetMap.get(variant);
                            return eta.substring(0, 2) + ':' + eta.substring(2, 4);
                        }
                    }
                }
            }

            return '----';
        }

        function updateTime() {
            const now = new Date();
            document.getElementById('time-display').textContent =
                `${String(now.getUTCHours()).padStart(2,'0')}:${String(now.getUTCMinutes()).padStart(2,'0')}z`;

            // Update TMI (changes at 0000z)
            document.getElementById('tmi-display').textContent = `TMI ${calculateTMI()}`;

            refreshCountdown--;
            if (refreshCountdown <= 0) refreshCountdown = 30;
            document.getElementById('last-update').textContent = `Next refresh: ${refreshCountdown}s`;
        }

        // =====================================================================
        // Aircraft Filtering Functions
        // =====================================================================

        function isOceanicRelevant(pilot) {
            const lat = pilot.latitude,
                lon = pilot.longitude,
                alt = pilot.altitude;

            if (alt < 24500) return false;
            if (lat < 30 || lat > 70) return false;
            if (lon < -75 || lon > 10) return false;

            if (lat >= 45 && lat <= 65 && lon >= -60 && lon <= -8) {
                return true;
            }

            if (pilot.flight_plan?.route) {
                const route = pilot.flight_plan.route.toUpperCase();

                for (const fix of NAT_FIXES) {
                    if (route.includes(fix)) return true;
                }

                if (/\d{2}N\d{2,3}W|\d{2}\/\d{2}|\d{4}N/.test(route)) return true;
                if (/\bNAT[A-Z]\b|\bNATS\b/.test(route)) return true;
            }

            if (pilot.flight_plan) {
                const dep = (pilot.flight_plan.departure || '').toUpperCase();
                const arr = (pilot.flight_plan.arrival || '').toUpperCase();

                const euroAirports = /^(EG|EI|LF|ED|EH|EB|LO|LI|LE|LP|LK|LS|LH|LZ|LW|LY|LJ|LD|LR|LB|LT|LC|LG|LA|LM|BI|EN)/;
                const usCanAirports = /^(K|C[YZ]|M[MU]|T[JIGNF])/;

                if ((euroAirports.test(arr) && usCanAirports.test(dep)) ||
                    (usCanAirports.test(arr) && euroAirports.test(dep))) {
                    return true;
                }
            }

            return false;
        }

        function getDirection(heading, departure, arrival) {
            const dep = (departure || '').toUpperCase();
            const arr = (arrival || '').toUpperCase();

            const euroAirports = /^(EG|EI|LF|ED|EH|EB|LO|LI|LE|LP|LK|LS|LH|LZ|LW|LY|LJ|LD|LR|LB|LT|LC|LG|LA|LM|BI|EN)/;
            const usCanAirports = /^(K|C[YZ]|M[MU]|T[JIGNF])/;

            if (euroAirports.test(dep) && usCanAirports.test(arr)) {
                return 'west';
            }

            if (usCanAirports.test(dep) && euroAirports.test(arr)) {
                return 'east';
            }

            if (heading >= 0 && heading < 180) {
                return 'east';
            }
            return 'west';
        }

        function detectTrack(route) {
            if (!route) return 'RR';
            const routeUpper = route.toUpperCase();
            const natMatch = routeUpper.match(/NAT([A-Z])/);
            if (natMatch) return natMatch[1];

            for (const [trackId, track] of natTracks) {
                let matchCount = 0;
                for (const wp of track.route) {
                    if (routeUpper.includes(wp.name.toUpperCase())) matchCount++;
                }
                if (matchCount >= 3) return trackId;
            }
            return 'RR';
        }

        function shortenCoordinate(wp) {
            if (/^\d{2}\/\d{2}$/.test(wp)) return wp;

            let match = wp.match(/^(\d{2})[NS]0?(\d{2,3})[EW]?$/);
            if (match) {
                let lon = match[2];
                if (lon.length === 3) lon = lon.substring(0, 2);
                return `${match[1]}/${lon}`;
            }

            match = wp.match(/^(\d{2})\d{2}[NS]0?(\d{2,3})\d{0,2}[EW]$/);
            if (match) {
                let lon = match[2];
                if (lon.length === 3) lon = lon.substring(0, 2);
                return `${match[1]}/${lon}`;
            }

            match = wp.match(/^(\d{2})(\d{2})[NS]$/);
            if (match) {
                return `${match[1]}/${match[2]}`;
            }

            return wp;
        }

        function extractOceanicRoute(route) {
            if (!route) return [];

            const oceanicWaypoints = [];
            const parts = route.toUpperCase().split(/\s+/);

            for (const part of parts) {
                if (part === 'DCT' || part === 'NATS' || part.startsWith('N') && /^N\d{4}/.test(part)) continue;
                if (part.includes('/M0') || part.includes('/N0') || part.includes('F3') || part.includes('F4')) continue;
                if (/^[A-Z]\d+$/.test(part) && part.length <= 5) continue;

                let cleanWp = part.split('/')[0];

                if (/^\d{2}[NS]\d{2,3}[EW]?$/.test(cleanWp) ||
                    /^\d{4}[NS]$/.test(cleanWp) ||
                    /^\d{2}\/\d{2}$/.test(cleanWp) ||
                    /^\d{4}[NS]\d{4,5}[EW]$/.test(cleanWp)) {
                    oceanicWaypoints.push(shortenCoordinate(cleanWp));
                    continue;
                }

                if (NAT_FIXES.includes(cleanWp)) {
                    oceanicWaypoints.push(cleanWp);
                    continue;
                }
            }

            return oceanicWaypoints.slice(0, 12);
        }

        // =====================================================================
        // API Fetch Functions
        // =====================================================================

        async function fetchTracks() {
            try {
                const response = await fetch(NATTRAK_TRACKS_API);
                const data = await response.json();
                natTracks.clear();

                const now = new Date();
                const twelveHoursAgo = new Date(now.getTime() - 12 * 60 * 60 * 1000);

                data.forEach(track => {
                    // Include if: active OR was active within last 12 hours
                    let isActive = track.active;
                    let isRecent = false;

                    if (!isActive && track.last_active) {
                        const lastActive = new Date(track.last_active);
                        if (lastActive >= twelveHoursAgo) {
                            isRecent = true;
                        }
                    }

                    if (!isActive && !isRecent) return;

                    const waypoints = track.last_routeing.split(' ').map(wp => ({
                        name: wp
                    }));

                    natTracks.set(track.identifier, {
                        id: track.identifier,
                        route: waypoints,
                        flightLevels: track.flight_levels,
                        direction: track.direction === 'east' ? 1 : 0,
                        validFrom: track.valid_from,
                        validTo: track.valid_to,
                        lastActive: track.last_active,
                        isActive: isActive,
                        isRecent: isRecent
                    });
                });

                document.getElementById('tmi-display').textContent = `TMI ${calculateTMI()}`;
                renderTracks();
            } catch (e) {
                console.error('Tracks error:', e);
            }
        }

        async function fetchClearances() {
            try {
                const response = await fetch(NATTRAK_RCL_API);
                const data = await response.json();

                const currentCallsigns = new Set(clearances.keys());
                clearances.clear();

                setTimeout(() => {
                    newClearances.clear();
                    renderClearances();
                }, 10000);

                data.forEach(clx => {
                    const callsignUpper = clx.callsign.toUpperCase();

                    if (processingClearances.has(callsignUpper)) return;

                    if (clx.status === 'PENDING' && !currentCallsigns.has(callsignUpper) && previousClearances
                        .size > 0) {
                        newClearances.add(callsignUpper);
                    }

                    let machValue = clx.mach || 0;
                    if (typeof machValue === 'number' && machValue < 1) {
                        machValue = Math.round(machValue * 100);
                    }

                    let etaSort = 9999;
                    let etaDisplay = clx.estimating_time || '';
                    if (etaDisplay && etaDisplay !== ':') {
                        const etaClean = etaDisplay.replace(':', '');
                        etaSort = parseInt(etaClean) || 9999;
                    }

                    clearances.set(callsignUpper, {
                        callsign: clx.callsign,
                        status: clx.status || 'PENDING',
                        track: clx.nat || 'RR',
                        fix: clx.fix || '',
                        level: clx.level || 0,
                        mach: machValue,
                        destination: '',
                        entry_estimate: etaDisplay,
                        entry_time_sort: etaSort,
                        clearance_issued: clx.clearance_issued || '',
                        extra_info: clx.extra_info || '',
                        isNew: newClearances.has(callsignUpper),
                        isAck: acknowledgedClearances.has(callsignUpper)
                    });
                });

                previousClearances = new Set(clearances.keys());

                let pendingCount = 0;
                clearances.forEach(clx => {
                    if (clx.status === 'PENDING') pendingCount++;
                });

                document.getElementById('clx-count').textContent = `${pendingCount} RCL`;
                renderClearances();
            } catch (e) {
                console.error('Clearances error:', e);
            }
        }

        async function fetchVatsimPilots() {
            try {
                const response = await fetch(VATSIM_API);
                const data = await response.json();

                oceanicAircraft = (data.pilots || []).filter(isOceanicRelevant).map(pilot => {
                    const fp = pilot.flight_plan || {};
                    const clx = clearances.get(pilot.callsign.toUpperCase());
                    const direction = getDirection(pilot.heading, fp.departure, fp.arrival);

                    return {
                        callsign: pilot.callsign,
                        type: fp.aircraft_short || '????',
                        departure: fp.departure || '----',
                        arrival: fp.arrival || '----',
                        filedLevel: fp.altitude ? Math.floor(parseInt(fp.altitude) / 100) : 0,
                        clearedLevel: clx?.level || null,
                        clearedMach: clx?.mach || null,
                        track: clx?.track || detectTrack(fp.route),
                        route: extractOceanicRoute(fp.route),
                        latitude: pilot.latitude,
                        longitude: pilot.longitude,
                        groundspeed: pilot.groundspeed,
                        heading: pilot.heading,
                        direction: direction,
                        isCleared: !!clx,
                        entryFix: clx?.fix || '',
                        entryEstimate: clx?.entry_estimate || '',
                        remarks: fp.remarks || '',
                        deptime: fp.deptime || '',
                        eetMap: parseEETFromRemarks(fp.remarks, fp.deptime)
                    };
                });

                document.getElementById('ac-count').textContent = `${oceanicAircraft.length} AC`;
                renderFDD();
            } catch (e) {
                console.error('VATSIM error:', e);
            }
        }

        // =====================================================================
        // Render Functions
        // =====================================================================

        function renderTracks() {
            const panel = document.getElementById('tracks-panel');
            if (natTracks.size === 0) {
                panel.innerHTML = '<div class="no-data">No active or recent tracks</div>';
                return;
            }

            let html = '';
            const sorted = [...natTracks.entries()].sort((a, b) => a[0].localeCompare(b[0]));

            sorted.forEach(([id, track]) => {
                const dir = track.direction === 1 ? 'east' : 'west';
                const fls = (track.flightLevels || []).map(fl => fl >= 1000 ? Math.floor(fl / 100) : fl).join(' ');

                // Show last active time for inactive, valid to for active
                let validTimeText = '';
                if (track.isActive && track.validTo) {
                    validTimeText = 'Until: ' + new Date(track.validTo).toISOString().substring(11, 16) + 'z';
                } else if (track.isRecent && track.lastActive) {
                    const lastActive = new Date(track.lastActive);
                    validTimeText = 'Ended: ' + lastActive.toISOString().substring(11, 16) + 'z';
                }

                // Dim inactive tracks
                const opacityStyle = track.isRecent ? 'opacity: 0.5;' : '';
                const statusBadge = track.isRecent ?
                    '<span style="background:#6b7280;color:#fff;padding:1px 5px;font-size:0.7rem;margin-left:5px;border-radius:2px;">INACTIVE</span>' :
                    '';

                html += `<div class="track-strip" style="${opacityStyle}">
                    <div class="track-strip-header">
                        <span style="width:30px;font-weight:bold">${id}</span>
                        <span style="width:60px">${dir.toUpperCase()}</span>
                        <span style="flex:1">${fls}${statusBadge}</span>
                        <span>${validTimeText}</span>
                    </div>
                    <div class="track-strip-body ${dir}">
                        ${track.route.map(wp => `<span style="margin-right:8px">${wp.name}</span>`).join('')}
                    </div>
                </div>`;
            });

            panel.innerHTML = html;
        }

        function renderFDD() {
            const panel = document.getElementById('fdd-panel');
            document.getElementById('loading').style.display = 'none';

            const filters = {
                east: document.getElementById('filter-east').checked,
                west: document.getElementById('filter-west').checked,
                cleared: document.getElementById('filter-cleared').checked,
                uncleared: document.getElementById('filter-uncleared').checked,
                tracks: document.getElementById('filter-tracks').checked,
                random: document.getElementById('filter-random').checked
            };

            const filtered = oceanicAircraft.filter(ac => {
                if (ac.direction === 'east' && !filters.east) return false;
                if (ac.direction === 'west' && !filters.west) return false;
                if (ac.isCleared && !filters.cleared) return false;
                if (!ac.isCleared && !filters.uncleared) return false;
                if (ac.track !== 'RR' && !filters.tracks) return false;
                if (ac.track === 'RR' && !filters.random) return false;
                return true;
            });

            const byTrack = new Map();
            byTrack.set('RR', []);
            natTracks.forEach((_, id) => byTrack.set(id, []));

            filtered.forEach(ac => {
                const track = ac.track || 'RR';
                if (!byTrack.has(track)) byTrack.set(track, []);
                byTrack.get(track).push(ac);
            });

            let html = '';

            const trackOrder = [...natTracks.keys()].sort();
            trackOrder.push('RR-EAST');
            trackOrder.push('RR-WEST');

            const randomEast = [];
            const randomWest = [];
            (byTrack.get('RR') || []).forEach(ac => {
                if (ac.direction === 'east') randomEast.push(ac);
                else randomWest.push(ac);
            });
            byTrack.set('RR-EAST', randomEast);
            byTrack.set('RR-WEST', randomWest);
            byTrack.delete('RR');

            trackOrder.forEach(trackId => {
                const aircraft = byTrack.get(trackId) || [];
                if (aircraft.length === 0) return;

                const trackData = natTracks.get(trackId);
                const isRandomEast = trackId === 'RR-EAST';
                const isRandomWest = trackId === 'RR-WEST';
                const isRandom = isRandomEast || isRandomWest;

                let headerText = trackId;
                if (isRandomEast) headerText = 'RANDOM EASTBOUND';
                else if (isRandomWest) headerText = 'RANDOM WESTBOUND';

                html += `<div class="track-header ${isRandomEast ? 'track-header-east' : ''} ${isRandomWest ? 'track-header-west' : ''}">
                    <div class="track-id">${headerText}</div>
                    <div class="track-route">${trackData ? trackData.route.map(wp=>`<span>${wp.name}</span>`).join('') : ''}</div>
                </div>`;

                aircraft.sort((a, b) => {
                    let etaA = 9999,
                        etaB = 9999;

                    if (a.eetMap && a.eetMap.size > 0) {
                        const lastEtaA = Array.from(a.eetMap.values()).pop();
                        if (lastEtaA) etaA = parseInt(lastEtaA) || 9999;
                    }
                    if (b.eetMap && b.eetMap.size > 0) {
                        const lastEtaB = Array.from(b.eetMap.values()).pop();
                        if (lastEtaB) etaB = parseInt(lastEtaB) || 9999;
                    }

                    return etaA - etaB;
                });

                aircraft.forEach(ac => {
                    // For aircraft on named tracks, use the track's direction
                    // For random routes, use the aircraft's calculated direction
                    let isEast = ac.direction === 'east';

                    if (trackData && !isRandom) {
                        // Use track direction for named tracks
                        isEast = trackData.direction === 1;
                    }

                    const stripClass = isEast ? 'flight-strip-east' : 'flight-strip-west';

                    const level = ac.clearedLevel || ac.filedLevel || '---';
                    const levelStr = level ? String(level).replace(/^0+/, '') : '---';

                    let machStr = '----';
                    if (ac.clearedMach) {
                        machStr = 'M.' + String(ac.clearedMach).padStart(2, '0');
                    }

                    const routePoints = ac.route.slice(0, 10);
                    const routeRowClass = isEast ? 'route-row-east' : 'route-row-west';

                    html += `<div class="flight-strip-container">`;

                    html += `<div class="flight-strip ${stripClass}">
                        <div class="callsign">${ac.callsign}</div>
                        <div class="type">${ac.type}</div>
                        <div class="airports">${ac.departure}/${ac.arrival}</div>
                        <div class="level">${levelStr}</div>
                        <div class="mach">${machStr}</div>
                    </div>`;

                    if (routePoints.length > 0) {
                        html += `<div class="flight-strip-route ${routeRowClass}">
                            <div class="route-points">
                                ${routePoints.map(wp => {
                                    const eta = getWaypointETA(wp, ac.eetMap);
                                    return `<div class="route-point"><span class="wp-name">${wp}</span><span class="wp-eta">${eta}</span></div>`;
                                }).join('')}
                            </div>
                        </div>`;
                    }

                    html += `</div>`;
                });
            });

            panel.innerHTML = html;
        }

        function renderClearances() {
            const panel = document.getElementById('clearances-panel');

            const pendingList = Array.from(clearances.values()).filter(clx => clx.status === 'PENDING');

            if (pendingList.length === 0) {
                panel.innerHTML = '<div class="no-data">No pending clearance requests</div>';
                return;
            }

            let html = `<div class="clearance-row" style="font-weight:bold;border-bottom:2px solid rgba(255,255,255,0.3);background:rgba(0,0,0,0.2);cursor:default">
                <span class="cs">C/S</span>
                <span class="fix">FIX</span>
                <span class="eta">ETA</span>
                <span class="level">FL</span>
                <span class="mach">M#</span>
                <span class="track">TK</span>
                <span class="status">STATUS</span>
            </div>`;

            const sorted = pendingList.sort((a, b) => a.entry_time_sort - b.entry_time_sort);

            sorted.forEach(clx => {
                const fl = clx.level ? String(clx.level) : '---';
                const mach = clx.mach ? '.' + String(clx.mach).padStart(2, '0') : '---';
                const track = clx.track || 'RR';
                const fix = clx.fix || '---';

                let etaStr = '----';
                if (clx.entry_estimate && clx.entry_estimate !== ':') {
                    etaStr = clx.entry_estimate;
                    if (!etaStr.includes(':') && etaStr.length === 4) {
                        etaStr = etaStr.substring(0, 2) + ':' + etaStr.substring(2, 4);
                    }
                }

                let statusText = 'PENDING';
                let statusClass = 'status-pending';

                if (clx.isAck || acknowledgedClearances.has(clx.callsign.toUpperCase())) {
                    statusText = 'ACK';
                    statusClass = 'status-clr';
                }

                const isNew = newClearances.has(clx.callsign.toUpperCase());
                const isAck = acknowledgedClearances.has(clx.callsign.toUpperCase());
                let rowClasses = 'clearance-row';
                if (isNew) rowClasses += ' clearance-new';
                if (isAck) rowClasses += ' clearance-ack';

                html += `<div class="${rowClasses}" onclick="showClearanceModal('${clx.callsign}')">
                    <span class="cs">${clx.callsign}</span>
                    <span class="fix">${fix}</span>
                    <span class="eta">${etaStr}</span>
                    <span class="level">${fl}</span>
                    <span class="mach">${mach}</span>
                    <span class="track">${track}</span>
                    <span class="status ${statusClass}">${statusText}</span>
                </div>`;
            });

            panel.innerHTML = html;
        }

        // =====================================================================
        // Modal Functions
        // =====================================================================

        function showClearanceModal(callsign) {
            selectedCallsign = callsign;
            document.getElementById('modal-callsign').textContent = callsign;
            document.getElementById('modal-content').style.display = 'block';
            document.getElementById('modal-confirm').style.display = 'none';
            document.getElementById('clearance-modal').classList.add('show');
        }

        function hideModal() {
            document.getElementById('clearance-modal').classList.remove('show');
            selectedCallsign = null;
        }

        function ackClearance() {
            if (!selectedCallsign) return;
            acknowledgedClearances.add(selectedCallsign.toUpperCase());
            hideModal();
            renderClearances();
        }

        function processClearance() {
            if (!selectedCallsign) return;
            const cs = selectedCallsign.toUpperCase();
            processingClearances.add(cs);
            acknowledgedClearances.delete(cs);
            hideModal();
            renderClearances();

            setTimeout(() => {
                processingClearances.delete(cs);
                clearances.delete(cs);
                renderClearances();
            }, 5000);
        }

        function showRemoveConfirm() {
            document.getElementById('modal-content').style.display = 'none';
            document.getElementById('modal-confirm').style.display = 'block';
        }

        function hideRemoveConfirm() {
            document.getElementById('modal-content').style.display = 'block';
            document.getElementById('modal-confirm').style.display = 'none';
        }

        function removeClearance() {
            if (!selectedCallsign) return;
            const cs = selectedCallsign.toUpperCase();
            clearances.delete(cs);
            acknowledgedClearances.delete(cs);
            processingClearances.delete(cs);
            hideModal();
            renderClearances();
        }

        async function refreshAll() {
            document.getElementById('loading').style.display = 'block';
            refreshCountdown = 30;
            await fetchTracks();
            await fetchClearances();
            await fetchVatsimPilots();
            document.getElementById('loading').style.display = 'none';
        }

        // =====================================================================
        // Initialization
        // =====================================================================

        async function init() {
            updateTime();
            setInterval(updateTime, 1000);

            ['filter-east', 'filter-west', 'filter-cleared', 'filter-uncleared', 'filter-tracks', 'filter-random']
            .forEach(id => document.getElementById(id).addEventListener('change', renderFDD));

            await refreshAll();

            // Auto-refresh intervals
            setInterval(fetchTracks, 300000); // Tracks every 5 minutes
            setInterval(fetchClearances, 30000); // RCL requests every 30 seconds
            setInterval(fetchVatsimPilots, 30000); // VATSIM data every 30 seconds

            console.log('FDD initialized - auto-refresh active (30 sec intervals)');
        }

        init();
    </script>
    <script src="https://cdn.tailwindcss.com"></script>
@endsection
