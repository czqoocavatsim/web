/*
Format of clearance request:
CALLSIGN request clearance via Track LETTER|route ROUTE. Estimating
ENTRY at TIME. Request Flight Level FLIGHTLEVEL, Mach MACHSPEED.
(Result will say 'Readback TMI [TMI] on readback of clearance from controller.)
*/

//Generate result
function generateOceanicClearance(){
    //Get variables from form
    var callsign = document.getElementById('callsignB').value;
    var flightLevel = document.getElementById('flightLevelB').value;
    var mach = document.getElementById('machB').value;
    var nat = document.getElementById('natB').value;
    var route = document.getElementById('routeB').value;
    var entry = document.getElementById('entryB').value;
    var time = document.getElementById('timeB').value;
    var tmi = document.getElementById('tmiB').value;

    //In case there are errors...
    var errors = [];

    //Check if fields aren't filled.
    //First, NAT/Route because there might be a reason for it not being filled.
    var routeMode;
    if (document.getElementById('natRoutePanel').style.display == 'block'){
        if (nat == ''){
            errors.push('NAT track not filled.');
        }else{routeMode = 0;}
}else{
        if (route == ''){
            errors.push('Random route not filled.');
        }else{routeMode = 1;}
    }

    //Callsign, flight level, mach, entry, estimating
    if (callsign == ''){
        errors.push('Callsign not filled');
    }
    if (flightLevel == ''){
        errors.push('Flight level not filled.');
    }
    if (mach == ''){
        errors.push('Mach speed not filled.');
    }
    if (entry == ''){
        errors.push('Entry fix not filled.');
    }
    if (time == ''){
        errors.push('Estimating time not filled.');
    }

    //There are errors... tell the user to fix 'em!
    if (errors.length >= 1){
        return invalidSubmission(errors);
    }

    //No errors? March on!
    //Generate main request transcript.
    var transcript;
    //Nat routing
    if (routeMode == 0){
        transcript = callsign + " request clearance via Track " + nat + ". Estimating " + entry + " at " + time + ". Request Flight Level " + flightLevel + ", Mach " + mach + ".";
    }else{
        transcript = callsign + " request clearance via route " + route + ". Estimating " + entry + " at"  + time + ". Request Flight Level " + flightLevel + ", Mach " + mach + ".";
    }

    //Display it!
    document.getElementById('errorA').style.display = 'none';
    document.getElementById('results').innerHTML = transcript;
    if (tmi !== ''){
        document.getElementById('results').innerHTML = document.getElementById('results').innerHTML + "<br/><strong>On readback, state you have TMI " + tmi + ".</strong>";
    }
}

//Nat/random routing select
function routingSelect(value){
    if (value == 'nat'){
        document.getElementById('natRoutePanel').style.display = 'block';
        document.getElementById('randomRoutePanel').style.display = 'none';
    }else{
        document.getElementById('natRoutePanel').style.display = 'none';
        document.getElementById('randomRoutePanel').style.display = 'block';
    }
}

/*
Format of clearance request:
CALLSIGN request clearance via Track LETTER|route ROUTE. Estimating
ENTRY at TIME. Request Flight Level FLIGHTLEVEL, Mach MACHSPEED.
(Result will say 'Readback TMI [TMI] on readback of clearance from controller.)
*/

//Generate results
function generatePositionReport(){
    //Get variables from form
    var callsign = document.getElementById('callsignB').value;
    var reporting = document.getElementById('reportingB').value;
    var time = document.getElementById('timeB').value;
    var flightLevel = document.getElementById('flightLevelB').value;
    var next = document.getElementById('nextB').value;
    var estimating = document.getElementById('estimatingB').value;
    var thereafter = document.getElementById('thereafterB').value;

    //In case there are errors...
    var errors = [];

    //Check if fields aren't filled
    if (callsign == ''){
        errors.push('Callsign not filled');
    }
    if (reporting == ''){
        errors.push('Reporting fix not filled');
    }
    if (time == ''){
        errors.push('Time not filled');
    }
    if (flightLevel == ''){
        errors.push('Flight level not filled');
    }
    if (next == ''){
        errors.push('Next fix not filled');
    }
    if (estimating == ''){
        errors.push('Estimating next fix time not filled');
    }
    if (thereafter == ''){
        errors.push('Fix thereafter not filled');
    }

    //There are errors... tell the user to fix 'em!
    if (errors.length >= 1){
        return invalidSubmission(errors);
    }

    //No errors? March on!
    //Generate main request transcript.
    var transcript;
    //Create transcript
    transcript = callsign + ', position ' + reporting + ' at ' + time + ', Flight Level ' + flightLevel + ', Estimating ' + next + ' at ' + estimating + ', ' + thereafter + ' thereafter.';


    //Display it!
    document.getElementById('errorA').style.display = 'none';
    document.getElementById('results').innerHTML = transcript;
}

//Deal with invalid submission
function invalidSubmission(errors){
    document.getElementById('errorContent').innerHTML = "";
    document.getElementById('errorA').style.display = 'block';
    for (i = 0; i < errors.length; i++) {
        document.getElementById('errorContent').innerHTML = document.getElementById('errorContent').innerHTML + '<br/>' + errors[i];
    }
}

function createNatTrakMap()
{
    var map = L.map('map').setView([50.198470, -32.708], 3);

    L.tileLayer('https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token={accessToken}', {
        attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, <a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
        maxZoom: 18,
        id: 'mapbox.light',
        accessToken: 'pk.eyJ1IjoiZWx0ZWNocm9uIiwiYSI6ImNqOTlydHR4czB4NG8ycWxzYXNla2pmOXcifQ.hBI3z2L84aiEDfp5H946_Q'
    }).addTo(map);

    /* var polygon = L.polygon([
        [51.509, -0.08],
        [51.503, -0.06],
        [51.51, -0.047]
    ]).addTo(map);
    polygon.bindPopup("I am a polygon."); */

    //var marker = L.marker([51.5, -0.09]).addTo(mymap);

    //Get tracks
    let api = "https://api.flightplandatabase.com/nav/NATS";
    let xmlHttp = new XMLHttpRequest();
    xmlHttp.open("GET", api, false);
    xmlHttp.send(null);
    let apiString = xmlHttp.responseText;
    let apiJson = JSON.parse(apiString);
    console.log(apiJson);

    var processedNats = [];

    //Go through all the tracks
    for (track in apiJson) {
        //Go through the tracks and only use the good ones...
        if (checkIfNatProcessed(apiJson[track].ident) == false) {
            processedNats.push(apiJson[track].ident);
            //Create some markers
            let fixArray = [];
            for (n in apiJson[track].route.nodes) {
                if (apiJson[track].route.eastLevels.length == 0) {
                    createMarker(apiJson[track].route.nodes[n], apiJson[track].ident, 'orange');
                }else{
                createMarker(apiJson[track].route.nodes[n], apiJson[track].ident, 'blue');}
                fixArray.push([apiJson[track].route.nodes[n].lat, apiJson[track].route.nodes[n].lon]);
            }
            let polyline = new L.Polyline(fixArray, {
                color: '#1c5fc9',
                weight: 2,
                opacity: 1,
                smoothFactor: 1
            });
            if (apiJson[track].route.eastLevels.length == 0) {
                polyline.setStyle({
                    color: '#c92d1c'
                });
            }
            polyline.addTo(map);
        };
    }

    let table = document.getElementById('tableBody');
    processedNats = [];
    for (track in apiJson) {
        if (checkIfNatProcessed(apiJson[track].ident) == false) {
            processedNats.push(apiJson[track].ident);
            //Create a row
            let row = document.createElement('tr');
            table.appendChild(row);

            //Get the track ID
            let identCol = document.createElement('th');
            identCol.scope = 'row';
            identCol.innerHTML = apiJson[track].ident;
            row.appendChild(identCol);

            //Get the fixes
            let fixArray = [];
            for (n in apiJson[track].route.nodes) {
                fixArray.push(" " + apiJson[track].route.nodes[n].ident);
            }
            let fixesCol = document.createElement('td');
            fixesCol.innerHTML = fixArray;
            row.appendChild(fixesCol);

            //figure out the direction and get levels
            let levelArray = [];
            let directionCol = document.createElement('td');
            let levelsCol = document.createElement('td');
            if (apiJson[track].route.eastLevels.length == 0) {
                apiJson[track].route.westLevels.forEach(function (element) {
                    levelArray.push(" " + element);
                });
                directionCol.innerHTML = "West";
            } else {
                apiJson[track].route.eastLevels.forEach(function (element) {
                    levelArray.push(" " + element);
                });
                directionCol.innerHTML = "East";
            }
            levelsCol.innerHTML = levelArray;
            row.appendChild(directionCol);
            row.appendChild(levelsCol);

            //validity
            let validityCol = document.createElement('td');
            let validFrom = " " + apiJson[track].validFrom;
            let validTo = apiJson[track].validTo;
            validityCol.innerHTML = validFrom + " to " + validTo;
            row.appendChild(validityCol);
        };
    }


}



function checkIfNatProcessed(ident) {
    if (processedNats.indexOf(ident) > -1) {
        return true;
    } else {
        return false;
    }
}

function createMarker(node, trackId, colour) {
    let markerIcon = L.icon({
        iconUrl: 'https://nesa.com.au/wp-content/uploads/2017/05/Dot-points-1.png',
        iconSize: [10, 10],
        iconAnchor: [2,4]
    });
    let marker = L.marker([node.lat, node.lon], {icon: markerIcon}).addTo(map);
    marker.bindPopup("<b>"+node.ident+"</b><br/>"+node.type+"<br/>"+node.lat+" "+node.lon);
}

/*
Function to expand and hide policy embeds on /policies
*/
$(document).ready(function () {
$(".expandHidePolicyButton").on('click', function() {
    //Get policy id
    policyId = $(this).data("policy-id")

    //Toggle the embed
    $(`#policyEmbed${policyId}`).toggleClass('d-none');
});
});



var processedNats = [];
function createMap(planes, ganderControllers, shanwickControllers) {
    const map = L.map('map', { minZoom: 4, maxZoom: 7 }).setView([60, -30], 1);
const icon = L.icon({ iconUrl: '/img/oep.png', iconAnchor: [5, 5] });

var OpenStreetMap_Mapnik = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

// Gander OEP's

const pointsGander = [
    ['AVPUT', [ '65.03333333333333', '-60' ]],
    ['CLAVY', [ '64.23333333333333', '-59' ]],
    ['EMBOK', [ '63.46666666666667', '-58' ]],
    ['KETLA', [ '62.46666666666667', '-58' ]],
    ['LIBOR', [ '61.96666666666667', '-58' ]],
    ['MAXAR', [ '61.46666666666667', '-58' ]],
    ['NIFTY', [ '60.96666666666667', '-58' ]],
    ['PIDSO', [ '60.46666666666667', '-58' ]],
    ['RADUN', [ '59.96666666666667', '-58' ]],
    ['SAVRY', [ '59.46666666666667', '-58' ]],
    ['TOXIT', [ '58.96666666666667', '-58' ]],
    ['URTAK', [ '58.46666666666667', '-58' ]],
    ['VESMI', [ '57.96666666666667', '-58' ]],
    ['AVUTI', [ '57.46666666666667', '-58' ]],
    ['BOKTO', [ '56.96666666666667', '-58' ]],
    ['CUDDY', [ '56.7', '-57' ]],
    ['DORYY', [ '56.03333333333333', '-57' ]],
    ['ENNSO', [ '55.53333333333333', '-57' ]],
    ['HOIST', [ '55.03333333333333', '-57' ]],
    ['IRLOK', [ '54.53333333333333', '-57' ]],
    ['JANJO', [ '54.03333333333333', '-57' ]],
    ['KODIK', [ '53.46666666666667', '-57.2' ]],
    ['LOMSI', [ '53.1', '-56.78333333333333' ]],
    ['MELDI', [ '52.733333333333334', '-56.35' ]],
    ['NEEKO', [ '52.4', '-55.833333333333336' ]],
    ['PELTU', [ '52.1', '-55.166666666666664' ]],
    ['RIKAL', [ '51.8', '-54.53333333333333' ]],
    ['SAXAN', [ '51.483333333333334', '-53.85' ]],
    ['TUDEP', [ '51.166666666666664', '-53.233333333333334' ]],
    ['UMESI', [ '50.833333333333336', '-52.6' ]],
    ['ALLRY', [ '50.5', '-52' ]],
    ['BUDAR', [ '50', '-52' ]],
    ['ELSIR', [ '49.5', '-52' ]],
    ['IBERG', [ '49', '-52' ]],
    ['JOOPY', [ '48.5', '-52' ]],
    ['MUSAK', [ '48', '-52' ]],
    ['NICSO', [ '47.5', '-52' ]],
    ['OMSAT', [ '47', '-52' ]],
    ['PORTI', [ '46.5', '-52' ]],
    ['RELIC', [ '46', '-52' ]],
    ['SUPRY', [ '45.5', '-52' ]],
    ['RAFIN', [ '44.88333333333333', '-51.80472222222222' ]],
    ['JAROM', [ '44.166666666666664', '-54.88333333333333' ]],
    ['BOBTU', [ '44.117222222222225', '-52.82222222222222' ]]
];

pointsGander.forEach(point => {
    L.marker([parseFloat(point[1][0]), parseFloat(point[1][1])], {icon: icon, opacity: 0.3}).addTo(map).bindPopup(point[0]);
});

// Shanwick OEP's

const pointsShanwick = [
    ['RATSU', [ '61', '-10' ]],
    ['LUSEN', [ '60.5', '-10' ]],
    ['ATSIX', [ '60', '-10' ]],
    ['ORTAV', [ '59.5', '-10' ]],
    ['BALIX', [ '59', '-10' ]],
    ['ADODO', [ '58.5', '-10' ]],
    ['ERAKA', [ '58', '-10' ]],
    ['ETILO', [ '57.5', '-10' ]],
    ['GOMUP', [ '57', '-10' ]],
    ['AGORI', [ '57', '-13' ]],
    ['SUNOT', [ '57', '-15' ]],
    ['BILTO', [ '56.5', '-15' ]],
    ['PIKIL', [ '56', '-15' ]],
    ['ETARI', [ '55.5', '-15' ]],
    ['RESNO', [ '55', '-15' ]],
    ['VENER', [ '54.5', '-15' ]],
    ['DOGAL', [ '54', '-15' ]],
    ['NEBIN', [ '53.5', '-15' ]],
    ['MALOT', [ '53', '-15' ]],
    ['TOBOR', [ '52.5', '-15' ]],
    ['LIMRI', [ '52', '-15' ]],
    ['ADARA', [ '51.5', '-15' ]],
    ['DINIM', [ '51', '-15' ]],
    ['RODEL', [ '50.5', '-15' ]],
    ['SOMAX', [ '50', '-15' ]],
    ['KOGAD', [ '49.5', '-15' ]],
    ['BEDRA', [ '49', '-15' ]],
    ['NERTU', [ '49', '-14' ]],
    ['NASBA', [ '49', '-13' ]],
    ['OMOKO', [ '48.83888888888889', '-12' ]],
    ['TAMEL', [ '48.728611111111114', '-10.497222222222222' ]],
    ['GELPO', [ '48.64416666666666', '-9.5025' ]],
    ['LASNO', [ '48.598333333333336', '-9' ]],
    ['ETIKI', [ '48', '-8.75' ]],
    ['UMLER', [ '47.5', '-8.75' ]],
    ['SEPAL', [ '47', '-8.75' ]],
    ['BUNAV', [ '46.5', '-8.75' ]],
    ['SIVIR', [ '46', '-8.75' ]],
    ['BEGAS', [ '45', '-9' ]],
    ['DIVAT', [ '45', '-9.469722222222222' ]],
    ['DIXIS', [ '45', '-10' ]],
    ['BERUX', [ '45', '-11' ]],
    ['PITAX', [ '45', '-12' ]],
    ['PASAS', [ '45', '-13' ]],
    ['NILAV', [ '45', '-13.416666666666666' ]],
    ['GONAN', [ '45', '-14' ]]
];

pointsShanwick.forEach(point => {
    L.marker([parseFloat(point[1][0]), parseFloat(point[1][1])], {icon: icon, opacity: 0.3}).addTo(map).bindPopup(point[0]);
});

// Coordinate grid

L.latlngGraticule({
    showLabel: true,
    dashArray: [5, 5],
    zoomInterval: [ { start: 0, end: 10, interval: 5 } ]
}).addTo(map);

// OCA's, FIR's and delegated areas

const Gander = [
    [ '45', '-51' ],
    [ '45', '-50' ],
    [ '44', '-50' ],
    [ '44', '-40' ],
    [ '45', '-40' ],
    [ '45', '-30' ],
    [ '61', '-30' ],
    [ '63.5', '-39' ],
    [ '58.5', '-43' ],
    [ '58.5', '-50' ],
    [ '65', '-57.75' ],
    [ '65', '-60' ],
    [ '64', '-63' ],
    [ '61', '-63' ],
    [ '58.471111111111114', '-60.35111111111111' ],
    [ '57', '-59' ],
    [ '53', '-54' ],
    [ '49', '-51' ],
    [ '45', '-51' ]
];
L.polyline(Gander, { color: '#777', weight: 0.5 }).addTo(map);

const Shanwick = [
    [ '45', '-30' ],
    [ '45', '-8' ],
    [ '51', '-8' ],
    [ '51', '-15' ],
    [ '54', '-15' ],
    [ '54.56666666666667', '-10' ],
    [ '61', '-10' ],
    [ '61', '-30' ],
    [ '45', '-30' ]
];
L.polyline(Shanwick, { color: '#777', weight: 0.5 }).addTo(map);

const NOTA = [
    [ '54', '-15' ],
    [ '54.56666666666667', '-10' ],
    [ '57', '-10' ],
    [ '57', '-15' ],
    [ '54', '-15' ]
];
L.polyline(NOTA, { color: '#777', weight: 0.5 }).addTo(map);

const SOTA = [
    [ '49', '-15' ],
    [ '48.5769444444', '-8.75' ],
    [ '48.5769444444', '-8' ],
    [ '51', '-8' ],
    [ '51', '-15' ],
    [ '49', '-15' ]
];
L.polyline(SOTA, { color: '#777', weight: 0.5 }).addTo(map);

const BOTA = [
    [ '45', '-8.75' ],
    [ '45', '-8' ],
    [ '48.5769444444', '-8' ],
    [ '48.5769444444', '-8.75' ],
    [ '45', '-8.75' ]
];
L.polyline(BOTA, { color: '#777', weight: 0.5 }).addTo(map);

const GOTA = [
    [ '53.8', '-55' ],
    [ '62.85', '-55' ],
    [ '65', '-57.75' ],
    [ '65', '-60' ],
    [ '64', '-63' ],
    [ '61', '-63' ],
    [ '57', '-59' ],
    [ '53.8', '-55' ]
];
L.polyline(GOTA, { color: '#777', weight: 0.5 }).addTo(map);

const Nuuk = [
    [ '58.5', '-50' ],
    [ '58.5', '-43' ],
    [ '63.5', '-39' ],
    [ '63.5', '-55.80928' ],
    [ '58.5', '-50' ]
];
L.polyline(Nuuk, { color: '#777', weight: 0.5 }).addTo(map);

const GanderDomestic = [
    [ '45', '-51' ],
    [ '45', '-53' ],
    [ '44.446666666666665', '-56.05166666666666' ],
    [ '45.61194444444445', '-56.47361111111111' ],
    [ '48.5', '-62' ],
    [ '49.3', '-61' ],
    [ '49.53333333333333', '-61' ],
    [ '51', '-58' ],
    [ '51.28333333333333', '-57' ],
    [ '51.735', '-57' ],
    [ '52.19638888888888', '-58.14277777777778' ],
    [ '51.63333333333333', '-59.5' ],
    [ '51.333333333333336', '-59.5' ],
    [ '50.833333333333336', '-60' ],
    [ '50.833333333333336', '-62.083333333333336' ],
    [ '51.416666666666664', '-64' ],
    [ '53.7', '-64.91666666666667' ],
    [ '54.416666666666664', '-65.33333333333333' ],
    [ '55.083333333333336', '-65.08333333333333' ],
    [ '55.355555555555554', '-64' ],
    [ '57.55', '-64' ],
    [ '58.471111111111114', '-60.35111111111111' ],
    [ '57', '-59' ],
    [ '53', '-54' ],
    [ '49', '-51' ],
    [ '45', '-51' ]
];
L.polyline(GanderDomestic, { color: '#777', weight: 0.5 }).addTo(map);

const GanderDomesticDelegated = [
    [ '53.0833333333', '-54.0833333333' ],
    [ '49', '-51' ],
    [ '45', '-51' ],
    [ '45', '-53' ],
    [ '44.446666666666665', '-56.05166666666666' ],
    [ '43.446666666666665', '-56.05166666666666' ],
    [ '44', '-50' ],
    [ '50', '-50' ],
    [ '53.0833333333', '-54.0833333333' ]
];
L.polyline(GanderDomesticDelegated, { color: '#777', weight: 0.5 }).addTo(map);

const Moncton = [
    [ '44.446666666666665', '-56.05166666666666' ],
    [ '43.6', '-60' ],
    [ '41.86666666666667', '-67' ],
    [ '44.5', '-67' ],
    [ '44.5', '-67.11666666666666' ],
    [ '44.776666666666664', '-66.9025' ],
    [ '47.2875', '-68.57666666666667' ],
    [ '47.525277777777774', '-68' ],
    [ '47.733333333333334', '-67.95' ],
    [ '47.88333333333333', '-66.89666666666668' ],
    [ '48', '-65.94111111111111' ],
    [ '47.848333333333336', '-64.62222222222222' ],
    [ '48.5', '-62' ],
    [ '45.61194444444445', '-56.47361111111111' ],
    [ '44.446666666666665', '-56.05166666666666' ]
];
L.polyline(Moncton, { color: '#777', weight: 0.5 }).addTo(map);

const Montreal = [
    [ '47.459833333333336', '-69.22444444444444' ],
    [ '44.22141666666667', '-76.19172222222223' ],
    [ '45.837500000000006', '-76.26666666666667' ],
    [ '45.961111111111116', '-76.92777777777778' ],
    [ '46.13333333333333', '-77.25' ],
    [ '46.94688055555555', '-77.25' ],
    [ '47.11110277777778', '-77.54586388888889' ],
    [ '47.55425833333333', '-78.11756944444444' ],
    [ '47.84006388888889', '-78.56570555555555' ],
    [ '48.587047222222225', '-79' ],
    [ '49', '-79' ],
    [ '53.46666666666667', '-80' ],
    [ '62.75', '-80' ],
    [ '65', '-68' ],
    [ '65', '-60' ],
    [ '64', '-63' ],
    [ '61', '-63' ],
    [ '58.471111111111114', '-60.35111111111111' ],
    [ '57.55', '-64' ],
    [ '55.355555555555554', '-64' ],
    [ '55.083333333333336', '-65.08333333333333' ],
    [ '54.416666666666664', '-65.33333333333333' ],
    [ '53.7', '-64.91666666666667' ],
    [ '51.416666666666664', '-64' ],
    [ '50.833333333333336', '-62.083333333333336' ],
    [ '50.833333333333336', '-60' ],
    [ '51.333333333333336', '-59.5' ],
    [ '51.63333333333333', '-59.5' ],
    [ '52.19638888888888', '-58.14277777777778' ],
    [ '51.735', '-57' ],
    [ '51.28333333333333', '-57' ],
    [ '51', '-58' ],
    [ '49.53333333333333', '-61' ],
    [ '49.3', '-61' ],
    [ '48.5', '-62' ],
    [ '47.848333333333336', '-64.62222222222222' ],
    [ '48', '-65.94111111111111' ],
    [ '47.88333333333333', '-66.89666666666668' ]
];
L.polyline(Montreal, { color: '#777', weight: 0.5 }).addTo(map);

const Edmonton = [
    [ '65', '-57.75' ],
    [ '73', '-69.92' ],
    [ '73', '-80' ],
    [ '64.40833333333335', '-80' ],
    [ '62.75', '-80' ],
    [ '65', '-68' ],
    [ '65', '-60' ]

];
L.polyline(Edmonton, { color: '#777', weight: 0.5 }).addTo(map);

const Reykjavik = [
    [ '63.5', '-55.80928' ],
    [ '63.5', '-39' ],
    [ '61', '-30' ],
    [ '61', '0' ],
    [ '73', '0' ],
    [ '73', '-69.92' ],
    [ '63.5', '-55.80928' ]

];
L.polyline(Reykjavik, { color: '#777', weight: 0.5 }).addTo(map);

const Scottish = [
    [ '61', '0' ],
    [ '60', '0' ],
    [ '57', '5' ],
    [ '55', '5' ],
    [ '55', '-5.5' ],
    [ '53.916666666666664', '-5.5' ],
    [ '54.416666666666664', '-8.166666666666666' ],
    [ '55.333333333333336', '-6.916666666666667' ],
    [ '55.416666666666664', '-7.333333333333333' ],
    [ '55.333333333333336', '-8.25' ],
    [ '54.75', '-9' ],
    [ '54.56666666666667', '-10' ],
    [ '61', '-10' ],
    [ '61', '0' ]
];
L.polyline(Scottish, { color: '#777', weight: 0.5 }).addTo(map);

const London = [
    [ '55', '5' ],
    [ '51.5', '2' ],
    [ '51.11666666666667', '2' ],
    [ '51', '1.4666666666666668' ],
    [ '50.666666666666664', '1.4666666666666668' ],
    [ '50', '-0.25' ],
    [ '50', '-2' ],
    [ '48.833333333333336', '-8' ],
    [ '51', '-8' ],
    [ '52.333333333333336', '-5.5' ],
    [ '55', '-5.5' ],
    [ '55', '5' ]
];
L.polyline(London, { color: '#777', weight: 0.5 }).addTo(map);

const Brest = [
    [ '50', '-0.25' ],
    [ '46.5', '-0.25' ],
    [ '46.5', '-1.6333333333333333' ],
    [ '43.583333333333336', '-1.7833333333333332' ],
    [ '44.333333333333336', '-4' ],
    [ '45', '-8' ],
    [ '48.833333333333336', '-8' ],
    [ '50', '-2' ],
    [ '50', '-0.25' ]
];
L.polyline(Brest, { color: '#777', weight: 0.5 }).addTo(map);

const Madrid = [
    [ '45', '-13' ],
    [ '45', '-8' ],
    [ '44.333333333333336', '-4' ],
    [ '43.583333333333336', '-1.7833333333333332' ],
    [ '43.38333333333333', '-1.7833333333333332' ],
    [ '42.7', '-0.06666666666666667' ],
    [ '39.733333333333334', '-1.1' ],
    [ '35.833333333333336', '-2.1' ],
    [ '35.833333333333336', '-7.383333333333334' ],
    [ '35.96666666666667', '-7.383333333333334' ],
    [ '42', '-10' ],
    [ '43', '-13' ],
    [ '45', '-13' ]
];
L.polyline(Madrid, { color: '#777', weight: 0.5 }).addTo(map);

const Lisbon = [
    [ '43', '-13' ],
    [ '42', '-10' ],
    [ '35.96666666666667', '-7.383333333333334' ],
    [ '35.96666666666667', '-12' ],
    [ '32.25', '-14.633333333333333' ],
    [ '33.92500000', '-18.06916667' ],
    [ '36.5', '-15' ],
    [ '42', '-15' ],
    [ '43', '-13' ]
];
L.polyline(Lisbon, { color: '#777', weight: 0.5 }).addTo(map);

const SantaMaria = [
    [ '45', '-40' ],
    [ '45', '-13' ],
    [ '43', '-13' ],
    [ '42', '-15' ],
    [ '36.5', '-15' ],
    [ '33.92500000', '-18.06916667' ],
    [ '30', '-20' ],
    [ '30', '-25' ],
    [ '24', '-25' ],
    [ '17', '-37.5' ],
    [ '22.3', '-40' ],
    [ '44', '-40' ]
];
L.polyline(SantaMaria, { color: '#777', weight: 0.5 }).addTo(map);

const NewYork = [
    [ '44', '-40' ],
    [ '22.3', '-40' ],
    [ '18', '-45' ],
    [ '18', '-61.5' ],
    [ '38.331222', '-70.059528' ],
    [ '39', '-67' ],
    [ '42', '-67' ],
    [ '43.446666666666665', '-56.05166666666666' ],
    [ '44', '-50' ],
    [ '44', '-40' ]
];
L.polyline(NewYork, { color: '#777', weight: 0.5 }).addTo(map);
    //Create plane markers and controllers
    planes.forEach(function (plane) {
        let markerIcon = L.icon({
            iconUrl: '/img/planes/base.png',
            iconSize: [30, 30],
            iconAnchor: [2,4]
        });
       var marker = L.marker([plane.latitude, plane.longitude], {rotationAngle: plane.heading, icon:markerIcon}).addTo(map);
       marker.bindPopup(`<h4>${plane.callsign}</h4><br>${plane.realname} ${plane.cid}<br>${plane.planned_depairport} to ${plane.planned_destairport}<br>${plane.planned_aircraft}`)
    });
    if(ganderControllers.length > 0) {
        console.log('test');
        var ganderOca = L.polygon([
            [45.0, -30],
            [45.0, -40],
            [45,-51],
            [49,-51],
            [52.39, -53.44],
            [53, -54],
            [57,-59],
            [58.28,-60.21],
            [64,-63],
            [65, -60],
            [65,-57.45],
            [63.3,-55.5],
            [58.3,-50],
            [58.3, -43],
            [63.3, -39],
            [61,-30]
        ]).addTo(map);
        ganderOca.bindPopup('<h3>Gander OCA online</h3>')
    }
    if (shanwickControllers.length > 0) {
        var shanwickOca = L.polygon([
            [61.0, -30],
            [61.0, -10],
            [57.0, -10],
            [57.0, -15],
            [49.0,-15],
            [48.49, -8],
            [45.0, -8],
            [45.0, -30]
        ]).addTo(map);
        shanwickOca.bindPopup('<h3>Shanwick OCA online</h3>')
    }

    //Get tracks
    let api = "https://api.flightplandatabase.com/nav/NATS";
    let xmlHttp = new XMLHttpRequest();
    xmlHttp.open("GET", api, false);
    xmlHttp.send(null);
    let apiString = xmlHttp.responseText;
    let apiJson = JSON.parse(apiString);
    console.log(apiJson);

    //Go through all the tracks
    for (track in apiJson) {
        //Go through the tracks and only use the good ones...
        if (checkIfNatProcessed(apiJson[track].ident) == false) {
            processedNats.push(apiJson[track].ident);
            //Create some markers
            let fixArray = [];
            for (n in apiJson[track].route.nodes) {/*
                if (apiJson[track].route.eastLevels.length == 0) {
                    createMarker(apiJson[track].route.nodes[n], apiJson[track].ident, 'orange');
                }
                else
                {
                    createMarker(apiJson[track].route.nodes[n], apiJson[track].ident, 'blue');
                } */
                fixArray.push([apiJson[track].route.nodes[n].lat, apiJson[track].route.nodes[n].lon]);
            }
            let polyline = new L.Polyline(fixArray, {
                color: '#616161 ',
                weight: 2,
                opacity: 1,
                smoothFactor: 1
            });
            if (apiJson[track].route.eastLevels.length == 0) {
                polyline.setStyle({
                    color: '#757575 '
                });
            }
            polyline.addTo(map);
        };
    }

    let table = document.getElementById('natTrackTable');
    processedNats = [];
    for (track in apiJson) {
        if (checkIfNatProcessed(apiJson[track].ident) == false) {
            processedNats.push(apiJson[track].ident);
            //Create a row
            let row = document.createElement('tr');
            table.appendChild(row);

            //Get the track ID
            let identCol = document.createElement('th');
            identCol.scope = 'row';
            identCol.innerHTML = apiJson[track].ident;
            row.appendChild(identCol);

            //Get the fixes
            let fixArray = [];
            for (n in apiJson[track].route.nodes) {
                fixArray.push(" " + apiJson[track].route.nodes[n].ident);
            }
            let fixesCol = document.createElement('td');
            fixesCol.innerHTML = fixArray;
            row.appendChild(fixesCol);

            //figure out the direction and get levels
            let levelArray = [];
            let directionCol = document.createElement('td');
            let levelsCol = document.createElement('td');
            if (apiJson[track].route.eastLevels.length == 0) {
                apiJson[track].route.westLevels.forEach(function (element) {
                    levelArray.push(" " + element);
                });
                directionCol.innerHTML = "West";
            } else {
                apiJson[track].route.eastLevels.forEach(function (element) {
                    levelArray.push(" " + element);
                });
                directionCol.innerHTML = "East";
            }
            levelsCol.innerHTML = levelArray;
            row.appendChild(directionCol);
            row.appendChild(levelsCol);

            //validity
            let validityCol = document.createElement('td');
            let validFrom = " " + apiJson[track].validFrom;
            let validTo = apiJson[track].validTo;
            validityCol.innerHTML = validFrom + " to " + validTo;
            row.appendChild(validityCol);
        };
    }
}


function createNatTrackMap()
{
    const map = L.map('map', { minZoom: 4, maxZoom: 7 }).setView([52, -35], 1);
    const icon = L.icon({ iconUrl: '/img/oep.png', iconAnchor: [5, 5] });

    var OpenStreetMap_Mapnik = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    //Get tracks
    let api = "https://api.flightplandatabase.com/nav/NATS";
    let xmlHttp = new XMLHttpRequest();
    xmlHttp.open("GET", api, false);
    xmlHttp.send(null);
    let apiString = xmlHttp.responseText;
    let apiJson = JSON.parse(apiString);
    console.log(apiJson);

    //Go through all the tracks
    for (track in apiJson) {
        //Go through the tracks and only use the good ones...
        if (checkIfNatProcessed(apiJson[track].ident) == false) {
            processedNats.push(apiJson[track].ident);
            //Create some markers
            let fixArray = [];
            for (n in apiJson[track].route.nodes) {
                if (apiJson[track].route.eastLevels.length == 0) {
                    createMarker(apiJson[track].route.nodes[n], apiJson[track].ident, 'orange', map);
                }
                else
                {
                    createMarker(apiJson[track].route.nodes[n], apiJson[track].ident, 'blue', map);
                }
                fixArray.push([apiJson[track].route.nodes[n].lat, apiJson[track].route.nodes[n].lon]);
            }
            let polyline = new L.Polyline(fixArray,{
                color: '#1c5fc9',
                weight: 2,
                opacity: 1,
                smoothFactor: 1
            });
            if (apiJson[track].route.eastLevels.length == 0) {
                polyline.setStyle({
                    color: '#c92d1c'
                });
            }
            polyline.addTo(map);
        };
    }

    let table = document.getElementById('natTrackTable');
    processedNats = [];
    for (track in apiJson) {
        if (checkIfNatProcessed(apiJson[track].ident) == false) {
            processedNats.push(apiJson[track].ident);
            //Create a row
            let row = document.createElement('tr');
            table.appendChild(row);

            //Get the track ID
            let identCol = document.createElement('th');
            identCol.scope = 'row';
            identCol.innerHTML = apiJson[track].ident;
            row.appendChild(identCol);

            //Get the fixes
            let fixArray = [];
            for (n in apiJson[track].route.nodes) {
                fixArray.push(" " + apiJson[track].route.nodes[n].ident);
            }
            let fixesCol = document.createElement('td');
            fixesCol.innerHTML = fixArray;
            row.appendChild(fixesCol);

            //figure out the direction and get levels
            let levelArray = [];
            let directionCol = document.createElement('td');
            let levelsCol = document.createElement('td');
            if (apiJson[track].route.eastLevels.length == 0) {
                apiJson[track].route.westLevels.forEach(function (element) {
                    levelArray.push(" " + element);
                });
                directionCol.innerHTML = "West";
            } else {
                apiJson[track].route.eastLevels.forEach(function (element) {
                    levelArray.push(" " + element);
                });
                directionCol.innerHTML = "East";
            }
            levelsCol.innerHTML = levelArray;
            row.appendChild(directionCol);
            row.appendChild(levelsCol);

            //validity
            let validityCol = document.createElement('td');
            let validFrom = " " + apiJson[track].validFrom;
            let validTo = apiJson[track].validTo;
            validityCol.innerHTML = validFrom + " to " + validTo;
            row.appendChild(validityCol);
        };
    }

    L.latlngGraticule({
        showLabel: true,
        dashArray: [5, 5],
        zoomInterval: [ { start: 0, end: 10, interval: 5 } ]
    }).addTo(map);

    // OCA's, FIR's and delegated areas

    const Gander = [
        [ '45', '-51' ],
        [ '45', '-50' ],
        [ '44', '-50' ],
        [ '44', '-40' ],
        [ '45', '-40' ],
        [ '45', '-30' ],
        [ '61', '-30' ],
        [ '63.5', '-39' ],
        [ '58.5', '-43' ],
        [ '58.5', '-50' ],
        [ '65', '-57.75' ],
        [ '65', '-60' ],
        [ '64', '-63' ],
        [ '61', '-63' ],
        [ '58.471111111111114', '-60.35111111111111' ],
        [ '57', '-59' ],
        [ '53', '-54' ],
        [ '49', '-51' ],
        [ '45', '-51' ]
    ];
    L.polyline(Gander, { color: '#777', weight: 0.5 }).addTo(map);

    const Shanwick = [
        [ '45', '-30' ],
        [ '45', '-8' ],
        [ '51', '-8' ],
        [ '51', '-15' ],
        [ '54', '-15' ],
        [ '54.56666666666667', '-10' ],
        [ '61', '-10' ],
        [ '61', '-30' ],
        [ '45', '-30' ]
    ];
    L.polyline(Shanwick, { color: '#777', weight: 0.5 }).addTo(map);

    const NOTA = [
        [ '54', '-15' ],
        [ '54.56666666666667', '-10' ],
        [ '57', '-10' ],
        [ '57', '-15' ],
        [ '54', '-15' ]
    ];
    L.polyline(NOTA, { color: '#777', weight: 0.5 }).addTo(map);

    const SOTA = [
        [ '49', '-15' ],
        [ '48.5769444444', '-8.75' ],
        [ '48.5769444444', '-8' ],
        [ '51', '-8' ],
        [ '51', '-15' ],
        [ '49', '-15' ]
    ];
    L.polyline(SOTA, { color: '#777', weight: 0.5 }).addTo(map);

    const BOTA = [
        [ '45', '-8.75' ],
        [ '45', '-8' ],
        [ '48.5769444444', '-8' ],
        [ '48.5769444444', '-8.75' ],
        [ '45', '-8.75' ]
    ];
    L.polyline(BOTA, { color: '#777', weight: 0.5 }).addTo(map);

    const GOTA = [
        [ '53.8', '-55' ],
        [ '62.85', '-55' ],
        [ '65', '-57.75' ],
        [ '65', '-60' ],
        [ '64', '-63' ],
        [ '61', '-63' ],
        [ '57', '-59' ],
        [ '53.8', '-55' ]
    ];
    L.polyline(GOTA, { color: '#777', weight: 0.5 }).addTo(map);

    const Nuuk = [
        [ '58.5', '-50' ],
        [ '58.5', '-43' ],
        [ '63.5', '-39' ],
        [ '63.5', '-55.80928' ],
        [ '58.5', '-50' ]
    ];
    L.polyline(Nuuk, { color: '#777', weight: 0.5 }).addTo(map);

    const GanderDomestic = [
        [ '45', '-51' ],
        [ '45', '-53' ],
        [ '44.446666666666665', '-56.05166666666666' ],
        [ '45.61194444444445', '-56.47361111111111' ],
        [ '48.5', '-62' ],
        [ '49.3', '-61' ],
        [ '49.53333333333333', '-61' ],
        [ '51', '-58' ],
        [ '51.28333333333333', '-57' ],
        [ '51.735', '-57' ],
        [ '52.19638888888888', '-58.14277777777778' ],
        [ '51.63333333333333', '-59.5' ],
        [ '51.333333333333336', '-59.5' ],
        [ '50.833333333333336', '-60' ],
        [ '50.833333333333336', '-62.083333333333336' ],
        [ '51.416666666666664', '-64' ],
        [ '53.7', '-64.91666666666667' ],
        [ '54.416666666666664', '-65.33333333333333' ],
        [ '55.083333333333336', '-65.08333333333333' ],
        [ '55.355555555555554', '-64' ],
        [ '57.55', '-64' ],
        [ '58.471111111111114', '-60.35111111111111' ],
        [ '57', '-59' ],
        [ '53', '-54' ],
        [ '49', '-51' ],
        [ '45', '-51' ]
    ];
    L.polyline(GanderDomestic, { color: '#777', weight: 0.5 }).addTo(map);

    const GanderDomesticDelegated = [
        [ '53.0833333333', '-54.0833333333' ],
        [ '49', '-51' ],
        [ '45', '-51' ],
        [ '45', '-53' ],
        [ '44.446666666666665', '-56.05166666666666' ],
        [ '43.446666666666665', '-56.05166666666666' ],
        [ '44', '-50' ],
        [ '50', '-50' ],
        [ '53.0833333333', '-54.0833333333' ]
    ];
    L.polyline(GanderDomesticDelegated, { color: '#777', weight: 0.5 }).addTo(map);

    const Moncton = [
        [ '44.446666666666665', '-56.05166666666666' ],
        [ '43.6', '-60' ],
        [ '41.86666666666667', '-67' ],
        [ '44.5', '-67' ],
        [ '44.5', '-67.11666666666666' ],
        [ '44.776666666666664', '-66.9025' ],
        [ '47.2875', '-68.57666666666667' ],
        [ '47.525277777777774', '-68' ],
        [ '47.733333333333334', '-67.95' ],
        [ '47.88333333333333', '-66.89666666666668' ],
        [ '48', '-65.94111111111111' ],
        [ '47.848333333333336', '-64.62222222222222' ],
        [ '48.5', '-62' ],
        [ '45.61194444444445', '-56.47361111111111' ],
        [ '44.446666666666665', '-56.05166666666666' ]
    ];
    L.polyline(Moncton, { color: '#777', weight: 0.5 }).addTo(map);

    const Montreal = [
        [ '47.459833333333336', '-69.22444444444444' ],
        [ '44.22141666666667', '-76.19172222222223' ],
        [ '45.837500000000006', '-76.26666666666667' ],
        [ '45.961111111111116', '-76.92777777777778' ],
        [ '46.13333333333333', '-77.25' ],
        [ '46.94688055555555', '-77.25' ],
        [ '47.11110277777778', '-77.54586388888889' ],
        [ '47.55425833333333', '-78.11756944444444' ],
        [ '47.84006388888889', '-78.56570555555555' ],
        [ '48.587047222222225', '-79' ],
        [ '49', '-79' ],
        [ '53.46666666666667', '-80' ],
        [ '62.75', '-80' ],
        [ '65', '-68' ],
        [ '65', '-60' ],
        [ '64', '-63' ],
        [ '61', '-63' ],
        [ '58.471111111111114', '-60.35111111111111' ],
        [ '57.55', '-64' ],
        [ '55.355555555555554', '-64' ],
        [ '55.083333333333336', '-65.08333333333333' ],
        [ '54.416666666666664', '-65.33333333333333' ],
        [ '53.7', '-64.91666666666667' ],
        [ '51.416666666666664', '-64' ],
        [ '50.833333333333336', '-62.083333333333336' ],
        [ '50.833333333333336', '-60' ],
        [ '51.333333333333336', '-59.5' ],
        [ '51.63333333333333', '-59.5' ],
        [ '52.19638888888888', '-58.14277777777778' ],
        [ '51.735', '-57' ],
        [ '51.28333333333333', '-57' ],
        [ '51', '-58' ],
        [ '49.53333333333333', '-61' ],
        [ '49.3', '-61' ],
        [ '48.5', '-62' ],
        [ '47.848333333333336', '-64.62222222222222' ],
        [ '48', '-65.94111111111111' ],
        [ '47.88333333333333', '-66.89666666666668' ]
    ];
    L.polyline(Montreal, { color: '#777', weight: 0.5 }).addTo(map);

    const Edmonton = [
        [ '65', '-57.75' ],
        [ '73', '-69.92' ],
        [ '73', '-80' ],
        [ '64.40833333333335', '-80' ],
        [ '62.75', '-80' ],
        [ '65', '-68' ],
        [ '65', '-60' ]

    ];
    L.polyline(Edmonton, { color: '#777', weight: 0.5 }).addTo(map);

    const Reykjavik = [
        [ '63.5', '-55.80928' ],
        [ '63.5', '-39' ],
        [ '61', '-30' ],
        [ '61', '0' ],
        [ '73', '0' ],
        [ '73', '-69.92' ],
        [ '63.5', '-55.80928' ]

    ];
    L.polyline(Reykjavik, { color: '#777', weight: 0.5 }).addTo(map);

    const Scottish = [
        [ '61', '0' ],
        [ '60', '0' ],
        [ '57', '5' ],
        [ '55', '5' ],
        [ '55', '-5.5' ],
        [ '53.916666666666664', '-5.5' ],
        [ '54.416666666666664', '-8.166666666666666' ],
        [ '55.333333333333336', '-6.916666666666667' ],
        [ '55.416666666666664', '-7.333333333333333' ],
        [ '55.333333333333336', '-8.25' ],
        [ '54.75', '-9' ],
        [ '54.56666666666667', '-10' ],
        [ '61', '-10' ],
        [ '61', '0' ]
    ];
    L.polyline(Scottish, { color: '#777', weight: 0.5 }).addTo(map);

    const London = [
        [ '55', '5' ],
        [ '51.5', '2' ],
        [ '51.11666666666667', '2' ],
        [ '51', '1.4666666666666668' ],
        [ '50.666666666666664', '1.4666666666666668' ],
        [ '50', '-0.25' ],
        [ '50', '-2' ],
        [ '48.833333333333336', '-8' ],
        [ '51', '-8' ],
        [ '52.333333333333336', '-5.5' ],
        [ '55', '-5.5' ],
        [ '55', '5' ]
    ];
    L.polyline(London, { color: '#777', weight: 0.5 }).addTo(map);

    const Brest = [
        [ '50', '-0.25' ],
        [ '46.5', '-0.25' ],
        [ '46.5', '-1.6333333333333333' ],
        [ '43.583333333333336', '-1.7833333333333332' ],
        [ '44.333333333333336', '-4' ],
        [ '45', '-8' ],
        [ '48.833333333333336', '-8' ],
        [ '50', '-2' ],
        [ '50', '-0.25' ]
    ];
    L.polyline(Brest, { color: '#777', weight: 0.5 }).addTo(map);

    const Madrid = [
        [ '45', '-13' ],
        [ '45', '-8' ],
        [ '44.333333333333336', '-4' ],
        [ '43.583333333333336', '-1.7833333333333332' ],
        [ '43.38333333333333', '-1.7833333333333332' ],
        [ '42.7', '-0.06666666666666667' ],
        [ '39.733333333333334', '-1.1' ],
        [ '35.833333333333336', '-2.1' ],
        [ '35.833333333333336', '-7.383333333333334' ],
        [ '35.96666666666667', '-7.383333333333334' ],
        [ '42', '-10' ],
        [ '43', '-13' ],
        [ '45', '-13' ]
    ];
    L.polyline(Madrid, { color: '#777', weight: 0.5 }).addTo(map);

    const Lisbon = [
        [ '43', '-13' ],
        [ '42', '-10' ],
        [ '35.96666666666667', '-7.383333333333334' ],
        [ '35.96666666666667', '-12' ],
        [ '32.25', '-14.633333333333333' ],
        [ '33.92500000', '-18.06916667' ],
        [ '36.5', '-15' ],
        [ '42', '-15' ],
        [ '43', '-13' ]
    ];
    L.polyline(Lisbon, { color: '#777', weight: 0.5 }).addTo(map);

    const SantaMaria = [
        [ '45', '-40' ],
        [ '45', '-13' ],
        [ '43', '-13' ],
        [ '42', '-15' ],
        [ '36.5', '-15' ],
        [ '33.92500000', '-18.06916667' ],
        [ '30', '-20' ],
        [ '30', '-25' ],
        [ '24', '-25' ],
        [ '17', '-37.5' ],
        [ '22.3', '-40' ],
        [ '44', '-40' ]
    ];
    L.polyline(SantaMaria, { color: '#777', weight: 0.5 }).addTo(map);

    const NewYork = [
        [ '44', '-40' ],
        [ '22.3', '-40' ],
        [ '18', '-45' ],
        [ '18', '-61.5' ],
        [ '38.331222', '-70.059528' ],
        [ '39', '-67' ],
        [ '42', '-67' ],
        [ '43.446666666666665', '-56.05166666666666' ],
        [ '44', '-50' ],
        [ '44', '-40' ]
    ];
    L.polyline(NewYork, { color: '#777', weight: 0.5 }).addTo(map);
}


function checkIfNatProcessed(ident) {
    if (processedNats.indexOf(ident) > -1) {
        return true;
    } else {
        return false;
    }
}


function createMarker(node, trackId, colour, map) {
    let markerIcon = L.icon({
        iconUrl: 'https://nesa.com.au/wp-content/uploads/2017/05/Dot-points-1.png',
        iconSize: [10, 10],
        iconAnchor: [2, 4]
    });
    let marker = L.marker([node.lat, node.lon], {icon: markerIcon}).addTo(map);
    marker.bindPopup("<b>"+node.ident+"</b><br/>"+node.type+"<br/>"+node.lat+" "+node.lon+"<br>Track "+trackId);
}

function createAboutPageMap() {
    const map = L.map('aboutPageMap').setView([55, -30], 3.48);
    const icon = L.icon({ iconUrl: '/img/oep.png', iconAnchor: [5, 5] });

    var OpenStreetMap_Mapnik = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    // Gander OEP's

    const pointsGander = [
        ['AVPUT', [ '65.03333333333333', '-60' ]],
        ['CLAVY', [ '64.23333333333333', '-59' ]],
        ['EMBOK', [ '63.46666666666667', '-58' ]],
        ['KETLA', [ '62.46666666666667', '-58' ]],
        ['LIBOR', [ '61.96666666666667', '-58' ]],
        ['MAXAR', [ '61.46666666666667', '-58' ]],
        ['NIFTY', [ '60.96666666666667', '-58' ]],
        ['PIDSO', [ '60.46666666666667', '-58' ]],
        ['RADUN', [ '59.96666666666667', '-58' ]],
        ['SAVRY', [ '59.46666666666667', '-58' ]],
        ['TOXIT', [ '58.96666666666667', '-58' ]],
        ['URTAK', [ '58.46666666666667', '-58' ]],
        ['VESMI', [ '57.96666666666667', '-58' ]],
        ['AVUTI', [ '57.46666666666667', '-58' ]],
        ['BOKTO', [ '56.96666666666667', '-58' ]],
        ['CUDDY', [ '56.7', '-57' ]],
        ['DORYY', [ '56.03333333333333', '-57' ]],
        ['ENNSO', [ '55.53333333333333', '-57' ]],
        ['HOIST', [ '55.03333333333333', '-57' ]],
        ['IRLOK', [ '54.53333333333333', '-57' ]],
        ['JANJO', [ '54.03333333333333', '-57' ]],
        ['KODIK', [ '53.46666666666667', '-57.2' ]],
        ['LOMSI', [ '53.1', '-56.78333333333333' ]],
        ['MELDI', [ '52.733333333333334', '-56.35' ]],
        ['NEEKO', [ '52.4', '-55.833333333333336' ]],
        ['PELTU', [ '52.1', '-55.166666666666664' ]],
        ['RIKAL', [ '51.8', '-54.53333333333333' ]],
        ['SAXAN', [ '51.483333333333334', '-53.85' ]],
        ['TUDEP', [ '51.166666666666664', '-53.233333333333334' ]],
        ['UMESI', [ '50.833333333333336', '-52.6' ]],
        ['ALLRY', [ '50.5', '-52' ]],
        ['BUDAR', [ '50', '-52' ]],
        ['ELSIR', [ '49.5', '-52' ]],
        ['IBERG', [ '49', '-52' ]],
        ['JOOPY', [ '48.5', '-52' ]],
        ['MUSAK', [ '48', '-52' ]],
        ['NICSO', [ '47.5', '-52' ]],
        ['OMSAT', [ '47', '-52' ]],
        ['PORTI', [ '46.5', '-52' ]],
        ['RELIC', [ '46', '-52' ]],
        ['SUPRY', [ '45.5', '-52' ]],
        ['RAFIN', [ '44.88333333333333', '-51.80472222222222' ]],
        ['JAROM', [ '44.166666666666664', '-54.88333333333333' ]],
        ['BOBTU', [ '44.117222222222225', '-52.82222222222222' ]]
    ];

    pointsGander.forEach(point => {
        L.marker([parseFloat(point[1][0]), parseFloat(point[1][1])], {icon: icon, opacity: 0.3}).addTo(map).bindPopup(point[0]);
    });

    // Shanwick OEP's

    const pointsShanwick = [
        ['RATSU', [ '61', '-10' ]],
        ['LUSEN', [ '60.5', '-10' ]],
        ['ATSIX', [ '60', '-10' ]],
        ['ORTAV', [ '59.5', '-10' ]],
        ['BALIX', [ '59', '-10' ]],
        ['ADODO', [ '58.5', '-10' ]],
        ['ERAKA', [ '58', '-10' ]],
        ['ETILO', [ '57.5', '-10' ]],
        ['GOMUP', [ '57', '-10' ]],
        ['AGORI', [ '57', '-13' ]],
        ['SUNOT', [ '57', '-15' ]],
        ['BILTO', [ '56.5', '-15' ]],
        ['PIKIL', [ '56', '-15' ]],
        ['ETARI', [ '55.5', '-15' ]],
        ['RESNO', [ '55', '-15' ]],
        ['VENER', [ '54.5', '-15' ]],
        ['DOGAL', [ '54', '-15' ]],
        ['NEBIN', [ '53.5', '-15' ]],
        ['MALOT', [ '53', '-15' ]],
        ['TOBOR', [ '52.5', '-15' ]],
        ['LIMRI', [ '52', '-15' ]],
        ['ADARA', [ '51.5', '-15' ]],
        ['DINIM', [ '51', '-15' ]],
        ['RODEL', [ '50.5', '-15' ]],
        ['SOMAX', [ '50', '-15' ]],
        ['KOGAD', [ '49.5', '-15' ]],
        ['BEDRA', [ '49', '-15' ]],
        ['NERTU', [ '49', '-14' ]],
        ['NASBA', [ '49', '-13' ]],
        ['OMOKO', [ '48.83888888888889', '-12' ]],
        ['TAMEL', [ '48.728611111111114', '-10.497222222222222' ]],
        ['GELPO', [ '48.64416666666666', '-9.5025' ]],
        ['LASNO', [ '48.598333333333336', '-9' ]],
        ['ETIKI', [ '48', '-8.75' ]],
        ['UMLER', [ '47.5', '-8.75' ]],
        ['SEPAL', [ '47', '-8.75' ]],
        ['BUNAV', [ '46.5', '-8.75' ]],
        ['SIVIR', [ '46', '-8.75' ]],
        ['BEGAS', [ '45', '-9' ]],
        ['DIVAT', [ '45', '-9.469722222222222' ]],
        ['DIXIS', [ '45', '-10' ]],
        ['BERUX', [ '45', '-11' ]],
        ['PITAX', [ '45', '-12' ]],
        ['PASAS', [ '45', '-13' ]],
        ['NILAV', [ '45', '-13.416666666666666' ]],
        ['GONAN', [ '45', '-14' ]]
    ];

    pointsShanwick.forEach(point => {
        L.marker([parseFloat(point[1][0]), parseFloat(point[1][1])], {icon: icon, opacity: 0.3}).addTo(map).bindPopup(point[0], {permanent:true});
    });

    // Coordinate grid

    L.latlngGraticule({
        showLabel: true,
        dashArray: [5, 5],
        zoomInterval: [ { start: 0, end: 10, interval: 5 } ]
    }).addTo(map);

    // OCA's, FIR's and delegated areas

    const Gander = [
        [ '45', '-51' ],
        [ '45', '-50' ],
        [ '44', '-50' ],
        [ '44', '-40' ],
        [ '45', '-40' ],
        [ '45', '-30' ],
        [ '61', '-30' ],
        [ '63.5', '-39' ],
        [ '58.5', '-43' ],
        [ '58.5', '-50' ],
        [ '65', '-57.75' ],
        [ '65', '-60' ],
        [ '64', '-63' ],
        [ '61', '-63' ],
        [ '58.471111111111114', '-60.35111111111111' ],
        [ '57', '-59' ],
        [ '53', '-54' ],
        [ '49', '-51' ],
        [ '45', '-51' ]
    ];
    L.polyline(Gander, { color: '#777', weight: 0.5 }).addTo(map);

    const Shanwick = [
        [ '45', '-30' ],
        [ '45', '-8' ],
        [ '51', '-8' ],
        [ '51', '-15' ],
        [ '54', '-15' ],
        [ '54.56666666666667', '-10' ],
        [ '61', '-10' ],
        [ '61', '-30' ],
        [ '45', '-30' ]
    ];
    L.polyline(Shanwick, { color: '#777', weight: 0.5 }).addTo(map);

    const NOTA = [
        [ '54', '-15' ],
        [ '54.56666666666667', '-10' ],
        [ '57', '-10' ],
        [ '57', '-15' ],
        [ '54', '-15' ]
    ];
    L.polyline(NOTA, { color: '#777', weight: 0.5 }).addTo(map);

    const SOTA = [
        [ '49', '-15' ],
        [ '48.5769444444', '-8.75' ],
        [ '48.5769444444', '-8' ],
        [ '51', '-8' ],
        [ '51', '-15' ],
        [ '49', '-15' ]
    ];
    L.polyline(SOTA, { color: '#777', weight: 0.5 }).addTo(map);

    const BOTA = [
        [ '45', '-8.75' ],
        [ '45', '-8' ],
        [ '48.5769444444', '-8' ],
        [ '48.5769444444', '-8.75' ],
        [ '45', '-8.75' ]
    ];
    L.polyline(BOTA, { color: '#777', weight: 0.5 }).addTo(map);

    const GOTA = [
        [ '53.8', '-55' ],
        [ '62.85', '-55' ],
        [ '65', '-57.75' ],
        [ '65', '-60' ],
        [ '64', '-63' ],
        [ '61', '-63' ],
        [ '57', '-59' ],
        [ '53.8', '-55' ]
    ];
    L.polyline(GOTA, { color: '#777', weight: 0.5 }).addTo(map);

    const Nuuk = [
        [ '58.5', '-50' ],
        [ '58.5', '-43' ],
        [ '63.5', '-39' ],
        [ '63.5', '-55.80928' ],
        [ '58.5', '-50' ]
    ];
    L.polyline(Nuuk, { color: '#777', weight: 0.5 }).addTo(map);

    const GanderDomestic = [
        [ '45', '-51' ],
        [ '45', '-53' ],
        [ '44.446666666666665', '-56.05166666666666' ],
        [ '45.61194444444445', '-56.47361111111111' ],
        [ '48.5', '-62' ],
        [ '49.3', '-61' ],
        [ '49.53333333333333', '-61' ],
        [ '51', '-58' ],
        [ '51.28333333333333', '-57' ],
        [ '51.735', '-57' ],
        [ '52.19638888888888', '-58.14277777777778' ],
        [ '51.63333333333333', '-59.5' ],
        [ '51.333333333333336', '-59.5' ],
        [ '50.833333333333336', '-60' ],
        [ '50.833333333333336', '-62.083333333333336' ],
        [ '51.416666666666664', '-64' ],
        [ '53.7', '-64.91666666666667' ],
        [ '54.416666666666664', '-65.33333333333333' ],
        [ '55.083333333333336', '-65.08333333333333' ],
        [ '55.355555555555554', '-64' ],
        [ '57.55', '-64' ],
        [ '58.471111111111114', '-60.35111111111111' ],
        [ '57', '-59' ],
        [ '53', '-54' ],
        [ '49', '-51' ],
        [ '45', '-51' ]
    ];
    L.polyline(GanderDomestic, { color: '#777', weight: 0.5 }).addTo(map);

    const GanderDomesticDelegated = [
        [ '53.0833333333', '-54.0833333333' ],
        [ '49', '-51' ],
        [ '45', '-51' ],
        [ '45', '-53' ],
        [ '44.446666666666665', '-56.05166666666666' ],
        [ '43.446666666666665', '-56.05166666666666' ],
        [ '44', '-50' ],
        [ '50', '-50' ],
        [ '53.0833333333', '-54.0833333333' ]
    ];
    L.polyline(GanderDomesticDelegated, { color: '#777', weight: 0.5 }).addTo(map);

    const Moncton = [
        [ '44.446666666666665', '-56.05166666666666' ],
        [ '43.6', '-60' ],
        [ '41.86666666666667', '-67' ],
        [ '44.5', '-67' ],
        [ '44.5', '-67.11666666666666' ],
        [ '44.776666666666664', '-66.9025' ],
        [ '47.2875', '-68.57666666666667' ],
        [ '47.525277777777774', '-68' ],
        [ '47.733333333333334', '-67.95' ],
        [ '47.88333333333333', '-66.89666666666668' ],
        [ '48', '-65.94111111111111' ],
        [ '47.848333333333336', '-64.62222222222222' ],
        [ '48.5', '-62' ],
        [ '45.61194444444445', '-56.47361111111111' ],
        [ '44.446666666666665', '-56.05166666666666' ]
    ];
    L.polyline(Moncton, { color: '#777', weight: 0.5 }).addTo(map);

    const Montreal = [
        [ '47.459833333333336', '-69.22444444444444' ],
        [ '44.22141666666667', '-76.19172222222223' ],
        [ '45.837500000000006', '-76.26666666666667' ],
        [ '45.961111111111116', '-76.92777777777778' ],
        [ '46.13333333333333', '-77.25' ],
        [ '46.94688055555555', '-77.25' ],
        [ '47.11110277777778', '-77.54586388888889' ],
        [ '47.55425833333333', '-78.11756944444444' ],
        [ '47.84006388888889', '-78.56570555555555' ],
        [ '48.587047222222225', '-79' ],
        [ '49', '-79' ],
        [ '53.46666666666667', '-80' ],
        [ '62.75', '-80' ],
        [ '65', '-68' ],
        [ '65', '-60' ],
        [ '64', '-63' ],
        [ '61', '-63' ],
        [ '58.471111111111114', '-60.35111111111111' ],
        [ '57.55', '-64' ],
        [ '55.355555555555554', '-64' ],
        [ '55.083333333333336', '-65.08333333333333' ],
        [ '54.416666666666664', '-65.33333333333333' ],
        [ '53.7', '-64.91666666666667' ],
        [ '51.416666666666664', '-64' ],
        [ '50.833333333333336', '-62.083333333333336' ],
        [ '50.833333333333336', '-60' ],
        [ '51.333333333333336', '-59.5' ],
        [ '51.63333333333333', '-59.5' ],
        [ '52.19638888888888', '-58.14277777777778' ],
        [ '51.735', '-57' ],
        [ '51.28333333333333', '-57' ],
        [ '51', '-58' ],
        [ '49.53333333333333', '-61' ],
        [ '49.3', '-61' ],
        [ '48.5', '-62' ],
        [ '47.848333333333336', '-64.62222222222222' ],
        [ '48', '-65.94111111111111' ],
        [ '47.88333333333333', '-66.89666666666668' ]
    ];
    L.polyline(Montreal, { color: '#777', weight: 0.5 }).addTo(map);

    const Edmonton = [
        [ '65', '-57.75' ],
        [ '73', '-69.92' ],
        [ '73', '-80' ],
        [ '64.40833333333335', '-80' ],
        [ '62.75', '-80' ],
        [ '65', '-68' ],
        [ '65', '-60' ]

    ];
    L.polyline(Edmonton, { color: '#777', weight: 0.5 }).addTo(map);

    const Reykjavik = [
        [ '63.5', '-55.80928' ],
        [ '63.5', '-39' ],
        [ '61', '-30' ],
        [ '61', '0' ],
        [ '73', '0' ],
        [ '73', '-69.92' ],
        [ '63.5', '-55.80928' ]

    ];
    L.polyline(Reykjavik, { color: '#777', weight: 0.5 }).addTo(map);

    const Scottish = [
        [ '61', '0' ],
        [ '60', '0' ],
        [ '57', '5' ],
        [ '55', '5' ],
        [ '55', '-5.5' ],
        [ '53.916666666666664', '-5.5' ],
        [ '54.416666666666664', '-8.166666666666666' ],
        [ '55.333333333333336', '-6.916666666666667' ],
        [ '55.416666666666664', '-7.333333333333333' ],
        [ '55.333333333333336', '-8.25' ],
        [ '54.75', '-9' ],
        [ '54.56666666666667', '-10' ],
        [ '61', '-10' ],
        [ '61', '0' ]
    ];
    L.polyline(Scottish, { color: '#777', weight: 0.5 }).addTo(map);

    const London = [
        [ '55', '5' ],
        [ '51.5', '2' ],
        [ '51.11666666666667', '2' ],
        [ '51', '1.4666666666666668' ],
        [ '50.666666666666664', '1.4666666666666668' ],
        [ '50', '-0.25' ],
        [ '50', '-2' ],
        [ '48.833333333333336', '-8' ],
        [ '51', '-8' ],
        [ '52.333333333333336', '-5.5' ],
        [ '55', '-5.5' ],
        [ '55', '5' ]
    ];
    L.polyline(London, { color: '#777', weight: 0.5 }).addTo(map);

    const Brest = [
        [ '50', '-0.25' ],
        [ '46.5', '-0.25' ],
        [ '46.5', '-1.6333333333333333' ],
        [ '43.583333333333336', '-1.7833333333333332' ],
        [ '44.333333333333336', '-4' ],
        [ '45', '-8' ],
        [ '48.833333333333336', '-8' ],
        [ '50', '-2' ],
        [ '50', '-0.25' ]
    ];
    L.polyline(Brest, { color: '#777', weight: 0.5 }).addTo(map);

    const Madrid = [
        [ '45', '-13' ],
        [ '45', '-8' ],
        [ '44.333333333333336', '-4' ],
        [ '43.583333333333336', '-1.7833333333333332' ],
        [ '43.38333333333333', '-1.7833333333333332' ],
        [ '42.7', '-0.06666666666666667' ],
        [ '39.733333333333334', '-1.1' ],
        [ '35.833333333333336', '-2.1' ],
        [ '35.833333333333336', '-7.383333333333334' ],
        [ '35.96666666666667', '-7.383333333333334' ],
        [ '42', '-10' ],
        [ '43', '-13' ],
        [ '45', '-13' ]
    ];
    L.polyline(Madrid, { color: '#777', weight: 0.5 }).addTo(map);

    const Lisbon = [
        [ '43', '-13' ],
        [ '42', '-10' ],
        [ '35.96666666666667', '-7.383333333333334' ],
        [ '35.96666666666667', '-12' ],
        [ '32.25', '-14.633333333333333' ],
        [ '33.92500000', '-18.06916667' ],
        [ '36.5', '-15' ],
        [ '42', '-15' ],
        [ '43', '-13' ]
    ];
    L.polyline(Lisbon, { color: '#777', weight: 0.5 }).addTo(map);

    const SantaMaria = [
        [ '45', '-40' ],
        [ '45', '-13' ],
        [ '43', '-13' ],
        [ '42', '-15' ],
        [ '36.5', '-15' ],
        [ '33.92500000', '-18.06916667' ],
        [ '30', '-20' ],
        [ '30', '-25' ],
        [ '24', '-25' ],
        [ '17', '-37.5' ],
        [ '22.3', '-40' ],
        [ '44', '-40' ]
    ];
    L.polyline(SantaMaria, { color: '#777', weight: 0.5 }).addTo(map);

    const NewYork = [
        [ '44', '-40' ],
        [ '22.3', '-40' ],
        [ '18', '-45' ],
        [ '18', '-61.5' ],
        [ '38.331222', '-70.059528' ],
        [ '39', '-67' ],
        [ '42', '-67' ],
        [ '43.446666666666665', '-56.05166666666666' ],
        [ '44', '-50' ],
        [ '44', '-40' ]
    ];
    L.polyline(NewYork, { color: '#777', weight: 0.5 }).addTo(map);
}


tabs = [
    'yourProfileTab',
    'supportTab',
    'certificationTrainingTab',
    'staffTab'
]

$(document).ready(function () {


    $(document).on('click','.myczqo-tab', function(element){
        tab = $(this).data("myczqo-tab")
        if (tab === "none") { return }
        //Hide every other tab
        tabs.forEach(element => {
            $(`#${element}`).hide();
        });
        //Show the tab
        $("#" + tab).show();
        //Make the current tab inactive
        $(".myczqo-tab.active").removeClass('active')
        //make new tab active
        $(".myczqo-tab[data-myczqo-tab="+tab+']').addClass('active')
    });
})

/**
 * @returns {Date} - Date of this view.
 */
Time.prototype.getDate = function() {
    return this._parseDateGroup(this.options.ymd);
};

/**
 * @override
 * @param {string} ymd The date of schedules. YYYYMMDD format
 * @param {array} matrices Matrices for placing schedules
 * @param {number} containerHeight - container's height
 */
Time.prototype.render = function(ymd, matrices, containerHeight) {
    this._getBaseViewModel(ymd, matrices, containerHeight);
    this.container.innerHTML = this.timeTmpl({
        matrices: matrices,
        styles: this._getStyles(this.theme),
        isReadOnly: this.options.isReadOnly
    });
};

/**
 * Get the styles from theme
 * @param {Theme} theme - theme instance
 * @returns {object} styles - styles object
 */
Time.prototype._getStyles = function(theme) {
    var styles = {};
    var options = this.options;

    if (theme) {
        styles.borderRight = theme.week.timegrid.borderRight || theme.common.border;
        styles.marginRight = theme.week.timegrid.paddingRight;
        styles.borderRadius = theme.week.timegridSchedule.borderRadius;
        styles.paddingLeft = theme.week.timegridSchedule.paddingLeft;
        styles.backgroundColor = options.isToday ? theme.week.today.backgroundColor : 'inherit';
    }

    return styles;
};

Time.prototype.applyTheme = function() {
    var style = this.container.style;
    var styles = this._getStyles(this.theme);

    style.borderRight = styles.borderRight;
    style.backgroundColor = styles.backgroundColor;
};

module.exports = Time;


/***/ }),

/***/ "./src/js/view/week/timeGrid.js":
/*!**************************************!*\
  !*** ./src/js/view/week/timeGrid.js ***!
  \**************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

"use strict";
/**
 * @fileoverview View for rendered schedules by times.
 * @author NHN FE Development Lab <dl_javascript@nhn.com>
 */


var util = __webpack_require__(/*! tui-code-snippet */ "tui-code-snippet");
var config = __webpack_require__(/*! ../../config */ "./src/js/config.js");
var common = __webpack_require__(/*! ../../common/common */ "./src/js/common/common.js");
var domutil = __webpack_require__(/*! ../../common/domutil */ "./src/js/common/domutil.js");
var domevent = __webpack_require__(/*! ../../common/domevent */ "./src/js/common/domevent.js");
var datetime = __webpack_require__(/*! ../../common/datetime */ "./src/js/common/datetime.js");
var Timezone = __webpack_require__(/*! ../../common/timezone */ "./src/js/common/timezone.js");
var reqAnimFrame = __webpack_require__(/*! ../../common/reqAnimFrame */ "./src/js/common/reqAnimFrame.js");
var View = __webpack_require__(/*! ../view */ "./src/js/view/view.js");
var Time = __webpack_require__(/*! ./time */ "./src/js/view/week/time.js");
var AutoScroll = __webpack_require__(/*! ../../common/autoScroll */ "./src/js/common/autoScroll.js");
var mainTmpl = __webpack_require__(/*! ../template/week/timeGrid.hbs */ "./src/js/view/template/week/timeGrid.hbs");
var timezoneStickyTmpl = __webpack_require__(/*! ../template/week/timezoneSticky.hbs */ "./src/js/view/template/week/timezoneSticky.hbs");
var timegridCurrentTimeTmpl = __webpack_require__(/*! ../template/week/timeGridCurrentTime.hbs */ "./src/js/view/template/week/timeGridCurrentTime.hbs");
var TZDate = Timezone.Date;
var HOURMARKER_REFRESH_INTERVAL = 1000 * 60;
var SIXTY_SECONDS = 60;
var SIXTY_MINUTES = 60;

/**
 * Returns a list of time labels from start to end.
 * For hidden labels near the current time, set to hidden: true.
 * @param {object} opt - TimeGrid.options
 * @param {boolean} hasHourMarker - Whether the current time is displayed
 * @param {number} timezoneOffset - timezone offset
 * @param {object} styles - styles
 * @returns {Array.<Object>}
 */
function getHoursLabels(opt, hasHourMarker, timezoneOffset, styles) {
    var hourStart = opt.hourStart;
    var hourEnd = opt.hourEnd;
    var renderEndDate = new TZDate(opt.renderEndDate);
    var shiftByOffset = parseInt(timezoneOffset / SIXTY_MINUTES, 10);
    var shiftMinutes = Math.abs(timezoneOffset % SIXTY_MINUTES);
    var now = new TZDate().toLocalTime();
    var nowMinutes = now.getMinutes();
    var hoursRange = util.range(0, 24);
    var nowAroundHours = null;
    var nowHours, nowHoursIndex;
    var isNegativeZero = 1 / -Infinity === shiftByOffset;

    if ((shiftByOffset < 0 || isNegativeZero) && shiftMinutes > 0) {
        shiftByOffset -= 1;
    }

    // shift the array and take elements between start and end
    common.shiftArray(hoursRange, shiftByOffset);
    common.takeArray(hoursRange, hourStart, hourEnd);

    nowHours = common.shiftHours(now.getHours(), shiftByOffset) % 24;
    nowHoursIndex = util.inArray(nowHours, hoursRange);

    if (hasHourMarker) {
        if (nowMinutes < 20) {
            nowAroundHours = nowHours;
        } else if (nowMinutes > 40) {
            nowAroundHours = nowHours + 1;
        }

        if (util.isNumber(nowAroundHours)) {
            nowAroundHours %= 24;
        }
    }

    return util.map(hoursRange, function(hour, index) {
        var color;
        var fontWeight;
        var isPast = (hasHourMarker && index <= nowHoursIndex) ||
                     (renderEndDate < now && !datetime.isSameDate(renderEndDate, now));

        if (isPast) {
            // past
            color = styles.pastTimeColor;
            fontWeight = styles.pastTimeFontWeight;
        } else {
            // future
            color = styles.futureTimeColor;
            fontWeight = styles.futureTimeFontWeight;
        }

        return {
            hour: hour,
            minutes: shiftMinutes,
            hidden: nowAroundHours === hour || index === 0,
            color: color || '',
            fontWeight: fontWeight || ''
        };
    });
}
/**
 * @constructor
 * @extends {View}
 * @param {string} name - view name
 * @param {object} options The object for view customization.
 * @param {string} options.renderStartDate - render start date. YYYY-MM-DD
 * @param {string} options.renderEndDate - render end date. YYYY-MM-DD
 * @param {number} [options.hourStart=0] You can change view's start hours.
 * @param {number} [options.hourEnd=0] You can change view's end hours.
 * @param {HTMLElement} panelElement panel element.
 */
function TimeGrid(name, options, panelElement) {
    var container = domutil.appendHTMLElement(
        'div',
        panelElement,
        config.classname('timegrid-container')
    );
    var stickyContainer = domutil.appendHTMLElement(
        'div',
        panelElement,
        config.classname('timegrid-sticky-container')
    );

    panelElement.style.position = 'relative'; // for stickyContainer

    name = name || 'time';

    View.call(this, container);

    if (!util.browser.safari) {
        /**
         * @type {AutoScroll}
         */
        this._autoScroll = new AutoScroll(container);
    }

    this.stickyContainer = stickyContainer;

    /**
     * Time view options.
     * @type {object}
     */
    this.options = util.extend({
        viewName: name,
        renderStartDate: '',
        renderEndDate: '',
        hourStart: 0,
        hourEnd: 24,
        timezones: options.timezones,
        isReadOnly: options.isReadOnly,
        showTimezoneCollapseButton: false
    }, options.week);

    if (this.options.timezones.length < 1) {
        this.options.timezones = [{
            timezoneOffset: Timezone.getOffset()
        }];
    }

    /**
     * Interval id for hourmarker animation.
     * @type {number}
     */
    this.intervalID = 0;

    /**
     * timer id for hourmarker initial state
     * @type {number}
     */
    this.timerID = 0;

    /**
     * requestAnimationFrame unique ID
     * @type {number}
     */
    this.rAnimationFrameID = 0;

    /**
     * @type {boolean}
     */
    this._scrolled = false;

    /**
     * cache parent's view model
     * @type {object}
     */
    this._cacheParentViewModel = null;

    /**
     * cache hoursLabels view model to render again TimeGrid
     * @type {object}
     */
    this._cacheHoursLabels = null;

    this.attachEvent();
}

util.inherit(TimeGrid, View);

/**********
 * Prototype props
 **********/

/**
 * @type {string}
 */
TimeGrid.prototype.viewName = 'timegrid';

/**
 * Destroy view.
 * @override
 */
TimeGrid.prototype._beforeDestroy = function() {
    clearInterval(this.intervalID);
    clearTimeout(this.timerID);
    reqAnimFrame.cancelAnimFrame(this.rAnimationFrameID);

    if (this._autoScroll) {
        this._autoScroll.destroy();
    }

    domevent.off(this.stickyContainer, 'click', this._onClickStickyContainer, this);

    this._autoScroll = this.hourmarkers = this.intervalID =
    this.timerID = this.rAnimationFrameID = this._cacheParentViewModel = this.stickyContainer = null;
};

/**
 * @param {Date} [time] - date object to convert pixel in grids.
 * use **Date.now()** when not supplied.
 * @returns {number} The pixel value represent current time in grids.
 */
TimeGrid.prototype._getTopPercentByTime = function(time) {
    var opt = this.options,
        raw = datetime.raw(time || new TZDate()),
        hourLength = util.range(opt.hourStart, opt.hourEnd).length,
        maxMilliseconds = hourLength * datetime.MILLISECONDS_PER_HOUR,
        hmsMilliseconds = datetime.millisecondsFrom('hour', raw.h) +
            datetime.millisecondsFrom('minutes', raw.m) +
            datetime.millisecondsFrom('seconds', raw.s) +
            raw.ms,
        topPercent;

    topPercent = common.ratio(maxMilliseconds, 100, hmsMilliseconds);
    topPercent -= common.ratio(maxMilliseconds, 100, datetime.millisecondsFrom('hour', opt.hourStart));

    return common.limit(topPercent, [0], [100]);
};

/**
 * Get Hourmarker viewmodel.
 * @param {TZDate} now - now
 * @param {object} grids grid information(width, left, day)
 * @param {Array.<TZDate>} range render range
 * @returns {object} ViewModel of hourmarker.
 */
TimeGrid.prototype._getHourmarkerViewModel = function(now, grids, range) {
    var todaymarkerLeft = -1;
    var todaymarkerWidth = -1;
    var hourmarkerTimzones = [];
    var opt = this.options;
    var primaryOffset = Timezone.getOffset();
    var timezones = opt.timezones;
    var viewModel;

    util.forEach(range, function(date, index) {
        if (datetime.isSameDate(now, date)) {
            todaymarkerLeft = grids[index] ? grids[index].left : 0;
            todaymarkerWidth = grids[index] ? grids[index].width : 0;
        }
    });

    util.forEach(timezones, function(timezone) {
        var timezoneDifference = timezone.timezoneOffset + primaryOffset;
        var hourmarker = new TZDate(now);
        var dateDifference;

        hourmarker.setMinutes(hourmarker.getMinutes() + timezoneDifference);
        dateDifference = datetime.getDateDifference(hourmarker, now);

        hourmarkerTimzones.push({
            hourmarker: hourmarker,
            dateDifferenceSign: (dateDifference < 0) ? '-' : '+',
            dateDifference: Math.abs(dateDifference)
        });
    });

    viewModel = {
        currentHours: now.getHours(),
        hourmarkerTop: this._getTopPercentByTime(now),
        hourmarkerTimzones: hourmarkerTimzones,
        todaymarkerLeft: todaymarkerLeft,
        todaymarkerWidth: todaymarkerWidth,
        todaymarkerRight: todaymarkerLeft + todaymarkerWidth
    };

    return viewModel;
};

/**
 * Get timezone view model
 * @param {number} currentHours - current hour
 * @param {boolean} timezonesCollapsed - multiple timezones are collapsed.
 * @param {object} styles - styles
 * @returns {object} ViewModel
 */
TimeGrid.prototype._getTimezoneViewModel = function(currentHours, timezonesCollapsed, styles) {
    var opt = this.options;
    var primaryOffset = Timezone.getOffset();
    var timezones = opt.timezones;
    var timezonesLength = timezones.length;
    var timezoneViewModel = [];
    var collapsed = timezonesCollapsed;
    var width = collapsed ? 100 : 100 / timezonesLength;
    var now = new TZDate().toLocalTime();
    var backgroundColor = styles.displayTimezoneLabelBackgroundColor;

    util.forEach(timezones, function(timezone, index) {
        var hourmarker = new TZDate(now);
        var timezoneDifference;
        var timeSlots;
        var dateDifference;

        timezoneDifference = timezone.timezoneOffset + primaryOffset;
        timeSlots = getHoursLabels(opt, currentHours >= 0, timezoneDifference, styles);

        hourmarker.setMinutes(hourmarker.getMinutes() + timezoneDifference);
        dateDifference = datetime.getDateDifference(hourmarker, now);

        if (index > 0) {
            backgroundColor = styles.additionalTimezoneBackgroundColor;
        }

        timezoneViewModel.push({
            timeSlots: timeSlots,
            displayLabel: timezone.displayLabel,
            timezoneOffset: timezone.timezoneOffset,
            tooltip: timezone.tooltip || '',
            width: width,
            left: collapsed ? 0 : (timezones.length - index - 1) * width,
            isPrimary: index === 0,
            backgroundColor: backgroundColor || '',
            hidden: index !== 0 && collapsed,
            hourmarker: hourmarker,
            dateDifferenceSign: (dateDifference < 0) ? '-' : '+',
            dateDifference: Math.abs(dateDifference)
        });
    });

    return timezoneViewModel;
};

/**
 * Get base viewModel.
 * @param {object} viewModel - view model
 * @returns {object} ViewModel
 */
TimeGrid.prototype._getBaseViewModel = function(viewModel) {
    var grids = viewModel.grids;
    var range = viewModel.range;
    var opt = this.options;
    var baseViewModel = this._getHourmarkerViewModel(new TZDate().toLocalTime(), grids, range);
    var timezonesCollapsed = util.pick(viewModel, 'state', 'timezonesCollapsed');
    var styles = this._getStyles(viewModel.theme, timezonesCollapsed);

    return util.extend(baseViewModel, {
        timezones: this._getTimezoneViewModel(baseViewModel.todaymarkerLeft, timezonesCollapsed, styles),
        hoursLabels: getHoursLabels(opt, baseViewModel.todaymarkerLeft >= 0, 0, styles),
        styles: styles,
        showTimezoneCollapseButton: util.pick(opt, 'showTimezoneCollapseButton'),
        timezonesCollapsed: timezonesCollapsed
    });
};

/**
 * Reconcilation child views and render.
 * @param {object} viewModels Viewmodel
 * @param {object} grids grid information(width, left, day)
 * @param {HTMLElement} container Container element for each time view.
 * @param {Theme} theme - theme instance
 */
TimeGrid.prototype._renderChildren = function(viewModels, grids, container, theme) {
    var self = this,
        options = this.options,
        childOption,
        child,
        isToday,
        containerHeight,
        today = datetime.format(new TZDate(), 'YYYYMMDD'),
        i = 0;

    // clear contents
    container.innerHTML = '';
    this.children.clear();

    containerHeight = domutil.getSize(container.parentElement)[1];

    // reconcilation of child views
    util.forEach(viewModels, function(schedules, ymd) {
        isToday = ymd === today;

        childOption = {
            index: i,
            left: grids[i] ? grids[i].left : 0,
            width: grids[i] ? grids[i].width : 0,
            ymd: ymd,
            isToday: isToday,
            isPending: options.isPending,
            isFocused: options.isFocused,
            isReadOnly: options.isReadOnly,
            hourStart: options.hourStart,
            hourEnd: options.hourEnd
        };

        child = new Time(
            childOption,
            domutil.appendHTMLElement('div', container, config.classname('time-date')),
            theme
        );
        child.render(ymd, schedules, containerHeight);

        self.addChild(child);

        i += 1;
    });
};

/**
 * @override
 * @param {object} viewModel ViewModel list from Week view.
 */
TimeGrid.prototype.render = function(viewModel) {
    var opt = this.options,
        timeViewModel = viewModel.schedulesInDateRange[opt.viewName],
        container = this.container,
        grids = viewModel.grids,
        baseViewModel = this._getBaseViewModel(viewModel),
        scheduleLen = util.keys(timeViewModel).length;

    this._cacheParentViewModel = viewModel;
    this._cacheHoursLabels = baseViewModel.hoursLabels;

    if (!scheduleLen) {
        return;
    }

    baseViewModel.showHourMarker = baseViewModel.todaymarkerLeft >= 0;

    container.innerHTML = mainTmpl(baseViewModel);

    /**********
     * Render sticky container for timezone display label
     **********/
    this.renderStickyContainer(baseViewModel);

    /**********
     * Render children
     **********/
    this._renderChildren(
        timeViewModel,
        grids,
        domutil.find(config.classname('.timegrid-schedules-container'), container),
        viewModel.theme
    );

    this._hourLabels = domutil.find('ul', container);

    /**********
     * Render hourmarker
     **********/
    this.hourmarkers = domutil.find(config.classname('.timegrid-hourmarker'), container, true);

    if (!this._scrolled) {
        this._scrolled = true;
        this.scrollToNow();
    }
};

TimeGrid.prototype.renderStickyContainer = function(baseViewModel) {
    var stickyContainer = this.stickyContainer;

    stickyContainer.innerHTML = timezoneStickyTmpl(baseViewModel);

    stickyContainer.style.display = baseViewModel.timezones.length > 1 ? 'block' : 'none';
    stickyContainer.style.width = baseViewModel.styles.leftWidth;
    stickyContainer.style.height = baseViewModel.styles.displayTimezoneLabelHeight;
    stickyContainer.style.borderBottom = baseViewModel.styles.leftBorderRight;
};

/**
 * Refresh hourmarker element.
 */
TimeGrid.prototype.refreshHourmarker = function() {
    var hourmarkers = this.hourmarkers;
    var viewModel = this._cacheParentViewModel;
    var hoursLabels = this._cacheHoursLabels;
    var rAnimationFrameID = this.rAnimationFrameID;
    var baseViewModel;

    if (!hourmarkers || !viewModel || rAnimationFrameID) {
        return;
    }

    baseViewModel = this._getBaseViewModel(viewModel);

    this.rAnimationFrameID = reqAnimFrame.requestAnimFrame(function() {
        var needsRender = false;

        util.forEach(hoursLabels, function(hoursLabel, index) {
            if (hoursLabel.hidden !== baseViewModel.hoursLabels[index].hidden) {
                needsRender = true;

                return false;
            }

            return true;
        });

        if (needsRender) {
            this.render(viewModel);
        } else {
            util.forEach(hourmarkers, function(hourmarker) {
                var todaymarker = domutil.find(config.classname('.timegrid-todaymarker'), hourmarker);
                var hourmarkerContainer = domutil.find(config.classname('.timegrid-hourmarker-time'), hourmarker);
                var timezone = domutil.closest(hourmarker, config.classname('.timegrid-timezone'));
                var timezoneIndex = timezone ? domutil.getData(timezone, 'timezoneIndex') : 0;

                hourmarker.style.top = baseViewModel.hourmarkerTop + '%';

                if (todaymarker) {
                    todaymarker.style.display = (baseViewModel.todaymarkerLeft >= 0) ? 'block' : 'none';
                }
                if (hourmarkerContainer) {
                    hourmarkerContainer.innerHTML = timegridCurrentTimeTmpl(
                        baseViewModel.hourmarkerTimzones[timezoneIndex]
                    );
                }
            });
        }

        this.rAnimationFrameID = null;
    }, this);
};

/**
 * Attach events
 */
TimeGrid.prototype.attachEvent = function() {
    clearInterval(this.intervalID);
    clearTimeout(this.timerID);
    this.intervalID = this.timerID = this.rAnimationFrameID = null;

    this.timerID = setTimeout(this.onTick.bind(this), (SIXTY_SECONDS - new TZDate().getSeconds()) * 1000);

    domevent.on(this.stickyContainer, 'click', this._onClickStickyContainer, this);
};

/**
 * Scroll time grid to current hourmarker.
 */
TimeGrid.prototype.scrollToNow = function() {
    var container = this.container;
    var offsetTop,
        viewBound,
        scrollTop,
        scrollAmount,
        scrollBy,
        scrollFn;

    if (!this.hourmarkers || !this.hourmarkers.length) {
        return;
    }

    offsetTop = this.hourmarkers[0].offsetTop;
    viewBound = this.getViewBound();
    scrollTop = offsetTop;
    scrollAmount = viewBound.height / 4;
    scrollBy = 10;

    scrollFn = function() {
        if (scrollTop > offsetTop - scrollAmount) {
            scrollTop -= scrollBy;
            container.scrollTop = scrollTop;

            reqAnimFrame.requestAnimFrame(scrollFn);
        } else {
            container.scrollTop = offsetTop - scrollAmount;
        }
    };

    reqAnimFrame.requestAnimFrame(scrollFn);
};

/**********
 * Schedule handlers
 **********/

/**
 * Interval tick handler
 */
TimeGrid.prototype.onTick = function() {
    if (this.timerID) {
        clearTimeout(this.timerID);
        this.timerID = null;
    }

    if (!this.intervalID) {
        this.intervalID = setInterval(this.onTick.bind(this), HOURMARKER_REFRESH_INTERVAL);
    }
    this.refreshHourmarker();
};

/**
 * Get the styles from theme
 * @param {Theme} theme - theme instance
 * @param {boolean} timezonesCollapsed - multiple timezones are collapsed.
 * @returns {object} styles - styles object
 */
TimeGrid.prototype._getStyles = function(theme, timezonesCollapsed) {
    var styles = {};
    var timezonesLength = this.options.timezones.length;
    var collapsed = timezonesCollapsed;
    var numberAndUnit;

    if (theme) {
        styles.borderBottom = theme.week.timegridHorizontalLine.borderBottom || theme.common.border;
        styles.halfHourBorderBottom = theme.week.timegridHalfHour.borderBottom || theme.common.border;

        styles.todayBackgroundColor = theme.week.today.backgroundColor;
        styles.weekendBackgroundColor = theme.week.weekend.backgroundColor;
        styles.backgroundColor = theme.week.daygrid.backgroundColor;
        styles.leftWidth = theme.week.timegridLeft.width;
        styles.leftBackgroundColor = theme.week.timegridLeft.backgroundColor;
        styles.leftBorderRight = theme.week.timegridLeft.borderRight || theme.common.border;
        styles.leftFontSize = theme.week.timegridLeft.fontSize;
        styles.timezoneWidth = theme.week.timegridLeft.width;
        styles.additionalTimezoneBackgroundColor = theme.week.timegridLeftAdditionalTimezone.backgroundColor
                                                || styles.leftBackgroundColor;

        styles.displayTimezoneLabelHeight = theme.week.timegridLeftTimezoneLabel.height;
        styles.displayTimezoneLabelBackgroundColor = theme.week.timegridLeft.backgroundColor === 'inherit' ? 'white' : theme.week.timegridLeft.backgroundColor;

        styles.oneHourHeight = theme.week.timegridOneHour.height;
        styles.halfHourHeight = theme.week.timegridHalfHour.height;
        styles.quaterHourHeight = (parseInt(styles.halfHourHeight, 10) / 2) + 'px';

        styles.currentTimeColor = theme.week.currentTime.color;
        styles.currentTimeFontSize = theme.week.currentTime.fontSize;
        styles.currentTimeFontWeight = theme.week.currentTime.fontWeight;

        styles.pastTimeColor = theme.week.pastTime.color;
        styles.pastTimeFontWeight = theme.week.pastTime.fontWeight;

        styles.futureTimeColor = theme.week.futureTime.color;
        styles.futureTimeFontWeight = theme.week.futureTime.fontWeight;

        styles.currentTimeLeftBorderTop = theme.week.currentTimeLinePast.border;
        styles.currentTimeBulletBackgroundColor = theme.week.currentTimeLineBullet.backgroundColor;
        styles.currentTimeTodayBorderTop = theme.week.currentTimeLineToday.border;
        styles.currentTimeRightBorderTop = theme.week.currentTimeLineFuture.border;

        if (!collapsed && timezonesLength > 1) {
            numberAndUnit = common.parseUnit(styles.leftWidth);
            styles.leftWidth = (numberAndUnit[0] * timezonesLength) + numberAndUnit[1];
        }
    }

    return styles;
};

/**
 * @param {MouseEvent} event - mouse event object
 */
TimeGrid.prototype._onClickStickyContainer = function(event) {
    var target = domevent.getEventTarget(event);
    var closeBtn = domutil.closest(target, config.classname('.timegrid-timezone-close-btn'));

    if (!closeBtn) {
        return;
    }

    this.fire('clickTimezonesCollapsedBtn');
};

module.exports = TimeGrid;


/***/ }),

/***/ "./src/js/view/week/week.js":
/*!**********************************!*\
  !*** ./src/js/view/week/week.js ***!
  \**********************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

"use strict";
/**
 * @fileoverview View of days UI.
 * @author NHN FE Development Lab <dl_javascript@nhn.com>
 */


var util = __webpack_require__(/*! tui-code-snippet */ "tui-code-snippet");
var config = __webpack_require__(/*! ../../config */ "./src/js/config.js");
var domutil = __webpack_require__(/*! ../../common/domutil */ "./src/js/common/domutil.js");
var datetime = __webpack_require__(/*! ../../common/datetime */ "./src/js/common/datetime.js");
var TZDate = __webpack_require__(/*! ../../common/timezone */ "./src/js/common/timezone.js").Date;
var View = __webpack_require__(/*! ../view */ "./src/js/view/view.js");

/**
 * @constructor
 * @param {Base.Week} controller The controller mixin part.
 * @param {object} options View options
 * @param {string} [options.renderStartDate] Start date of render.
 *  if not supplied then use -3d from today. YYYY-MM-DD format.
 * @param {string} [options.renderEndDate] End date of render.
 *  if not supplied then use +3d from today. YYYY-MM-DD format.
 * @param {string} [options.cssPrefix] - CSS classname prefix
 * @param {HTMLElement} container The element to use container for this view.
 * @param {object} panels - schedule panels like 'milestone', 'task', 'allday', 'time'
 * @param {string} viewName - 'week', 'day'
 * @extends {View}
 */
function Week(controller, options, container, panels, viewName) {
    var range;

    container = domutil.appendHTMLElement('div', container);

    View.call(this, container);

    domutil.addClass(container, config.classname('week-container'));

    range = this._getRenderDateRange(new TZDate());

    /**
     * @type {object} Options for view.
     */
    this.options = util.extend({
        scheduleFilter: [function(schedule) {
            return Boolean(schedule.isVisible);
        }],
        renderStartDate: datetime.format(range.start, 'YYYY-MM-DD'),
        renderEndDate: datetime.format(range.end, 'YYYY-MM-DD'),
        narrowWeekend: false,
        startDayOfWeek: 0,
        workweek: false,
        showTimezoneCollapseButton: false,
        timezonesCollapsed: false,
        hourStart: 0,
        hourEnd: 24
    }, options);

    /**
     * Week controller mixin.
     * @type {Base.Week}
     */
    this.controller = controller;

    /**
     * Schedule Panels
     * @type {Array.<object>}
     */
    this.panels = panels;

    /**
     * Week view states
     * @type {object}
     */
    this.state = {
        timezonesCollapsed: this.options.timezonesCollapsed
    };

    if (viewName === 'day') {
        _disableDayOptions(this.options);
    }
}

util.inherit(Week, View);

/**********
 * Override props
 **********/

/**
 * Render each child view with schedules in ranges.
 * @fires Week#afterRender
 * @override
 */
Week.prototype.render = function() {
    var self = this,
        options = this.options,
        scheduleFilter = options.scheduleFilter,
        narrowWeekend = options.narrowWeekend,
        startDayOfWeek = options.startDayOfWeek,
        workweek = options.workweek,
        theme = this.controller.theme || {},
        state = this.state;
    var renderStartDate, renderEndDate, schedulesInDateRange, viewModel, grids, range;

    renderStartDate = new TZDate(options.renderStartDate);
    renderEndDate = new TZDate(options.renderEndDate);

    range = datetime.range(
        datetime.start(renderStartDate),
        datetime.end(renderEndDate),
        datetime.MILLISECONDS_PER_DAY
    );

    if (options.workweek && datetime.compare(renderStartDate, renderEndDate)) {
        range = util.filter(range, function(date) {
            return !datetime.isWeekend(date.getDay());
        });

        renderStartDate = range[0];
        renderEndDate = range[range.length - 1];
    }

    schedulesInDateRange = this.controller.findByDateRange(
        datetime.start(renderStartDate),
        datetime.end(renderEndDate),
        this.panels,
        scheduleFilter,
        this.options
    );

    grids = datetime.getGridLeftAndWidth(
        range.length,
        narrowWeekend,
        startDayOfWeek,
        workweek
    );

    viewModel = {
        schedulesInDateRange: schedulesInDateRange,
        renderStartDate: renderStartDate,
        renderEndDate: renderEndDate,
        grids: grids,
        range: range,
        theme: theme,
        state: state
    };

    this.children.each(function(childView) {
        var matrices;
        var viewName = util.pick(childView.options, 'viewName');
        childView.render(viewModel);

        if (viewName) {
            matrices = viewModel.schedulesInDateRange[viewName]; // DayGrid limits schedule count by visibleScheduleCount after rendering it.

            if (util.isArray(matrices)) {
                self._invokeAfterRenderSchedule(matrices);
            } else {
                util.forEach(matrices, function(matricesOfDay) {
                    self._invokeAfterRenderSchedule(matricesOfDay);
                });
            }
        }
    });

    /**
     * @event Week#afterRender
     */
    this.fire('afterRender');
};

/**
 * Fire 'afterRenderSchedule' event
 * @param {Array} matrices - schedule matrices from view model
 * @fires Week#afterRenderSchedule
 */
Week.prototype._invokeAfterRenderSchedule = function(matrices) {
    var self = this;
    util.forEachArray(matrices, function(matrix) {
        util.forEachArray(matrix, function(column) {
            util.forEachArray(column, function(scheduleViewModel) {
                if (scheduleViewModel) {
                    /**
                     * @event Week#afterRenderSchedule
                     */
                    self.fire('afterRenderSchedule', {schedule: scheduleViewModel.model});
                }
            });
        });
    });
};

/**********
 * Prototype props
 **********/

Week.prototype.viewName = 'week';

/**
 * Calculate default render date range from supplied date.
 * @param {Date} baseDate base date.
 * @returns {object} date range.
 */
Week.prototype._getRenderDateRange = function(baseDate) {
    var base = datetime.start(baseDate),
        start = new TZDate(Number(base)),
        end = new TZDate(Number(base));

    start.setDate(start.getDate() - 3);
    end.setDate(end.getDate() + 3);

    return {
        start: start,
        end: end
    };
};

/**
 * disable options for day view
 * @param {WeekOptions} options - week options to disable
 */
function _disableDayOptions(options) {
    options.workweek = false;
}

util.CustomEvents.mixin(Week);

module.exports = Week;


/***/ }),

/***/ "./src/js/view/weekday.js":
/*!********************************!*\
  !*** ./src/js/view/weekday.js ***!
  \********************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

"use strict";
/**
 * @fileoverview Weekday view
 * @author NHN FE Development Lab <dl_javascript@nhn.com>
 */


var util = __webpack_require__(/*! tui-code-snippet */ "tui-code-snippet");
var config = __webpack_require__(/*! ../config */ "./src/js/config.js"),
    domutil = __webpack_require__(/*! ../common/domutil */ "./src/js/common/domutil.js"),
    datetime = __webpack_require__(/*! ../common/datetime */ "./src/js/common/datetime.js"),
    TZDate = __webpack_require__(/*! ../common/timezone */ "./src/js/common/timezone.js").Date,
    View = __webpack_require__(/*! ./view */ "./src/js/view/view.js");

/**
 * @constructor
 * @extends {View}
 * @param {object} options - view options.
 * @param {number} [options.containerButtonGutter=8] - free space at bottom to
 *  make create easy.
 * @param {number} [options.scheduleHeight=18] - height of each schedule block.
 * @param {number} [options.scheduleGutter=2] - gutter height of each schedule block.
 * @param {HTMLDIVElement} container - DOM element to use container for this
 *  view.
 */
function Weekday(options, container) {
    container = domutil.appendHTMLElement(
        'div',
        container,
        config.classname('weekday')
    );

    /**
     * @type {object}
     */
    this.options = util.extend({
        containerBottomGutter: 8,
        scheduleHeight: 18,
        scheduleGutter: 2,
        narrowWeekend: false,
        startDayOfWeek: 0,
        workweek: false
    }, options);

    /*
     * cache parent's view model
     * @type {object}
     */
    this._cacheParentViewModel = null;

    View.call(this, container);
}

util.inherit(Weekday, View);

/**
 * Get render date range
 * @returns {Date[]} rendered date range
 */
Weekday.prototype.getRenderDateRange = function() {
    return this._cacheParentViewModel.range;
};

/**
 * Get render date grids information
 * @returns {Date[]} rendered date grids information
 */
Weekday.prototype.getRenderDateGrids = function() {
    return this._cacheParentViewModel.grids;
};

/**
 * Get default view model.
 * @param {object} viewModel parent's view model
 * @returns {object} viewModel to rendering.
 */
Weekday.prototype.getBaseViewModel = function(viewModel) {
    var opt = this.options;
    var range = viewModel.range;
    var gridWidth = (100 / range.length);
    var grids = viewModel.grids;
    var exceedDate = viewModel.exceedDate || {};
    var theme = viewModel.theme;
    var now = new TZDate().toLocalTime();

    this._cacheParentViewModel = viewModel;

    return {
        width: gridWidth,
        scheduleHeight: opt.scheduleHeight,
        scheduleBlockHeight: (opt.scheduleHeight + opt.scheduleGutter),
        scheduleBlockGutter: opt.scheduleGutter,
        dates: util.map(range, function(date, index) {
            var day = date.getDay();
            var ymd = datetime.format(new TZDate(date), 'YYYYMMDD');
            var isToday = datetime.isSameDate(now, date);

            return {
                date: datetime.format(date, 'YYYY-MM-DD'),
                month: date.getMonth() + 1,
                day: day,
                isToday: isToday,
                ymd: ymd,
                hiddenSchedules: exceedDate[ymd] || 0,
                width: grids[index] ? grids[index].width : 0,
                left: grids[index] ? grids[index].left : 0,
                color: this._getDayNameColor(theme, day, isToday),
                backgroundColor: this._getDayBackgroundColor(theme, day)
            };
        }, this)
    };
};

/* eslint max-nested-callbacks: 0 */
/**
 * Make exceed date information
 * @param {number} maxCount - exceed schedule count
 * @param {Array} eventsInDateRange  - matrix of ScheduleViewModel
 * @param {Array.<TZDate>} range - date range of one week
 * @returns {object} exceedDate
 */
Weekday.prototype.getExceedDate = function(maxCount, eventsInDateRange, range) {
    var exceedDate = this._initExceedDate(range);

    util.forEach(eventsInDateRange, function(matrix) {
        util.forEach(matrix, function(column) {
            util.forEach(column, function(viewModel) {
                var period;
                if (!viewModel || viewModel.top < maxCount) {
                    return;
                }

                // check that this schedule block is not visible after rendered.
                viewModel.hidden = true;

                period = datetime.range(
                    viewModel.getStarts(),
                    viewModel.getEnds(),
                    datetime.MILLISECONDS_PER_DAY
                );

                util.forEach(period, function(date) {
                    var ymd = datetime.format(date, 'YYYYMMDD');
                    exceedDate[ymd] += 1;
                });
            });
        });
    });

    return exceedDate;
};

/**
 * Initiate exceed date information
 * @param {Array.<TZDate>} range - date range of one week
 * @returns {Object} - initiated exceed date
 */
Weekday.prototype._initExceedDate = function(range) {
    var exceedDate = {};

    util.forEach(range, function(date) {
        var ymd = datetime.format(date, 'YYYYMMDD');
        exceedDate[ymd] = 0;
    });

    return exceedDate;
};

/**
 * Get a day name color
 * @param {Theme} theme - theme instance
 * @param {number} day - day number
 * @param {boolean} isToday - today flag
 * @param {boolean} isOtherMonth - not this month flag
 * @returns {string} style - color style
 */
Weekday.prototype._getDayNameColor = function(theme, day, isToday, isOtherMonth) {
    var color = '';

    if (theme) {
        if (day === 0) {
            color = isOtherMonth ? theme.month.holidayExceptThisMonth.color : theme.common.holiday.color;
        } else if (day === 6) {
            color = isOtherMonth ? theme.month.dayExceptThisMonth.color : theme.common.saturday.color;
        } else if (isToday) {
            color = theme.common.today.color;
        } else {
            color = isOtherMonth ? theme.month.dayExceptThisMonth.color : theme.common.dayname.color;
        }
    }

    return color;
};

/**
 * Get a day background color
 * @param {Theme} theme - theme instance
 * @param {number} day - day number
 * @returns {string} style - color style
 */
Weekday.prototype._getDayBackgroundColor = function(theme, day) {
    var color = '';

    if (theme) {
        if (day === 0 || day === 6) {
            color = theme.month.weekend.backgroundColor;
        } else {
            color = 'inherit';
        }
    }

    return color;
};

module.exports = Weekday;


/***/ }),

/***/ "tui-code-snippet":
/*!******************************************************************************************************************************!*\
  !*** external {"commonjs":"tui-code-snippet","commonjs2":"tui-code-snippet","amd":"tui-code-snippet","root":["tui","util"]} ***!
  \******************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

module.exports = __WEBPACK_EXTERNAL_MODULE_tui_code_snippet__;

/***/ }),

/***/ "tui-date-picker":
/*!*********************************************************************************************************************************!*\
  !*** external {"commonjs":"tui-date-picker","commonjs2":"tui-date-picker","amd":"tui-date-picker","root":["tui","DatePicker"]} ***!
  \*********************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

module.exports = __WEBPACK_EXTERNAL_MODULE_tui_date_picker__;

/***/ })

/******/ });
});
//# sourceMappingURL=tui-calendar.js.map

/***/ }),

/***/ "./node_modules/tui-code-snippet/dist/tui-code-snippet.js":
/*!****************************************************************!*\
  !*** ./node_modules/tui-code-snippet/dist/tui-code-snippet.js ***!
  \****************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

/*!
 * tui-code-snippet.js
 * @version 1.5.2
 * @author NHN. FE Development Lab <dl_javascript@nhn.com>
 * @license MIT
 */
(function webpackUniversalModuleDefinition(root, factory) {
	if(true)
		module.exports = factory();
	else {}
})(this, function() {
return /******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};

/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {

/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId])
/******/ 			return installedModules[moduleId].exports;

/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			exports: {},
/******/ 			id: moduleId,
/******/ 			loaded: false
/******/ 		};

/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);

/******/ 		// Flag the module as loaded
/******/ 		module.loaded = true;

/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}


/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;

/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;

/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "dist";

/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(0);
/******/ })
/************************************************************************/
/******/ ([
/* 0 */
/***/ (function(module, exports, __webpack_require__) {

	'use strict';

	/**
	 * @fileoverview
	 * @author NHN.
	 *         FE Development Lab <dl_javascript@nhn.com>
	 * @namespace tui.util
	 * @example
	 * // node, commonjs
	 * var util = require('tui-code-snippet');
	 * @example
	 * // distribution file, script
	 * <script src='path-to/tui-code-snippt.js'></script>
	 * <script>
	 * var util = tui.util;
	 * <script>
	 */
	var util = {};
	var object = __webpack_require__(1);
	var extend = object.extend;

	extend(util, object);
	extend(util, __webpack_require__(3));
	extend(util, __webpack_require__(2));
	extend(util, __webpack_require__(4));
	extend(util, __webpack_require__(5));
	extend(util, __webpack_require__(6));
	extend(util, __webpack_require__(7));
	extend(util, __webpack_require__(8));
	extend(util, __webpack_require__(9));

	util.browser = __webpack_require__(10);
	util.popup = __webpack_require__(11);
	util.formatDate = __webpack_require__(12);
	util.defineClass = __webpack_require__(13);
	util.defineModule = __webpack_require__(14);
	util.defineNamespace = __webpack_require__(15);
	util.CustomEvents = __webpack_require__(16);
	util.Enum = __webpack_require__(17);
	util.ExMap = __webpack_require__(18);
	util.HashMap = __webpack_require__(20);
	util.Map = __webpack_require__(19);

	module.exports = util;


/***/ }),
/* 1 */
/***/ (function(module, exports, __webpack_require__) {

	/**
	 * @fileoverview This module has some functions for handling a plain object, json.
	 * @author NHN.
	 *         FE Development Lab <dl_javascript@nhn.com>
	 */

	'use strict';

	var type = __webpack_require__(2);
	var array = __webpack_require__(3);

	/**
	 * The last id of stamp
	 * @type {number}
	 * @private
	 */
	var lastId = 0;

	/**
	 * Extend the target object from other objects.
	 * @param {object} target - Object that will be extended
	 * @param {...object} objects - Objects as sources
	 * @returns {object} Extended object
	 * @memberof tui.util
	 */
	function extend(target, objects) { // eslint-disable-line no-unused-vars
	    var hasOwnProp = Object.prototype.hasOwnProperty;
	    var source, prop, i, len;

	    for (i = 1, len = arguments.length; i < len; i += 1) {
	        source = arguments[i];
	        for (prop in source) {
	            if (hasOwnProp.call(source, prop)) {
	                target[prop] = source[prop];
	            }
	        }
	    }

	    return target;
	}

	/**
	 * Assign a unique id to an object
	 * @param {object} obj - Object that will be assigned id.
	 * @returns {number} Stamped id
	 * @memberof tui.util
	 */
	function stamp(obj) {
	    if (!obj.__fe_id) {
	        lastId += 1;
	        obj.__fe_id = lastId; // eslint-disable-line camelcase
	    }

	    return obj.__fe_id;
	}

	/**
	 * Verify whether an object has a stamped id or not.
	 * @param {object} obj - adjusted object
	 * @returns {boolean}
	 * @memberof tui.util
	 */
	function hasStamp(obj) {
	    return type.isExisty(pick(obj, '__fe_id'));
	}

	/**
	 * Reset the last id of stamp
	 * @private
	 */
	function resetLastId() {
	    lastId = 0;
	}

	/**
	 * Return a key-list(array) of a given object
	 * @param {object} obj - Object from which a key-list will be extracted
	 * @returns {Array} A key-list(array)
	 * @memberof tui.util
	 */
	function keys(obj) {
	    var keyArray = [];
	    var key;

	    for (key in obj) {
	        if (obj.hasOwnProperty(key)) {
	            keyArray.push(key);
	        }
	    }

	    return keyArray;
	}

	/**
	 * Return the equality for multiple objects(jsonObjects).<br>
	 *  See {@link http://stackoverflow.com/questions/1068834/object-comparison-in-javascript}
	 * @param {...object} object - Multiple objects for comparing.
	 * @returns {boolean} Equality
	 * @memberof tui.util
	 * @example
	 * //-- #1. Get Module --//
	 * var util = require('tui-code-snippet'); // node, commonjs
	 * var util = tui.util; // distribution file
	 *
	 * //-- #2. Use property --//
	 * var jsonObj1 = {name:'milk', price: 1000};
	 * var jsonObj2 = {name:'milk', price: 1000};
	 * var jsonObj3 = {name:'milk', price: 1000};
	 * util.compareJSON(jsonObj1, jsonObj2, jsonObj3);   // true
	 *
	 * var jsonObj4 = {name:'milk', price: 1000};
	 * var jsonObj5 = {name:'beer', price: 3000};
	 * util.compareJSON(jsonObj4, jsonObj5); // false
	 */
	function compareJSON(object) {
	    var argsLen = arguments.length;
	    var i = 1;

	    if (argsLen < 1) {
	        return true;
	    }

	    for (; i < argsLen; i += 1) {
	        if (!isSameObject(object, arguments[i])) {
	            return false;
	        }
	    }

	    return true;
	}

	/**
	 * @param {*} x - object to compare
	 * @param {*} y - object to compare
	 * @returns {boolean} - whether object x and y is same or not
	 * @private
	 */
	function isSameObject(x, y) { // eslint-disable-line complexity
	    var leftChain = [];
	    var rightChain = [];
	    var p;

	    // remember that NaN === NaN returns false
	    // and isNaN(undefined) returns true
	    if (isNaN(x) &&
	        isNaN(y) &&
	        type.isNumber(x) &&
	        type.isNumber(y)) {
	        return true;
	    }

	    // Compare primitives and functions.
	    // Check if both arguments link to the same object.
	    // Especially useful on step when comparing prototypes
	    if (x === y) {
	        return true;
	    }

	    // Works in case when functions are created in constructor.
	    // Comparing dates is a common scenario. Another built-ins?
	    // We can even handle functions passed across iframes
	    if ((type.isFunction(x) && type.isFunction(y)) ||
	        (x instanceof Date && y instanceof Date) ||
	        (x instanceof RegExp && y instanceof RegExp) ||
	        (x instanceof String && y instanceof String) ||
	        (x instanceof Number && y instanceof Number)) {
	        return x.toString() === y.toString();
	    }

	    // At last checking prototypes as good a we can
	    if (!(x instanceof Object && y instanceof Object)) {
	        return false;
	    }

	    if (x.isPrototypeOf(y) ||
	        y.isPrototypeOf(x) ||
	        x.constructor !== y.constructor ||
	        x.prototype !== y.prototype) {
	        return false;
	    }

	    // check for infinitive linking loops
	    if (array.inArray(x, leftChain) > -1 ||
	        array.inArray(y, rightChain) > -1) {
	        return false;
	    }

	    // Quick checking of one object beeing a subset of another.
	    for (p in y) {
	        if (y.hasOwnProperty(p) !== x.hasOwnProperty(p)) {
	            return false;
	        } else if (typeof y[p] !== typeof x[p]) {
	            return false;
	        }
	    }

	    // This for loop executes comparing with hasOwnProperty() and typeof for each property in 'x' object,
	    // and verifying equality for x[property] and y[property].
	    for (p in x) {
	        if (y.hasOwnProperty(p) !== x.hasOwnProperty(p)) {
	            return false;
	        } else if (typeof y[p] !== typeof x[p]) {
	            return false;
	        }

	        if (typeof (x[p]) === 'object' || typeof (x[p]) === 'function') {
	            leftChain.push(x);
	            rightChain.push(y);

	            if (!isSameObject(x[p], y[p])) {
	                return false;
	            }

	            leftChain.pop();
	            rightChain.pop();
	        } else if (x[p] !== y[p]) {
	            return false;
	        }
	    }

	    return true;
	}
	/* eslint-enable complexity */

	/**
	 * Retrieve a nested item from the given object/array
	 * @param {object|Array} obj - Object for retrieving
	 * @param {...string|number} paths - Paths of property
	 * @returns {*} Value
	 * @memberof tui.util
	 * @example
	 * //-- #1. Get Module --//
	 * var util = require('tui-code-snippet'); // node, commonjs
	 * var util = tui.util; // distribution file
	 *
	 * //-- #2. Use property --//
	 * var obj = {
	 *     'key1': 1,
	 *     'nested' : {
	 *         'key1': 11,
	 *         'nested': {
	 *             'key1': 21
	 *         }
	 *     }
	 * };
	 * util.pick(obj, 'nested', 'nested', 'key1'); // 21
	 * util.pick(obj, 'nested', 'nested', 'key2'); // undefined
	 *
	 * var arr = ['a', 'b', 'c'];
	 * util.pick(arr, 1); // 'b'
	 */
	function pick(obj, paths) { // eslint-disable-line no-unused-vars
	    var args = arguments;
	    var target = args[0];
	    var i = 1;
	    var length = args.length;

	    for (; i < length; i += 1) {
	        if (type.isUndefined(target) ||
	            type.isNull(target)) {
	            return;
	        }

	        target = target[args[i]];
	    }

	    return target; // eslint-disable-line consistent-return
	}

	module.exports = {
	    extend: extend,
	    stamp: stamp,
	    hasStamp: hasStamp,
	    resetLastId: resetLastId,
	    keys: Object.prototype.keys || keys,
	    compareJSON: compareJSON,
	    pick: pick
	};


/***/ }),
/* 2 */
/***/ (function(module, exports) {

	/**
	 * @fileoverview This module provides some functions to check the type of variable
	 * @author NHN.
	 *         FE Development Lab <dl_javascript@nhn.com>
	 */

	'use strict';

	var toString = Object.prototype.toString;

	/**
	 * Check whether the given variable is existing or not.<br>
	 *  If the given variable is not null and not undefined, returns true.
	 * @param {*} param - Target for checking
	 * @returns {boolean} Is existy?
	 * @memberof tui.util
	 * @example
	 * //-- #1. Get Module --//
	 * var util = require('tui-code-snippet'); // node, commonjs
	 * var util = tui.util; // distribution file
	 *
	 * //-- #2. Use property --//
	 * util.isExisty(''); //true
	 * util.isExisty(0); //true
	 * util.isExisty([]); //true
	 * util.isExisty({}); //true
	 * util.isExisty(null); //false
	 * util.isExisty(undefined); //false
	*/
	function isExisty(param) {
	    return !isUndefined(param) && !isNull(param);
	}

	/**
	 * Check whether the given variable is undefined or not.<br>
	 *  If the given variable is undefined, returns true.
	 * @param {*} obj - Target for checking
	 * @returns {boolean} Is undefined?
	 * @memberof tui.util
	 */
	function isUndefined(obj) {
	    return obj === undefined; // eslint-disable-line no-undefined
	}

	/**
	 * Check whether the given variable is null or not.<br>
	 *  If the given variable(arguments[0]) is null, returns true.
	 * @param {*} obj - Target for checking
	 * @returns {boolean} Is null?
	 * @memberof tui.util
	 */
	function isNull(obj) {
	    return obj === null;
	}

	/**
	 * Check whether the given variable is truthy or not.<br>
	 *  If the given variable is not null or not undefined or not false, returns true.<br>
	 *  (It regards 0 as true)
	 * @param {*} obj - Target for checking
	 * @returns {boolean} Is truthy?
	 * @memberof tui.util
	 */
	function isTruthy(obj) {
	    return isExisty(obj) && obj !== false;
	}

	/**
	 * Check whether the given variable is falsy or not.<br>
	 *  If the given variable is null or undefined or false, returns true.
	 * @param {*} obj - Target for checking
	 * @returns {boolean} Is falsy?
	 * @memberof tui.util
	 */
	function isFalsy(obj) {
	    return !isTruthy(obj);
	}

	/**
	 * Check whether the given variable is an arguments object or not.<br>
	 *  If the given variable is an arguments object, return true.
	 * @param {*} obj - Target for checking
	 * @returns {boolean} Is arguments?
	 * @memberof tui.util
	 */
	function isArguments(obj) {
	    var result = isExisty(obj) &&
	        ((toString.call(obj) === '[object Arguments]') || !!obj.callee);

	    return result;
	}

	/**
	 * Check whether the given variable is an instance of Array or not.<br>
	 *  If the given variable is an instance of Array, return true.
	 * @param {*} obj - Target for checking
	 * @returns {boolean} Is array instance?
	 * @memberof tui.util
	 */
	function isArray(obj) {
	    return obj instanceof Array;
	}

	/**
	 * Check whether the given variable is an object or not.<br>
	 *  If the given variable is an object, return true.
	 * @param {*} obj - Target for checking
	 * @returns {boolean} Is object?
	 * @memberof tui.util
	 */
	function isObject(obj) {
	    return obj === Object(obj);
	}

	/**
	 * Check whether the given variable is a function or not.<br>
	 *  If the given variable is a function, return true.
	 * @param {*} obj - Target for checking
	 * @returns {boolean} Is function?
	 * @memberof tui.util
	 */
	function isFunction(obj) {
	    return obj instanceof Function;
	}

	/**
	 * Check whether the given variable is a number or not.<br>
	 *  If the given variable is a number, return true.
	 * @param {*} obj - Target for checking
	 * @returns {boolean} Is number?
	 * @memberof tui.util
	 */
	function isNumber(obj) {
	    return typeof obj === 'number' || obj instanceof Number;
	}

	/**
	 * Check whether the given variable is a string or not.<br>
	 *  If the given variable is a string, return true.
	 * @param {*} obj - Target for checking
	 * @returns {boolean} Is string?
	 * @memberof tui.util
	 */
	function isString(obj) {
	    return typeof obj === 'string' || obj instanceof String;
	}

	/**
	 * Check whether the given variable is a boolean or not.<br>
	 *  If the given variable is a boolean, return true.
	 * @param {*} obj - Target for checking
	 * @returns {boolean} Is boolean?
	 * @memberof tui.util
	 */
	function isBoolean(obj) {
	    return typeof obj === 'boolean' || obj instanceof Boolean;
	}

	/**
	 * Check whether the given variable is an instance of Array or not.<br>
	 *  If the given variable is an instance of Array, return true.<br>
	 *  (It is used for multiple frame environments)
	 * @param {*} obj - Target for checking
	 * @returns {boolean} Is an instance of array?
	 * @memberof tui.util
	 */
	function isArraySafe(obj) {
	    return toString.call(obj) === '[object Array]';
	}

	/**
	 * Check whether the given variable is a function or not.<br>
	 *  If the given variable is a function, return true.<br>
	 *  (It is used for multiple frame environments)
	 * @param {*} obj - Target for checking
	 * @returns {boolean} Is a function?
	 * @memberof tui.util
	 */
	function isFunctionSafe(obj) {
	    return toString.call(obj) === '[object Function]';
	}

	/**
	 * Check whether the given variable is a number or not.<br>
	 *  If the given variable is a number, return true.<br>
	 *  (It is used for multiple frame environments)
	 * @param {*} obj - Target for checking
	 * @returns {boolean} Is a number?
	 * @memberof tui.util
	 */
	function isNumberSafe(obj) {
	    return toString.call(obj) === '[object Number]';
	}

	/**
	 * Check whether the given variable is a string or not.<br>
	 *  If the given variable is a string, return true.<br>
	 *  (It is used for multiple frame environments)
	 * @param {*} obj - Target for checking
	 * @returns {boolean} Is a string?
	 * @memberof tui.util
	 */
	function isStringSafe(obj) {
	    return toString.call(obj) === '[object String]';
	}

	/**
	 * Check whether the given variable is a boolean or not.<br>
	 *  If the given variable is a boolean, return true.<br>
	 *  (It is used for multiple frame environments)
	 * @param {*} obj - Target for checking
	 * @returns {boolean} Is a boolean?
	 * @memberof tui.util
	 */
	function isBooleanSafe(obj) {
	    return toString.call(obj) === '[object Boolean]';
	}

	/**
	 * Check whether the given variable is a instance of HTMLNode or not.<br>
	 *  If the given variables is a instance of HTMLNode, return true.
	 * @param {*} html - Target for checking
	 * @returns {boolean} Is HTMLNode ?
	 * @memberof tui.util
	 */
	function isHTMLNode(html) {
	    if (typeof HTMLElement === 'object') {
	        return (html && (html instanceof HTMLElement || !!html.nodeType));
	    }

	    return !!(html && html.nodeType);
	}

	/**
	 * Check whether the given variable is a HTML tag or not.<br>
	 *  If the given variables is a HTML tag, return true.
	 * @param {*} html - Target for checking
	 * @returns {Boolean} Is HTML tag?
	 * @memberof tui.util
	 */
	function isHTMLTag(html) {
	    if (typeof HTMLElement === 'object') {
	        return (html && (html instanceof HTMLElement));
	    }

	    return !!(html && html.nodeType && html.nodeType === 1);
	}

	/**
	 * Check whether the given variable is empty(null, undefined, or empty array, empty object) or not.<br>
	 *  If the given variables is empty, return true.
	 * @param {*} obj - Target for checking
	 * @returns {boolean} Is empty?
	 * @memberof tui.util
	 */
	function isEmpty(obj) {
	    if (!isExisty(obj) || _isEmptyString(obj)) {
	        return true;
	    }

	    if (isArray(obj) || isArguments(obj)) {
	        return obj.length === 0;
	    }

	    if (isObject(obj) && !isFunction(obj)) {
	        return !_hasOwnProperty(obj);
	    }

	    return true;
	}

	/**
	 * Check whether given argument is empty string
	 * @param {*} obj - Target for checking
	 * @returns {boolean} whether given argument is empty string
	 * @memberof tui.util
	 * @private
	 */
	function _isEmptyString(obj) {
	    return isString(obj) && obj === '';
	}

	/**
	 * Check whether given argument has own property
	 * @param {Object} obj - Target for checking
	 * @returns {boolean} - whether given argument has own property
	 * @memberof tui.util
	 * @private
	 */
	function _hasOwnProperty(obj) {
	    var key;
	    for (key in obj) {
	        if (obj.hasOwnProperty(key)) {
	            return true;
	        }
	    }

	    return false;
	}

	/**
	 * Check whether the given variable is not empty
	 * (not null, not undefined, or not empty array, not empty object) or not.<br>
	 *  If the given variables is not empty, return true.
	 * @param {*} obj - Target for checking
	 * @returns {boolean} Is not empty?
	 * @memberof tui.util
	 */
	function isNotEmpty(obj) {
	    return !isEmpty(obj);
	}

	/**
	 * Check whether the given variable is an instance of Date or not.<br>
	 *  If the given variables is an instance of Date, return true.
	 * @param {*} obj - Target for checking
	 * @returns {boolean} Is an instance of Date?
	 * @memberof tui.util
	 */
	function isDate(obj) {
	    return obj instanceof Date;
	}

	/**
	 * Check whether the given variable is an instance of Date or not.<br>
	 *  If the given variables is an instance of Date, return true.<br>
	 *  (It is used for multiple frame environments)
	 * @param {*} obj - Target for checking
	 * @returns {boolean} Is an instance of Date?
	 * @memberof tui.util
	 */
	function isDateSafe(obj) {
	    return toString.call(obj) === '[object Date]';
	}

	module.exports = {
	    isExisty: isExisty,
	    isUndefined: isUndefined,
	    isNull: isNull,
	    isTruthy: isTruthy,
	    isFalsy: isFalsy,
	    isArguments: isArguments,
	    isArray: isArray,
	    isArraySafe: isArraySafe,
	    isObject: isObject,
	    isFunction: isFunction,
	    isFunctionSafe: isFunctionSafe,
	    isNumber: isNumber,
	    isNumberSafe: isNumberSafe,
	    isDate: isDate,
	    isDateSafe: isDateSafe,
	    isString: isString,
	    isStringSafe: isStringSafe,
	    isBoolean: isBoolean,
	    isBooleanSafe: isBooleanSafe,
	    isHTMLNode: isHTMLNode,
	    isHTMLTag: isHTMLTag,
	    isEmpty: isEmpty,
	    isNotEmpty: isNotEmpty
	};


/***/ }),
/* 3 */
/***/ (function(module, exports, __webpack_require__) {

	/**
	 * @fileoverview This module has some functions for handling array.
	 * @author NHN.
	 *         FE Development Lab <dl_javascript@nhn.com>
	 */

	'use strict';

	var collection = __webpack_require__(4);
	var type = __webpack_require__(2);

	var aps = Array.prototype.slice;
	var util;

	/**
	 * Generate an integer Array containing an arithmetic progression.
	 * @param {number} start - start index
	 * @param {number} stop - stop index
	 * @param {number} step - next visit index = current index + step
	 * @returns {Array}
	 * @memberof tui.util
	 * @example
	 * //-- #1. Get Module --//
	 * var util = require('tui-code-snippet'); // node, commonjs
	 * var util = tui.util; // distribution file
	 *
	 * //-- #2. Use property --//
	 * util.range(5); // [0, 1, 2, 3, 4]
	 * util.range(1, 5); // [1,2,3,4]
	 * util.range(2, 10, 2); // [2,4,6,8]
	 * util.range(10, 2, -2); // [10,8,6,4]
	 */
	var range = function(start, stop, step) {
	    var arr = [];
	    var flag;

	    if (type.isUndefined(stop)) {
	        stop = start || 0;
	        start = 0;
	    }

	    step = step || 1;
	    flag = step < 0 ? -1 : 1;
	    stop *= flag;

	    for (; start * flag < stop; start += step) {
	        arr.push(start);
	    }

	    return arr;
	};

	/* eslint-disable valid-jsdoc */
	/**
	 * Zip together multiple lists into a single array
	 * @param {...Array}
	 * @returns {Array}
	 * @memberof tui.util
	 * @example
	 * //-- #1. Get Module --//
	 * var util = require('tui-code-snippet'); // node, commonjs
	 * var util = tui.util; // distribution file
	 *
	 * //-- #2. Use property --//
	 * var result = util.zip([1, 2, 3], ['a', 'b','c'], [true, false, true]);
	 * console.log(result[0]); // [1, 'a', true]
	 * console.log(result[1]); // [2, 'b', false]
	 * console.log(result[2]); // [3, 'c', true]
	 */
	var zip = function() {/* eslint-enable valid-jsdoc */
	    var arr2d = aps.call(arguments);
	    var result = [];

	    collection.forEach(arr2d, function(arr) {
	        collection.forEach(arr, function(value, index) {
	            if (!result[index]) {
	                result[index] = [];
	            }
	            result[index].push(value);
	        });
	    });

	    return result;
	};

	/**
	 * Returns the first index at which a given element can be found in the array
	 * from start index(default 0), or -1 if it is not present.<br>
	 * It compares searchElement to elements of the Array using strict equality
	 * (the same method used by the ===, or triple-equals, operator).
	 * @param {*} searchElement Element to locate in the array
	 * @param {Array} array Array that will be traversed.
	 * @param {number} startIndex Start index in array for searching (default 0)
	 * @returns {number} the First index at which a given element, or -1 if it is not present
	 * @memberof tui.util
	 * @example
	 * //-- #1. Get Module --//
	 * var util = require('tui-code-snippet'); // node, commonjs
	 * var util = tui.util; // distribution file
	 *
	 * //-- #2. Use property --//
	 * var arr = ['one', 'two', 'three', 'four'];
	 * var idx1 = util.inArray('one', arr, 3); // -1
	 * var idx2 = util.inArray('one', arr); // 0
	 */
	var inArray = function(searchElement, array, startIndex) {
	    var i;
	    var length;
	    startIndex = startIndex || 0;

	    if (!type.isArray(array)) {
	        return -1;
	    }

	    if (Array.prototype.indexOf) {
	        return Array.prototype.indexOf.call(array, searchElement, startIndex);
	    }

	    length = array.length;
	    for (i = startIndex; startIndex >= 0 && i < length; i += 1) {
	        if (array[i] === searchElement) {
	            return i;
	        }
	    }

	    return -1;
	};

	util = {
	    inArray: inArray,
	    range: range,
	    zip: zip
	};

	module.exports = util;


/***/ }),
/* 4 */
/***/ (function(module, exports, __webpack_require__) {

	/**
	 * @fileoverview This module has some functions for handling object as collection.
	 * @author NHN.
	 *         FE Development Lab <dl_javascript@nhn.com>
	 */

	'use strict';

	var type = __webpack_require__(2);
	var object = __webpack_require__(1);

	/**
	 * Execute the provided callback once for each element present
	 * in the array(or Array-like object) in ascending order.<br>
	 * If the callback function returns false, the loop will be stopped.<br>
	 * Callback function(iteratee) is invoked with three arguments:
	 *  - The value of the element
	 *  - The index of the element
	 *  - The array(or Array-like object) being traversed
	 * @param {Array} arr The array(or Array-like object) that will be traversed
	 * @param {function} iteratee Callback function
	 * @param {Object} [context] Context(this) of callback function
	 * @memberof tui.util
	 * @example
	 * //-- #1. Get Module --//
	 * var util = require('tui-code-snippet'); // node, commonjs
	 * var util = tui.util; // distribution file
	 *
	 * //-- #2. Use property --//
	 * var sum = 0;
	 *
	 * util.forEachArray([1,2,3], function(value){
	 *     sum += value;
	 * });
	 * alert(sum); // 6
	 */
	function forEachArray(arr, iteratee, context) {
	    var index = 0;
	    var len = arr.length;

	    context = context || null;

	    for (; index < len; index += 1) {
	        if (iteratee.call(context, arr[index], index, arr) === false) {
	            break;
	        }
	    }
	}

	/**
	 * Execute the provided callback once for each property of object which actually exist.<br>
	 * If the callback function returns false, the loop will be stopped.<br>
	 * Callback function(iteratee) is invoked with three arguments:
	 *  - The value of the property
	 *  - The name of the property
	 *  - The object being traversed
	 * @param {Object} obj The object that will be traversed
	 * @param {function} iteratee  Callback function
	 * @param {Object} [context] Context(this) of callback function
	 * @memberof tui.util
	 * @example
	 * //-- #1. Get Module --//
	 * var util = require('tui-code-snippet'); // node, commonjs
	 * var util = tui.util; // distribution file
	 *
	 * //-- #2. Use property --//
	 * var sum = 0;
	 *
	 * util.forEachOwnProperties({a:1,b:2,c:3}, function(value){
	 *     sum += value;
	 * });
	 * alert(sum); // 6
	 **/
	function forEachOwnProperties(obj, iteratee, context) {
	    var key;

	    context = context || null;

	    for (key in obj) {
	        if (obj.hasOwnProperty(key)) {
	            if (iteratee.call(context, obj[key], key, obj) === false) {
	                break;
	            }
	        }
	    }
	}

	/**
	 * Execute the provided callback once for each property of object(or element of array) which actually exist.<br>
	 * If the object is Array-like object(ex-arguments object), It needs to transform to Array.(see 'ex2' of example).<br>
	 * If the callback function returns false, the loop will be stopped.<br>
	 * Callback function(iteratee) is invoked with three arguments:
	 *  - The value of the property(or The value of the element)
	 *  - The name of the property(or The index of the element)
	 *  - The object being traversed
	 * @param {Object} obj The object that will be traversed
	 * @param {function} iteratee Callback function
	 * @param {Object} [context] Context(this) of callback function
	 * @memberof tui.util
	 * @example
	 * //-- #1. Get Module --//
	 * var util = require('tui-code-snippet'); // node, commonjs
	 * var util = tui.util; // distribution file
	 *
	 * //-- #2. Use property --//
	 * var sum = 0;
	 *
	 * util.forEach([1,2,3], function(value){
	 *     sum += value;
	 * });
	 * alert(sum); // 6
	 *
	 * // In case of Array-like object
	 * var array = Array.prototype.slice.call(arrayLike); // change to array
	 * util.forEach(array, function(value){
	 *     sum += value;
	 * });
	 */
	function forEach(obj, iteratee, context) {
	    if (type.isArray(obj)) {
	        forEachArray(obj, iteratee, context);
	    } else {
	        forEachOwnProperties(obj, iteratee, context);
	    }
	}

	/**
	 * Execute the provided callback function once for each element in an array, in order,
	 * and constructs a new array from the results.<br>
	 * If the object is Array-like object(ex-arguments object),
	 * It needs to transform to Array.(see 'ex2' of forEach example)<br>
	 * Callback function(iteratee) is invoked with three arguments:
	 *  - The value of the property(or The value of the element)
	 *  - The name of the property(or The index of the element)
	 *  - The object being traversed
	 * @param {Object} obj The object that will be traversed
	 * @param {function} iteratee Callback function
	 * @param {Object} [context] Context(this) of callback function
	 * @returns {Array} A new array composed of returned values from callback function
	 * @memberof tui.util
	 * @example
	 * //-- #1. Get Module --//
	 * var util = require('tui-code-snippet'); // node, commonjs
	 * var util = tui.util; // distribution file
	 *
	 * //-- #2. Use property --//
	 * var result = util.map([0,1,2,3], function(value) {
	 *     return value + 1;
	 * });
	 *
	 * alert(result);  // 1,2,3,4
	 */
	function map(obj, iteratee, context) {
	    var resultArray = [];

	    context = context || null;

	    forEach(obj, function() {
	        resultArray.push(iteratee.apply(context, arguments));
	    });

	    return resultArray;
	}

	/**
	 * Execute the callback function once for each element present in the array(or Array-like object or plain object).<br>
	 * If the object is Array-like object(ex-arguments object),
	 * It needs to transform to Array.(see 'ex2' of forEach example)<br>
	 * Callback function(iteratee) is invoked with four arguments:
	 *  - The previousValue
	 *  - The currentValue
	 *  - The index
	 *  - The object being traversed
	 * @param {Object} obj The object that will be traversed
	 * @param {function} iteratee Callback function
	 * @param {Object} [context] Context(this) of callback function
	 * @returns {*} The result value
	 * @memberof tui.util
	 * @example
	 * //-- #1. Get Module --//
	 * var util = require('tui-code-snippet'); // node, commonjs
	 * var util = tui.util; // distribution file
	 *
	 * //-- #2. Use property --//
	 * var result = util.reduce([0,1,2,3], function(stored, value) {
	 *     return stored + value;
	 * });
	 *
	 * alert(result); // 6
	 */
	function reduce(obj, iteratee, context) {
	    var index = 0;
	    var keys, length, store;

	    context = context || null;

	    if (!type.isArray(obj)) {
	        keys = object.keys(obj);
	        length = keys.length;
	        store = obj[keys[index += 1]];
	    } else {
	        length = obj.length;
	        store = obj[index];
	    }

	    index += 1;
	    for (; index < length; index += 1) {
	        store = iteratee.call(context, store, obj[keys ? keys[index] : index]);
	    }

	    return store;
	}

	/**
	 * Transform the Array-like object to Array.<br>
	 * In low IE (below 8), Array.prototype.slice.call is not perfect. So, try-catch statement is used.
	 * @param {*} arrayLike Array-like object
	 * @returns {Array} Array
	 * @memberof tui.util
	 * @example
	 * //-- #1. Get Module --//
	 * var util = require('tui-code-snippet'); // node, commonjs
	 * var util = tui.util; // distribution file
	 *
	 * //-- #2. Use property --//
	 * var arrayLike = {
	 *     0: 'one',
	 *     1: 'two',
	 *     2: 'three',
	 *     3: 'four',
	 *     length: 4
	 * };
	 * var result = util.toArray(arrayLike);
	 *
	 * alert(result instanceof Array); // true
	 * alert(result); // one,two,three,four
	 */
	function toArray(arrayLike) {
	    var arr;
	    try {
	        arr = Array.prototype.slice.call(arrayLike);
	    } catch (e) {
	        arr = [];
	        forEachArray(arrayLike, function(value) {
	            arr.push(value);
	        });
	    }

	    return arr;
	}

	/**
	 * Create a new array or plain object with all elements(or properties)
	 * that pass the test implemented by the provided function.<br>
	 * Callback function(iteratee) is invoked with three arguments:
	 *  - The value of the property(or The value of the element)
	 *  - The name of the property(or The index of the element)
	 *  - The object being traversed
	 * @param {Object} obj Object(plain object or Array) that will be traversed
	 * @param {function} iteratee Callback function
	 * @param {Object} [context] Context(this) of callback function
	 * @returns {Object} plain object or Array
	 * @memberof tui.util
	 * @example
	  * //-- #1. Get Module --//
	 * var util = require('tui-code-snippet'); // node, commonjs
	 * var util = tui.util; // distribution file
	 *
	 * //-- #2. Use property --//
	 * var result1 = util.filter([0,1,2,3], function(value) {
	 *     return (value % 2 === 0);
	 * });
	 * alert(result1); // [0, 2]
	 *
	 * var result2 = util.filter({a : 1, b: 2, c: 3}, function(value) {
	 *     return (value % 2 !== 0);
	 * });
	 * alert(result2.a); // 1
	 * alert(result2.b); // undefined
	 * alert(result2.c); // 3
	 */
	function filter(obj, iteratee, context) {
	    var result, add;

	    context = context || null;

	    if (!type.isObject(obj) || !type.isFunction(iteratee)) {
	        throw new Error('wrong parameter');
	    }

	    if (type.isArray(obj)) {
	        result = [];
	        add = function(subResult, args) {
	            subResult.push(args[0]);
	        };
	    } else {
	        result = {};
	        add = function(subResult, args) {
	            subResult[args[1]] = args[0];
	        };
	    }

	    forEach(obj, function() {
	        if (iteratee.apply(context, arguments)) {
	            add(result, arguments);
	        }
	    }, context);

	    return result;
	}

	/**
	 * fetching a property
	 * @param {Array} arr target collection
	 * @param {String|Number} property property name
	 * @returns {Array}
	 * @memberof tui.util
	 * @example
	 * //-- #1. Get Module --//
	 * var util = require('tui-code-snippet'); // node, commonjs
	 * var util = tui.util; // distribution file
	 *
	 * //-- #2. Use property --//
	 * var objArr = [
	 *     {'abc': 1, 'def': 2, 'ghi': 3},
	 *     {'abc': 4, 'def': 5, 'ghi': 6},
	 *     {'abc': 7, 'def': 8, 'ghi': 9}
	 * ];
	 * var arr2d = [
	 *     [1, 2, 3],
	 *     [4, 5, 6],
	 *     [7, 8, 9]
	 * ];
	 * util.pluck(objArr, 'abc'); // [1, 4, 7]
	 * util.pluck(arr2d, 2); // [3, 6, 9]
	 */
	function pluck(arr, property) {
	    var result = map(arr, function(item) {
	        return item[property];
	    });

	    return result;
	}

	module.exports = {
	    forEachOwnProperties: forEachOwnProperties,
	    forEachArray: forEachArray,
	    forEach: forEach,
	    toArray: toArray,
	    map: map,
	    reduce: reduce,
	    filter: filter,
	    pluck: pluck
	};


/***/ }),
/* 5 */
/***/ (function(module, exports) {

	/**
	 * @fileoverview This module provides a bind() function for context binding.
	 * @author NHN.
	 *         FE Development Lab <dl_javascript@nhn.com>
	 */

	'use strict';

	/**
	 * Create a new function that, when called, has its this keyword set to the provided value.
	 * @param {function} fn A original function before binding
	 * @param {*} obj context of function in arguments[0]
	 * @returns {function()} A new bound function with context that is in arguments[1]
	 * @memberof tui.util
	 */
	function bind(fn, obj) {
	    var slice = Array.prototype.slice;
	    var args;

	    if (fn.bind) {
	        return fn.bind.apply(fn, slice.call(arguments, 1));
	    }

	    /* istanbul ignore next */
	    args = slice.call(arguments, 2);

	    /* istanbul ignore next */
	    return function() {
	        /* istanbul ignore next */
	        return fn.apply(obj, args.length ? args.concat(slice.call(arguments)) : arguments);
	    };
	}

	module.exports = {
	    bind: bind
	};


/***/ }),
/* 6 */
/***/ (function(module, exports) {

	/**
	 * @fileoverview This module provides some simple function for inheritance.
	 * @author NHN.
	 *         FE Development Lab <dl_javascript@nhn.com>
	 */

	'use strict';

	/**
	 * Create a new object with the specified prototype object and properties.
	 * @param {Object} obj This object will be a prototype of the newly-created object.
	 * @returns {Object}
	 * @memberof tui.util
	 */
	function createObject(obj) {
	    function F() {} // eslint-disable-line require-jsdoc
	    F.prototype = obj;

	    return new F();
	}

	/**
	 * Provide a simple inheritance in prototype-oriented.<br>
	 * Caution :
	 *  Don't overwrite the prototype of child constructor.
	 *
	 * @param {function} subType Child constructor
	 * @param {function} superType Parent constructor
	 * @memberof tui.util
	 * @example
	 * //-- #1. Get Module --//
	 * var util = require('tui-code-snippet'); // node, commonjs
	 * var util = tui.util; // distribution file
	 *
	 * //-- #2. Use property --//
	 * // Parent constructor
	 * function Animal(leg) {
	 *     this.leg = leg;
	 * }
	 * Animal.prototype.growl = function() {
	 *     // ...
	 * };
	 *
	 * // Child constructor
	 * function Person(name) {
	 *     this.name = name;
	 * }
	 *
	 * // Inheritance
	 * util.inherit(Person, Animal);
	 *
	 * // After this inheritance, please use only the extending of property.
	 * // Do not overwrite prototype.
	 * Person.prototype.walk = function(direction) {
	 *     // ...
	 * };
	 */
	function inherit(subType, superType) {
	    var prototype = createObject(superType.prototype);
	    prototype.constructor = subType;
	    subType.prototype = prototype;
	}

	module.exports = {
	    createObject: createObject,
	    inherit: inherit
	};


/***/ }),
/* 7 */
/***/ (function(module, exports, __webpack_require__) {

	/**
	 * @fileoverview This module has some functions for handling the string.
	 * @author NHN.
	 *         FE Development Lab <dl_javascript@nhn.com>
	 */

	'use strict';

	var collection = __webpack_require__(4);
	var object = __webpack_require__(1);
	/**
	 * Transform the given HTML Entity string into plain string
	 * @param {String} htmlEntity - HTML Entity type string
	 * @returns {String} Plain string
	 * @memberof tui.util
	 * @example
	 * //-- #1. Get Module --//
	 * var util = require('tui-code-snippet'); // node, commonjs
	 * var util = tui.util; // distribution file
	 *
	 * //-- #2. Use property --//
	 *  var htmlEntityString = "A &#39;quote&#39; is &lt;b&gt;bold&lt;/b&gt;"
	 *  var result = util.decodeHTMLEntity(htmlEntityString); //"A 'quote' is <b>bold</b>"
	 */
	function decodeHTMLEntity(htmlEntity) {
	    var entities = {
	        '&quot;': '"',
	        '&amp;': '&',
	        '&lt;': '<',
	        '&gt;': '>',
	        '&#39;': '\'',
	        '&nbsp;': ' '
	    };

	    return htmlEntity.replace(/&amp;|&lt;|&gt;|&quot;|&#39;|&nbsp;/g, function(m0) {
	        return entities[m0] ? entities[m0] : m0;
	    });
	}

	/**
	 * Transform the given string into HTML Entity string
	 * @param {String} html - String for encoding
	 * @returns {String} HTML Entity
	 * @memberof tui.util
	 * @example
	 * //-- #1. Get Module --//
	 * var util = require('tui-code-snippet'); // node, commonjs
	 * var util = tui.util; // distribution file
	 *
	 * //-- #2. Use property --//
	 *  var htmlEntityString = "<script> alert('test');</script><a href='test'>";
	 *  var result = util.encodeHTMLEntity(htmlEntityString);
	 * //"&lt;script&gt; alert(&#39;test&#39;);&lt;/script&gt;&lt;a href=&#39;test&#39;&gt;"
	 */
	function encodeHTMLEntity(html) {
	    var entities = {
	        '"': 'quot',
	        '&': 'amp',
	        '<': 'lt',
	        '>': 'gt',
	        '\'': '#39'
	    };

	    return html.replace(/[<>&"']/g, function(m0) {
	        return entities[m0] ? '&' + entities[m0] + ';' : m0;
	    });
	}

	/**
	 * Return whether the string capable to transform into plain string is in the given string or not.
	 * @param {String} string - test string
	 * @memberof tui.util
	 * @returns {boolean}
	 */
	function hasEncodableString(string) {
	    return (/[<>&"']/).test(string);
	}

	/**
	 * Return duplicate charters
	 * @param {string} operandStr1 The operand string
	 * @param {string} operandStr2 The operand string
	 * @private
	 * @memberof tui.util
	 * @returns {string}
	 * @example
	 * //-- #1. Get Module --//
	 * var util = require('tui-code-snippet'); // node, commonjs
	 * var util = tui.util; // distribution file
	 *
	 * //-- #2. Use property --//
	 * util.getDuplicatedChar('fe dev', 'nhn entertainment'); // 'e'
	 * util.getDuplicatedChar('fdsa', 'asdf'); // 'asdf'
	 */
	function getDuplicatedChar(operandStr1, operandStr2) {
	    var i = 0;
	    var len = operandStr1.length;
	    var pool = {};
	    var dupl, key;

	    for (; i < len; i += 1) {
	        key = operandStr1.charAt(i);
	        pool[key] = 1;
	    }

	    for (i = 0, len = operandStr2.length; i < len; i += 1) {
	        key = operandStr2.charAt(i);
	        if (pool[key]) {
	            pool[key] += 1;
	        }
	    }

	    pool = collection.filter(pool, function(item) {
	        return item > 1;
	    });

	    pool = object.keys(pool).sort();
	    dupl = pool.join('');

	    return dupl;
	}

	module.exports = {
	    decodeHTMLEntity: decodeHTMLEntity,
	    encodeHTMLEntity: encodeHTMLEntity,
	    hasEncodableString: hasEncodableString,
	    getDuplicatedChar: getDuplicatedChar
	};


/***/ }),
/* 8 */
/***/ (function(module, exports) {

	/**
	 * @fileoverview collections of some technic methods.
	 * @author NHN.
	 *         FE Development Lab <dl_javascript.nhn.com>
	 */

	'use strict';

	var tricks = {};
	var aps = Array.prototype.slice;

	/**
	 * Creates a debounced function that delays invoking fn until after delay milliseconds has elapsed
	 * since the last time the debouced function was invoked.
	 * @param {function} fn The function to debounce.
	 * @param {number} [delay=0] The number of milliseconds to delay
	 * @memberof tui.util
	 * @returns {function} debounced function.
	 * @example
	 * //-- #1. Get Module --//
	 * var util = require('tui-code-snippet'); // node, commonjs
	 * var util = tui.util; // distribution file
	 *
	 * //-- #2. Use property --//
	 * function someMethodToInvokeDebounced() {}
	 *
	 * var debounced = util.debounce(someMethodToInvokeDebounced, 300);
	 *
	 * // invoke repeatedly
	 * debounced();
	 * debounced();
	 * debounced();
	 * debounced();
	 * debounced();
	 * debounced();    // last invoke of debounced()
	 *
	 * // invoke someMethodToInvokeDebounced() after 300 milliseconds.
	 */
	function debounce(fn, delay) {
	    var timer, args;

	    /* istanbul ignore next */
	    delay = delay || 0;

	    function debounced() { // eslint-disable-line require-jsdoc
	        args = aps.call(arguments);

	        window.clearTimeout(timer);
	        timer = window.setTimeout(function() {
	            fn.apply(null, args);
	        }, delay);
	    }

	    return debounced;
	}

	/**
	 * return timestamp
	 * @memberof tui.util
	 * @returns {number} The number of milliseconds from Jan. 1970 00:00:00 (GMT)
	 */
	function timestamp() {
	    return Number(new Date());
	}

	/**
	 * Creates a throttled function that only invokes fn at most once per every interval milliseconds.
	 *
	 * You can use this throttle short time repeatedly invoking functions. (e.g MouseMove, Resize ...)
	 *
	 * if you need reuse throttled method. you must remove slugs (e.g. flag variable) related with throttling.
	 * @param {function} fn function to throttle
	 * @param {number} [interval=0] the number of milliseconds to throttle invocations to.
	 * @memberof tui.util
	 * @returns {function} throttled function
	 * @example
	 * //-- #1. Get Module --//
	 * var util = require('tui-code-snippet'); // node, commonjs
	 * var util = tui.util; // distribution file
	 *
	 * //-- #2. Use property --//
	 * function someMethodToInvokeThrottled() {}
	 *
	 * var throttled = util.throttle(someMethodToInvokeThrottled, 300);
	 *
	 * // invoke repeatedly
	 * throttled();    // invoke (leading)
	 * throttled();
	 * throttled();    // invoke (near 300 milliseconds)
	 * throttled();
	 * throttled();
	 * throttled();    // invoke (near 600 milliseconds)
	 * // ...
	 * // invoke (trailing)
	 *
	 * // if you need reuse throttled method. then invoke reset()
	 * throttled.reset();
	 */
	function throttle(fn, interval) {
	    var base;
	    var isLeading = true;
	    var tick = function(_args) {
	        fn.apply(null, _args);
	        base = null;
	    };
	    var debounced, stamp, args;

	    /* istanbul ignore next */
	    interval = interval || 0;

	    debounced = tricks.debounce(tick, interval);

	    function throttled() { // eslint-disable-line require-jsdoc
	        args = aps.call(arguments);

	        if (isLeading) {
	            tick(args);
	            isLeading = false;

	            return;
	        }

	        stamp = tricks.timestamp();

	        base = base || stamp;

	        // pass array directly because `debounce()`, `tick()` are already use
	        // `apply()` method to invoke developer's `fn` handler.
	        //
	        // also, this `debounced` line invoked every time for implements
	        // `trailing` features.
	        debounced(args);

	        if ((stamp - base) >= interval) {
	            tick(args);
	        }
	    }

	    function reset() { // eslint-disable-line require-jsdoc
	        isLeading = true;
	        base = null;
	    }

	    throttled.reset = reset;

	    return throttled;
	}

	tricks.timestamp = timestamp;
	tricks.debounce = debounce;
	tricks.throttle = throttle;

	module.exports = tricks;


/***/ }),
/* 9 */
/***/ (function(module, exports, __webpack_require__) {

	/**
	 * @fileoverview This module has some functions for handling object as collection.
	 * @author NHN.
	 *         FE Development Lab <dl_javascript@nhn.com>
	 */
	'use strict';

	var object = __webpack_require__(1);
	var collection = __webpack_require__(4);
	var type = __webpack_require__(2);
	var ms7days = 7 * 24 * 60 * 60 * 1000;

	/**
	 * Check if the date has passed 7 days
	 * @param {number} date - milliseconds
	 * @returns {boolean}
	 * @ignore
	 */
	function isExpired(date) {
	    var now = new Date().getTime();

	    return now - date > ms7days;
	}

	/**
	 * Send hostname on DOMContentLoaded.
	 * To prevent hostname set tui.usageStatistics to false.
	 * @param {string} appName - application name
	 * @param {string} trackingId - GA tracking ID
	 * @ignore
	 */
	function sendHostname(appName, trackingId) {
	    var url = 'https://www.google-analytics.com/collect';
	    var hostname = location.hostname;
	    var hitType = 'event';
	    var eventCategory = 'use';
	    var applicationKeyForStorage = 'TOAST UI ' + appName + ' for ' + hostname + ': Statistics';
	    var date = window.localStorage.getItem(applicationKeyForStorage);

	    // skip if the flag is defined and is set to false explicitly
	    if (!type.isUndefined(window.tui) && window.tui.usageStatistics === false) {
	        return;
	    }

	    // skip if not pass seven days old
	    if (date && !isExpired(date)) {
	        return;
	    }

	    window.localStorage.setItem(applicationKeyForStorage, new Date().getTime());

	    setTimeout(function() {
	        if (document.readyState === 'interactive' || document.readyState === 'complete') {
	            imagePing(url, {
	                v: 1,
	                t: hitType,
	                tid: trackingId,
	                cid: hostname,
	                dp: hostname,
	                dh: appName,
	                el: appName,
	                ec: eventCategory
	            });
	        }
	    }, 1000);
	}

	/**
	 * Request image ping.
	 * @param {String} url url for ping request
	 * @param {Object} trackingInfo infos for make query string
	 * @returns {HTMLElement}
	 * @memberof tui.util
	 * @example
	 * //-- #1. Get Module --//
	 * var util = require('tui-code-snippet'); // node, commonjs
	 * var util = tui.util; // distribution file
	 *
	 * //-- #2. Use property --//
	 * util.imagePing('https://www.google-analytics.com/collect', {
	 *     v: 1,
	 *     t: 'event',
	 *     tid: 'trackingid',
	 *     cid: 'cid',
	 *     dp: 'dp',
	 *     dh: 'dh'
	 * });
	 */
	function imagePing(url, trackingInfo) {
	    var queryString = collection.map(object.keys(trackingInfo), function(key, index) {
	        var startWith = index === 0 ? '' : '&';

	        return startWith + key + '=' + trackingInfo[key];
	    }).join('');
	    var trackingElement = document.createElement('img');

	    trackingElement.src = url + '?' + queryString;

	    trackingElement.style.display = 'none';
	    document.body.appendChild(trackingElement);
	    document.body.removeChild(trackingElement);

	    return trackingElement;
	}

	module.exports = {
	    imagePing: imagePing,
	    sendHostname: sendHostname
	};


/***/ }),
/* 10 */
/***/ (function(module, exports) {

	/**
	 * @fileoverview This module detects the kind of well-known browser and version.
	 * @author NHN.
	 *         FE Development Lab <dl_javascript@nhn.com>
	 */

	'use strict';

	/**
	 * This object has an information that indicate the kind of browser.<br>
	 * The list below is a detectable browser list.
	 *  - ie8 ~ ie11
	 *  - chrome
	 *  - firefox
	 *  - safari
	 *  - edge
	 * @memberof tui.util
	 * @example
	 * //-- #1. Get Module --//
	 * var util = require('tui-code-snippet'); // node, commonjs
	 * var util = tui.util; // distribution file
	 *
	 * //-- #2. Use property --//
	 * util.browser.chrome === true; // chrome
	 * util.browser.firefox === true; // firefox
	 * util.browser.safari === true; // safari
	 * util.browser.msie === true; // IE
	 * util.browser.edge === true; // edge
	 * util.browser.others === true; // other browser
	 * util.browser.version; // browser version
	 */
	var browser = {
	    chrome: false,
	    firefox: false,
	    safari: false,
	    msie: false,
	    edge: false,
	    others: false,
	    version: 0
	};

	if (window && window.navigator) {
	    detectBrowser();
	}

	/**
	 * Detect the browser.
	 * @private
	 */
	function detectBrowser() {
	    var nav = window.navigator;
	    var appName = nav.appName.replace(/\s/g, '_');
	    var userAgent = nav.userAgent;

	    var rIE = /MSIE\s([0-9]+[.0-9]*)/;
	    var rIE11 = /Trident.*rv:11\./;
	    var rEdge = /Edge\/(\d+)\./;
	    var versionRegex = {
	        firefox: /Firefox\/(\d+)\./,
	        chrome: /Chrome\/(\d+)\./,
	        safari: /Version\/([\d.]+).*Safari\/(\d+)/
	    };

	    var key, tmp;

	    var detector = {
	        Microsoft_Internet_Explorer: function() { // eslint-disable-line camelcase
	            var detectedVersion = userAgent.match(rIE);

	            if (detectedVersion) { // ie8 ~ ie10
	                browser.msie = true;
	                browser.version = parseFloat(detectedVersion[1]);
	            } else { // no version information
	                browser.others = true;
	            }
	        },
	        Netscape: function() { // eslint-disable-line complexity
	            var detected = false;

	            if (rIE11.exec(userAgent)) {
	                browser.msie = true;
	                browser.version = 11;
	                detected = true;
	            } else if (rEdge.exec(userAgent)) {
	                browser.edge = true;
	                browser.version = userAgent.match(rEdge)[1];
	                detected = true;
	            } else {
	                for (key in versionRegex) {
	                    if (versionRegex.hasOwnProperty(key)) {
	                        tmp = userAgent.match(versionRegex[key]);
	                        if (tmp && tmp.length > 1) { // eslint-disable-line max-depth
	                            browser[key] = detected = true;
	                            browser.version = parseFloat(tmp[1] || 0);
	                            break;
	                        }
	                    }
	                }
	            }
	            if (!detected) {
	                browser.others = true;
	            }
	        }
	    };

	    var fn = detector[appName];

	    if (fn) {
	        detector[appName]();
	    }
	}

	module.exports = browser;


/***/ }),
/* 11 */
/***/ (function(module, exports, __webpack_require__) {

	/**
	 * @fileoverview This module has some methods for handling popup-window
	 * @author NHN.
	 *         FE Development Lab <dl_javascript@nhn.com>
	 */

	'use strict';

	var collection = __webpack_require__(4);
	var type = __webpack_require__(2);
	var func = __webpack_require__(5);
	var browser = __webpack_require__(10);
	var object = __webpack_require__(1);

	var popupId = 0;

	/**
	 * Popup management class
	 * @constructor
	 * @memberof tui.util
	 * @example
	 * // node, commonjs
	 * var popup = require('tui-code-snippet').popup;
	 * @example
	 * // distribution file, script
	 * <script src='path-to/tui-code-snippt.js'></script>
	 * <script>
	 * var popup = tui.util.popup;
	 * <script>
	 */
	function Popup() {
	    /**
	     * Caching the window-contexts of opened popups
	     * @type {Object}
	     */
	    this.openedPopup = {};

	    /**
	     * In IE7, an error occurs when the closeWithParent property attaches to window object.<br>
	     * So, It is for saving the value of closeWithParent instead of attaching to window object.
	     * @type {Object}
	     */
	    this.closeWithParentPopup = {};

	    /**
	     * Post data bridge for IE11 popup
	     * @type {string}
	     */
	    this.postBridgeUrl = '';
	}

	/**********
	 * public methods
	 **********/

	/**
	 * Returns a popup-list administered by current window.
	 * @param {string} [key] The key of popup.
	 * @returns {Object} popup window list object
	 */
	Popup.prototype.getPopupList = function(key) {
	    var target;
	    if (type.isExisty(key)) {
	        target = this.openedPopup[key];
	    } else {
	        target = this.openedPopup;
	    }

	    return target;
	};

	/**
	 * Open popup
	 * Caution:
	 *  In IE11, when transfer data to popup by POST, must set the postBridgeUrl.
	 *
	 * @param {string} url - popup url
	 * @param {Object} options - popup options
	 *     @param {string} [options.popupName] - Key of popup window.<br>
	 *      If the key is set, when you try to open by this key, the popup of this key is focused.<br>
	 *      Or else a new popup window having this key is opened.
	 *
	 *     @param {string} [options.popupOptionStr=""] - Option string of popup window<br>
	 *      It is same with the third parameter of window.open() method.<br>
	 *      See {@link http://www.w3schools.com/jsref/met_win_open.asp}
	 *
	 *     @param {boolean} [options.closeWithParent=true] - Is closed when parent window closed?
	 *
	 *     @param {boolean} [options.useReload=false] - This property indicates whether reload the popup or not.<br>
	 *      If true, the popup will be reloaded when you try to re-open the popup that has been opened.<br>
	 *      When transmit the POST-data, some browsers alert a message for confirming whether retransmit or not.
	 *
	 *     @param {string} [options.postBridgeUrl='']
	 *      Use this url to avoid a certain bug occuring when transmitting POST data to the popup in IE11.<br>
	 *      This specific buggy situation is known to happen because IE11 tries to open the requested url<br>
	 *      not in a new popup window as intended, but in a new tab.<br>
	 *      See {@link http://wiki.nhnent.com/pages/viewpage.action?pageId=240562844}
	 *
	 *     @param {string} [options.method=get]
	 *     The method of transmission when the form-data is transmitted to popup-window.
	 *
	 *     @param {Object} [options.param=null]
	 *     Using as parameters for transmission when the form-data is transmitted to popup-window.
	 */
	Popup.prototype.openPopup = function(url, options) { // eslint-disable-line complexity
	    var popup, formElement, useIEPostBridge;

	    options = object.extend({
	        popupName: 'popup_' + popupId + '_' + Number(new Date()),
	        popupOptionStr: '',
	        useReload: true,
	        closeWithParent: true,
	        method: 'get',
	        param: {}
	    }, options || {});

	    options.method = options.method.toUpperCase();

	    this.postBridgeUrl = options.postBridgeUrl || this.postBridgeUrl;

	    useIEPostBridge = options.method === 'POST' && options.param &&
	            browser.msie && browser.version === 11;

	    if (!type.isExisty(url)) {
	        throw new Error('Popup#open() need popup url.');
	    }

	    popupId += 1;

	    /*
	     * In form-data transmission
	     * 1. Create a form before opening a popup.
	     * 2. Transmit the form-data.
	     * 3. Remove the form after transmission.
	     */
	    if (options.param) {
	        if (options.method === 'GET') {
	            url = url + (/\?/.test(url) ? '&' : '?') + this._parameterize(options.param);
	        } else if (options.method === 'POST') {
	            if (!useIEPostBridge) {
	                formElement = this.createForm(url, options.param, options.method, options.popupName);
	                url = 'about:blank';
	            }
	        }
	    }

	    popup = this.openedPopup[options.popupName];

	    if (!type.isExisty(popup)) {
	        this.openedPopup[options.popupName] = popup = this._open(useIEPostBridge, options.param,
	            url, options.popupName, options.popupOptionStr);
	    } else if (popup.closed) {
	        this.openedPopup[options.popupName] = popup = this._open(useIEPostBridge, options.param,
	            url, options.popupName, options.popupOptionStr);
	    } else {
	        if (options.useReload) {
	            popup.location.replace(url);
	        }
	        popup.focus();
	    }

	    this.closeWithParentPopup[options.popupName] = options.closeWithParent;

	    if (!popup || popup.closed || type.isUndefined(popup.closed)) {
	        alert('please enable popup windows for this website');
	    }

	    if (options.param && options.method === 'POST' && !useIEPostBridge) {
	        if (popup) {
	            formElement.submit();
	        }
	        if (formElement.parentNode) {
	            formElement.parentNode.removeChild(formElement);
	        }
	    }

	    window.onunload = func.bind(this.closeAllPopup, this);
	};

	/**
	 * Close the popup
	 * @param {boolean} [skipBeforeUnload] - If true, the 'window.onunload' will be null and skip unload event.
	 * @param {Window} [popup] - Window-context of popup for closing. If omit this, current window-context will be closed.
	 */
	Popup.prototype.close = function(skipBeforeUnload, popup) {
	    var target = popup || window;
	    skipBeforeUnload = type.isExisty(skipBeforeUnload) ? skipBeforeUnload : false;

	    if (skipBeforeUnload) {
	        window.onunload = null;
	    }

	    if (!target.closed) {
	        target.opener = window.location.href;
	        target.close();
	    }
	};

	/**
	 * Close all the popups in current window.
	 * @param {boolean} closeWithParent - If true, popups having the closeWithParentPopup property as true will be closed.
	 */
	Popup.prototype.closeAllPopup = function(closeWithParent) {
	    var hasArg = type.isExisty(closeWithParent);

	    collection.forEachOwnProperties(this.openedPopup, function(popup, key) {
	        if ((hasArg && this.closeWithParentPopup[key]) || !hasArg) {
	            this.close(false, popup);
	        }
	    }, this);
	};

	/**
	 * Activate(or focus) the popup of the given name.
	 * @param {string} popupName - Name of popup for activation
	 */
	Popup.prototype.focus = function(popupName) {
	    this.getPopupList(popupName).focus();
	};

	/**
	 * Return an object made of parsing the query string.
	 * @returns {Object} An object having some information of the query string.
	 * @private
	 */
	Popup.prototype.parseQuery = function() {
	    var param = {};
	    var search, pair;

	    search = window.location.search.substr(1);
	    collection.forEachArray(search.split('&'), function(part) {
	        pair = part.split('=');
	        param[decodeURIComponent(pair[0])] = decodeURIComponent(pair[1]);
	    });

	    return param;
	};

	/**
	 * Create a hidden form from the given arguments and return this form.
	 * @param {string} action - URL for form transmission
	 * @param {Object} [data] - Data for form transmission
	 * @param {string} [method] - Method of transmission
	 * @param {string} [target] - Target of transmission
	 * @param {HTMLElement} [container] - Container element of form.
	 * @returns {HTMLElement} Form element
	 */
	Popup.prototype.createForm = function(action, data, method, target, container) {
	    var form = document.createElement('form'),
	        input;

	    container = container || document.body;

	    form.method = method || 'POST';
	    form.action = action || '';
	    form.target = target || '';
	    form.style.display = 'none';

	    collection.forEachOwnProperties(data, function(value, key) {
	        input = document.createElement('input');
	        input.name = key;
	        input.type = 'hidden';
	        input.value = value;
	        form.appendChild(input);
	    });

	    container.appendChild(form);

	    return form;
	};

	/**********
	 * private methods
	 **********/

	/**
	 * Return an query string made by parsing the given object
	 * @param {Object} obj - An object that has information for query string
	 * @returns {string} - Query string
	 * @private
	 */
	Popup.prototype._parameterize = function(obj) {
	    var query = [];

	    collection.forEachOwnProperties(obj, function(value, key) {
	        query.push(encodeURIComponent(key) + '=' + encodeURIComponent(value));
	    });

	    return query.join('&');
	};

	/**
	 * Open popup
	 * @param {boolean} useIEPostBridge - A switch option whether to use alternative
	 *                                  of tossing POST data to the popup window in IE11
	 * @param {Object} param - A data for tossing to popup
	 * @param {string} url - Popup url
	 * @param {string} popupName - Popup name
	 * @param {string} optionStr - Setting for popup, ex) 'width=640,height=320,scrollbars=yes'
	 * @returns {Window} Window context of popup
	 * @private
	 */
	Popup.prototype._open = function(useIEPostBridge, param, url, popupName, optionStr) {
	    var popup;

	    if (useIEPostBridge) {
	        popup = window.open(this.postBridgeUrl, popupName, optionStr);
	        setTimeout(function() {
	            popup.redirect(url, param);
	        }, 100);
	    } else {
	        popup = window.open(url, popupName, optionStr);
	    }

	    return popup;
	};

	module.exports = new Popup();


/***/ }),
/* 12 */
/***/ (function(module, exports, __webpack_require__) {

	/**
	 * @fileoverview This module has a function for date format.
	 * @author NHN.
	 *         FE Development Lab <dl_javascript@nhn.com>
	 */

	'use strict';

	var type = __webpack_require__(2);
	var object = __webpack_require__(1);

	var tokens = /[\\]*YYYY|[\\]*YY|[\\]*MMMM|[\\]*MMM|[\\]*MM|[\\]*M|[\\]*DD|[\\]*D|[\\]*HH|[\\]*H|[\\]*A/gi;
	var MONTH_STR = [
	    'Invalid month', 'January', 'February', 'March', 'April', 'May',
	    'June', 'July', 'August', 'September', 'October', 'November', 'December'
	];
	var MONTH_DAYS = [0, 31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];
	var replaceMap = {
	    M: function(date) {
	        return Number(date.month);
	    },
	    MM: function(date) {
	        var month = date.month;

	        return (Number(month) < 10) ? '0' + month : month;
	    },
	    MMM: function(date) {
	        return MONTH_STR[Number(date.month)].substr(0, 3);
	    },
	    MMMM: function(date) {
	        return MONTH_STR[Number(date.month)];
	    },
	    D: function(date) {
	        return Number(date.date);
	    },
	    d: function(date) {
	        return replaceMap.D(date); // eslint-disable-line new-cap
	    },
	    DD: function(date) {
	        var dayInMonth = date.date;

	        return (Number(dayInMonth) < 10) ? '0' + dayInMonth : dayInMonth;
	    },
	    dd: function(date) {
	        return replaceMap.DD(date); // eslint-disable-line new-cap
	    },
	    YY: function(date) {
	        return Number(date.year) % 100;
	    },
	    yy: function(date) {
	        return replaceMap.YY(date); // eslint-disable-line new-cap
	    },
	    YYYY: function(date) {
	        var prefix = '20',
	            year = date.year;
	        if (year > 69 && year < 100) {
	            prefix = '19';
	        }

	        return (Number(year) < 100) ? prefix + String(year) : year;
	    },
	    yyyy: function(date) {
	        return replaceMap.YYYY(date); // eslint-disable-line new-cap
	    },
	    A: function(date) {
	        return date.meridiem;
	    },
	    a: function(date) {
	        return date.meridiem;
	    },
	    hh: function(date) {
	        var hour = date.hour;

	        return (Number(hour) < 10) ? '0' + hour : hour;
	    },
	    HH: function(date) {
	        return replaceMap.hh(date);
	    },
	    h: function(date) {
	        return String(Number(date.hour));
	    },
	    H: function(date) {
	        return replaceMap.h(date);
	    },
	    m: function(date) {
	        return String(Number(date.minute));
	    },
	    mm: function(date) {
	        var minute = date.minute;

	        return (Number(minute) < 10) ? '0' + minute : minute;
	    }
	};

	/**
	 * Check whether the given variables are valid date or not.
	 * @param {number} year - Year
	 * @param {number} month - Month
	 * @param {number} date - Day in month.
	 * @returns {boolean} Is valid?
	 * @private
	 */
	function isValidDate(year, month, date) { // eslint-disable-line complexity
	    var isValidYear, isValidMonth, isValid, lastDayInMonth;

	    year = Number(year);
	    month = Number(month);
	    date = Number(date);

	    isValidYear = (year > -1 && year < 100) || ((year > 1969) && (year < 2070));
	    isValidMonth = (month > 0) && (month < 13);

	    if (!isValidYear || !isValidMonth) {
	        return false;
	    }

	    lastDayInMonth = MONTH_DAYS[month];
	    if (month === 2 && year % 4 === 0) {
	        if (year % 100 !== 0 || year % 400 === 0) {
	            lastDayInMonth = 29;
	        }
	    }

	    isValid = (date > 0) && (date <= lastDayInMonth);

	    return isValid;
	}

	/**
	 * Return a string that transformed from the given form and date.
	 * @param {string} form - Date form
	 * @param {Date|Object} date - Date object
	 * @param {{meridiemSet: {AM: string, PM: string}}} option - Option
	 * @returns {boolean|string} A transformed string or false.
	 * @memberof tui.util
	 * @example
	 *  // key             | Shorthand
	 *  // --------------- |-----------------------
	 *  // years           | YY / YYYY / yy / yyyy
	 *  // months(n)       | M / MM
	 *  // months(str)     | MMM / MMMM
	 *  // days            | D / DD / d / dd
	 *  // hours           | H / HH / h / hh
	 *  // minutes         | m / mm
	 *  // meridiem(AM,PM) | A / a
	 *
	 * //-- #1. Get Module --//
	 * var util = require('tui-code-snippet'); // node, commonjs
	 * var util = tui.util; // distribution file
	 *
	 * //-- #2. Use property --//
	 * var dateStr1 = util.formatDate('yyyy-MM-dd', {
	 *     year: 2014,
	 *     month: 12,
	 *     date: 12
	 * });
	 * alert(dateStr1); // '2014-12-12'
	 *
	 * var dateStr2 = util.formatDate('MMM DD YYYY HH:mm', {
	 *     year: 1999,
	 *     month: 9,
	 *     date: 9,
	 *     hour: 0,
	 *     minute: 2
	 * });
	 * alert(dateStr2); // 'Sep 09 1999 00:02'
	 *
	 * var dt = new Date(2010, 2, 13),
	 *     dateStr3 = util.formatDate('yyyy년 M월 dd일', dt);
	 * alert(dateStr3); // '2010년 3월 13일'
	 *
	 * var option4 = {
	 *     meridiemSet: {
	 *         AM: '오전',
	 *         PM: '오후'
	 *     }
	 * };
	 * var date4 = {year: 1999, month: 9, date: 9, hour: 13, minute: 2};
	 * var dateStr4 = util.formatDate('yyyy-MM-dd A hh:mm', date4, option4));
	 * alert(dateStr4); // '1999-09-09 오후 01:02'
	 */
	function formatDate(form, date, option) { // eslint-disable-line complexity
	    var am = object.pick(option, 'meridiemSet', 'AM') || 'AM';
	    var pm = object.pick(option, 'meridiemSet', 'PM') || 'PM';
	    var meridiem, nDate, resultStr;

	    if (type.isDate(date)) {
	        nDate = {
	            year: date.getFullYear(),
	            month: date.getMonth() + 1,
	            date: date.getDate(),
	            hour: date.getHours(),
	            minute: date.getMinutes()
	        };
	    } else {
	        nDate = {
	            year: date.year,
	            month: date.month,
	            date: date.date,
	            hour: date.hour,
	            minute: date.minute
	        };
	    }

	    if (!isValidDate(nDate.year, nDate.month, nDate.date)) {
	        return false;
	    }

	    nDate.meridiem = '';
	    if (/([^\\]|^)[aA]\b/.test(form)) {
	        meridiem = (nDate.hour > 11) ? pm : am;
	        if (nDate.hour > 12) { // See the clock system: https://en.wikipedia.org/wiki/12-hour_clock
	            nDate.hour %= 12;
	        }
	        if (nDate.hour === 0) {
	            nDate.hour = 12;
	        }
	        nDate.meridiem = meridiem;
	    }

	    resultStr = form.replace(tokens, function(key) {
	        if (key.indexOf('\\') > -1) { // escape character
	            return key.replace(/\\/, '');
	        }

	        return replaceMap[key](nDate) || '';
	    });

	    return resultStr;
	}

	module.exports = formatDate;


/***/ }),
/* 13 */
/***/ (function(module, exports, __webpack_require__) {

	/**
	 * @fileoverview
	 *  This module provides a function to make a constructor
	 * that can inherit from the other constructors like the CLASS easily.
	 * @author NHN.
	 *         FE Development Lab <dl_javascript@nhn.com>
	 */

	'use strict';

	var inherit = __webpack_require__(6).inherit;
	var extend = __webpack_require__(1).extend;

	/**
	 * Help a constructor to be defined and to inherit from the other constructors
	 * @param {*} [parent] Parent constructor
	 * @param {Object} props Members of constructor
	 *  @param {Function} props.init Initialization method
	 *  @param {Object} [props.static] Static members of constructor
	 * @returns {*} Constructor
	 * @memberof tui.util
	 * @example
	 * //-- #1. Get Module --//
	 * var util = require('tui-code-snippet'); // node, commonjs
	 * var util = tui.util; // distribution file
	 *
	 * //-- #2. Use property --//
	 * var Parent = util.defineClass({
	 *     init: function() { // constuructor
	 *         this.name = 'made by def';
	 *     },
	 *     method: function() {
	 *         // ...
	 *     },
	 *     static: {
	 *         staticMethod: function() {
	 *              // ...
	 *         }
	 *     }
	 * });
	 *
	 * var Child = util.defineClass(Parent, {
	 *     childMethod: function() {}
	 * });
	 *
	 * Parent.staticMethod();
	 *
	 * var parentInstance = new Parent();
	 * console.log(parentInstance.name); //made by def
	 * parentInstance.staticMethod(); // Error
	 *
	 * var childInstance = new Child();
	 * childInstance.method();
	 * childInstance.childMethod();
	 */
	function defineClass(parent, props) {
	    var obj;

	    if (!props) {
	        props = parent;
	        parent = null;
	    }

	    obj = props.init || function() {};

	    if (parent) {
	        inherit(obj, parent);
	    }

	    if (props.hasOwnProperty('static')) {
	        extend(obj, props['static']);
	        delete props['static'];
	    }

	    extend(obj.prototype, props);

	    return obj;
	}

	module.exports = defineClass;


/***/ }),
/* 14 */
/***/ (function(module, exports, __webpack_require__) {

	/**
	 * @fileoverview Define module
	 * @author NHN.
	 *         FE Development Lab <dl_javscript@nhn.com>
	 * @dependency type.js, defineNamespace.js
	 */

	'use strict';

	var defineNamespace = __webpack_require__(15);
	var type = __webpack_require__(2);

	var INITIALIZATION_METHOD_NAME = 'initialize';

	/**
	 * Define module
	 * @param {string} namespace - Namespace of module
	 * @param {Object} moduleDefinition - Object literal for module
	 * @returns {Object} Defined module
	 * @memberof tui.util
	 * @example
	  * //-- #1. Get Module --//
	 * var util = require('tui-code-snippet'); // node, commonjs
	 * var util = tui.util; // distribution file
	 *
	 * //-- #2. Use property --//
	 * var myModule = util.defineModule('modules.myModule', {
	 *     name: 'john',
	 *     message: '',
	 *     initialize: function() {
	 *        this.message = 'hello world';
	 *     },
	 *     getMessage: function() {
	 *         return this.name + ': ' + this.message
	 *     }
	 * });
	 *
	 * console.log(myModule.getMessage());  // 'john: hello world';
	 */
	function defineModule(namespace, moduleDefinition) {
	    var base = moduleDefinition || {};

	    if (type.isFunction(base[INITIALIZATION_METHOD_NAME])) {
	        base[INITIALIZATION_METHOD_NAME]();
	    }

	    return defineNamespace(namespace, base);
	}

	module.exports = defineModule;


/***/ }),
/* 15 */
/***/ (function(module, exports, __webpack_require__) {

	/**
	 * @fileoverview Define namespace
	 * @author NHN.
	 *         FE Development Lab <dl_javascript@nhn.com>
	 * @dependency object.js, collection.js
	 */

	'use strict';

	var collection = __webpack_require__(4);
	var object = __webpack_require__(1);

	/**
	 * Define namespace
	 * @param {string} namespace - Namespace (ex- 'foo.bar.baz')
	 * @param {(object|function)} props - A set of modules or one module
	 * @param {boolean} [isOverride] - Override the props to the namespace.<br>
	 *                                  (It removes previous properties of this namespace)
	 * @returns {(object|function)} Defined namespace
	 * @memberof tui.util
	 * @example
	 * //-- #1. Get Module --//
	 * var util = require('tui-code-snippet'); // node, commonjs
	 * var util = tui.util; // distribution file
	 *
	 * //-- #2. Use property --//
	 * var neComp = util.defineNamespace;
	 * neComp.listMenu = defineClass({
	 *     init: function() {
	 *         // ...
	 *     }
	 * });
	 */
	function defineNamespace(namespace, props, isOverride) {
	    var names, result, prevLast, last;

	    names = namespace.split('.');
	    names.unshift(window);

	    result = collection.reduce(names, function(obj, name) {
	        obj[name] = obj[name] || {};

	        return obj[name];
	    });

	    if (isOverride) {
	        last = names.pop();
	        prevLast = object.pick.apply(null, names);
	        result = prevLast[last] = props;
	    } else {
	        object.extend(result, props);
	    }

	    return result;
	}

	module.exports = defineNamespace;


/***/ }),
/* 16 */
/***/ (function(module, exports, __webpack_require__) {

	/**
	 * @fileoverview
	 *  This module provides some functions for custom events.<br>
	 *  And it is implemented in the observer design pattern.
	 * @author NHN.
	 *         FE Development Lab <dl_javascript@nhn.com>
	 */

	'use strict';

	var collection = __webpack_require__(4);
	var type = __webpack_require__(2);
	var object = __webpack_require__(1);

	var R_EVENTNAME_SPLIT = /\s+/g;

	/**
	 * A unit of event handler item.
	 * @ignore
	 * @typedef {object} HandlerItem
	 * @property {function} fn - event handler
	 * @property {object} ctx - context of event handler
	 */

	/**
	 * @class
	 * @memberof tui.util
	 * @example
	 * // node, commonjs
	 * var CustomEvents = require('tui-code-snippet').CustomEvents;
	 * @example
	 * // distribution file, script
	 * <script src='path-to/tui-code-snippt.js'></script>
	 * <script>
	 * var CustomEvents = tui.util.CustomEvents;
	 * </script>
	 */
	function CustomEvents() {
	    /**
	     * @type {HandlerItem[]}
	     */
	    this.events = null;

	    /**
	     * only for checking specific context event was binded
	     * @type {object[]}
	     */
	    this.contexts = null;
	}

	/**
	 * Mixin custom events feature to specific constructor
	 * @param {function} func - constructor
	 * @example
	 * //-- #1. Get Module --//
	 * var CustomEvents = require('tui-code-snippet').CustomEvents; // node, commonjs
	 * var CustomEvents = tui.util.CustomEvents; // distribution file
	 *
	 * //-- #2. Use property --//
	 * var model;
	 * function Model() {
	 *     this.name = '';
	 * }
	 * CustomEvents.mixin(Model);
	 *
	 * model = new Model();
	 * model.on('change', function() { this.name = 'model'; }, this);
	 * model.fire('change');
	 * alert(model.name); // 'model';
	 */
	CustomEvents.mixin = function(func) {
	    object.extend(func.prototype, CustomEvents.prototype);
	};

	/**
	 * Get HandlerItem object
	 * @param {function} handler - handler function
	 * @param {object} [context] - context for handler
	 * @returns {HandlerItem} HandlerItem object
	 * @private
	 */
	CustomEvents.prototype._getHandlerItem = function(handler, context) {
	    var item = {handler: handler};

	    if (context) {
	        item.context = context;
	    }

	    return item;
	};

	/**
	 * Get event object safely
	 * @param {string} [eventName] - create sub event map if not exist.
	 * @returns {(object|array)} event object. if you supplied `eventName`
	 *  parameter then make new array and return it
	 * @private
	 */
	CustomEvents.prototype._safeEvent = function(eventName) {
	    var events = this.events;
	    var byName;

	    if (!events) {
	        events = this.events = {};
	    }

	    if (eventName) {
	        byName = events[eventName];

	        if (!byName) {
	            byName = [];
	            events[eventName] = byName;
	        }

	        events = byName;
	    }

	    return events;
	};

	/**
	 * Get context array safely
	 * @returns {array} context array
	 * @private
	 */
	CustomEvents.prototype._safeContext = function() {
	    var context = this.contexts;

	    if (!context) {
	        context = this.contexts = [];
	    }

	    return context;
	};

	/**
	 * Get index of context
	 * @param {object} ctx - context that used for bind custom event
	 * @returns {number} index of context
	 * @private
	 */
	CustomEvents.prototype._indexOfContext = function(ctx) {
	    var context = this._safeContext();
	    var index = 0;

	    while (context[index]) {
	        if (ctx === context[index][0]) {
	            return index;
	        }

	        index += 1;
	    }

	    return -1;
	};

	/**
	 * Memorize supplied context for recognize supplied object is context or
	 *  name: handler pair object when off()
	 * @param {object} ctx - context object to memorize
	 * @private
	 */
	CustomEvents.prototype._memorizeContext = function(ctx) {
	    var context, index;

	    if (!type.isExisty(ctx)) {
	        return;
	    }

	    context = this._safeContext();
	    index = this._indexOfContext(ctx);

	    if (index > -1) {
	        context[index][1] += 1;
	    } else {
	        context.push([ctx, 1]);
	    }
	};

	/**
	 * Forget supplied context object
	 * @param {object} ctx - context object to forget
	 * @private
	 */
	CustomEvents.prototype._forgetContext = function(ctx) {
	    var context, contextIndex;

	    if (!type.isExisty(ctx)) {
	        return;
	    }

	    context = this._safeContext();
	    contextIndex = this._indexOfContext(ctx);

	    if (contextIndex > -1) {
	        context[contextIndex][1] -= 1;

	        if (context[contextIndex][1] <= 0) {
	            context.splice(contextIndex, 1);
	        }
	    }
	};

	/**
	 * Bind event handler
	 * @param {(string|{name:string, handler:function})} eventName - custom
	 *  event name or an object {eventName: handler}
	 * @param {(function|object)} [handler] - handler function or context
	 * @param {object} [context] - context for binding
	 * @private
	 */
	CustomEvents.prototype._bindEvent = function(eventName, handler, context) {
	    var events = this._safeEvent(eventName);
	    this._memorizeContext(context);
	    events.push(this._getHandlerItem(handler, context));
	};

	/**
	 * Bind event handlers
	 * @param {(string|{name:string, handler:function})} eventName - custom
	 *  event name or an object {eventName: handler}
	 * @param {(function|object)} [handler] - handler function or context
	 * @param {object} [context] - context for binding
	 * //-- #1. Get Module --//
	 * var CustomEvents = require('tui-code-snippet').CustomEvents; // node, commonjs
	 * var CustomEvents = tui.util.CustomEvents; // distribution file
	 *
	 * //-- #2. Use property --//
	 * // # 2.1 Basic Usage
	 * CustomEvents.on('onload', handler);
	 *
	 * // # 2.2 With context
	 * CustomEvents.on('onload', handler, myObj);
	 *
	 * // # 2.3 Bind by object that name, handler pairs
	 * CustomEvents.on({
	 *     'play': handler,
	 *     'pause': handler2
	 * });
	 *
	 * // # 2.4 Bind by object that name, handler pairs with context object
	 * CustomEvents.on({
	 *     'play': handler
	 * }, myObj);
	 */
	CustomEvents.prototype.on = function(eventName, handler, context) {
	    var self = this;

	    if (type.isString(eventName)) {
	        // [syntax 1, 2]
	        eventName = eventName.split(R_EVENTNAME_SPLIT);
	        collection.forEach(eventName, function(name) {
	            self._bindEvent(name, handler, context);
	        });
	    } else if (type.isObject(eventName)) {
	        // [syntax 3, 4]
	        context = handler;
	        collection.forEach(eventName, function(func, name) {
	            self.on(name, func, context);
	        });
	    }
	};

	/**
	 * Bind one-shot event handlers
	 * @param {(string|{name:string,handler:function})} eventName - custom
	 *  event name or an object {eventName: handler}
	 * @param {function|object} [handler] - handler function or context
	 * @param {object} [context] - context for binding
	 */
	CustomEvents.prototype.once = function(eventName, handler, context) {
	    var self = this;

	    if (type.isObject(eventName)) {
	        context = handler;
	        collection.forEach(eventName, function(func, name) {
	            self.once(name, func, context);
	        });

	        return;
	    }

	    function onceHandler() { // eslint-disable-line require-jsdoc
	        handler.apply(context, arguments);
	        self.off(eventName, onceHandler, context);
	    }

	    this.on(eventName, onceHandler, context);
	};

	/**
	 * Splice supplied array by callback result
	 * @param {array} arr - array to splice
	 * @param {function} predicate - function return boolean
	 * @private
	 */
	CustomEvents.prototype._spliceMatches = function(arr, predicate) {
	    var i = 0;
	    var len;

	    if (!type.isArray(arr)) {
	        return;
	    }

	    for (len = arr.length; i < len; i += 1) {
	        if (predicate(arr[i]) === true) {
	            arr.splice(i, 1);
	            len -= 1;
	            i -= 1;
	        }
	    }
	};

	/**
	 * Get matcher for unbind specific handler events
	 * @param {function} handler - handler function
	 * @returns {function} handler matcher
	 * @private
	 */
	CustomEvents.prototype._matchHandler = function(handler) {
	    var self = this;

	    return function(item) {
	        var needRemove = handler === item.handler;

	        if (needRemove) {
	            self._forgetContext(item.context);
	        }

	        return needRemove;
	    };
	};

	/**
	 * Get matcher for unbind specific context events
	 * @param {object} context - context
	 * @returns {function} object matcher
	 * @private
	 */
	CustomEvents.prototype._matchContext = function(context) {
	    var self = this;

	    return function(item) {
	        var needRemove = context === item.context;

	        if (needRemove) {
	            self._forgetContext(item.context);
	        }

	        return needRemove;
	    };
	};

	/**
	 * Get matcher for unbind specific hander, context pair events
	 * @param {function} handler - handler function
	 * @param {object} context - context
	 * @returns {function} handler, context matcher
	 * @private
	 */
	CustomEvents.prototype._matchHandlerAndContext = function(handler, context) {
	    var self = this;

	    return function(item) {
	        var matchHandler = (handler === item.handler);
	        var matchContext = (context === item.context);
	        var needRemove = (matchHandler && matchContext);

	        if (needRemove) {
	            self._forgetContext(item.context);
	        }

	        return needRemove;
	    };
	};

	/**
	 * Unbind event by event name
	 * @param {string} eventName - custom event name to unbind
	 * @param {function} [handler] - handler function
	 * @private
	 */
	CustomEvents.prototype._offByEventName = function(eventName, handler) {
	    var self = this;
	    var forEach = collection.forEachArray;
	    var andByHandler = type.isFunction(handler);
	    var matchHandler = self._matchHandler(handler);

	    eventName = eventName.split(R_EVENTNAME_SPLIT);

	    forEach(eventName, function(name) {
	        var handlerItems = self._safeEvent(name);

	        if (andByHandler) {
	            self._spliceMatches(handlerItems, matchHandler);
	        } else {
	            forEach(handlerItems, function(item) {
	                self._forgetContext(item.context);
	            });

	            self.events[name] = [];
	        }
	    });
	};

	/**
	 * Unbind event by handler function
	 * @param {function} handler - handler function
	 * @private
	 */
	CustomEvents.prototype._offByHandler = function(handler) {
	    var self = this;
	    var matchHandler = this._matchHandler(handler);

	    collection.forEach(this._safeEvent(), function(handlerItems) {
	        self._spliceMatches(handlerItems, matchHandler);
	    });
	};

	/**
	 * Unbind event by object(name: handler pair object or context object)
	 * @param {object} obj - context or {name: handler} pair object
	 * @param {function} handler - handler function
	 * @private
	 */
	CustomEvents.prototype._offByObject = function(obj, handler) {
	    var self = this;
	    var matchFunc;

	    if (this._indexOfContext(obj) < 0) {
	        collection.forEach(obj, function(func, name) {
	            self.off(name, func);
	        });
	    } else if (type.isString(handler)) {
	        matchFunc = this._matchContext(obj);

	        self._spliceMatches(this._safeEvent(handler), matchFunc);
	    } else if (type.isFunction(handler)) {
	        matchFunc = this._matchHandlerAndContext(handler, obj);

	        collection.forEach(this._safeEvent(), function(handlerItems) {
	            self._spliceMatches(handlerItems, matchFunc);
	        });
	    } else {
	        matchFunc = this._matchContext(obj);

	        collection.forEach(this._safeEvent(), function(handlerItems) {
	            self._spliceMatches(handlerItems, matchFunc);
	        });
	    }
	};

	/**
	 * Unbind custom events
	 * @param {(string|object|function)} eventName - event name or context or
	 *  {name: handler} pair object or handler function
	 * @param {(function)} handler - handler function
	 * @example
	 * //-- #1. Get Module --//
	 * var CustomEvents = require('tui-code-snippet').CustomEvents; // node, commonjs
	 * var CustomEvents = tui.util.CustomEvents; // distribution file
	 *
	 * //-- #2. Use property --//
	 * // # 2.1 off by event name
	 * CustomEvents.off('onload');
	 *
	 * // # 2.2 off by event name and handler
	 * CustomEvents.off('play', handler);
	 *
	 * // # 2.3 off by handler
	 * CustomEvents.off(handler);
	 *
	 * // # 2.4 off by context
	 * CustomEvents.off(myObj);
	 *
	 * // # 2.5 off by context and handler
	 * CustomEvents.off(myObj, handler);
	 *
	 * // # 2.6 off by context and event name
	 * CustomEvents.off(myObj, 'onload');
	 *
	 * // # 2.7 off by an Object.<string, function> that is {eventName: handler}
	 * CustomEvents.off({
	 *   'play': handler,
	 *   'pause': handler2
	 * });
	 *
	 * // # 2.8 off the all events
	 * CustomEvents.off();
	 */
	CustomEvents.prototype.off = function(eventName, handler) {
	    if (type.isString(eventName)) {
	        // [syntax 1, 2]
	        this._offByEventName(eventName, handler);
	    } else if (!arguments.length) {
	        // [syntax 8]
	        this.events = {};
	        this.contexts = [];
	    } else if (type.isFunction(eventName)) {
	        // [syntax 3]
	        this._offByHandler(eventName);
	    } else if (type.isObject(eventName)) {
	        // [syntax 4, 5, 6]
	        this._offByObject(eventName, handler);
	    }
	};

	/**
	 * Fire custom event
	 * @param {string} eventName - name of custom event
	 */
	CustomEvents.prototype.fire = function(eventName) {  // eslint-disable-line
	    this.invoke.apply(this, arguments);
	};

	/**
	 * Fire a event and returns the result of operation 'boolean AND' with all
	 *  listener's results.
	 *
	 * So, It is different from {@link CustomEvents#fire}.
	 *
	 * In service code, use this as a before event in component level usually
	 *  for notifying that the event is cancelable.
	 * @param {string} eventName - Custom event name
	 * @param {...*} data - Data for event
	 * @returns {boolean} The result of operation 'boolean AND'
	 * @example
	 * var map = new Map();
	 * map.on({
	 *     'beforeZoom': function() {
	 *         // It should cancel the 'zoom' event by some conditions.
	 *         if (that.disabled && this.getState()) {
	 *             return false;
	 *         }
	 *         return true;
	 *     }
	 * });
	 *
	 * if (this.invoke('beforeZoom')) {    // check the result of 'beforeZoom'
	 *     // if true,
	 *     // doSomething
	 * }
	 */
	CustomEvents.prototype.invoke = function(eventName) {
	    var events, args, index, item;

	    if (!this.hasListener(eventName)) {
	        return true;
	    }

	    events = this._safeEvent(eventName);
	    args = Array.prototype.slice.call(arguments, 1);
	    index = 0;

	    while (events[index]) {
	        item = events[index];

	        if (item.handler.apply(item.context, args) === false) {
	            return false;
	        }

	        index += 1;
	    }

	    return true;
	};

	/**
	 * Return whether at least one of the handlers is registered in the given
	 *  event name.
	 * @param {string} eventName - Custom event name
	 * @returns {boolean} Is there at least one handler in event name?
	 */
	CustomEvents.prototype.hasListener = function(eventName) {
	    return this.getListenerLength(eventName) > 0;
	};

	/**
	 * Return a count of events registered.
	 * @param {string} eventName - Custom event name
	 * @returns {number} number of event
	 */
	CustomEvents.prototype.getListenerLength = function(eventName) {
	    var events = this._safeEvent(eventName);

	    return events.length;
	};

	module.exports = CustomEvents;


/***/ }),
/* 17 */
/***/ (function(module, exports, __webpack_require__) {

	/**
	 * @fileoverview This module provides a Enum Constructor.
	 * @author NHN.
	 *         FE Development Lab <dl_javascript@nhn.com>
	 * @example
	 * // node, commonjs
	 * var Enum = require('tui-code-snippet').Enum;
	 * @example
	 * // distribution file, script
	 * <script src='path-to/tui-code-snippt.js'></script>
	 * <script>
	 * var Enum = tui.util.Enum;
	 * <script>
	 */

	'use strict';

	var collection = __webpack_require__(4);
	var type = __webpack_require__(2);

	/**
	 * Check whether the defineProperty() method is supported.
	 * @type {boolean}
	 * @ignore
	 */
	var isSupportDefinedProperty = (function() {
	    try {
	        Object.defineProperty({}, 'x', {});

	        return true;
	    } catch (e) {
	        return false;
	    }
	})();

	/**
	 * A unique value of a constant.
	 * @type {number}
	 * @ignore
	 */
	var enumValue = 0;

	/**
	 * Make a constant-list that has unique values.<br>
	 * In modern browsers (except IE8 and lower),<br>
	 *  a value defined once can not be changed.
	 *
	 * @param {...string|string[]} itemList Constant-list (An array of string is available)
	 * @class
	 * @memberof tui.util
	 * @example
	 * //-- #1. Get Module --//
	 * var Enum = require('tui-code-snippet').Enum; // node, commonjs
	 * var Enum = tui.util.Enum; // distribution file
	 *
	 * //-- #2. Use property --//
	 * var MYENUM = new Enum('TYPE1', 'TYPE2');
	 * var MYENUM2 = new Enum(['TYPE1', 'TYPE2']);
	 *
	 * //usage
	 * if (value === MYENUM.TYPE1) {
	 *      ....
	 * }
	 *
	 * //add (If a duplicate name is inputted, will be disregarded.)
	 * MYENUM.set('TYPE3', 'TYPE4');
	 *
	 * //get name of a constant by a value
	 * MYENUM.getName(MYENUM.TYPE1); // 'TYPE1'
	 *
	 * // In modern browsers (except IE8 and lower), a value can not be changed in constants.
	 * var originalValue = MYENUM.TYPE1;
	 * MYENUM.TYPE1 = 1234; // maybe TypeError
	 * MYENUM.TYPE1 === originalValue; // true
	 **/
	function Enum(itemList) {
	    if (itemList) {
	        this.set.apply(this, arguments);
	    }
	}

	/**
	 * Define a constants-list
	 * @param {...string|string[]} itemList Constant-list (An array of string is available)
	 */
	Enum.prototype.set = function(itemList) {
	    var self = this;

	    if (!type.isArray(itemList)) {
	        itemList = collection.toArray(arguments);
	    }

	    collection.forEach(itemList, function itemListIteratee(item) {
	        self._addItem(item);
	    });
	};

	/**
	 * Return a key of the constant.
	 * @param {number} value A value of the constant.
	 * @returns {string|undefined} Key of the constant.
	 */
	Enum.prototype.getName = function(value) {
	    var self = this;
	    var foundedKey;

	    collection.forEach(this, function(itemValue, key) { // eslint-disable-line consistent-return
	        if (self._isEnumItem(key) && value === itemValue) {
	            foundedKey = key;

	            return false;
	        }
	    });

	    return foundedKey;
	};

	/**
	 * Create a constant.
	 * @private
	 * @param {string} name Constant name. (It will be a key of a constant)
	 */
	Enum.prototype._addItem = function(name) {
	    var value;

	    if (!this.hasOwnProperty(name)) {
	        value = this._makeEnumValue();

	        if (isSupportDefinedProperty) {
	            Object.defineProperty(this, name, {
	                enumerable: true,
	                configurable: false,
	                writable: false,
	                value: value
	            });
	        } else {
	            this[name] = value;
	        }
	    }
	};

	/**
	 * Return a unique value for assigning to a constant.
	 * @private
	 * @returns {number} A unique value
	 */
	Enum.prototype._makeEnumValue = function() {
	    var value;

	    value = enumValue;
	    enumValue += 1;

	    return value;
	};

	/**
	 * Return whether a constant from the given key is in instance or not.
	 * @param {string} key - A constant key
	 * @returns {boolean} Result
	 * @private
	 */
	Enum.prototype._isEnumItem = function(key) {
	    return type.isNumber(this[key]);
	};

	module.exports = Enum;


/***/ }),
/* 18 */
/***/ (function(module, exports, __webpack_require__) {

	/**
	 * @fileoverview
	 *  Implements the ExMap (Extended Map) object.
	 * @author NHN.
	 *         FE Development Lab <dl_javascript@nhn.com>
	 */

	'use strict';

	var collection = __webpack_require__(4);
	var Map = __webpack_require__(19);

	// Caching tui.util for performance enhancing
	var mapAPIsForRead = ['get', 'has', 'forEach', 'keys', 'values', 'entries'];
	var mapAPIsForDelete = ['delete', 'clear'];

	/**
	 * The ExMap object is Extended Version of the tui.util.Map object.<br>
	 * and added some useful feature to make it easy to manage the Map object.
	 * @constructor
	 * @param {Array} initData - Array of key-value pairs (2-element Arrays).
	 *      Each key-value pair will be added to the new Map
	 * @memberof tui.util
	 * @example
	 * // node, commonjs
	 * var ExMap = require('tui-code-snippet').ExMap;
	 * @example
	 * // distribution file, script
	 * <script src='path-to/tui-code-snippt.js'></script>
	 * <script>
	 * var ExMap = tui.util.ExMap;
	 * <script>
	 */
	function ExMap(initData) {
	    this._map = new Map(initData);
	    this.size = this._map.size;
	}

	collection.forEachArray(mapAPIsForRead, function(name) {
	    ExMap.prototype[name] = function() {
	        return this._map[name].apply(this._map, arguments);
	    };
	});

	collection.forEachArray(mapAPIsForDelete, function(name) {
	    ExMap.prototype[name] = function() {
	        var result = this._map[name].apply(this._map, arguments);
	        this.size = this._map.size;

	        return result;
	    };
	});

	ExMap.prototype.set = function() {
	    this._map.set.apply(this._map, arguments);
	    this.size = this._map.size;

	    return this;
	};

	/**
	 * Sets all of the key-value pairs in the specified object to the Map object.
	 * @param  {Object} object - Plain object that has a key-value pair
	 */
	ExMap.prototype.setObject = function(object) {
	    collection.forEachOwnProperties(object, function(value, key) {
	        this.set(key, value);
	    }, this);
	};

	/**
	 * Removes the elements associated with keys in the specified array.
	 * @param  {Array} keys - Array that contains keys of the element to remove
	 */
	ExMap.prototype.deleteByKeys = function(keys) {
	    collection.forEachArray(keys, function(key) {
	        this['delete'](key);
	    }, this);
	};

	/**
	 * Sets all of the key-value pairs in the specified Map object to this Map object.
	 * @param  {Map} map - Map object to be merged into this Map object
	 */
	ExMap.prototype.merge = function(map) {
	    map.forEach(function(value, key) {
	        this.set(key, value);
	    }, this);
	};

	/**
	 * Looks through each key-value pair in the map and returns the new ExMap object of
	 * all key-value pairs that pass a truth test implemented by the provided function.
	 * @param  {function} predicate - Function to test each key-value pair of the Map object.<br>
	 *      Invoked with arguments (value, key). Return true to keep the element, false otherwise.
	 * @returns {ExMap} A new ExMap object
	 */
	ExMap.prototype.filter = function(predicate) {
	    var filtered = new ExMap();

	    this.forEach(function(value, key) {
	        if (predicate(value, key)) {
	            filtered.set(key, value);
	        }
	    });

	    return filtered;
	};

	module.exports = ExMap;


/***/ }),
/* 19 */
/***/ (function(module, exports, __webpack_require__) {

	
	/**
	 * @fileoverview
	 *  Implements the Map object.
	 * @author NHN.
	 *         FE Development Lab <dl_javascript@nhn.com>
	 */

	'use strict';

	var collection = __webpack_require__(4);
	var type = __webpack_require__(2);
	var array = __webpack_require__(3);
	var browser = __webpack_require__(10);
	var func = __webpack_require__(5);

	/**
	 * Using undefined for a key can be ambiguous if there's deleted item in the array,<br>
	 * which is also undefined when accessed by index.<br>
	 * So use this unique object as an undefined key to distinguish it from deleted keys.
	 * @private
	 * @constant
	 */
	var _KEY_FOR_UNDEFINED = {};

	/**
	 * For using NaN as a key, use this unique object as a NaN key.<br>
	 * This makes it easier and faster to compare an object with each keys in the array<br>
	 * through no exceptional comapring for NaN.
	 * @private
	 * @constant
	 */
	var _KEY_FOR_NAN = {};

	/**
	 * Constructor of MapIterator<br>
	 * Creates iterator object with new keyword.
	 * @constructor
	 * @param  {Array} keys - The array of keys in the map
	 * @param  {function} valueGetter - Function that returns certain value,
	 *      taking key and keyIndex as arguments.
	 * @ignore
	 */
	function MapIterator(keys, valueGetter) {
	    this._keys = keys;
	    this._valueGetter = valueGetter;
	    this._length = this._keys.length;
	    this._index = -1;
	    this._done = false;
	}

	/**
	 * Implementation of Iterator protocol.
	 * @returns {{done: boolean, value: *}} Object that contains done(boolean) and value.
	 */
	MapIterator.prototype.next = function() {
	    var data = {};
	    do {
	        this._index += 1;
	    } while (type.isUndefined(this._keys[this._index]) && this._index < this._length);

	    if (this._index >= this._length) {
	        data.done = true;
	    } else {
	        data.done = false;
	        data.value = this._valueGetter(this._keys[this._index], this._index);
	    }

	    return data;
	};

	/**
	 * The Map object implements the ES6 Map specification as closely as possible.<br>
	 * For using objects and primitive values as keys, this object uses array internally.<br>
	 * So if the key is not a string, get(), set(), has(), delete() will operates in O(n),<br>
	 * and it can cause performance issues with a large dataset.
	 *
	 * Features listed below are not supported. (can't be implented without native support)
	 * - Map object is iterable<br>
	 * - Iterable object can be used as an argument of constructor
	 *
	 * If the browser supports full implementation of ES6 Map specification, native Map obejct
	 * will be used internally.
	 * @class
	 * @param  {Array} initData - Array of key-value pairs (2-element Arrays).
	 *      Each key-value pair will be added to the new Map
	 * @memberof tui.util
	 * @example
	 * // node, commonjs
	 * var Map = require('tui-code-snippet').Map;
	 * @example
	 * // distribution file, script
	 * <script src='path-to/tui-code-snippt.js'></script>
	 * <script>
	 * var Map = tui.util.Map;
	 * <script>
	 */
	function Map(initData) {
	    this._valuesForString = {};
	    this._valuesForIndex = {};
	    this._keys = [];

	    if (initData) {
	        this._setInitData(initData);
	    }

	    this.size = 0;
	}

	/* eslint-disable no-extend-native */
	/**
	 * Add all elements in the initData to the Map object.
	 * @private
	 * @param  {Array} initData - Array of key-value pairs to add to the Map object
	 */
	Map.prototype._setInitData = function(initData) {
	    if (!type.isArray(initData)) {
	        throw new Error('Only Array is supported.');
	    }
	    collection.forEachArray(initData, function(pair) {
	        this.set(pair[0], pair[1]);
	    }, this);
	};

	/**
	 * Returns true if the specified value is NaN.<br>
	 * For unsing NaN as a key, use this method to test equality of NaN<br>
	 * because === operator doesn't work for NaN.
	 * @private
	 * @param {*} value - Any object to be tested
	 * @returns {boolean} True if value is NaN, false otherwise.
	 */
	Map.prototype._isNaN = function(value) {
	    return typeof value === 'number' && value !== value; // eslint-disable-line no-self-compare
	};

	/**
	 * Returns the index of the specified key.
	 * @private
	 * @param  {*} key - The key object to search for.
	 * @returns {number} The index of the specified key
	 */
	Map.prototype._getKeyIndex = function(key) {
	    var result = -1;
	    var value;

	    if (type.isString(key)) {
	        value = this._valuesForString[key];
	        if (value) {
	            result = value.keyIndex;
	        }
	    } else {
	        result = array.inArray(key, this._keys);
	    }

	    return result;
	};

	/**
	 * Returns the original key of the specified key.
	 * @private
	 * @param  {*} key - key
	 * @returns {*} Original key
	 */
	Map.prototype._getOriginKey = function(key) {
	    var originKey = key;
	    if (key === _KEY_FOR_UNDEFINED) {
	        originKey = undefined; // eslint-disable-line no-undefined
	    } else if (key === _KEY_FOR_NAN) {
	        originKey = NaN;
	    }

	    return originKey;
	};

	/**
	 * Returns the unique key of the specified key.
	 * @private
	 * @param  {*} key - key
	 * @returns {*} Unique key
	 */
	Map.prototype._getUniqueKey = function(key) {
	    var uniqueKey = key;
	    if (type.isUndefined(key)) {
	        uniqueKey = _KEY_FOR_UNDEFINED;
	    } else if (this._isNaN(key)) {
	        uniqueKey = _KEY_FOR_NAN;
	    }

	    return uniqueKey;
	};

	/**
	 * Returns the value object of the specified key.
	 * @private
	 * @param  {*} key - The key of the value object to be returned
	 * @param  {number} keyIndex - The index of the key
	 * @returns {{keyIndex: number, origin: *}} Value object
	 */
	Map.prototype._getValueObject = function(key, keyIndex) { // eslint-disable-line consistent-return
	    if (type.isString(key)) {
	        return this._valuesForString[key];
	    }

	    if (type.isUndefined(keyIndex)) {
	        keyIndex = this._getKeyIndex(key);
	    }
	    if (keyIndex >= 0) {
	        return this._valuesForIndex[keyIndex];
	    }
	};

	/**
	 * Returns the original value of the specified key.
	 * @private
	 * @param  {*} key - The key of the value object to be returned
	 * @param  {number} keyIndex - The index of the key
	 * @returns {*} Original value
	 */
	Map.prototype._getOriginValue = function(key, keyIndex) {
	    return this._getValueObject(key, keyIndex).origin;
	};

	/**
	 * Returns key-value pair of the specified key.
	 * @private
	 * @param  {*} key - The key of the value object to be returned
	 * @param  {number} keyIndex - The index of the key
	 * @returns {Array} Key-value Pair
	 */
	Map.prototype._getKeyValuePair = function(key, keyIndex) {
	    return [this._getOriginKey(key), this._getOriginValue(key, keyIndex)];
	};

	/**
	 * Creates the wrapper object of original value that contains a key index
	 * and returns it.
	 * @private
	 * @param  {type} origin - Original value
	 * @param  {type} keyIndex - Index of the key
	 * @returns {{keyIndex: number, origin: *}} Value object
	 */
	Map.prototype._createValueObject = function(origin, keyIndex) {
	    return {
	        keyIndex: keyIndex,
	        origin: origin
	    };
	};

	/**
	 * Sets the value for the key in the Map object.
	 * @param  {*} key - The key of the element to add to the Map object
	 * @param  {*} value - The value of the element to add to the Map object
	 * @returns {Map} The Map object
	 */
	Map.prototype.set = function(key, value) {
	    var uniqueKey = this._getUniqueKey(key);
	    var keyIndex = this._getKeyIndex(uniqueKey);
	    var valueObject;

	    if (keyIndex < 0) {
	        keyIndex = this._keys.push(uniqueKey) - 1;
	        this.size += 1;
	    }
	    valueObject = this._createValueObject(value, keyIndex);

	    if (type.isString(key)) {
	        this._valuesForString[key] = valueObject;
	    } else {
	        this._valuesForIndex[keyIndex] = valueObject;
	    }

	    return this;
	};

	/**
	 * Returns the value associated to the key, or undefined if there is none.
	 * @param  {*} key - The key of the element to return
	 * @returns {*} Element associated with the specified key
	 */
	Map.prototype.get = function(key) {
	    var uniqueKey = this._getUniqueKey(key);
	    var value = this._getValueObject(uniqueKey);

	    return value && value.origin;
	};

	/**
	 * Returns a new Iterator object that contains the keys for each element
	 * in the Map object in insertion order.
	 * @returns {Iterator} A new Iterator object
	 */
	Map.prototype.keys = function() {
	    return new MapIterator(this._keys, func.bind(this._getOriginKey, this));
	};

	/**
	 * Returns a new Iterator object that contains the values for each element
	 * in the Map object in insertion order.
	 * @returns {Iterator} A new Iterator object
	 */
	Map.prototype.values = function() {
	    return new MapIterator(this._keys, func.bind(this._getOriginValue, this));
	};

	/**
	 * Returns a new Iterator object that contains the [key, value] pairs
	 * for each element in the Map object in insertion order.
	 * @returns {Iterator} A new Iterator object
	 */
	Map.prototype.entries = function() {
	    return new MapIterator(this._keys, func.bind(this._getKeyValuePair, this));
	};

	/**
	 * Returns a boolean asserting whether a value has been associated to the key
	 * in the Map object or not.
	 * @param  {*} key - The key of the element to test for presence
	 * @returns {boolean} True if an element with the specified key exists;
	 *          Otherwise false
	 */
	Map.prototype.has = function(key) {
	    return !!this._getValueObject(key);
	};

	/**
	 * Removes the specified element from a Map object.
	 * @param {*} key - The key of the element to remove
	 * @function delete
	 * @memberof tui.util.Map.prototype
	 */
	// cannot use reserved keyword as a property name in IE8 and under.
	Map.prototype['delete'] = function(key) {
	    var keyIndex;

	    if (type.isString(key)) {
	        if (this._valuesForString[key]) {
	            keyIndex = this._valuesForString[key].keyIndex;
	            delete this._valuesForString[key];
	        }
	    } else {
	        keyIndex = this._getKeyIndex(key);
	        if (keyIndex >= 0) {
	            delete this._valuesForIndex[keyIndex];
	        }
	    }

	    if (keyIndex >= 0) {
	        delete this._keys[keyIndex];
	        this.size -= 1;
	    }
	};

	/**
	 * Executes a provided function once per each key/value pair in the Map object,
	 * in insertion order.
	 * @param  {function} callback - Function to execute for each element
	 * @param  {thisArg} thisArg - Value to use as this when executing callback
	 */
	Map.prototype.forEach = function(callback, thisArg) {
	    thisArg = thisArg || this;
	    collection.forEachArray(this._keys, function(key) {
	        if (!type.isUndefined(key)) {
	            callback.call(thisArg, this._getValueObject(key).origin, key, this);
	        }
	    }, this);
	};

	/**
	 * Removes all elements from a Map object.
	 */
	Map.prototype.clear = function() {
	    Map.call(this);
	};
	/* eslint-enable no-extend-native */

	// Use native Map object if exists.
	// But only latest versions of Chrome and Firefox support full implementation.
	(function() {
	    if (window.Map && (
	        (browser.firefox && browser.version >= 37) ||
	            (browser.chrome && browser.version >= 42)
	    )
	    ) {
	        Map = window.Map; // eslint-disable-line no-func-assign
	    }
	})();

	module.exports = Map;


/***/ }),
/* 20 */
/***/ (function(module, exports, __webpack_require__) {

	/**
	 * @fileoverview This module provides the HashMap constructor.
	 * @author NHN.
	 *         FE Development Lab <dl_javascript@nhn.com>
	 */

	'use strict';

	var collection = __webpack_require__(4);
	var type = __webpack_require__(2);
	/**
	 * All the data in hashMap begin with _MAPDATAPREFIX;
	 * @type {string}
	 * @private
	 */
	var _MAPDATAPREFIX = 'å';

	/**
	 * HashMap can handle the key-value pairs.<br>
	 * Caution:<br>
	 *  HashMap instance has a length property but is not an instance of Array.
	 * @param {Object} [obj] A initial data for creation.
	 * @constructor
	 * @memberof tui.util
	 * @deprecated since version 1.3.0
	 * @example
	 * // node, commonjs
	 * var HashMap = require('tui-code-snippet').HashMap;
	 * var hm = new tui.util.HashMap({
	  'mydata': {
	    'hello': 'imfine'
	  },
	  'what': 'time'
	});
	 * @example
	 * // distribution file, script
	 * <script src='path-to/tui-code-snippt.js'></script>
	 * <script>
	 * var HashMap = tui.util.HashMap;
	 * <script>
	 * var hm = new tui.util.HashMap({
	  'mydata': {
	    'hello': 'imfine'
	  },
	  'what': 'time'
	});
	 */
	function HashMap(obj) {
	    /**
	     * size
	     * @type {number}
	     */
	    this.length = 0;

	    if (obj) {
	        this.setObject(obj);
	    }
	}

	/**
	 * Set a data from the given key with value or the given object.
	 * @param {string|Object} key A string or object for key
	 * @param {*} [value] A data
	 * @example
	 * //-- #1. Get Module --//
	 * var HashMap = require('tui-code-snippet').HashMap; // node, commonjs
	 * var HashMap = tui.util.HashMap; // distribution file
	 *
	 * //-- #2. Use property --//
	 * var hm = new HashMap();
	 * hm.set('key', 'value');
	 * hm.set({
	 *     'key1': 'data1',
	 *     'key2': 'data2'
	 * });
	 */
	HashMap.prototype.set = function(key, value) {
	    if (arguments.length === 2) {
	        this.setKeyValue(key, value);
	    } else {
	        this.setObject(key);
	    }
	};

	/**
	 * Set a data from the given key with value.
	 * @param {string} key A string for key
	 * @param {*} value A data
	 * @example
	 * //-- #1. Get Module --//
	 * var HashMap = require('tui-code-snippet').HashMap; // node, commonjs
	 * var HashMap = tui.util.HashMap; // distribution file
	 *
	 * //-- #2. Use property --//
	 * var hm = new HashMap();
	 * hm.setKeyValue('key', 'value');
	 */
	HashMap.prototype.setKeyValue = function(key, value) {
	    if (!this.has(key)) {
	        this.length += 1;
	    }
	    this[this.encodeKey(key)] = value;
	};

	/**
	 * Set a data from the given object.
	 * @param {Object} obj A object for data
	 * @example
	 * //-- #1. Get Module --//
	 * var HashMap = require('tui-code-snippet').HashMap; // node, commonjs
	 * var HashMap = tui.util.HashMap; // distribution file
	 *
	 * //-- #2. Use property --//
	 * var hm = new HashMap();
	 * hm.setObject({
	 *     'key1': 'data1',
	 *     'key2': 'data2'
	 * });
	 */
	HashMap.prototype.setObject = function(obj) {
	    var self = this;

	    collection.forEachOwnProperties(obj, function(value, key) {
	        self.setKeyValue(key, value);
	    });
	};

	/**
	 * Merge with the given another hashMap.
	 * @param {HashMap} hashMap Another hashMap instance
	 */
	HashMap.prototype.merge = function(hashMap) {
	    var self = this;

	    hashMap.each(function(value, key) {
	        self.setKeyValue(key, value);
	    });
	};

	/**
	 * Encode the given key for hashMap.
	 * @param {string} key A string for key
	 * @returns {string} A encoded key
	 * @private
	 */
	HashMap.prototype.encodeKey = function(key) {
	    return _MAPDATAPREFIX + key;
	};

	/**
	 * Decode the given key in hashMap.
	 * @param {string} key A string for key
	 * @returns {string} A decoded key
	 * @private
	 */
	HashMap.prototype.decodeKey = function(key) {
	    var decodedKey = key.split(_MAPDATAPREFIX);

	    return decodedKey[decodedKey.length - 1];
	};

	/**
	 * Return the value from the given key.
	 * @param {string} key A string for key
	 * @returns {*} The value from a key
	 * @example
	 * //-- #1. Get Module --//
	 * var HashMap = require('tui-code-snippet').HashMap; // node, commonjs
	 * var HashMap = tui.util.HashMap; // distribution file
	 *
	 * //-- #2. Use property --//
	 * var hm = new HashMap();
	 * hm.set('key', 'value');
	 * hm.get('key') // value
	 */
	HashMap.prototype.get = function(key) {
	    return this[this.encodeKey(key)];
	};

	/**
	 * Check the existence of a value from the key.
	 * @param {string} key A string for key
	 * @returns {boolean} Indicating whether a value exists or not.
	 * @example
	 * //-- #1. Get Module --//
	 * var HashMap = require('tui-code-snippet').HashMap; // node, commonjs
	 * var HashMap = tui.util.HashMap; // distribution file
	 *
	 * //-- #2. Use property --//
	 * var hm = new HashMap();
	 * hm.set('key', 'value');
	 * hm.has('key') // true
	 */
	HashMap.prototype.has = function(key) {
	    return this.hasOwnProperty(this.encodeKey(key));
	};

	/**
	 * Remove a data(key-value pairs) from the given key or the given key-list.
	 * @param {...string|string[]} key A string for key
	 * @returns {string|string[]} A removed data
	 * @example
	 * //-- #1. Get Module --//
	 * var HashMap = require('tui-code-snippet').HashMap; // node, commonjs
	 * var HashMap = tui.util.HashMap; // distribution file
	 *
	 * //-- #2. Use property --//
	 * var hm = new HashMap();
	 * hm.set('key', 'value');
	 * hm.set('key2', 'value');
	 *
	 * hm.remove('key');
	 * hm.remove('key', 'key2');
	 * hm.remove(['key', 'key2']);
	 */
	HashMap.prototype.remove = function(key) {
	    if (arguments.length > 1) {
	        key = collection.toArray(arguments);
	    }

	    return type.isArray(key) ? this.removeByKeyArray(key) : this.removeByKey(key);
	};

	/**
	 * Remove data(key-value pair) from the given key.
	 * @param {string} key A string for key
	 * @returns {*|null} A removed data
	 * @example
	 * //-- #1. Get Module --//
	 * var HashMap = require('tui-code-snippet').HashMap; // node, commonjs
	 * var HashMap = tui.util.HashMap; // distribution file
	 *
	 * //-- #2. Use property --//
	 * var hm = new HashMap();
	 * hm.set('key', 'value');
	 * hm.removeByKey('key')
	 */
	HashMap.prototype.removeByKey = function(key) {
	    var data = this.has(key) ? this.get(key) : null;

	    if (data !== null) {
	        delete this[this.encodeKey(key)];
	        this.length -= 1;
	    }

	    return data;
	};

	/**
	 * Remove a data(key-value pairs) from the given key-list.
	 * @param {string[]} keyArray An array of keys
	 * @returns {string[]} A removed data
	 * @example
	 * //-- #1. Get Module --//
	 * var HashMap = require('tui-code-snippet').HashMap; // node, commonjs
	 * var HashMap = tui.util.HashMap; // distribution file
	 *
	 * //-- #2. Use property --//
	 * var hm = new HashMap();
	 * hm.set('key', 'value');
	 * hm.set('key2', 'value');
	 * hm.removeByKeyArray(['key', 'key2']);
	 */
	HashMap.prototype.removeByKeyArray = function(keyArray) {
	    var data = [];
	    var self = this;

	    collection.forEach(keyArray, function(key) {
	        data.push(self.removeByKey(key));
	    });

	    return data;
	};

	/**
	 * Remove all the data
	 */
	HashMap.prototype.removeAll = function() {
	    var self = this;

	    this.each(function(value, key) {
	        self.remove(key);
	    });
	};

	/**
	 * Execute the provided callback once for each all the data.
	 * @param {Function} iteratee Callback function
	 * @example
	 * //-- #1. Get Module --//
	 * var HashMap = require('tui-code-snippet').HashMap; // node, commonjs
	 * var HashMap = tui.util.HashMap; // distribution file
	 *
	 * //-- #2. Use property --//
	 * var hm = new HashMap();
	 * hm.set('key', 'value');
	 * hm.set('key2', 'value');
	 *
	 * hm.each(function(value, key) {
	 *     //do something...
	 * });
	 */
	HashMap.prototype.each = function(iteratee) {
	    var self = this;
	    var flag;

	    collection.forEachOwnProperties(this, function(value, key) { // eslint-disable-line consistent-return
	        if (key.charAt(0) === _MAPDATAPREFIX) {
	            flag = iteratee(value, self.decodeKey(key));
	        }

	        if (flag === false) {
	            return flag;
	        }
	    });
	};

	/**
	 * Return the key-list stored.
	 * @returns {Array} A key-list
	 * @example
	 * //-- #1. Get Module --//
	 * var HashMap = require('tui-code-snippet').HashMap; // node, commonjs
	 * var HashMap = tui.util.HashMap; // distribution file
	 *
	 * //-- #2. Use property --//
	 *  var hm = new HashMap();
	 *  hm.set('key', 'value');
	 *  hm.set('key2', 'value');
	 *  hm.keys();  //['key', 'key2');
	 */
	HashMap.prototype.keys = function() {
	    var keys = [];
	    var self = this;

	    this.each(function(value, key) {
	        keys.push(self.decodeKey(key));
	    });

	    return keys;
	};

	/**
	 * Work similarly to Array.prototype.map().<br>
	 * It executes the provided callback that checks conditions once for each element of hashMap,<br>
	 *  and returns a new array having elements satisfying the conditions
	 * @param {Function} condition A function that checks conditions
	 * @returns {Array} A new array having elements satisfying the conditions
	 * @example
	 * //-- #1. Get Module --//
	 * var HashMap = require('tui-code-snippet').HashMap; // node, commonjs
	 * var HashMap = tui.util.HashMap; // distribution file
	 *
	 * //-- #2. Use property --//
	 * var hm1 = new HashMap();
	 * hm1.set('key', 'value');
	 * hm1.set('key2', 'value');
	 *
	 * hm1.find(function(value, key) {
	 *     return key === 'key2';
	 * }); // ['value']
	 *
	 * var hm2 = new HashMap({
	 *     'myobj1': {
	 *         visible: true
	 *     },
	 *     'mybobj2': {
	 *         visible: false
	 *     }
	 * });
	 *
	 * hm2.find(function(obj, key) {
	 *     return obj.visible === true;
	 * }); // [{visible: true}];
	 */
	HashMap.prototype.find = function(condition) {
	    var founds = [];

	    this.each(function(value, key) {
	        if (condition(value, key)) {
	            founds.push(value);
	        }
	    });

	    return founds;
	};

	/**
	 * Return a new Array having all values.
	 * @returns {Array} A new array having all values
	 */
	HashMap.prototype.toArray = function() {
	    var result = [];

	    this.each(function(v) {
	        result.push(v);
	    });

	    return result;
	};

	module.exports = HashMap;


/***/ })
/******/ ])
});
;

/***/ }),

/***/ "./node_modules/tui-date-picker/dist/tui-date-picker.css":
/*!***************************************************************!*\
  !*** ./node_modules/tui-date-picker/dist/tui-date-picker.css ***!
  \***************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {


var content = __webpack_require__(/*! !../../css-loader??ref--5-1!../../postcss-loader/src??ref--5-2!./tui-date-picker.css */ "./node_modules/css-loader/index.js?!./node_modules/postcss-loader/src/index.js?!./node_modules/tui-date-picker/dist/tui-date-picker.css");

if(typeof content === 'string') content = [[module.i, content, '']];

var transform;
var insertInto;



var options = {"hmr":true}

options.transform = transform
options.insertInto = undefined;

var update = __webpack_require__(/*! ../../style-loader/lib/addStyles.js */ "./node_modules/style-loader/lib/addStyles.js")(content, options);

if(content.locals) module.exports = content.locals;

if(false) {}

/***/ }),

/***/ "./node_modules/tui-date-picker/dist/tui-date-picker.js":
/*!**************************************************************!*\
  !*** ./node_modules/tui-date-picker/dist/tui-date-picker.js ***!
  \**************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

/*!
 * TOAST UI Date Picker
 * @version 4.1.0
 * @author NHN. FE Development Lab <dl_javascript@nhn.com>
 * @license MIT
 */
(function webpackUniversalModuleDefinition(root, factory) {
	if(true)
		module.exports = factory(__webpack_require__(/*! tui-time-picker */ "./node_modules/tui-time-picker/dist/tui-time-picker.js"));
	else {}
})(window, function(__WEBPACK_EXTERNAL_MODULE__43__) {
return /******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, { enumerable: true, get: getter });
/******/ 		}
/******/ 	};
/******/
/******/ 	// define __esModule on exports
/******/ 	__webpack_require__.r = function(exports) {
/******/ 		if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 			Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 		}
/******/ 		Object.defineProperty(exports, '__esModule', { value: true });
/******/ 	};
/******/
/******/ 	// create a fake namespace object
/******/ 	// mode & 1: value is a module id, require it
/******/ 	// mode & 2: merge all properties of value into the ns
/******/ 	// mode & 4: return value when already ns object
/******/ 	// mode & 8|1: behave like require
/******/ 	__webpack_require__.t = function(value, mode) {
/******/ 		if(mode & 1) value = __webpack_require__(value);
/******/ 		if(mode & 8) return value;
/******/ 		if((mode & 4) && typeof value === 'object' && value && value.__esModule) return value;
/******/ 		var ns = Object.create(null);
/******/ 		__webpack_require__.r(ns);
/******/ 		Object.defineProperty(ns, 'default', { enumerable: true, value: value });
/******/ 		if(mode & 2 && typeof value != 'string') for(var key in value) __webpack_require__.d(ns, key, function(key) { return value[key]; }.bind(null, key));
/******/ 		return ns;
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "dist";
/******/
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 34);
/******/ })
/************************************************************************/
/******/ ([
/* 0 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";
/**
 * @fileoverview
 * This module provides a function to make a constructor
 * that can inherit from the other constructors like the CLASS easily.
 * @author NHN FE Development Lab <dl_javascript@nhn.com>
 */



var inherit = __webpack_require__(35);
var extend = __webpack_require__(7);

/**
 * @module defineClass
 */

/**
 * Help a constructor to be defined and to inherit from the other constructors
 * @param {*} [parent] Parent constructor
 * @param {Object} props Members of constructor
 *  @param {Function} props.init Initialization method
 *  @param {Object} [props.static] Static members of constructor
 * @returns {*} Constructor
 * @memberof module:defineClass
 * @example
 * var defineClass = require('tui-code-snippet/defineClass/defineClass'); // node, commonjs
 *
 * //-- #2. Use property --//
 * var Parent = defineClass({
 *     init: function() { // constuructor
 *         this.name = 'made by def';
 *     },
 *     method: function() {
 *         // ...
 *     },
 *     static: {
 *         staticMethod: function() {
 *              // ...
 *         }
 *     }
 * });
 *
 * var Child = defineClass(Parent, {
 *     childMethod: function() {}
 * });
 *
 * Parent.staticMethod();
 *
 * var parentInstance = new Parent();
 * console.log(parentInstance.name); //made by def
 * parentInstance.staticMethod(); // Error
 *
 * var childInstance = new Child();
 * childInstance.method();
 * childInstance.childMethod();
 */
function defineClass(parent, props) {
  var obj;

  if (!props) {
    props = parent;
    parent = null;
  }

  obj = props.init || function() {};

  if (parent) {
    inherit(obj, parent);
  }

  if (props.hasOwnProperty('static')) {
    extend(obj, props['static']);
    delete props['static'];
  }

  extend(obj.prototype, props);

  return obj;
}

module.exports = defineClass;


/***/ }),
/* 1 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";
/**
 * @fileoverview Constants of date-picker
 * @author NHN. FE Development Lab <dl_javascript@nhn.com>
 */



module.exports = {
  TYPE_DATE: 'date',
  TYPE_MONTH: 'month',
  TYPE_YEAR: 'year',
  TYPE_HOUR: 'hour',
  TYPE_MINUTE: 'minute',
  TYPE_MERIDIEM: 'meridiem',
  MIN_DATE: new Date(1900, 0, 1),
  MAX_DATE: new Date(2999, 11, 31),

  DEFAULT_LANGUAGE_TYPE: 'en',

  CLASS_NAME_SELECTED: 'tui-is-selected',

  CLASS_NAME_PREV_MONTH_BTN: 'tui-calendar-btn-prev-month',
  CLASS_NAME_PREV_YEAR_BTN: 'tui-calendar-btn-prev-year',
  CLASS_NAME_NEXT_YEAR_BTN: 'tui-calendar-btn-next-year',
  CLASS_NAME_NEXT_MONTH_BTN: 'tui-calendar-btn-next-month'
};


/***/ }),
/* 2 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";
/**
 * @fileoverview Execute the provided callback once for each element present in the array(or Array-like object) in ascending order.
 * @author NHN FE Development Lab <dl_javascript@nhn.com>
 */



/**
 * Execute the provided callback once for each element present
 * in the array(or Array-like object) in ascending order.
 * If the callback function returns false, the loop will be stopped.
 * Callback function(iteratee) is invoked with three arguments:
 *  1) The value of the element
 *  2) The index of the element
 *  3) The array(or Array-like object) being traversed
 * @param {Array|Arguments|NodeList} arr The array(or Array-like object) that will be traversed
 * @param {function} iteratee Callback function
 * @param {Object} [context] Context(this) of callback function
 * @memberof module:collection
 * @example
 * var forEachArray = require('tui-code-snippet/collection/forEachArray'); // node, commonjs
 *
 * var sum = 0;
 *
 * forEachArray([1,2,3], function(value){
 *     sum += value;
 * });
 * alert(sum); // 6
 */
function forEachArray(arr, iteratee, context) {
  var index = 0;
  var len = arr.length;

  context = context || null;

  for (; index < len; index += 1) {
    if (iteratee.call(context, arr[index], index, arr) === false) {
      break;
    }
  }
}

module.exports = forEachArray;


/***/ }),
/* 3 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";
/* eslint-disable complexity */
/**
 * @fileoverview Returns the first index at which a given element can be found in the array.
 * @author NHN FE Development Lab <dl_javascript@nhn.com>
 */



var isArray = __webpack_require__(6);

/**
 * @module array
 */

/**
 * Returns the first index at which a given element can be found in the array
 * from start index(default 0), or -1 if it is not present.
 * It compares searchElement to elements of the Array using strict equality
 * (the same method used by the ===, or triple-equals, operator).
 * @param {*} searchElement Element to locate in the array
 * @param {Array} array Array that will be traversed.
 * @param {number} startIndex Start index in array for searching (default 0)
 * @returns {number} the First index at which a given element, or -1 if it is not present
 * @memberof module:array
 * @example
 * var inArray = require('tui-code-snippet/array/inArray'); // node, commonjs
 *
 * var arr = ['one', 'two', 'three', 'four'];
 * var idx1 = inArray('one', arr, 3); // -1
 * var idx2 = inArray('one', arr); // 0
 */
function inArray(searchElement, array, startIndex) {
  var i;
  var length;
  startIndex = startIndex || 0;

  if (!isArray(array)) {
    return -1;
  }

  if (Array.prototype.indexOf) {
    return Array.prototype.indexOf.call(array, searchElement, startIndex);
  }

  length = array.length;
  for (i = startIndex; startIndex >= 0 && i < length; i += 1) {
    if (array[i] === searchElement) {
      return i;
    }
  }

  return -1;
}

module.exports = inArray;


/***/ }),
/* 4 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";
/**
 * @fileoverview Utils for Datepicker component
 * @author NHN. FE Development Lab <dl_javascript@nhn.com>
 */



var forEachArray = __webpack_require__(2);
var isHTMLNode = __webpack_require__(46);
var sendHostname = __webpack_require__(47);

var currentId = 0;

var utils = {
  /**
   * Get a target element
   * @param {Event} ev Event object
   * @returns {HTMLElement} An event target element
   */
  getTarget: function(ev) {
    return ev.target || ev.srcElement;
  },

  /**
   * Return the same element with an element or a matched element searched by a selector.
   * @param {HTMLElement|string} param HTMLElement or selector
   * @returns {HTMLElement} A matched element
   */
  getElement: function(param) {
    return isHTMLNode(param) ? param : document.querySelector(param);
  },

  /**
   * Get a selector of the element.
   * @param {HTMLElement} elem An element
   * @returns {string}
   */
  getSelector: function(elem) {
    var selector = '';
    if (elem.id) {
      selector = '#' + elem.id;
    } else if (elem.className) {
      selector = '.' + elem.className.split(' ')[0];
    }

    return selector;
  },

  /**
   * Create an unique id.
   * @returns {number}
   */
  generateId: function() {
    currentId += 1;

    return currentId;
  },

  /**
   * Create a new array with all elements that pass the test implemented by the provided function.
   * @param {Array} arr - Array that will be traversed
   * @param {function} iteratee - iteratee callback function
   * @returns {Array}
   */
  filter: function(arr, iteratee) {
    var result = [];

    forEachArray(arr, function(item) {
      if (iteratee(item)) {
        result.push(item);
      }
    });

    return result;
  },

  /**
   * Send hostname for GA
   * @ignore
   */
  sendHostName: function() {
    sendHostname('date-picker', 'UA-129987462-1');
  }
};

module.exports = utils;


/***/ }),
/* 5 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";
/**
 * @fileoverview Utils for DatePicker component
 * @author NHN. FE dev Lab. <dl_javascript@nhn.com>
 */



var isDate = __webpack_require__(28);
var isNumber = __webpack_require__(15);

var constants = __webpack_require__(1);

var TYPE_DATE = constants.TYPE_DATE;
var TYPE_MONTH = constants.TYPE_MONTH;
var TYPE_YEAR = constants.TYPE_YEAR;

/**
 * Utils of calendar
 * @namespace dateUtil
 * @ignore
 */
var utils = {
  /**
   * Get weeks count by paramenter
   * @param {number} year A year
   * @param {number} month A month
   * @returns {number} Weeks count (4~6)
   **/
  getWeeksCount: function(year, month) {
    var firstDay = utils.getFirstDay(year, month),
      lastDate = utils.getLastDayInMonth(year, month);

    return Math.ceil((firstDay + lastDate) / 7);
  },

  /**
   * @param {Date} date - Date instance
   * @returns {boolean}
   */
  isValidDate: function(date) {
    return isDate(date) && !isNaN(date.getTime());
  },

  /**
   * Get which day is first by parameters that include year and month information.
   * @param {number} year A year
   * @param {number} month A month
   * @returns {number} (0~6)
   */
  getFirstDay: function(year, month) {
    return new Date(year, month - 1, 1).getDay();
  },

  /**
   * Get timestamp of the first day.
   * @param {number} year A year
   * @param {number} month A month
   * @returns {number} timestamp
   */
  getFirstDayTimestamp: function(year, month) {
    return new Date(year, month, 1).getTime();
  },

  /**
   * Get last date by parameters that include year and month information.
   * @param {number} year A year
   * @param {number} month A month
   * @returns {number} (1~31)
   */
  getLastDayInMonth: function(year, month) {
    return new Date(year, month, 0).getDate();
  },

  /**
   * Chagne number 0~9 to '00~09'
   * @param {number} number number
   * @returns {string}
   * @example
   *  dateUtil.prependLeadingZero(0); //  '00'
   *  dateUtil.prependLeadingZero(9); //  '09'
   *  dateUtil.prependLeadingZero(12); //  '12'
   */
  prependLeadingZero: function(number) {
    var prefix = '';

    if (number < 10) {
      prefix = '0';
    }

    return prefix + number;
  },

  /**
   * Get meridiem hour
   * @param {number} hour - Original hour
   * @returns {number} Converted meridiem hour
   */
  getMeridiemHour: function(hour) {
    hour %= 12;

    if (hour === 0) {
      hour = 12;
    }

    return hour;
  },

  /**
   * Returns number or default
   * @param {*} any - Any value
   * @param {number} defaultNumber - Default number
   * @throws Will throw an error if the defaultNumber is invalid.
   * @returns {number}
   */
  getSafeNumber: function(any, defaultNumber) {
    if (isNaN(defaultNumber) || !isNumber(defaultNumber)) {
      throw Error('The defaultNumber must be a valid number.');
    }
    if (isNaN(any)) {
      return defaultNumber;
    }

    return Number(any);
  },

  /**
   * Return date of the week
   * @param {number} year - Year
   * @param {number} month - Month
   * @param {number} weekNumber - Week number (0~5)
   * @param {number} dayNumber - Day number (0: sunday, 1: monday, ....)
   * @returns {number}
   */
  getDateOfWeek: function(year, month, weekNumber, dayNumber) {
    var firstDayOfMonth = new Date(year, month - 1).getDay();
    var dateOffset = firstDayOfMonth - dayNumber - 1;

    return new Date(year, month - 1, weekNumber * 7 - dateOffset);
  },

  /**
   * Returns range arr
   * @param {number} start - Start value
   * @param {number} end - End value
   * @returns {Array}
   */
  getRangeArr: function(start, end) {
    var arr = [];
    var i;

    if (start > end) {
      for (i = end; i >= start; i -= 1) {
        arr.push(i);
      }
    } else {
      for (i = start; i <= end; i += 1) {
        arr.push(i);
      }
    }

    return arr;
  },

  /**
   * Returns cloned date with the start of a unit of time
   * @param {Date|number} date - Original date
   * @param {string} [type = TYPE_DATE] - Unit type
   * @throws {Error}
   * @returns {Date}
   */
  cloneWithStartOf: function(date, type) {
    type = type || TYPE_DATE;
    date = new Date(date);

    // Does not consider time-level yet.
    date.setHours(0, 0, 0, 0);

    switch (type) {
      case TYPE_DATE:
        break;
      case TYPE_MONTH:
        date.setDate(1);
        break;
      case TYPE_YEAR:
        date.setMonth(0, 1);
        break;
      default:
        throw Error('Unsupported type: ' + type);
    }

    return date;
  },

  /**
   * Returns cloned date with the end of a unit of time
   * @param {Date|number} date - Original date
   * @param {string} [type = TYPE_DATE] - Unit type
   * @throws {Error}
   * @returns {Date}
   */
  cloneWithEndOf: function(date, type) {
    type = type || TYPE_DATE;
    date = new Date(date);

    // Does not consider time-level yet.
    date.setHours(23, 59, 59, 999);

    switch (type) {
      case TYPE_DATE:
        break;
      case TYPE_MONTH:
        date.setMonth(date.getMonth() + 1, 0);
        break;
      case TYPE_YEAR:
        date.setMonth(11, 31);
        break;
      default:
        throw Error('Unsupported type: ' + type);
    }

    return date;
  },

  /**
   * Compare two dates
   * @param {Date|number} dateA - Date
   * @param {Date|number} dateB - Date
   * @param {string} [cmpLevel] - Comparing level
   * @returns {number}
   */
  compare: function(dateA, dateB, cmpLevel) {
    var aTimestamp, bTimestamp;

    if (!(utils.isValidDate(dateA) && utils.isValidDate(dateB))) {
      return NaN;
    }

    if (!cmpLevel) {
      aTimestamp = dateA.getTime();
      bTimestamp = dateB.getTime();
    } else {
      aTimestamp = utils.cloneWithStartOf(dateA, cmpLevel).getTime();
      bTimestamp = utils.cloneWithStartOf(dateB, cmpLevel).getTime();
    }

    if (aTimestamp > bTimestamp) {
      return 1;
    }

    return aTimestamp === bTimestamp ? 0 : -1;
  },

  /**
   * Returns whether two dates are same
   * @param {Date|number} dateA - Date
   * @param {Date|number} dateB - Date
   * @param {string} [cmpLevel] - Comparing level
   * @returns {boolean}
   */
  isSame: function(dateA, dateB, cmpLevel) {
    return utils.compare(dateA, dateB, cmpLevel) === 0;
  },

  /**
   * Returns whether the target is in range
   * @param {Date|number} start - Range start
   * @param {Date|number} end - Range end
   * @param {Date|number} target - Target
   * @param {string} [cmpLevel = TYPE_DATE] - Comparing level
   * @returns {boolean}
   */
  inRange: function(start, end, target, cmpLevel) {
    return utils.compare(start, target, cmpLevel) < 1 && utils.compare(end, target, cmpLevel) > -1;
  }
};

module.exports = utils;


/***/ }),
/* 6 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";
/**
 * @fileoverview Check whether the given variable is an instance of Array or not.
 * @author NHN FE Development Lab <dl_javascript@nhn.com>
 */



/**
 * Check whether the given variable is an instance of Array or not.
 * If the given variable is an instance of Array, return true.
 * @param {*} obj - Target for checking
 * @returns {boolean} Is array instance?
 * @memberof module:type
 */
function isArray(obj) {
  return obj instanceof Array;
}

module.exports = isArray;


/***/ }),
/* 7 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";
/**
 * @fileoverview Extend the target object from other objects.
 * @author NHN FE Development Lab <dl_javascript@nhn.com>
 */



/**
 * @module object
 */

/**
 * Extend the target object from other objects.
 * @param {object} target - Object that will be extended
 * @param {...object} objects - Objects as sources
 * @returns {object} Extended object
 * @memberof module:object
 */
function extend(target, objects) { // eslint-disable-line no-unused-vars
  var hasOwnProp = Object.prototype.hasOwnProperty;
  var source, prop, i, len;

  for (i = 1, len = arguments.length; i < len; i += 1) {
    source = arguments[i];
    for (prop in source) {
      if (hasOwnProp.call(source, prop)) {
        target[prop] = source[prop];
      }
    }
  }

  return target;
}

module.exports = extend;


/***/ }),
/* 8 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";
/**
 * @fileoverview This module provides some functions for custom events. And it is implemented in the observer design pattern.
 * @author NHN FE Development Lab <dl_javascript@nhn.com>
 */



var extend = __webpack_require__(7);
var isExisty = __webpack_require__(37);
var isString = __webpack_require__(13);
var isObject = __webpack_require__(22);
var isArray = __webpack_require__(6);
var isFunction = __webpack_require__(39);
var forEach = __webpack_require__(9);

var R_EVENTNAME_SPLIT = /\s+/g;

/**
 * @class
 * @example
 * // node, commonjs
 * var CustomEvents = require('tui-code-snippet/customEvents/customEvents');
 */
function CustomEvents() {
  /**
     * @type {HandlerItem[]}
     */
  this.events = null;

  /**
     * only for checking specific context event was binded
     * @type {object[]}
     */
  this.contexts = null;
}

/**
 * Mixin custom events feature to specific constructor
 * @param {function} func - constructor
 * @example
 * var CustomEvents = require('tui-code-snippet/customEvents/customEvents'); // node, commonjs
 *
 * var model;
 * function Model() {
 *     this.name = '';
 * }
 * CustomEvents.mixin(Model);
 *
 * model = new Model();
 * model.on('change', function() { this.name = 'model'; }, this);
 * model.fire('change');
 * alert(model.name); // 'model';
 */
CustomEvents.mixin = function(func) {
  extend(func.prototype, CustomEvents.prototype);
};

/**
 * Get HandlerItem object
 * @param {function} handler - handler function
 * @param {object} [context] - context for handler
 * @returns {HandlerItem} HandlerItem object
 * @private
 */
CustomEvents.prototype._getHandlerItem = function(handler, context) {
  var item = {handler: handler};

  if (context) {
    item.context = context;
  }

  return item;
};

/**
 * Get event object safely
 * @param {string} [eventName] - create sub event map if not exist.
 * @returns {(object|array)} event object. if you supplied `eventName`
 *  parameter then make new array and return it
 * @private
 */
CustomEvents.prototype._safeEvent = function(eventName) {
  var events = this.events;
  var byName;

  if (!events) {
    events = this.events = {};
  }

  if (eventName) {
    byName = events[eventName];

    if (!byName) {
      byName = [];
      events[eventName] = byName;
    }

    events = byName;
  }

  return events;
};

/**
 * Get context array safely
 * @returns {array} context array
 * @private
 */
CustomEvents.prototype._safeContext = function() {
  var context = this.contexts;

  if (!context) {
    context = this.contexts = [];
  }

  return context;
};

/**
 * Get index of context
 * @param {object} ctx - context that used for bind custom event
 * @returns {number} index of context
 * @private
 */
CustomEvents.prototype._indexOfContext = function(ctx) {
  var context = this._safeContext();
  var index = 0;

  while (context[index]) {
    if (ctx === context[index][0]) {
      return index;
    }

    index += 1;
  }

  return -1;
};

/**
 * Memorize supplied context for recognize supplied object is context or
 *  name: handler pair object when off()
 * @param {object} ctx - context object to memorize
 * @private
 */
CustomEvents.prototype._memorizeContext = function(ctx) {
  var context, index;

  if (!isExisty(ctx)) {
    return;
  }

  context = this._safeContext();
  index = this._indexOfContext(ctx);

  if (index > -1) {
    context[index][1] += 1;
  } else {
    context.push([ctx, 1]);
  }
};

/**
 * Forget supplied context object
 * @param {object} ctx - context object to forget
 * @private
 */
CustomEvents.prototype._forgetContext = function(ctx) {
  var context, contextIndex;

  if (!isExisty(ctx)) {
    return;
  }

  context = this._safeContext();
  contextIndex = this._indexOfContext(ctx);

  if (contextIndex > -1) {
    context[contextIndex][1] -= 1;

    if (context[contextIndex][1] <= 0) {
      context.splice(contextIndex, 1);
    }
  }
};

/**
 * Bind event handler
 * @param {(string|{name:string, handler:function})} eventName - custom
 *  event name or an object {eventName: handler}
 * @param {(function|object)} [handler] - handler function or context
 * @param {object} [context] - context for binding
 * @private
 */
CustomEvents.prototype._bindEvent = function(eventName, handler, context) {
  var events = this._safeEvent(eventName);
  this._memorizeContext(context);
  events.push(this._getHandlerItem(handler, context));
};

/**
 * Bind event handlers
 * @param {(string|{name:string, handler:function})} eventName - custom
 *  event name or an object {eventName: handler}
 * @param {(function|object)} [handler] - handler function or context
 * @param {object} [context] - context for binding
 * //-- #1. Get Module --//
 * var CustomEvents = require('tui-code-snippet/customEvents/customEvents'); // node, commonjs
 *
 * //-- #2. Use method --//
 * // # 2.1 Basic Usage
 * CustomEvents.on('onload', handler);
 *
 * // # 2.2 With context
 * CustomEvents.on('onload', handler, myObj);
 *
 * // # 2.3 Bind by object that name, handler pairs
 * CustomEvents.on({
 *     'play': handler,
 *     'pause': handler2
 * });
 *
 * // # 2.4 Bind by object that name, handler pairs with context object
 * CustomEvents.on({
 *     'play': handler
 * }, myObj);
 */
CustomEvents.prototype.on = function(eventName, handler, context) {
  var self = this;

  if (isString(eventName)) {
    // [syntax 1, 2]
    eventName = eventName.split(R_EVENTNAME_SPLIT);
    forEach(eventName, function(name) {
      self._bindEvent(name, handler, context);
    });
  } else if (isObject(eventName)) {
    // [syntax 3, 4]
    context = handler;
    forEach(eventName, function(func, name) {
      self.on(name, func, context);
    });
  }
};

/**
 * Bind one-shot event handlers
 * @param {(string|{name:string,handler:function})} eventName - custom
 *  event name or an object {eventName: handler}
 * @param {function|object} [handler] - handler function or context
 * @param {object} [context] - context for binding
 */
CustomEvents.prototype.once = function(eventName, handler, context) {
  var self = this;

  if (isObject(eventName)) {
    context = handler;
    forEach(eventName, function(func, name) {
      self.once(name, func, context);
    });

    return;
  }

  function onceHandler() { // eslint-disable-line require-jsdoc
    handler.apply(context, arguments);
    self.off(eventName, onceHandler, context);
  }

  this.on(eventName, onceHandler, context);
};

/**
 * Splice supplied array by callback result
 * @param {array} arr - array to splice
 * @param {function} predicate - function return boolean
 * @private
 */
CustomEvents.prototype._spliceMatches = function(arr, predicate) {
  var i = 0;
  var len;

  if (!isArray(arr)) {
    return;
  }

  for (len = arr.length; i < len; i += 1) {
    if (predicate(arr[i]) === true) {
      arr.splice(i, 1);
      len -= 1;
      i -= 1;
    }
  }
};

/**
 * Get matcher for unbind specific handler events
 * @param {function} handler - handler function
 * @returns {function} handler matcher
 * @private
 */
CustomEvents.prototype._matchHandler = function(handler) {
  var self = this;

  return function(item) {
    var needRemove = handler === item.handler;

    if (needRemove) {
      self._forgetContext(item.context);
    }

    return needRemove;
  };
};

/**
 * Get matcher for unbind specific context events
 * @param {object} context - context
 * @returns {function} object matcher
 * @private
 */
CustomEvents.prototype._matchContext = function(context) {
  var self = this;

  return function(item) {
    var needRemove = context === item.context;

    if (needRemove) {
      self._forgetContext(item.context);
    }

    return needRemove;
  };
};

/**
 * Get matcher for unbind specific hander, context pair events
 * @param {function} handler - handler function
 * @param {object} context - context
 * @returns {function} handler, context matcher
 * @private
 */
CustomEvents.prototype._matchHandlerAndContext = function(handler, context) {
  var self = this;

  return function(item) {
    var matchHandler = (handler === item.handler);
    var matchContext = (context === item.context);
    var needRemove = (matchHandler && matchContext);

    if (needRemove) {
      self._forgetContext(item.context);
    }

    return needRemove;
  };
};

/**
 * Unbind event by event name
 * @param {string} eventName - custom event name to unbind
 * @param {function} [handler] - handler function
 * @private
 */
CustomEvents.prototype._offByEventName = function(eventName, handler) {
  var self = this;
  var andByHandler = isFunction(handler);
  var matchHandler = self._matchHandler(handler);

  eventName = eventName.split(R_EVENTNAME_SPLIT);

  forEach(eventName, function(name) {
    var handlerItems = self._safeEvent(name);

    if (andByHandler) {
      self._spliceMatches(handlerItems, matchHandler);
    } else {
      forEach(handlerItems, function(item) {
        self._forgetContext(item.context);
      });

      self.events[name] = [];
    }
  });
};

/**
 * Unbind event by handler function
 * @param {function} handler - handler function
 * @private
 */
CustomEvents.prototype._offByHandler = function(handler) {
  var self = this;
  var matchHandler = this._matchHandler(handler);

  forEach(this._safeEvent(), function(handlerItems) {
    self._spliceMatches(handlerItems, matchHandler);
  });
};

/**
 * Unbind event by object(name: handler pair object or context object)
 * @param {object} obj - context or {name: handler} pair object
 * @param {function} handler - handler function
 * @private
 */
CustomEvents.prototype._offByObject = function(obj, handler) {
  var self = this;
  var matchFunc;

  if (this._indexOfContext(obj) < 0) {
    forEach(obj, function(func, name) {
      self.off(name, func);
    });
  } else if (isString(handler)) {
    matchFunc = this._matchContext(obj);

    self._spliceMatches(this._safeEvent(handler), matchFunc);
  } else if (isFunction(handler)) {
    matchFunc = this._matchHandlerAndContext(handler, obj);

    forEach(this._safeEvent(), function(handlerItems) {
      self._spliceMatches(handlerItems, matchFunc);
    });
  } else {
    matchFunc = this._matchContext(obj);

    forEach(this._safeEvent(), function(handlerItems) {
      self._spliceMatches(handlerItems, matchFunc);
    });
  }
};

/**
 * Unbind custom events
 * @param {(string|object|function)} eventName - event name or context or
 *  {name: handler} pair object or handler function
 * @param {(function)} handler - handler function
 * @example
 * //-- #1. Get Module --//
 * var CustomEvents = require('tui-code-snippet/customEvents/customEvents'); // node, commonjs
 *
 * //-- #2. Use method --//
 * // # 2.1 off by event name
 * CustomEvents.off('onload');
 *
 * // # 2.2 off by event name and handler
 * CustomEvents.off('play', handler);
 *
 * // # 2.3 off by handler
 * CustomEvents.off(handler);
 *
 * // # 2.4 off by context
 * CustomEvents.off(myObj);
 *
 * // # 2.5 off by context and handler
 * CustomEvents.off(myObj, handler);
 *
 * // # 2.6 off by context and event name
 * CustomEvents.off(myObj, 'onload');
 *
 * // # 2.7 off by an Object.<string, function> that is {eventName: handler}
 * CustomEvents.off({
 *   'play': handler,
 *   'pause': handler2
 * });
 *
 * // # 2.8 off the all events
 * CustomEvents.off();
 */
CustomEvents.prototype.off = function(eventName, handler) {
  if (isString(eventName)) {
    // [syntax 1, 2]
    this._offByEventName(eventName, handler);
  } else if (!arguments.length) {
    // [syntax 8]
    this.events = {};
    this.contexts = [];
  } else if (isFunction(eventName)) {
    // [syntax 3]
    this._offByHandler(eventName);
  } else if (isObject(eventName)) {
    // [syntax 4, 5, 6]
    this._offByObject(eventName, handler);
  }
};

/**
 * Fire custom event
 * @param {string} eventName - name of custom event
 */
CustomEvents.prototype.fire = function(eventName) {  // eslint-disable-line
  this.invoke.apply(this, arguments);
};

/**
 * Fire a event and returns the result of operation 'boolean AND' with all
 *  listener's results.
 *
 * So, It is different from {@link CustomEvents#fire}.
 *
 * In service code, use this as a before event in component level usually
 *  for notifying that the event is cancelable.
 * @param {string} eventName - Custom event name
 * @param {...*} data - Data for event
 * @returns {boolean} The result of operation 'boolean AND'
 * @example
 * var map = new Map();
 * map.on({
 *     'beforeZoom': function() {
 *         // It should cancel the 'zoom' event by some conditions.
 *         if (that.disabled && this.getState()) {
 *             return false;
 *         }
 *         return true;
 *     }
 * });
 *
 * if (this.invoke('beforeZoom')) {    // check the result of 'beforeZoom'
 *     // if true,
 *     // doSomething
 * }
 */
CustomEvents.prototype.invoke = function(eventName) {
  var events, args, index, item;

  if (!this.hasListener(eventName)) {
    return true;
  }

  events = this._safeEvent(eventName);
  args = Array.prototype.slice.call(arguments, 1);
  index = 0;

  while (events[index]) {
    item = events[index];

    if (item.handler.apply(item.context, args) === false) {
      return false;
    }

    index += 1;
  }

  return true;
};

/**
 * Return whether at least one of the handlers is registered in the given
 *  event name.
 * @param {string} eventName - Custom event name
 * @returns {boolean} Is there at least one handler in event name?
 */
CustomEvents.prototype.hasListener = function(eventName) {
  return this.getListenerLength(eventName) > 0;
};

/**
 * Return a count of events registered.
 * @param {string} eventName - Custom event name
 * @returns {number} number of event
 */
CustomEvents.prototype.getListenerLength = function(eventName) {
  var events = this._safeEvent(eventName);

  return events.length;
};

module.exports = CustomEvents;


/***/ }),
/* 9 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";
/**
 * @fileoverview Execute the provided callback once for each property of object(or element of array) which actually exist.
 * @author NHN FE Development Lab <dl_javascript@nhn.com>
 */



var isArray = __webpack_require__(6);
var forEachArray = __webpack_require__(2);
var forEachOwnProperties = __webpack_require__(23);

/**
 * @module collection
 */

/**
 * Execute the provided callback once for each property of object(or element of array) which actually exist.
 * If the object is Array-like object(ex-arguments object), It needs to transform to Array.(see 'ex2' of example).
 * If the callback function returns false, the loop will be stopped.
 * Callback function(iteratee) is invoked with three arguments:
 *  1) The value of the property(or The value of the element)
 *  2) The name of the property(or The index of the element)
 *  3) The object being traversed
 * @param {Object} obj The object that will be traversed
 * @param {function} iteratee Callback function
 * @param {Object} [context] Context(this) of callback function
 * @memberof module:collection
 * @example
 * var forEach = require('tui-code-snippet/collection/forEach'); // node, commonjs
 *
 * var sum = 0;
 *
 * forEach([1,2,3], function(value){
 *     sum += value;
 * });
 * alert(sum); // 6
 *
 * // In case of Array-like object
 * var array = Array.prototype.slice.call(arrayLike); // change to array
 * forEach(array, function(value){
 *     sum += value;
 * });
 */
function forEach(obj, iteratee, context) {
  if (isArray(obj)) {
    forEachArray(obj, iteratee, context);
  } else {
    forEachOwnProperties(obj, iteratee, context);
  }
}

module.exports = forEach;


/***/ }),
/* 10 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";
/**
 * @fileoverview Default locale texts
 * @author NHN. FE Development Lab <dl_javascript@nhn.com>
 */



module.exports = {
  en: {
    titles: {
      DD: ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'],
      D: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
      MMM: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
      MMMM: [
        'January',
        'February',
        'March',
        'April',
        'May',
        'June',
        'July',
        'August',
        'September',
        'October',
        'November',
        'December'
      ]
    },
    titleFormat: 'MMMM yyyy',
    todayFormat: 'To\\d\\ay: DD, MMMM d, yyyy',
    time: 'Time',
    date: 'Date'
  },
  ko: {
    titles: {
      DD: ['일요일', '월요일', '화요일', '수요일', '목요일', '금요일', '토요일'],
      D: ['일', '월', '화', '수', '목', '금', '토'],
      MMM: ['1월', '2월', '3월', '4월', '5월', '6월', '7월', '8월', '9월', '10월', '11월', '12월'],
      MMMM: ['1월', '2월', '3월', '4월', '5월', '6월', '7월', '8월', '9월', '10월', '11월', '12월']
    },
    titleFormat: 'yyyy.MM',
    todayFormat: '오늘: yyyy.MM.dd (D)',
    date: '날짜',
    time: '시간'
  }
};


/***/ }),
/* 11 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";
/**
 * @fileoverview Convert text by binding expressions with context.
 * @author NHN FE Development Lab <dl_javascript@nhn.com>
 */



var inArray = __webpack_require__(3);
var forEach = __webpack_require__(9);
var isArray = __webpack_require__(6);
var isString = __webpack_require__(13);
var extend = __webpack_require__(7);

// IE8 does not support capture groups.
var EXPRESSION_REGEXP = /{{\s?|\s?}}/g;
var BRACKET_NOTATION_REGEXP = /^[a-zA-Z0-9_@]+\[[a-zA-Z0-9_@"']+\]$/;
var BRACKET_REGEXP = /\[\s?|\s?\]/;
var DOT_NOTATION_REGEXP = /^[a-zA-Z_]+\.[a-zA-Z_]+$/;
var DOT_REGEXP = /\./;
var STRING_NOTATION_REGEXP = /^["']\w+["']$/;
var STRING_REGEXP = /"|'/g;
var NUMBER_REGEXP = /^-?\d+\.?\d*$/;

var EXPRESSION_INTERVAL = 2;

var BLOCK_HELPERS = {
  'if': handleIf,
  'each': handleEach,
  'with': handleWith
};

var isValidSplit = 'a'.split(/a/).length === 3;

/**
 * Split by RegExp. (Polyfill for IE8)
 * @param {string} text - text to be splitted\
 * @param {RegExp} regexp - regular expression
 * @returns {Array.<string>}
 */
var splitByRegExp = (function() {
  if (isValidSplit) {
    return function(text, regexp) {
      return text.split(regexp);
    };
  }

  return function(text, regexp) {
    var result = [];
    var prevIndex = 0;
    var match, index;

    if (!regexp.global) {
      regexp = new RegExp(regexp, 'g');
    }

    match = regexp.exec(text);
    while (match !== null) {
      index = match.index;
      result.push(text.slice(prevIndex, index));

      prevIndex = index + match[0].length;
      match = regexp.exec(text);
    }
    result.push(text.slice(prevIndex));

    return result;
  };
})();

/**
 * Find value in the context by an expression.
 * @param {string} exp - an expression
 * @param {object} context - context
 * @returns {*}
 * @private
 */
// eslint-disable-next-line complexity
function getValueFromContext(exp, context) {
  var splitedExps;
  var value = context[exp];

  if (exp === 'true') {
    value = true;
  } else if (exp === 'false') {
    value = false;
  } else if (STRING_NOTATION_REGEXP.test(exp)) {
    value = exp.replace(STRING_REGEXP, '');
  } else if (BRACKET_NOTATION_REGEXP.test(exp)) {
    splitedExps = exp.split(BRACKET_REGEXP);
    value = getValueFromContext(splitedExps[0], context)[getValueFromContext(splitedExps[1], context)];
  } else if (DOT_NOTATION_REGEXP.test(exp)) {
    splitedExps = exp.split(DOT_REGEXP);
    value = getValueFromContext(splitedExps[0], context)[splitedExps[1]];
  } else if (NUMBER_REGEXP.test(exp)) {
    value = parseFloat(exp);
  }

  return value;
}

/**
 * Extract elseif and else expressions.
 * @param {Array.<string>} ifExps - args of if expression
 * @param {Array.<string>} sourcesInsideBlock - sources inside if block
 * @returns {object} - exps: expressions of if, elseif, and else / sourcesInsideIf: sources inside if, elseif, and else block.
 * @private
 */
function extractElseif(ifExps, sourcesInsideBlock) {
  var exps = [ifExps];
  var sourcesInsideIf = [];
  var otherIfCount = 0;
  var start = 0;

  // eslint-disable-next-line complexity
  forEach(sourcesInsideBlock, function(source, index) {
    if (source.indexOf('if') === 0) {
      otherIfCount += 1;
    } else if (source === '/if') {
      otherIfCount -= 1;
    } else if (!otherIfCount && (source.indexOf('elseif') === 0 || source === 'else')) {
      exps.push(source === 'else' ? ['true'] : source.split(' ').slice(1));
      sourcesInsideIf.push(sourcesInsideBlock.slice(start, index));
      start = index + 1;
    }
  });

  sourcesInsideIf.push(sourcesInsideBlock.slice(start));

  return {
    exps: exps,
    sourcesInsideIf: sourcesInsideIf
  };
}

/**
 * Helper function for "if". 
 * @param {Array.<string>} exps - array of expressions split by spaces
 * @param {Array.<string>} sourcesInsideBlock - array of sources inside the if block
 * @param {object} context - context
 * @returns {string}
 * @private
 */
function handleIf(exps, sourcesInsideBlock, context) {
  var analyzed = extractElseif(exps, sourcesInsideBlock);
  var result = false;
  var compiledSource = '';

  forEach(analyzed.exps, function(exp, index) {
    result = handleExpression(exp, context);
    if (result) {
      compiledSource = compile(analyzed.sourcesInsideIf[index], context);
    }

    return !result;
  });

  return compiledSource;
}

/**
 * Helper function for "each".
 * @param {Array.<string>} exps - array of expressions split by spaces
 * @param {Array.<string>} sourcesInsideBlock - array of sources inside the each block
 * @param {object} context - context
 * @returns {string}
 * @private
 */
function handleEach(exps, sourcesInsideBlock, context) {
  var collection = handleExpression(exps, context);
  var additionalKey = isArray(collection) ? '@index' : '@key';
  var additionalContext = {};
  var result = '';

  forEach(collection, function(item, key) {
    additionalContext[additionalKey] = key;
    additionalContext['@this'] = item;
    extend(context, additionalContext);

    result += compile(sourcesInsideBlock.slice(), context);
  });

  return result;
}

/**
 * Helper function for "with ... as"
 * @param {Array.<string>} exps - array of expressions split by spaces
 * @param {Array.<string>} sourcesInsideBlock - array of sources inside the with block
 * @param {object} context - context
 * @returns {string}
 * @private
 */
function handleWith(exps, sourcesInsideBlock, context) {
  var asIndex = inArray('as', exps);
  var alias = exps[asIndex + 1];
  var result = handleExpression(exps.slice(0, asIndex), context);

  var additionalContext = {};
  additionalContext[alias] = result;

  return compile(sourcesInsideBlock, extend(context, additionalContext)) || '';
}

/**
 * Extract sources inside block in place.
 * @param {Array.<string>} sources - array of sources
 * @param {number} start - index of start block
 * @param {number} end - index of end block
 * @returns {Array.<string>}
 * @private
 */
function extractSourcesInsideBlock(sources, start, end) {
  var sourcesInsideBlock = sources.splice(start + 1, end - start);
  sourcesInsideBlock.pop();

  return sourcesInsideBlock;
}

/**
 * Handle block helper function
 * @param {string} helperKeyword - helper keyword (ex. if, each, with)
 * @param {Array.<string>} sourcesToEnd - array of sources after the starting block
 * @param {object} context - context
 * @returns {Array.<string>}
 * @private
 */
function handleBlockHelper(helperKeyword, sourcesToEnd, context) {
  var executeBlockHelper = BLOCK_HELPERS[helperKeyword];
  var helperCount = 1;
  var startBlockIndex = 0;
  var endBlockIndex;
  var index = startBlockIndex + EXPRESSION_INTERVAL;
  var expression = sourcesToEnd[index];

  while (helperCount && isString(expression)) {
    if (expression.indexOf(helperKeyword) === 0) {
      helperCount += 1;
    } else if (expression.indexOf('/' + helperKeyword) === 0) {
      helperCount -= 1;
      endBlockIndex = index;
    }

    index += EXPRESSION_INTERVAL;
    expression = sourcesToEnd[index];
  }

  if (helperCount) {
    throw Error(helperKeyword + ' needs {{/' + helperKeyword + '}} expression.');
  }

  sourcesToEnd[startBlockIndex] = executeBlockHelper(
    sourcesToEnd[startBlockIndex].split(' ').slice(1),
    extractSourcesInsideBlock(sourcesToEnd, startBlockIndex, endBlockIndex),
    context
  );

  return sourcesToEnd;
}

/**
 * Helper function for "custom helper".
 * If helper is not a function, return helper itself.
 * @param {Array.<string>} exps - array of expressions split by spaces (first element: helper)
 * @param {object} context - context
 * @returns {string}
 * @private
 */
function handleExpression(exps, context) {
  var result = getValueFromContext(exps[0], context);

  if (result instanceof Function) {
    return executeFunction(result, exps.slice(1), context);
  }

  return result;
}

/**
 * Execute a helper function.
 * @param {Function} helper - helper function
 * @param {Array.<string>} argExps - expressions of arguments
 * @param {object} context - context
 * @returns {string} - result of executing the function with arguments
 * @private
 */
function executeFunction(helper, argExps, context) {
  var args = [];
  forEach(argExps, function(exp) {
    args.push(getValueFromContext(exp, context));
  });

  return helper.apply(null, args);
}

/**
 * Get a result of compiling an expression with the context.
 * @param {Array.<string>} sources - array of sources split by regexp of expression.
 * @param {object} context - context
 * @returns {Array.<string>} - array of sources that bind with its context
 * @private
 */
function compile(sources, context) {
  var index = 1;
  var expression = sources[index];
  var exps, firstExp, result;

  while (isString(expression)) {
    exps = expression.split(' ');
    firstExp = exps[0];

    if (BLOCK_HELPERS[firstExp]) {
      result = handleBlockHelper(firstExp, sources.splice(index, sources.length - index), context);
      sources = sources.concat(result);
    } else {
      sources[index] = handleExpression(exps, context);
    }

    index += EXPRESSION_INTERVAL;
    expression = sources[index];
  }

  return sources.join('');
}

/**
 * Convert text by binding expressions with context.
 * <br>
 * If expression exists in the context, it will be replaced.
 * ex) '{{title}}' with context {title: 'Hello!'} is converted to 'Hello!'.
 * An array or object can be accessed using bracket and dot notation.
 * ex) '{{odds\[2\]}}' with context {odds: \[1, 3, 5\]} is converted to '5'.
 * ex) '{{evens\[first\]}}' with context {evens: \[2, 4\], first: 0} is converted to '2'.
 * ex) '{{project\["name"\]}}' and '{{project.name}}' with context {project: {name: 'CodeSnippet'}} is converted to 'CodeSnippet'.
 * <br>
 * If replaced expression is a function, next expressions will be arguments of the function.
 * ex) '{{add 1 2}}' with context {add: function(a, b) {return a + b;}} is converted to '3'.
 * <br>
 * It has 3 predefined block helpers '{{helper ...}} ... {{/helper}}': 'if', 'each', 'with ... as ...'.
 * 1) 'if' evaluates conditional statements. It can use with 'elseif' and 'else'.
 * 2) 'each' iterates an array or object. It provides '@index'(array), '@key'(object), and '@this'(current element).
 * 3) 'with ... as ...' provides an alias.
 * @param {string} text - text with expressions
 * @param {object} context - context
 * @returns {string} - text that bind with its context
 * @memberof module:domUtil
 * @example
 * var template = require('tui-code-snippet/domUtil/template');
 * 
 * var source = 
 *     '<h1>'
 *   +   '{{if isValidNumber title}}'
 *   +     '{{title}}th'
 *   +   '{{elseif isValidDate title}}'
 *   +     'Date: {{title}}'
 *   +   '{{/if}}'
 *   + '</h1>'
 *   + '{{each list}}'
 *   +   '{{with addOne @index as idx}}'
 *   +     '<p>{{idx}}: {{@this}}</p>'
 *   +   '{{/with}}'
 *   + '{{/each}}';
 * 
 * var context = {
 *   isValidDate: function(text) {
 *     return /^\d{4}-(0|1)\d-(0|1|2|3)\d$/.test(text);
 *   },
 *   isValidNumber: function(text) {
 *     return /^\d+$/.test(text);
 *   }
 *   title: '2019-11-25',
 *   list: ['Clean the room', 'Wash the dishes'],
 *   addOne: function(num) {
 *     return num + 1;
 *   }
 * };
 * 
 * var result = template(source, context);
 * console.log(result); // <h1>Date: 2019-11-25</h1><p>1: Clean the room</p><p>2: Wash the dishes</p>
 */
function template(text, context) {
  return compile(splitByRegExp(text, EXPRESSION_REGEXP), context);
}

module.exports = template;


/***/ }),
/* 12 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";
/**
 * @fileoverview Check whether the given variable is undefined or not.
 * @author NHN FE Development Lab <dl_javascript@nhn.com>
 */



/**
 * Check whether the given variable is undefined or not.
 * If the given variable is undefined, returns true.
 * @param {*} obj - Target for checking
 * @returns {boolean} Is undefined?
 * @memberof module:type
 */
function isUndefined(obj) {
  return obj === undefined; // eslint-disable-line no-undefined
}

module.exports = isUndefined;


/***/ }),
/* 13 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";
/**
 * @fileoverview Check whether the given variable is a string or not.
 * @author NHN FE Development Lab <dl_javascript@nhn.com>
 */



/**
 * Check whether the given variable is a string or not.
 * If the given variable is a string, return true.
 * @param {*} obj - Target for checking
 * @returns {boolean} Is string?
 * @memberof module:type
 */
function isString(obj) {
  return typeof obj === 'string' || obj instanceof String;
}

module.exports = isString;


/***/ }),
/* 14 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";
/**
 * @fileoverview Remove element from parent node.
 * @author NHN FE Development Lab <dl_javascript@nhn.com>
 */



/**
 * Remove element from parent node.
 * @param {HTMLElement} element - element to remove.
 * @memberof module:domUtil
 */
function removeElement(element) {
  if (element && element.parentNode) {
    element.parentNode.removeChild(element);
  }
}

module.exports = removeElement;


/***/ }),
/* 15 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";
/**
 * @fileoverview Check whether the given variable is a number or not.
 * @author NHN FE Development Lab <dl_javascript@nhn.com>
 */



/**
 * Check whether the given variable is a number or not.
 * If the given variable is a number, return true.
 * @param {*} obj - Target for checking
 * @returns {boolean} Is number?
 * @memberof module:type
 */
function isNumber(obj) {
  return typeof obj === 'number' || obj instanceof Number;
}

module.exports = isNumber;


/***/ }),
/* 16 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";
/**
 * @fileoverview Add css class to element
 * @author NHN FE Development Lab <dl_javascript@nhn.com>
 */



var forEach = __webpack_require__(9);
var inArray = __webpack_require__(3);
var getClass = __webpack_require__(17);
var setClassName = __webpack_require__(24);

/**
 * domUtil module
 * @module domUtil
 */

/**
 * Add css class to element
 * @param {(HTMLElement|SVGElement)} element - target element
 * @param {...string} cssClass - css classes to add
 * @memberof module:domUtil
 */
function addClass(element) {
  var cssClass = Array.prototype.slice.call(arguments, 1);
  var classList = element.classList;
  var newClass = [];
  var origin;

  if (classList) {
    forEach(cssClass, function(name) {
      element.classList.add(name);
    });

    return;
  }

  origin = getClass(element);

  if (origin) {
    cssClass = [].concat(origin.split(/\s+/), cssClass);
  }

  forEach(cssClass, function(cls) {
    if (inArray(cls, newClass) < 0) {
      newClass.push(cls);
    }
  });

  setClassName(element, newClass);
}

module.exports = addClass;


/***/ }),
/* 17 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";
/**
 * @fileoverview Get HTML element's design classes.
 * @author NHN FE Development Lab <dl_javascript@nhn.com>
 */



var isUndefined = __webpack_require__(12);

/**
 * Get HTML element's design classes.
 * @param {(HTMLElement|SVGElement)} element target element
 * @returns {string} element css class name
 * @memberof module:domUtil
 */
function getClass(element) {
  if (!element || !element.className) {
    return '';
  }

  if (isUndefined(element.className.baseVal)) {
    return element.className;
  }

  return element.className.baseVal;
}

module.exports = getClass;


/***/ }),
/* 18 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";
/**
 * @fileoverview Remove css class from element
 * @author NHN FE Development Lab <dl_javascript@nhn.com>
 */



var forEachArray = __webpack_require__(2);
var inArray = __webpack_require__(3);
var getClass = __webpack_require__(17);
var setClassName = __webpack_require__(24);

/**
 * Remove css class from element
 * @param {(HTMLElement|SVGElement)} element - target element
 * @param {...string} cssClass - css classes to remove
 * @memberof module:domUtil
 */
function removeClass(element) {
  var cssClass = Array.prototype.slice.call(arguments, 1);
  var classList = element.classList;
  var origin, newClass;

  if (classList) {
    forEachArray(cssClass, function(name) {
      classList.remove(name);
    });

    return;
  }

  origin = getClass(element).split(/\s+/);
  newClass = [];
  forEachArray(origin, function(name) {
    if (inArray(name, cssClass) < 0) {
      newClass.push(name);
    }
  });

  setClassName(element, newClass);
}

module.exports = removeClass;


/***/ }),
/* 19 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";
/**
 * @fileoverview Set mouse-touch event
 * @author NHN. FE Development Lab <dl_javascript@nhn.com>
 */



var on = __webpack_require__(31);
var off = __webpack_require__(33);

var mouseTouchEvent = {
  /**
   * Detect mobile browser
   * @type {boolean} Whether using Mobile browser
   * @private
   */
  _isMobile: (function() {
    return /Android|BlackBerry|iPhone|iPad|iPod|Opera Mini|IEMobile|WPDesktop/i.test(
      navigator.userAgent
    );
  })(),

  /**
   * Return a matched event type by a mouse event type
   * @param {string} type A mouse event type - mousedown, click
   * @returns {string}
   * @private
   */
  _getEventType: function(type) {
    if (this._isMobile) {
      if (type === 'mousedown') {
        type = 'touchstart';
      } else if (type === 'click') {
        type = 'touchend';
      }
    }

    return type;
  },

  /**
   * Bind touch or mouse events
   * @param {HTMLElement} element An element to bind
   * @param {string} type A mouse event type - mousedown, click
   * @param {Function} handler A handler function
   * @param {object} [context] A context for handler.
   */
  on: function(element, type, handler, context) {
    on(element, this._getEventType(type), handler, context);
  },

  /**
   * Unbind touch or mouse events
   * @param {HTMLElement} element - Target element
   * @param {string} type A mouse event type - mousedown, click
   * @param {Function} handler - Handler
   */
  off: function(element, type, handler) {
    off(element, this._getEventType(type), handler);
  }
};

module.exports = mouseTouchEvent;


/***/ }),
/* 20 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";
/**
 * @fileoverview Layer base
 * @author NHN. FE Development Lab <dl_javascript@nhn.com>
 */



var defineClass = __webpack_require__(0);
var removeElement = __webpack_require__(14);

var localeText = __webpack_require__(10);
var DEFAULT_LANGUAGE_TYPE = __webpack_require__(1).DEFAULT_LANGUAGE_TYPE;

/**
 * @abstract
 * @class
 * @ignore
 * @param {string} language - Initial language
 * Layer base
 */
var LayerBase = defineClass(
  /** @lends LayerBase.prototype */ {
    init: function(language) {
      language = language || DEFAULT_LANGUAGE_TYPE;

      /**
       * Layer element
       * @type {HTMLElement}
       * @private
       */
      this._element = null;

      /**
       * Language type
       * @type {string}
       * @private
       */
      this._localeText = localeText[language];

      /**
       * Layer type
       * @type {string}
       * @private
       */
      this._type = 'base';
    },

    /**
     * Make context
     * @abstract
     * @throws {Error}
     * @returns {object}
     * @private
     */
    _makeContext: function() {
      throwOverrideError(this.getType(), '_makeContext');
    },

    /**
     * Render the layer element
     * @abstract
     * @throws {Error}
     */
    render: function() {
      throwOverrideError(this.getType(), 'render');
    },

    /**
     * Returns date elements
     * @abstract
     * @throws {Error}
     * @returns {HTMLElement[]}
     */
    getDateElements: function() {
      throwOverrideError(this.getType(), 'getDateElements');
    },

    /**
     * Returns layer type
     * @returns {string}
     */
    getType: function() {
      return this._type;
    },

    /**
     * Set language
     * @param {string} language - Language name
     */
    changeLanguage: function(language) {
      this._localeText = localeText[language];
    },

    /**
     * Remove elements
     */
    remove: function() {
      if (this._element) {
        removeElement(this._element);
      }
      this._element = null;
    }
  }
);

/**
 * Throw - method override error
 * @ignore
 * @param {string} layerType - Layer type
 * @param {string} methodName - Method name
 * @throws {Error}
 */
function throwOverrideError(layerType, methodName) {
  throw new Error(layerType + ' layer does not have the "' + methodName + '" method.');
}

module.exports = LayerBase;


/***/ }),
/* 21 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";
/**
 * @fileoverview DatePicker component
 * @author NHN. FE dev Lab <dl_javascript@nhn.com>
 */



var inArray = __webpack_require__(3);
var forEachArray = __webpack_require__(2);
var defineClass = __webpack_require__(0);
var CustomEvents = __webpack_require__(8);
var addClass = __webpack_require__(16);
var closest = __webpack_require__(25);
var getData = __webpack_require__(26);
var hasClass = __webpack_require__(27);
var removeClass = __webpack_require__(18);
var removeElement = __webpack_require__(14);
var extend = __webpack_require__(7);
var isArray = __webpack_require__(6);
var isDate = __webpack_require__(28);
var isNumber = __webpack_require__(15);
var isObject = __webpack_require__(22);

var TimePicker = __webpack_require__(43);

var Calendar = __webpack_require__(29);
var RangeModel = __webpack_require__(56);
var constants = __webpack_require__(1);
var localeTexts = __webpack_require__(10);
var dateUtil = __webpack_require__(5);
var util = __webpack_require__(4);
var mouseTouchEvent = __webpack_require__(19);
var tmpl = __webpack_require__(58);
var DatePickerInput = __webpack_require__(59);

var DEFAULT_LANGUAGE_TYPE = constants.DEFAULT_LANGUAGE_TYPE;
var TYPE_DATE = constants.TYPE_DATE;
var TYPE_MONTH = constants.TYPE_MONTH;
var TYPE_YEAR = constants.TYPE_YEAR;
var CLASS_NAME_NEXT_YEAR_BTN = constants.CLASS_NAME_NEXT_YEAR_BTN;
var CLASS_NAME_NEXT_MONTH_BTN = constants.CLASS_NAME_NEXT_MONTH_BTN;
var CLASS_NAME_PREV_YEAR_BTN = constants.CLASS_NAME_PREV_YEAR_BTN;
var CLASS_NAME_PREV_MONTH_BTN = constants.CLASS_NAME_PREV_MONTH_BTN;
var CLASS_NAME_SELECTED = constants.CLASS_NAME_SELECTED;

var CLASS_NAME_SELECTABLE = 'tui-is-selectable';
var CLASS_NAME_BLOCKED = 'tui-is-blocked';
var CLASS_NAME_CHECKED = 'tui-is-checked';
var CLASS_NAME_SELECTOR_BUTTON = 'tui-datepicker-selector-button';
var CLASS_NAME_TODAY = 'tui-calendar-today';
var CLASS_NAME_HIDDEN = 'tui-hidden';

var SELECTOR_BODY = '.tui-datepicker-body';
var SELECTOR_DATE_ICO = '.tui-ico-date';
var SELECTOR_CALENDAR_TITLE = '.tui-calendar-title';
var SELECTOR_CALENDAR_CONTAINER = '.tui-calendar-container';
var SELECTOR_TIMEPICKER_CONTAINER = '.tui-timepicker-container';

/**
 * Merge default option
 * @ignore
 * @param {object} option - DatePicker option
 * @returns {object}
 */
var mergeDefaultOption = function(option) {
  option = extend(
    {
      language: DEFAULT_LANGUAGE_TYPE,
      calendar: {},
      input: {
        element: null,
        format: null
      },
      timePicker: null,
      date: null,
      showAlways: false,
      type: TYPE_DATE,
      selectableRanges: null,
      openers: [],
      autoClose: true,
      usageStatistics: true
    },
    option
  );

  option.selectableRanges = option.selectableRanges || [[constants.MIN_DATE, constants.MAX_DATE]];

  if (!isObject(option.calendar)) {
    throw new Error('Calendar option must be an object');
  }
  if (!isObject(option.input)) {
    throw new Error('Input option must be an object');
  }
  if (!isArray(option.selectableRanges)) {
    throw new Error('Selectable-ranges must be a 2d-array');
  }

  option.localeText = localeTexts[option.language];

  // override calendar option
  option.calendar.language = option.language;
  option.calendar.type = option.type;

  // @TODO: after v5.0.0, remove option.timepicker
  option.timePicker = option.timePicker || option.timepicker;

  return option;
};

/**
 * @class
 * @description
 * Create a date picker.
 * @see {@link /tutorial-example01-basic DatePicker example}
 * @param {HTMLElement|string} container - Container element or selector of DatePicker
 * @param {Object} [options] - Options
 *      @param {Date|number} [options.date = null] - Initial date. Set by a Date instance or a number(timestamp). (default: no initial date)
 *      @param {('date'|'month'|'year')} [options.type = 'date'] - DatePicker type. Determine whether to choose a date, month, or year.
 *      @param {string} [options.language='en'] - Language code. English('en') and Korean('ko') are provided as default. To set to the other languages, use {@link DatePicker#localeTexts DatePicker.localeTexts}.
 *      @param {object|boolean} [options.timePicker] - [TimePicker](https://nhn.github.io/tui.time-picker/latest) options. Refer to the [TimePicker instance's options](https://nhn.github.io/tui.time-picker/latest/TimePicker). To create the TimePicker without customization, set to true.
 *      @param {object} [options.calendar] - {@link Calendar} options. Refer to the {@link Calendar Calendar instance's options}.
 *      @param {object} [options.input] - Input option
 *      @param {HTMLElement|string} [options.input.element] - Input element or selector
 *      @param {string} [options.input.format = 'yyyy-mm-dd'] - Format of the Date string
 *      @param {Array.<Array.<Date|number>>} [options.selectableRanges = 1900/1/1 ~ 2999/12/31]
 *        - Ranges of selectable date. Set by Date instances or numbers(timestamp).
 *      @param {Array<HTMLElement|string>} [options.openers = []] - List of the openers to open the DatePicker (example - icon, button, etc.)
 *      @param {boolean} [options.showAlways = false] - Show the DatePicker always
 *      @param {boolean} [options.autoClose = true] - Close the DatePicker after clicking the date
 *      @param {boolean} [options.usageStatistics = true] - Send a hostname to Google Analytics (default: true)
 * @example
 * import DatePicker from 'tui-date-picker' // ES6
 * // const DatePicker = require('tui-date-picker'); // CommonJS
 * // const DatePicker = tui.DatePicker;
 *
 * const range1 = [new Date(2015, 2, 1), new Date(2015, 3, 1)];
 * const range2 = [1465570800000, 1481266182155]; // timestamps
 *
 * const picker1 = new DatePicker('#datepicker-container1', {
 *     showAlways: true
 * });
 *
 * const picker2 = new DatePicker('#datepicker-container2', {
 *    showAlways: true,
 *    timePicker: true
 * });
 *
 * const picker3 = new DatePicker('#datepicker-container3', {
 *     language: 'ko',
 *     calendar: {
 *         showToday: true
 *     },
 *     timePicker: {
 *         showMeridiem: true,
 *         defaultHour: 13,
 *         defaultMinute: 24
 *     },
 *     input: {
 *         element: '#datepicker-input',
 *         format: 'yyyy년 MM월 dd일 hh:mm A'
 *     }
 *     type: 'date',
 *     date: new Date(2015, 0, 1)
 *     selectableRanges: [range1, range2],
 *     openers: ['#opener']
 * });
 */
var DatePicker = defineClass(
  /** @lends DatePicker.prototype */ {
    static: {
      /**
       * Locale text data. English('en') and Korean('ko') are provided as default.
       * @type {object}
       * @memberof DatePicker
       * @static
       * @example
       * DatePicker.localeTexts['customKey'] = {
       *     titles: {
       *         // days
       *         DD: ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'],
       *         // daysShort
       *         D: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
       *         // months
       *         MMMM: [
       *             'January', 'February', 'March', 'April', 'May', 'June',
       *             'July', 'August', 'September', 'October', 'November', 'December'
       *         ],
       *         // monthsShort
       *         MMM: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
       *     },
       *     titleFormat: 'MMM yyyy',
       *     todayFormat: 'D, MMMM dd, yyyy',
       *     date: 'Date',
       *     time: 'Time'
       * };
       *
       * const datepicker = new DatePicker('#datepicker-container', {
       *     language: 'customKey'
       * });
       */
      localeTexts: localeTexts
    },
    init: function(container, options) {
      options = mergeDefaultOption(options);

      /**
       * Language type
       * @type {string}
       * @private
       */
      this._language = options.language;

      /**
       * DatePicker container
       * @type {HTMLElement}
       * @private
       */
      this._container = util.getElement(container);
      this._container.innerHTML = tmpl(
        extend(options, {
          isTab: options.timePicker && options.timePicker.layoutType === 'tab'
        })
      );

      /**
       * DatePicker element
       * @type {HTMLElement}
       * @private
       */
      this._element = this._container.firstChild;

      /**
       * Calendar instance
       * @type {Calendar}
       * @private
       */
      this._calendar = new Calendar(
        this._element.querySelector(SELECTOR_CALENDAR_CONTAINER),
        extend(options.calendar, {
          usageStatistics: options.usageStatistics
        })
      );

      /**
       * TimePicker instance
       * @type {TimePicker}
       * @private
       */
      this._timePicker = null;

      /**
       * DatePicker input
       * @type {DatePickerInput}
       * @private
       */
      this._datepickerInput = null;

      /**
       * Object having date values
       * @type {Date}
       * @private
       */
      this._date = null;

      /**
       * Selectable date-ranges model
       * @type {RangeModel}
       * @private
       */
      this._rangeModel = null;

      /**
       * openers - opener list
       * @type {Array}
       * @private
       */
      this._openers = [];

      /**
       * State of picker enable
       * @type {boolean}
       * @private
       */
      this._isEnabled = true;

      /**
       * ID of instance
       * @type {number}
       * @private
       */
      this._id = 'tui-datepicker-' + util.generateId();

      /**
       * DatePicker type
       * @type {TYPE_DATE|TYPE_MONTH|TYPE_YEAR}
       * @private
       */
      this._type = options.type;

      /**
       * Show always or not
       * @type {boolean}
       */
      this.showAlways = options.showAlways;

      /**
       * Close after select a date
       * @type {boolean}
       */
      this.autoClose = options.autoClose;

      this._initializeDatePicker(options);
    },

    /**
     * Initialize method
     * @param {Object} option - user option
     * @private
     */
    _initializeDatePicker: function(option) {
      this.setRanges(option.selectableRanges);
      this._setEvents();
      this._initTimePicker(option.timePicker, option.usageStatistics);
      this.setInput(option.input.element);
      this.setDateFormat(option.input.format);
      this.setDate(option.date);

      forEachArray(option.openers, this.addOpener, this);
      if (!this.showAlways) {
        this._hide();
      }

      if (this.getType() === TYPE_DATE) {
        addClass(this._element.querySelector(SELECTOR_BODY), 'tui-datepicker-type-date');
      }
    },

    /**
     * Set events on the date picker's element
     * @param {object} option - Constructor option
     * @private
     */
    _setEvents: function() {
      mouseTouchEvent.on(this._element, 'click', this._onClickHandler, this);
      this._calendar.on('draw', this._onDrawCalendar, this);
    },

    /**
     * Remove events on the date picker's element
     * @private
     */
    _removeEvents: function() {
      mouseTouchEvent.off(this._element, 'click', this._onClickHandler, this);
      this._calendar.off();
    },

    /**
     * Set events on the document
     * @private
     */
    _setDocumentEvents: function() {
      mouseTouchEvent.on(document, 'mousedown', this._onMousedownDocument, this);
    },

    /**
     * Remove events on the document
     * @private
     */
    _removeDocumentEvents: function() {
      mouseTouchEvent.off(document, 'mousedown', this._onMousedownDocument);
    },

    /**
     * Set events on the opener
     * @param {HTMLElement} opener An opener to bind the events
     * @private
     */
    _setOpenerEvents: function(opener) {
      mouseTouchEvent.on(opener, 'click', this.toggle, this);
    },

    /**
     * Remove events on the opener
     * @param {HTMLElement} opener An opener to unbind the events
     * @private
     */
    _removeOpenerEvents: function(opener) {
      mouseTouchEvent.off(opener, 'click', this.toggle);
    },

    /**
     * Set TimePicker instance
     * @param {object|boolean} opTimePicker - TimePicker instance options
     * @param {boolean} usageStatistics - GA tracking options
     * @private
     */
    _initTimePicker: function(opTimePicker, usageStatistics) {
      var layoutType;
      if (!opTimePicker) {
        return;
      }

      layoutType = opTimePicker.layoutType || '';

      if (isObject(opTimePicker)) {
        opTimePicker.usageStatistics = usageStatistics;
      } else {
        opTimePicker = {
          usageStatistics: usageStatistics
        };
      }

      this._timePicker = new TimePicker(
        this._element.querySelector(SELECTOR_TIMEPICKER_CONTAINER),
        opTimePicker
      );

      if (layoutType.toLowerCase() === 'tab') {
        this._timePicker.hide();
      }

      this._timePicker.on(
        'change',
        function(ev) {
          var prevDate;
          if (this._date) {
            prevDate = new Date(this._date);
            this.setDate(prevDate.setHours(ev.hour, ev.minute));
          }
        },
        this
      );
    },

    /**
     * Change picker's type by a selector button.
     * @param {HTMLElement} target A target element
     * @private
     */
    _changePicker: function(target) {
      var btnSelector = '.' + CLASS_NAME_SELECTOR_BUTTON;
      var selectedBtn = closest(target, btnSelector);
      var isDateElement = !!selectedBtn.querySelector(SELECTOR_DATE_ICO);

      if (isDateElement) {
        this._calendar.show();
        this._timePicker.hide();
      } else {
        this._calendar.hide();
        this._timePicker.show();
      }
      removeClass(this._element.querySelector('.' + CLASS_NAME_CHECKED), CLASS_NAME_CHECKED);
      addClass(selectedBtn, CLASS_NAME_CHECKED);
    },

    /**
     * Returns whether the element is opener
     * @param {string|HTMLElement} element - Element or selector
     * @returns {boolean}
     * @private
     */
    _isOpener: function(element) {
      var el = util.getElement(element);

      return inArray(el, this._openers) > -1;
    },

    /**
     * add/remove today-class-name to date element
     * @param {HTMLElement} el - date element
     * @private
     */
    _setTodayClassName: function(el) {
      var timestamp, isToday;

      if (this.getCalendarType() !== TYPE_DATE) {
        return;
      }

      timestamp = Number(getData(el, 'timestamp'));
      isToday = timestamp === new Date().setHours(0, 0, 0, 0);

      if (isToday) {
        addClass(el, CLASS_NAME_TODAY);
      } else {
        removeClass(el, CLASS_NAME_TODAY);
      }
    },

    /**
     * add/remove selectable-class-name to date element
     * @param {HTMLElement} el - date element
     * @private
     */
    _setSelectableClassName: function(el) {
      var elDate = new Date(Number(getData(el, 'timestamp')));

      if (this._isSelectableOnCalendar(elDate)) {
        addClass(el, CLASS_NAME_SELECTABLE);
        removeClass(el, CLASS_NAME_BLOCKED);
      } else {
        removeClass(el, CLASS_NAME_SELECTABLE);
        addClass(el, CLASS_NAME_BLOCKED);
      }
    },

    /**
     * add/remove selected-class-name to date element
     * @param {HTMLElement} el - date element
     * @private
     */
    _setSelectedClassName: function(el) {
      var elDate = new Date(Number(getData(el, 'timestamp')));

      if (this._isSelectedOnCalendar(elDate)) {
        addClass(el, CLASS_NAME_SELECTED);
      } else {
        removeClass(el, CLASS_NAME_SELECTED);
      }
    },

    /**
     * Returns whether the date is selectable on calendar
     * @param {Date} date - Date instance
     * @returns {boolean}
     * @private
     */
    _isSelectableOnCalendar: function(date) {
      var type = this.getCalendarType();
      var start = dateUtil.cloneWithStartOf(date, type).getTime();
      var end = dateUtil.cloneWithEndOf(date, type).getTime();

      return this._rangeModel.hasOverlap(start, end);
    },

    /**
     * Returns whether the date is selected on calendar
     * @param {Date} date - Date instance
     * @returns {boolean}
     * @private
     */
    _isSelectedOnCalendar: function(date) {
      var curDate = this.getDate();
      var calendarType = this.getCalendarType();

      return curDate && dateUtil.isSame(curDate, date, calendarType);
    },

    /**
     * Show the date picker element
     * @private
     */
    _show: function() {
      removeClass(this._element, CLASS_NAME_HIDDEN);
    },

    /**
     * Hide the date picker element
     * @private
     */
    _hide: function() {
      addClass(this._element, CLASS_NAME_HIDDEN);
    },

    /**
     * Set value a date-string of current this instance to input element
     * @private
     */
    _syncToInput: function() {
      if (!this._date) {
        return;
      }

      this._datepickerInput.setDate(this._date);
    },

    /**
     * Set date from input value
     * @param {boolean} [shouldRollback = false] - Should rollback from unselectable or error
     * @private
     */
    _syncFromInput: function(shouldRollback) {
      var isFailed = false;
      var date;

      try {
        date = this._datepickerInput.getDate();

        if (this.isSelectable(date)) {
          if (this._timePicker) {
            this._timePicker.setTime(date.getHours(), date.getMinutes());
          }
          this.setDate(date);
        } else {
          isFailed = true;
        }
      } catch (err) {
        this.fire('error', {
          type: 'ParsingError',
          message: err.message
        });
        isFailed = true;
      } finally {
        if (isFailed) {
          if (shouldRollback) {
            this._syncToInput();
          } else {
            this.setNull();
          }
        }
      }
    },

    /**
     * Event handler for mousedown of document<br>
     * - When click the out of layer, close the layer
     * @param {Event} ev - Event object
     * @private
     */
    _onMousedownDocument: function(ev) {
      var target = util.getTarget(ev);
      var selector = util.getSelector(target);
      var isContain = selector ? this._element.querySelector(selector) : false;
      var isInput = this._datepickerInput.is(target);
      var isInOpener = inArray(target, this._openers) > -1;
      var shouldClose = !(this.showAlways || isInput || isContain || isInOpener);

      if (shouldClose) {
        this.close();
      }
    },

    /**
     * Event handler for click of calendar
     * @param {Event} ev An event object
     * @private
     */
    _onClickHandler: function(ev) {
      var target = util.getTarget(ev);

      if (closest(target, '.' + CLASS_NAME_SELECTABLE)) {
        this._updateDate(target);
      } else if (closest(target, SELECTOR_CALENDAR_TITLE)) {
        this.drawUpperCalendar(this._date);
      } else if (closest(target, '.' + CLASS_NAME_SELECTOR_BUTTON)) {
        this._changePicker(target);
      }
    },

    /**
     * Update date from event-target
     * @param {HTMLElement} target An event target element
     * @private
     */
    _updateDate: function(target) {
      var timestamp = Number(getData(target, 'timestamp'));
      var newDate = new Date(timestamp);
      var timePicker = this._timePicker;
      var prevDate = this._date;
      var calendarType = this.getCalendarType();
      var pickerType = this.getType();

      if (calendarType !== pickerType) {
        this.drawLowerCalendar(newDate);
      } else {
        if (timePicker) {
          newDate.setHours(timePicker.getHour(), timePicker.getMinute());
        } else if (prevDate) {
          newDate.setHours(prevDate.getHours(), prevDate.getMinutes());
        }
        this.setDate(newDate);

        if (!this.showAlways && this.autoClose) {
          this.close();
        }
      }
    },

    /**
     * Event handler for 'draw'-custom event of calendar
     * @param {Object} eventData - custom event data
     * @see {@link Calendar#draw}
     * @private
     */
    _onDrawCalendar: function(eventData) {
      forEachArray(
        eventData.dateElements,
        function(el) {
          this._setTodayClassName(el);
          this._setSelectableClassName(el);
          this._setSelectedClassName(el);
        },
        this
      );
      this._setDisplayHeadButtons();

      /**
       * Occur after the calendar is drawn.
       * @event DatePicker#draw
       * @see {@link https://nhn.github.io/tui.code-snippet/latest/CustomEvents#on datepicker.on()} to bind event handlers.
       * @see {@link https://nhn.github.io/tui.code-snippet/latest/CustomEvents#off datepicker.off()} to unbind event handlers.
       * @see Refer to {@link https://nhn.github.io/tui.code-snippet/latest/CustomEvents CustomEvents from tui-code-snippet} for more methods. DatePicker mixes in the methods from CustomEvents.
       * @property {Date} date - Calendar date
       * @property {('date'|'month'|'year')} type - Calendar type
       * @property {HTMLElement[]} dateElements - elements for dates
       * @example
       * // bind the 'draw' event
       * datepicker.on('draw', function(event) {
       *     console.log(`Draw the ${event.type} calendar and its date is ${event.date}.`);
       * });
       *
       * // unbind the 'draw' event
       * datepicker.off('draw');
       */
      this.fire('draw', eventData);
    },

    /**
     * Hide useless buttons (next, next-year, prev, prev-year)
     * @see Don't save buttons reference. The buttons are rerendered every "calendar.draw".
     * @private
     */
    _setDisplayHeadButtons: function() {
      var nextYearDate = this._calendar.getNextYearDate();
      var prevYearDate = this._calendar.getPrevYearDate();
      var maxTimestamp = this._rangeModel.getMaximumValue();
      var minTimestamp = this._rangeModel.getMinimumValue();
      var nextYearBtn = this._element.querySelector('.' + CLASS_NAME_NEXT_YEAR_BTN);
      var prevYearBtn = this._element.querySelector('.' + CLASS_NAME_PREV_YEAR_BTN);
      var nextMonthDate, prevMonthDate, nextMonBtn, prevMonBtn;

      if (this.getCalendarType() === TYPE_DATE) {
        nextMonthDate = dateUtil.cloneWithStartOf(this._calendar.getNextDate(), TYPE_MONTH);
        prevMonthDate = dateUtil.cloneWithEndOf(this._calendar.getPrevDate(), TYPE_MONTH);

        nextMonBtn = this._element.querySelector('.' + CLASS_NAME_NEXT_MONTH_BTN);
        prevMonBtn = this._element.querySelector('.' + CLASS_NAME_PREV_MONTH_BTN);

        this._setDisplay(nextMonBtn, nextMonthDate.getTime() <= maxTimestamp);
        this._setDisplay(prevMonBtn, prevMonthDate.getTime() >= minTimestamp);

        prevYearDate.setDate(1);
        nextYearDate.setDate(1);
      } else {
        prevYearDate.setMonth(12, 0);
        nextYearDate.setMonth(0, 1);
      }

      this._setDisplay(nextYearBtn, nextYearDate.getTime() <= maxTimestamp);
      this._setDisplay(prevYearBtn, prevYearDate.getTime() >= minTimestamp);
    },

    /**
     * Set display show/hide by condition
     * @param {HTMLElement} el - An Element
     * @param {boolean} shouldShow - Condition
     * @private
     */
    _setDisplay: function(el, shouldShow) {
      if (el) {
        if (shouldShow) {
          removeClass(el, CLASS_NAME_HIDDEN);
        } else {
          addClass(el, CLASS_NAME_HIDDEN);
        }
      }
    },

    /**
     * Input change handler
     * @private
     * @throws {Error}
     */
    _onChangeInput: function() {
      this._syncFromInput(true);
    },

    /**
     * Returns whether the date is changed
     * @param {Date} date - Date
     * @returns {boolean}
     * @private
     */
    _isChanged: function(date) {
      var prevDate = this.getDate();

      return !prevDate || date.getTime() !== prevDate.getTime();
    },

    /**
     * Refresh datepicker
     * @private
     */
    _refreshFromRanges: function() {
      if (!this.isSelectable(this._date)) {
        this.setNull();
      } else {
        this._calendar.draw(); // view update
      }
    },

    /**
     * Return the current calendar's type.
     * @returns {('date'|'month'|'year')}
     */
    getCalendarType: function() {
      return this._calendar.getType();
    },

    /**
     * Return the date picker's type.
     * @returns {('date'|'month'|'year')}
     */
    getType: function() {
      return this._type;
    },

    /**
     * Return whether the date is selectable.
     * @param {Date} date - Date to check
     * @returns {boolean}
     */
    isSelectable: function(date) {
      var type = this.getType();
      var start, end;

      if (!dateUtil.isValidDate(date)) {
        return false;
      }
      start = dateUtil.cloneWithStartOf(date, type).getTime();
      end = dateUtil.cloneWithEndOf(date, type).getTime();

      return this._rangeModel.hasOverlap(start, end);
    },

    /**
     * Return whether the date is selected.
     * @param {Date} date - Date to check
     * @returns {boolean}
     */
    isSelected: function(date) {
      return dateUtil.isValidDate(date) && dateUtil.isSame(this._date, date, this.getType());
    },

    /**
     * Set selectable ranges. Previous ranges will be removed.
     * @param {Array.<Array<Date|number>>} ranges - Selectable ranges. Use Date instances or numbers(timestamp).
     * @example
     * datepicker.setRanges([
     *     [new Date(2017, 0, 1), new Date(2018, 0, 2)],
     *     [new Date(2015, 2, 3), new Date(2016, 4, 2)]
     * ]);
     */
    setRanges: function(ranges) {
      var result = [];
      forEachArray(ranges, function(range) {
        var start = new Date(range[0]).getTime();
        var end = new Date(range[1]).getTime();

        result.push([start, end]);
      });

      this._rangeModel = new RangeModel(result);
      this._refreshFromRanges();
    },

    /**
     * Set the calendar's type.
     * @param {('date'|'month'|'year')} type - Calendar type
     * @example
     * datepicker.setType('month');
     */
    setType: function(type) {
      this._type = type;
    },

    /**
     * Add a selectable range. Use Date instances or numbers(timestamp).
     * @param {Date|number} start - the start date
     * @param {Date|number} end - the end date
     * @example
     * const start = new Date(2015, 1, 3);
     * const end = new Date(2015, 2, 6);
     *
     * datepicker.addRange(start, end);
     */
    addRange: function(start, end) {
      start = new Date(start).getTime();
      end = new Date(end).getTime();

      this._rangeModel.add(start, end);
      this._refreshFromRanges();
    },

    /**
     * Remove a range. Use Date instances or numbers(timestamp).
     * @param {Date|number} start - the start date
     * @param {Date|number} end - the end date
     * @param {null|'date'|'month'|'year'} type - Range type. If falsy, start and end values are considered as timestamp
     * @example
     * const start = new Date(2015, 1, 3);
     * const end = new Date(2015, 2, 6);
     *
     * datepicker.removeRange(start, end);
     */
    removeRange: function(start, end, type) {
      start = new Date(start);
      end = new Date(end);

      if (type) {
        // @todo Consider time-range on timePicker
        start = dateUtil.cloneWithStartOf(start, type);
        end = dateUtil.cloneWithEndOf(end, type);
      }

      this._rangeModel.exclude(start.getTime(), end.getTime());
      this._refreshFromRanges();
    },

    /**
     * Add an opener.
     * @param {HTMLElement|string} opener - element or selector of opener
     */
    addOpener: function(opener) {
      opener = util.getElement(opener);

      if (!this._isOpener(opener)) {
        this._openers.push(opener);
        this._setOpenerEvents(opener);
      }
    },

    /**
     * Remove an opener.
     * @param {HTMLElement|string} opener - element or selector of opener
     */
    removeOpener: function(opener) {
      var index;

      opener = util.getElement(opener);
      index = inArray(opener, this._openers);

      if (index > -1) {
        this._removeOpenerEvents(opener);
        this._openers.splice(index, 1);
      }
    },

    /**
     * Remove all openers.
     */
    removeAllOpeners: function() {
      forEachArray(
        this._openers,
        function(opener) {
          this._removeOpenerEvents(opener);
        },
        this
      );
      this._openers = [];
    },

    /**
     * Open the date picker.
     * @example
     * datepicker.open();
     */
    open: function() {
      if (this.isOpened() || !this._isEnabled) {
        return;
      }

      this._calendar.draw({
        date: this._date,
        type: this._type
      });
      this._show();

      if (!this.showAlways) {
        this._setDocumentEvents();
      }

      /**
       * Occur after the date picker opens.
       * @event DatePicker#open
       * @see {@link https://nhn.github.io/tui.code-snippet/latest/CustomEvents#on datepicker.on()} to bind event handlers.
       * @see {@link https://nhn.github.io/tui.code-snippet/latest/CustomEvents#off datepicker.off()} to unbind event handlers.
       * @see Refer to {@link https://nhn.github.io/tui.code-snippet/latest/CustomEvents CustomEvents from tui-code-snippet} for more methods. DatePicker mixes in the methods from CustomEvents.
       * @example
       * // bind the 'open' event
       * datepicker.on('open', function() {
       *     alert('open');
       * });
       *
       * // unbind the 'open' event
       * datepicker.off('open');
       */
      this.fire('open');
    },

    /**
     * Raise the calendar type. (date -> month -> year)
     * @param {Date} [date] - Date to set
     */
    drawUpperCalendar: function(date) {
      var calendarType = this.getCalendarType();

      if (calendarType === TYPE_DATE) {
        this._calendar.draw({
          date: date,
          type: TYPE_MONTH
        });
      } else if (calendarType === TYPE_MONTH) {
        this._calendar.draw({
          date: date,
          type: TYPE_YEAR
        });
      }
    },

    /**
     * Lower the calendar type. (year -> month -> date)
     * @param {Date} [date] - Date to set
     */
    drawLowerCalendar: function(date) {
      var calendarType = this.getCalendarType();
      var pickerType = this.getType();
      var isLast = calendarType === pickerType;

      if (isLast) {
        return;
      }

      if (calendarType === TYPE_MONTH) {
        this._calendar.draw({
          date: date,
          type: TYPE_DATE
        });
      } else if (calendarType === TYPE_YEAR) {
        this._calendar.draw({
          date: date,
          type: TYPE_MONTH
        });
      }
    },

    /**
     * Close the date picker.
     * @exmaple
     * datepicker.close();
     */
    close: function() {
      if (!this.isOpened()) {
        return;
      }
      this._removeDocumentEvents();
      this._hide();

      /**
       * Occur after the date picker closes.
       * @event DatePicker#close
       * @see {@link https://nhn.github.io/tui.code-snippet/latest/CustomEvents#on datepicker.on()} to bind event handlers.
       * @see {@link https://nhn.github.io/tui.code-snippet/latest/CustomEvents#off datepicker.off()} to unbind event handlers.
       * @see Refer to {@link https://nhn.github.io/tui.code-snippet/latest/CustomEvents CustomEvents from tui-code-snippet} for more methods. DatePicker mixes in the methods from CustomEvents.
       * @example
       * // bind the 'close' event
       * datepicker.on('close', function() {
       *     alert('close');
       * });
       *
       * // unbind the 'close' event
       * datepicker.off('close');
       */
      this.fire('close');
    },

    /**
     * Toggle the date picker.
     * @example
     * datepicker.toggle();
     */
    toggle: function() {
      if (this.isOpened()) {
        this.close();
      } else {
        this.open();
      }
    },

    /**
     * Return the selected date.
     * @returns {?Date} - selected date
     * @example
     * // 2015-04-13
     * datepicker.getDate(); // new Date(2015, 3, 13)
     */
    getDate: function() {
      if (!this._date) {
        return null;
      }

      return new Date(this._date);
    },

    /**
     * Select the date.
     * @param {Date|number} date - Date instance or timestamp to set
     * @example
     * datepicker.setDate(new Date()); // Set today
     */
    // eslint-disable-next-line complexity
    setDate: function(date) {
      var isValidInput, newDate, shouldUpdate;

      if (date === null) {
        this.setNull();

        return;
      }

      isValidInput = isNumber(date) || isDate(date);
      newDate = new Date(date);
      shouldUpdate = isValidInput && this._isChanged(newDate) && this.isSelectable(newDate);

      if (shouldUpdate) {
        newDate = new Date(date);
        this._date = newDate;
        this._calendar.draw({ date: newDate });
        if (this._timePicker) {
          this._timePicker.setTime(newDate.getHours(), newDate.getMinutes());
        }
        this._syncToInput();

        /**
         * Occur after the selected date is changed.
         * @event DatePicker#change
         * @see {@link https://nhn.github.io/tui.code-snippet/latest/CustomEvents#on datepicker.on()} to bind event handlers.
         * @see {@link https://nhn.github.io/tui.code-snippet/latest/CustomEvents#off datepicker.off()} to unbind event handlers.
         * @see Refer to {@link https://nhn.github.io/tui.code-snippet/latest/CustomEvents CustomEvents from tui-code-snippet} for more methods. DatePicker mixes in the methods from CustomEvents.
         * @example
         * // bind the 'change' event
         * datepicker.on('change', function() {
         *     console.log(`Selected date: ${datepicker.getDate()}`);
         * });
         *
         * // unbind the 'change' event
         * datepicker.off('change');
         */
        this.fire('change');
      }
    },

    /**
     * Set no date to be selected. (Selected date: null)
     */
    setNull: function() {
      var calendarDate = this._calendar.getDate();
      var isChagned = this._date !== null;

      this._date = null;

      if (this._datepickerInput) {
        this._datepickerInput.clearText();
      }
      if (this._timePicker) {
        this._timePicker.setTime(0, 0);
      }

      // View update
      if (!this.isSelectable(calendarDate)) {
        this._calendar.draw({
          date: new Date(this._rangeModel.getMinimumValue())
        });
      } else {
        this._calendar.draw();
      }

      if (isChagned) {
        this.fire('change');
      }
    },

    /**
     * Select the date by the date string format.
     * @param {String} [format] - Date string format
     * @example
     * datepicker.setDateFormat('yyyy-MM-dd');
     * datepicker.setDateFormat('MM-dd, yyyy');
     * datepicker.setDateFormat('yy/M/d');
     * datepicker.setDateFormat('yy/MM/dd');
     */
    setDateFormat: function(format) {
      this._datepickerInput.setFormat(format);
      this._syncToInput();
    },

    /**
     * Return whether the datepicker opens or not
     * @returns {boolean}
     * @example
     * datepicker.close();
     * datepicker.isOpened(); // false
     *
     * datepicker.open();
     * datepicker.isOpened(); // true
     */
    isOpened: function() {
      return !hasClass(this._element, CLASS_NAME_HIDDEN);
    },

    /**
     * Return the time picker instance
     * @returns {?TimePicker} - TimePicker instance
     * @see {@link https://nhn.github.io/tui.time-picker/latest tui-time-picker}
     * @example
     * const timePicker = this.getTimePicker();
     */
    getTimePicker: function() {
      return this._timePicker;
    },

    /**
     * Return the calendar instance.
     * @see {@link calendar Calendar}
     * @returns {Calendar}
     */
    getCalendar: function() {
      return this._calendar;
    },

    /**
     * Return the locale text object.
     * @see {@link DatePicker#localeTexts DatePicker.localeTexts}
     * @returns {object}
     */
    getLocaleText: function() {
      return localeTexts[this._language] || localeTexts[DEFAULT_LANGUAGE_TYPE];
    },

    /**
     * Set the input element
     * @param {string|HTMLElement} element - Input element or selector
     * @param {object} [options] - Input option
     * @param {string} [options.format = prevInput.format] - Format of the Date string in the input
     * @param {boolean} [options.syncFromInput = false] - Whether set the date from the input
     */
    setInput: function(element, options) {
      var prev = this._datepickerInput;
      var localeText = this.getLocaleText();
      var prevFormat;
      options = options || {};

      if (prev) {
        prevFormat = prev.getFormat();
        prev.destroy();
      }

      this._datepickerInput = new DatePickerInput(element, {
        format: options.format || prevFormat,
        id: this._id,
        localeText: localeText
      });

      this._datepickerInput.on(
        {
          change: this._onChangeInput,
          click: this.open
        },
        this
      );

      if (options.syncFromInput) {
        this._syncFromInput();
      } else {
        this._syncToInput();
      }
    },

    /**
     * Enable the date picker.
     */
    enable: function() {
      if (this._isEnabled) {
        return;
      }
      this._isEnabled = true;
      this._datepickerInput.enable();

      forEachArray(
        this._openers,
        function(opener) {
          opener.removeAttribute('disabled');
          this._setOpenerEvents(opener);
        },
        this
      );
    },

    /**
     * Disable the date picker.
     */
    disable: function() {
      if (!this._isEnabled) {
        return;
      }

      this._isEnabled = false;
      this.close();
      this._datepickerInput.disable();

      forEachArray(
        this._openers,
        function(opener) {
          opener.setAttribute('disabled', true);
          this._removeOpenerEvents(opener);
        },
        this
      );
    },

    /**
     * Return whether the date picker is disabled
     * @returns {boolean}
     */
    isDisabled: function() {
      // @todo this._isEnabled --> this._isDisabled
      return !this._isEnabled;
    },

    /**
     * Apply a CSS class to the date picker.
     * @param {string} className - Class name
     */
    addCssClass: function(className) {
      addClass(this._element, className);
    },

    /**
     * Remove a CSS class from the date picker.
     * @param {string} className - Class name
     */
    removeCssClass: function(className) {
      removeClass(this._element, className);
    },

    /**
     * Return the date elements on the calendar.
     * @returns {HTMLElement[]}
     */
    getDateElements: function() {
      return this._calendar.getDateElements();
    },

    /**
     * Return the first overlapped range from the point or range.
     * @param {Date|number} startDate - Start date to find overlapped range
     * @param {Date|number} endDate - End date to find overlapped range
     * @returns {Array.<Date>} - \[startDate, endDate]
     */
    findOverlappedRange: function(startDate, endDate) {
      var startTimestamp = new Date(startDate).getTime();
      var endTimestamp = new Date(endDate).getTime();
      var overlappedRange = this._rangeModel.findOverlappedRange(startTimestamp, endTimestamp);

      return [new Date(overlappedRange[0]), new Date(overlappedRange[1])];
    },

    /**
     * Change language.
     * @param {string} language - Language code. English('en') and Korean('ko') are provided as default.
     * @see To set to the other languages, use {@link DatePicker#localeTexts DatePicker.localeTexts}.
     */
    changeLanguage: function(language) {
      this._language = language;
      this._calendar.changeLanguage(this._language);
      this._datepickerInput.changeLocaleTitles(this.getLocaleText().titles);
      this.setDateFormat(this._datepickerInput.getFormat());

      if (this._timePicker) {
        this._timePicker.changeLanguage(this._language);
      }
    },

    /**
     * Destroy the date picker.
     */
    destroy: function() {
      this._removeDocumentEvents();
      this._calendar.destroy();
      if (this._timePicker) {
        this._timePicker.destroy();
      }
      if (this._datepickerInput) {
        this._datepickerInput.destroy();
      }
      this._removeEvents();
      removeElement(this._element);
      this.removeAllOpeners();

      this._calendar = this._timePicker = this._datepickerInput = this._container = this._element = this._date = this._rangeModel = this._openers = this._isEnabled = this._id = null;
    }
  }
);

CustomEvents.mixin(DatePicker);
module.exports = DatePicker;


/***/ }),
/* 22 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";
/**
 * @fileoverview Check whether the given variable is an object or not.
 * @author NHN FE Development Lab <dl_javascript@nhn.com>
 */



/**
 * Check whether the given variable is an object or not.
 * If the given variable is an object, return true.
 * @param {*} obj - Target for checking
 * @returns {boolean} Is object?
 * @memberof module:type
 */
function isObject(obj) {
  return obj === Object(obj);
}

module.exports = isObject;


/***/ }),
/* 23 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";
/**
 * @fileoverview Execute the provided callback once for each property of object which actually exist.
 * @author NHN FE Development Lab <dl_javascript@nhn.com>
 */



/**
 * Execute the provided callback once for each property of object which actually exist.
 * If the callback function returns false, the loop will be stopped.
 * Callback function(iteratee) is invoked with three arguments:
 *  1) The value of the property
 *  2) The name of the property
 *  3) The object being traversed
 * @param {Object} obj The object that will be traversed
 * @param {function} iteratee  Callback function
 * @param {Object} [context] Context(this) of callback function
 * @memberof module:collection
 * @example
 * var forEachOwnProperties = require('tui-code-snippet/collection/forEachOwnProperties'); // node, commonjs
 *
 * var sum = 0;
 *
 * forEachOwnProperties({a:1,b:2,c:3}, function(value){
 *     sum += value;
 * });
 * alert(sum); // 6
 */
function forEachOwnProperties(obj, iteratee, context) {
  var key;

  context = context || null;

  for (key in obj) {
    if (obj.hasOwnProperty(key)) {
      if (iteratee.call(context, obj[key], key, obj) === false) {
        break;
      }
    }
  }
}

module.exports = forEachOwnProperties;


/***/ }),
/* 24 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";
/**
 * @fileoverview Set className value
 * @author NHN FE Development Lab <dl_javascript@nhn.com>
 */



var isArray = __webpack_require__(6);
var isUndefined = __webpack_require__(12);

/**
 * Set className value
 * @param {(HTMLElement|SVGElement)} element - target element
 * @param {(string|string[])} cssClass - class names
 * @private
 */
function setClassName(element, cssClass) {
  cssClass = isArray(cssClass) ? cssClass.join(' ') : cssClass;

  cssClass = cssClass.replace(/^[\s\uFEFF\xA0]+|[\s\uFEFF\xA0]+$/g, '');

  if (isUndefined(element.className.baseVal)) {
    element.className = cssClass;

    return;
  }

  element.className.baseVal = cssClass;
}

module.exports = setClassName;


/***/ }),
/* 25 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";
/**
 * @fileoverview Find parent element recursively
 * @author NHN FE Development Lab <dl_javascript@nhn.com>
 */



var matches = __webpack_require__(40);

/**
 * Find parent element recursively
 * @param {HTMLElement} element - base element to start find
 * @param {string} selector - selector string for find
 * @returns {HTMLElement} - element finded or null
 * @memberof module:domUtil
 */
function closest(element, selector) {
  var parent = element.parentNode;

  if (matches(element, selector)) {
    return element;
  }

  while (parent && parent !== document) {
    if (matches(parent, selector)) {
      return parent;
    }

    parent = parent.parentNode;
  }

  return null;
}

module.exports = closest;


/***/ }),
/* 26 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";
/**
 * @fileoverview Get data value from data-attribute
 * @author NHN FE Development Lab <dl_javascript@nhn.com>
 */



var convertToKebabCase = __webpack_require__(42);

/**
 * Get data value from data-attribute
 * @param {HTMLElement} element - target element
 * @param {string} key - key
 * @returns {string} value
 * @memberof module:domUtil
 */
function getData(element, key) {
  if (element.dataset) {
    return element.dataset[key];
  }

  return element.getAttribute('data-' + convertToKebabCase(key));
}

module.exports = getData;


/***/ }),
/* 27 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";
/**
 * @fileoverview Check element has specific css class
 * @author NHN FE Development Lab <dl_javascript@nhn.com>
 */



var inArray = __webpack_require__(3);
var getClass = __webpack_require__(17);

/**
 * Check element has specific css class
 * @param {(HTMLElement|SVGElement)} element - target element
 * @param {string} cssClass - css class
 * @returns {boolean}
 * @memberof module:domUtil
 */
function hasClass(element, cssClass) {
  var origin;

  if (element.classList) {
    return element.classList.contains(cssClass);
  }

  origin = getClass(element).split(/\s+/);

  return inArray(cssClass, origin) > -1;
}

module.exports = hasClass;


/***/ }),
/* 28 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";
/**
 * @fileoverview Check whether the given variable is an instance of Date or not.
 * @author NHN FE Development Lab <dl_javascript@nhn.com>
 */



/**
 * Check whether the given variable is an instance of Date or not.
 * If the given variables is an instance of Date, return true.
 * @param {*} obj - Target for checking
 * @returns {boolean} Is an instance of Date?
 * @memberof module:type
 */
function isDate(obj) {
  return obj instanceof Date;
}

module.exports = isDate;


/***/ }),
/* 29 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";
/**
 * @fileoverview Calendar component
 * @author NHN. FE dev Lab <dl_javascript@nhn.com>
 */



var defineClass = __webpack_require__(0);
var CustomEvents = __webpack_require__(8);
var addClass = __webpack_require__(16);
var hasClass = __webpack_require__(27);
var removeClass = __webpack_require__(18);
var removeElement = __webpack_require__(14);
var extend = __webpack_require__(7);

var Header = __webpack_require__(44);
var Body = __webpack_require__(49);
var localeTexts = __webpack_require__(10);
var constants = __webpack_require__(1);
var dateUtil = __webpack_require__(5);
var util = __webpack_require__(4);

var DEFAULT_LANGUAGE_TYPE = constants.DEFAULT_LANGUAGE_TYPE;

var TYPE_DATE = constants.TYPE_DATE;
var TYPE_MONTH = constants.TYPE_MONTH;
var TYPE_YEAR = constants.TYPE_YEAR;

var CLASS_NAME_PREV_MONTH_BTN = constants.CLASS_NAME_PREV_MONTH_BTN;
var CLASS_NAME_PREV_YEAR_BTN = constants.CLASS_NAME_PREV_YEAR_BTN;
var CLASS_NAME_NEXT_YEAR_BTN = constants.CLASS_NAME_NEXT_YEAR_BTN;
var CLASS_NAME_NEXT_MONTH_BTN = constants.CLASS_NAME_NEXT_MONTH_BTN;

var CLASS_NAME_CALENDAR_MONTH = 'tui-calendar-month';
var CLASS_NAME_CALENDAR_YEAR = 'tui-calendar-year';
var CLASS_NAME_HIDDEN = 'tui-hidden';

var HEADER_SELECTOR = '.tui-calendar-header';
var BODY_SELECTOR = '.tui-calendar-body';

/**
 * @class
 * @description
 * Create a calendar by {@link DatePicker#createCalendar DatePicker.createCalendar()}.
 * @see {@link /tutorial-example07-calendar Calendar example}
 * @param {HTMLElement|string} container - Container or selector of the Calendar
 * @param {Object} [options] - Calendar options
 *     @param {Date} [options.date = new Date()] - Initial date (default: today)
 *     @param {('date'|'month'|'year')} [options.type = 'date'] - Calendar type. Determine whether to show a date, month, or year.
 *     @param {string} [options.language = 'en'] - Language code. English('en') and Korean('ko') are provided as default. To use the other languages, use {@link DatePicker#localeTexts DatePicker.localeTexts}.
 *     @param {boolean} [options.showToday = true] - Show today.
 *     @param {boolean} [options.showJumpButtons = false] - Show the yearly jump buttons (move to the previous and next year in 'date' Calendar)
 *     @param {boolean} [options.usageStatistics = true] - Send a hostname to Google Analytics (default: true)
 * @example
 * import DatePicker from 'tui-date-picker' // ES6
 * // const DatePicker = require('tui-date-picker'); // CommonJS
 * // const DatePicker = tui.DatePicker;
 *
 * const calendar = DatePicker.createCalendar('#calendar-wrapper', {
 *     language: 'en',
 *     showToday: true,
 *     showJumpButtons: false,
 *     date: new Date(),
 *     type: 'date'
 * });
 *
 * calendar.on('draw', function(event) {
 *     console.log(event.date);
 *     console.log(event.type);
 *     for (let i = 0, len = event.dateElements.length; i < len; i += 1) {
 *         const el = event.dateElements[i];
 *         const date = new Date(getData(el, 'timestamp'));
 *         console.log(date);
 *     }
 * });
 */
var Calendar = defineClass(
  /** @lends Calendar.prototype */ {
    static: {
      localeTexts: localeTexts
    },
    init: function(container, options) {
      options = extend(
        {
          language: DEFAULT_LANGUAGE_TYPE,
          showToday: true,
          showJumpButtons: false,
          date: new Date(),
          type: TYPE_DATE,
          usageStatistics: true
        },
        options
      );

      /**
       * Container element
       * @type {HTMLElement}
       * @private
       */
      this._container = util.getElement(container);
      this._container.innerHTML =
        '<div class="tui-calendar">' +
        '    <div class="tui-calendar-header"></div>' +
        '    <div class="tui-calendar-body"></div>' +
        '</div>';

      /**
       * Wrapper element
       * @type {HTMLElement}
       * @private
       */
      this._element = this._container.firstChild;

      /**
       * Date
       * @type {Date}
       * @private
       */
      this._date = null;

      /**
       * Layer type
       * @type {string}
       * @private
       */
      this._type = null;

      /**
       * Header box
       * @type {Header}
       * @private
       */
      this._header = null;

      /**
       * Body box
       * @type {Body}
       * @private
       */
      this._body = null;

      this._initHeader(options);
      this._initBody(options);
      this.draw({
        date: options.date,
        type: options.type
      });

      if (options.usageStatistics) {
        util.sendHostName();
      }
    },

    /**
     * Initialize header
     * @param {object} options - Header options
     * @private
     */
    _initHeader: function(options) {
      var headerContainer = this._element.querySelector(HEADER_SELECTOR);

      this._header = new Header(headerContainer, options);
      this._header.on(
        'click',
        function(ev) {
          var target = util.getTarget(ev);
          if (hasClass(target, CLASS_NAME_PREV_MONTH_BTN)) {
            this.drawPrev();
          } else if (hasClass(target, CLASS_NAME_PREV_YEAR_BTN)) {
            this._onClickPrevYear();
          } else if (hasClass(target, CLASS_NAME_NEXT_MONTH_BTN)) {
            this.drawNext();
          } else if (hasClass(target, CLASS_NAME_NEXT_YEAR_BTN)) {
            this._onClickNextYear();
          }
        },
        this
      );
    },

    /**
     * Initialize body
     * @param {object} options - Body options
     * @private
     */
    _initBody: function(options) {
      var bodyContainer = this._element.querySelector(BODY_SELECTOR);

      this._body = new Body(bodyContainer, options);
    },

    /**
     * clickHandler - prev year button
     * @private
     */
    _onClickPrevYear: function() {
      if (this.getType() === TYPE_DATE) {
        this.draw({
          date: this._getRelativeDate(-12)
        });
      } else {
        this.drawPrev();
      }
    },

    /**
     * clickHandler - next year button
     * @private
     */
    _onClickNextYear: function() {
      if (this.getType() === TYPE_DATE) {
        this.draw({
          date: this._getRelativeDate(12)
        });
      } else {
        this.drawNext();
      }
    },

    /**
     * Returns whether the layer type is valid
     * @param {string} type - Layer type to check
     * @returns {boolean}
     * @private
     */
    _isValidType: function(type) {
      return type === TYPE_DATE || type === TYPE_MONTH || type === TYPE_YEAR;
    },

    /**
     * @param {Date} date - Date to draw
     * @param {string} type - Layer type to draw
     * @returns {boolean}
     * @private
     */
    _shouldUpdate: function(date, type) {
      var prevDate = this._date;

      if (!dateUtil.isValidDate(date)) {
        throw new Error('Invalid date');
      }

      if (!this._isValidType(type)) {
        throw new Error('Invalid layer type');
      }

      return (
        !prevDate ||
        prevDate.getFullYear() !== date.getFullYear() ||
        prevDate.getMonth() !== date.getMonth() ||
        this.getType() !== type
      );
    },

    /**
     * Render header & body elements
     * @private
     */
    _render: function() {
      var date = this._date;
      var type = this.getType();

      this._header.render(date, type);
      this._body.render(date, type);
      removeClass(this._element, CLASS_NAME_CALENDAR_MONTH, CLASS_NAME_CALENDAR_YEAR);

      switch (type) {
        case TYPE_MONTH:
          addClass(this._element, CLASS_NAME_CALENDAR_MONTH);
          break;
        case TYPE_YEAR:
          addClass(this._element, CLASS_NAME_CALENDAR_YEAR);
          break;
        default:
          break;
      }
    },

    /**
     * Returns relative date
     * @param {number} step - Month step
     * @returns {Date}
     * @private
     */
    _getRelativeDate: function(step) {
      var prev = this._date;

      return new Date(prev.getFullYear(), prev.getMonth() + step);
    },

    /**
     * Draw the calendar.
     * @param {Object} [options] - Draw options
     *   @param {Date} [options.date] - Date to set
     *   @param {('date'|'month'|'year')} [options.type = 'date'] - Calendar type. Determine whether to show a date, month, or year.
     * @example
     * calendar.draw();
     * calendar.draw({
     *     date: new Date()
     * });
     * calendar.draw({
     *     type: 'month'
     * });
     * calendar.draw({
     *     type: 'month',
     *     date: new Date()
     * });
     */
    draw: function(options) {
      var date, type;

      options = options || {};
      date = options.date || this._date;
      type = (options.type || this.getType()).toLowerCase();

      if (this._shouldUpdate(date, type)) {
        this._date = date;
        this._type = type;
        this._render();
      }

      /**
       * Occur after the calendar draws.
       * @event Calendar#draw
       * @see {@link https://nhn.github.io/tui.code-snippet/latest/CustomEvents#on calendar.on()} to bind event handlers.
       * @see {@link https://nhn.github.io/tui.code-snippet/latest/CustomEvents#off calendar.off()} to unbind event handlers.
       * @see Refer to {@link https://nhn.github.io/tui.code-snippet/latest/CustomEvents CustomEvents from tui-code-snippet} for more methods. Calendar mixes in the methods from CustomEvents.
       * @property {Date} date - Calendar date
       * @property {('date'|'month'|'year')} type - Calendar type
       * @property {HTMLElement[]} dateElements - elements for dates
       * @example
       * // bind the 'draw' event
       * calendar.on('draw', function({type, date}) {
       *     console.log(`Draw the ${type} calendar and its date is ${date}.`);
       * });
       *
       * // unbind the 'draw' event
       * calendar.off('draw');
       */
      this.fire('draw', {
        date: this._date,
        type: type,
        dateElements: this._body.getDateElements()
      });
    },

    /**
     * Show the calendar.
     */
    show: function() {
      removeClass(this._element, CLASS_NAME_HIDDEN);
    },

    /**
     * Hide the calendar.
     */
    hide: function() {
      addClass(this._element, CLASS_NAME_HIDDEN);
    },

    /**
     * Draw the next page.
     */
    drawNext: function() {
      this.draw({
        date: this.getNextDate()
      });
    },

    /**
     * Draw the previous page.
     */
    drawPrev: function() {
      this.draw({
        date: this.getPrevDate()
      });
    },

    /**
     * Return the next date.
     * @returns {Date}
     */
    getNextDate: function() {
      if (this.getType() === TYPE_DATE) {
        return this._getRelativeDate(1);
      }

      return this.getNextYearDate();
    },

    /**
     * Return the previous date.
     * @returns {Date}
     */
    getPrevDate: function() {
      if (this.getType() === TYPE_DATE) {
        return this._getRelativeDate(-1);
      }

      return this.getPrevYearDate();
    },

    /**
     * Return the date a year later.
     * @returns {Date}
     */
    getNextYearDate: function() {
      switch (this.getType()) {
        case TYPE_DATE:
        case TYPE_MONTH:
          return this._getRelativeDate(12); // 12 months = 1 year
        case TYPE_YEAR:
          return this._getRelativeDate(108); // 108 months = 9 years = 12 * 9
        default:
          throw new Error('Unknown layer type');
      }
    },

    /**
     * Return the date a year previously.
     * @returns {Date}
     */
    getPrevYearDate: function() {
      switch (this.getType()) {
        case TYPE_DATE:
        case TYPE_MONTH:
          return this._getRelativeDate(-12); // 12 months = 1 year
        case TYPE_YEAR:
          return this._getRelativeDate(-108); // 108 months = 9 years = 12 * 9
        default:
          throw new Error('Unknown layer type');
      }
    },

    /**
     * Change language.
     * @param {string} language - Language code. English('en') and Korean('ko') are provided as default.
     * @see To set to the other languages, use {@link DatePicker#localeTexts DatePicker.localeTexts}.
     */
    changeLanguage: function(language) {
      this._header.changeLanguage(language);
      this._body.changeLanguage(language);
      this._render();
    },

    /**
     * Return the rendered date.
     * @returns {Date}
     */
    getDate: function() {
      return new Date(this._date);
    },

    /**
     * Return the calendar's type.
     * @returns {('date'|'month'|'year')}
     */
    getType: function() {
      return this._type;
    },

    /**
     * Returns HTML elements for dates.
     * @returns {HTMLElement[]}
     */
    getDateElements: function() {
      return this._body.getDateElements();
    },

    /**
     * Apply a CSS class to the calendar.
     * @param {string} className - Class name
     */
    addCssClass: function(className) {
      addClass(this._element, className);
    },

    /**
     * Remove a CSS class from the calendar.
     * @param {string} className - Class name
     */
    removeCssClass: function(className) {
      removeClass(this._element, className);
    },

    /**
     * Destroy the calendar.
     */
    destroy: function() {
      this._header.destroy();
      this._body.destroy();
      removeElement(this._element);

      this._type = this._date = this._container = this._element = this._header = this._body = null;
    }
  }
);

CustomEvents.mixin(Calendar);
module.exports = Calendar;


/***/ }),
/* 30 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";
/**
 * @fileoverview Date <-> Text formatting module
 * @author NHN. FE Development Lab <dl_javascript@nhn.com>
 */



var inArray = __webpack_require__(3);
var forEachArray = __webpack_require__(2);
var defineClass = __webpack_require__(0);

var util = __webpack_require__(4);
var dateUtil = __webpack_require__(5);
var constants = __webpack_require__(1);
var localeTexts = __webpack_require__(10);

var rFormableKeys = /\\?(yyyy|yy|mmmm|mmm|mm|m|dd|d|hh|h|a)/gi;
var mapForConverting = {
  yyyy: {
    expression: '(\\d{4}|\\d{2})',
    type: constants.TYPE_YEAR
  },
  yy: {
    expression: '(\\d{4}|\\d{2})',
    type: constants.TYPE_YEAR
  },
  y: {
    expression: '(\\d{4}|\\d{2})',
    type: constants.TYPE_YEAR
  },
  M: {
    expression: '(1[012]|0[1-9]|[1-9])',
    type: constants.TYPE_MONTH
  },
  MM: {
    expression: '(1[012]|0[1-9]|[1-9])',
    type: constants.TYPE_MONTH
  },
  MMM: {
    expression: '(1[012]|0[1-9]|[1-9])',
    type: constants.TYPE_MONTH
  },
  MMMM: {
    expression: '(1[012]|0[1-9]|[1-9])',
    type: constants.TYPE_MONTH
  },
  mmm: {
    expression: '(1[012]|0[1-9]|[1-9])',
    type: constants.TYPE_MONTH
  },
  mmmm: {
    expression: '(1[012]|0[1-9]|[1-9])',
    type: constants.TYPE_MONTH
  },
  dd: {
    expression: '([12]\\d{1}|3[01]|0[1-9]|[1-9])',
    type: constants.TYPE_DATE
  },
  d: {
    expression: '([12]\\d{1}|3[01]|0[1-9]|[1-9])',
    type: constants.TYPE_DATE
  },
  D: {
    expression: '([12]\\d{1}|3[01]|0[1-9]|[1-9])',
    type: constants.TYPE_DATE
  },
  DD: {
    expression: '([12]\\d{1}|3[01]|0[1-9]|[1-9])',
    type: constants.TYPE_DATE
  },
  h: {
    expression: '(d{1}|0\\d{1}|1\\d{1}|2[0123])',
    type: constants.TYPE_HOUR
  },
  hh: {
    expression: '(d{1}|[01]\\d{1}|2[0123])',
    type: constants.TYPE_HOUR
  },
  H: {
    expression: '(d{1}|0\\d{1}|1\\d{1}|2[0123])',
    type: constants.TYPE_HOUR
  },
  HH: {
    expression: '(d{1}|[01]\\d{1}|2[0123])',
    type: constants.TYPE_HOUR
  },
  m: {
    expression: '(d{1}|[012345]\\d{1})',
    type: constants.TYPE_MINUTE
  },
  mm: {
    expression: '(d{1}|[012345]\\d{1})',
    type: constants.TYPE_MINUTE
  },
  a: {
    expression: '([ap]m)',
    type: constants.TYPE_MERIDIEM
  },
  A: {
    expression: '([ap]m)',
    type: constants.TYPE_MERIDIEM
  }
};

/**
 * @class
 * @ignore
 */
var DateTimeFormatter = defineClass(
  /** @lends DateTimeFormatter.prototype */ {
    init: function(rawStr, titles) {
      /**
       * @type {string}
       * @private
       */
      this._rawStr = rawStr;

      /**
       * @type {Array}
       * @private
       * @example
       *  rawStr = "yyyy-MM-dd" --> keyOrder = ['year', 'month', 'date']
       *  rawStr = "MM/dd, yyyy" --> keyOrder = ['month', 'date', 'year']
       */
      this._keyOrder = null;

      /**
       * @type {RegExp}
       * @private
       */
      this._regExp = null;

      /**
       * Titles
       * @type {object}
       * @private
       */
      this._titles = titles || localeTexts.en.titles;

      this._parseFormat();
    },

    /**
     * Parse initial format and make the keyOrder, regExp
     * @private
     */
    _parseFormat: function() {
      var regExpStr = '^';
      var matchedKeys = this._rawStr.match(rFormableKeys);
      var keyOrder = [];

      matchedKeys = util.filter(matchedKeys, function(key) {
        return key[0] !== '\\';
      });

      forEachArray(matchedKeys, function(key, index) {
        if (!/m/i.test(key)) {
          key = key.toLowerCase();
        }

        regExpStr += mapForConverting[key].expression + '[\\D\\s]*';
        keyOrder[index] = mapForConverting[key].type;
      });

      // This formatter does not allow additional numbers at the end of string.
      regExpStr += '$';

      this._keyOrder = keyOrder;

      this._regExp = new RegExp(regExpStr, 'gi');
    },

    /**
     * Parse string to dateHash
     * @param {string} str - Date string
     * @returns {Date}
     */
    parse: function(str) {
      var dateHash = {
        year: 0,
        month: 1,
        date: 1,
        hour: 0,
        minute: 0
      };
      var hasMeridiem = false;
      var isPM = false;
      var matched;

      this._regExp.lastIndex = 0;
      matched = this._regExp.exec(str);

      if (!matched) {
        throw Error('DateTimeFormatter: Not matched - "' + str + '"');
      }

      // eslint-disable-next-line complexity
      forEachArray(this._keyOrder, function(name, index) {
        var value = matched[index + 1];

        if (name === constants.TYPE_MERIDIEM && /[ap]m/i.test(value)) {
          hasMeridiem = true;
          isPM = /pm/i.test(value);
        } else {
          value = Number(value);

          if (value !== 0 && !value) {
            throw Error('DateTimeFormatter: Unknown value - ' + matched[index + 1]);
          }

          if (name === constants.TYPE_YEAR && value < 100) {
            value += 2000;
          }

          dateHash[name] = value;
        }
      });

      if (hasMeridiem) {
        isPM = isPM || dateHash.hour > 12;
        dateHash.hour %= 12;
        if (isPM) {
          dateHash.hour += 12;
        }
      }

      return new Date(
        dateHash.year,
        dateHash.month - 1,
        dateHash.date,
        dateHash.hour,
        dateHash.minute
      );
    },

    /**
     * Returns raw string of format
     * @returns {string}
     */
    getRawString: function() {
      return this._rawStr;
    },

    /**
     * Format date to string
     * @param {Date} dateObj - Date object
     * @returns {string}
     */
    format: function(dateObj) {
      var year = dateObj.getFullYear();
      var month = dateObj.getMonth() + 1;
      var dayInMonth = dateObj.getDate();
      var day = dateObj.getDay();
      var hour = dateObj.getHours();
      var minute = dateObj.getMinutes();
      var meridiem = 'a'; // Default value for unusing meridiem format
      var replaceMap;

      if (inArray(constants.TYPE_MERIDIEM, this._keyOrder) > -1) {
        meridiem = hour >= 12 ? 'pm' : 'am';
        hour = dateUtil.getMeridiemHour(hour);
      }

      replaceMap = {
        yyyy: year,
        yy: String(year).substr(2, 2),
        M: month,
        MM: dateUtil.prependLeadingZero(month),
        MMM: this._titles.MMM[month - 1],
        MMMM: this._titles.MMMM[month - 1],
        d: dayInMonth,
        dd: dateUtil.prependLeadingZero(dayInMonth),
        D: this._titles.D[day],
        DD: this._titles.DD[day],
        hh: dateUtil.prependLeadingZero(hour),
        h: hour,
        mm: dateUtil.prependLeadingZero(minute),
        m: minute,
        A: meridiem.toUpperCase(),
        a: meridiem
      };

      return this._rawStr.replace(rFormableKeys, function(key) {
        if (key[0] === '\\') {
          return key.substr(1);
        }

        return replaceMap[key] || replaceMap[key.toLowerCase()] || '';
      });
    }
  }
);

module.exports = DateTimeFormatter;


/***/ }),
/* 31 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";
/**
 * @fileoverview Bind DOM events
 * @author NHN FE Development Lab <dl_javascript@nhn.com>
 */



var isString = __webpack_require__(13);
var forEach = __webpack_require__(9);

var safeEvent = __webpack_require__(32);

/**
 * Bind DOM events.
 * @param {HTMLElement} element - element to bind events
 * @param {(string|object)} types - Space splitted events names or eventName:handler object
 * @param {(function|object)} handler - handler function or context for handler method
 * @param {object} [context] context - context for handler method.
 * @memberof module:domEvent
 * @example
 * var div = document.querySelector('div');
 * 
 * // Bind one event to an element.
 * on(div, 'click', toggle);
 * 
 * // Bind multiple events with a same handler to multiple elements at once.
 * // Use event names splitted by a space.
 * on(div, 'mouseenter mouseleave', changeColor);
 * 
 * // Bind multiple events with different handlers to an element at once.
 * // Use an object which of key is an event name and value is a handler function.
 * on(div, {
 *   keydown: highlight,
 *   keyup: dehighlight
 * });
 * 
 * // Set a context for handler method.
 * var name = 'global';
 * var repository = {name: 'CodeSnippet'};
 * on(div, 'drag', function() {
 *  console.log(this.name);
 * }, repository);
 * // Result when you drag a div: "CodeSnippet"
 */
function on(element, types, handler, context) {
  if (isString(types)) {
    forEach(types.split(/\s+/g), function(type) {
      bindEvent(element, type, handler, context);
    });

    return;
  }

  forEach(types, function(func, type) {
    bindEvent(element, type, func, handler);
  });
}

/**
 * Bind DOM events
 * @param {HTMLElement} element - element to bind events
 * @param {string} type - events name
 * @param {function} handler - handler function or context for handler method
 * @param {object} [context] context - context for handler method.
 * @private
 */
function bindEvent(element, type, handler, context) {
  /**
     * Event handler
     * @param {Event} e - event object
     */
  function eventHandler(e) {
    handler.call(context || element, e || window.event);
  }

  if ('addEventListener' in element) {
    element.addEventListener(type, eventHandler);
  } else if ('attachEvent' in element) {
    element.attachEvent('on' + type, eventHandler);
  }
  memorizeHandler(element, type, handler, eventHandler);
}

/**
 * Memorize DOM event handler for unbinding.
 * @param {HTMLElement} element - element to bind events
 * @param {string} type - events name
 * @param {function} handler - handler function that user passed at on() use
 * @param {function} wrappedHandler - handler function that wrapped by domevent for implementing some features
 * @private
 */
function memorizeHandler(element, type, handler, wrappedHandler) {
  var events = safeEvent(element, type);
  var existInEvents = false;

  forEach(events, function(obj) {
    if (obj.handler === handler) {
      existInEvents = true;

      return false;
    }

    return true;
  });

  if (!existInEvents) {
    events.push({
      handler: handler,
      wrappedHandler: wrappedHandler
    });
  }
}

module.exports = on;


/***/ }),
/* 32 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";
/**
 * @fileoverview Get event collection for specific HTML element
 * @author NHN FE Development Lab <dl_javascript@nhn.com>
 */



var EVENT_KEY = '_feEventKey';

/**
 * Get event collection for specific HTML element
 * @param {HTMLElement} element - HTML element
 * @param {string} type - event type
 * @returns {array}
 * @private
 */
function safeEvent(element, type) {
  var events = element[EVENT_KEY];
  var handlers;

  if (!events) {
    events = element[EVENT_KEY] = {};
  }

  handlers = events[type];
  if (!handlers) {
    handlers = events[type] = [];
  }

  return handlers;
}

module.exports = safeEvent;


/***/ }),
/* 33 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";
/**
 * @fileoverview Unbind DOM events
 * @author NHN FE Development Lab <dl_javascript@nhn.com>
 */



var isString = __webpack_require__(13);
var forEach = __webpack_require__(9);

var safeEvent = __webpack_require__(32);

/**
 * Unbind DOM events
 * If a handler function is not passed, remove all events of that type.
 * @param {HTMLElement} element - element to unbind events
 * @param {(string|object)} types - Space splitted events names or eventName:handler object
 * @param {function} [handler] - handler function
 * @memberof module:domEvent
 * @example
 * // Following the example of domEvent#on
 * 
 * // Unbind one event from an element.
 * off(div, 'click', toggle);
 * 
 * // Unbind multiple events with a same handler from multiple elements at once.
 * // Use event names splitted by a space.
 * off(element, 'mouseenter mouseleave', changeColor);
 * 
 * // Unbind multiple events with different handlers from an element at once.
 * // Use an object which of key is an event name and value is a handler function.
 * off(div, {
 *   keydown: highlight,
 *   keyup: dehighlight
 * });
 * 
 * // Unbind events without handlers.
 * off(div, 'drag');
 */
function off(element, types, handler) {
  if (isString(types)) {
    forEach(types.split(/\s+/g), function(type) {
      unbindEvent(element, type, handler);
    });

    return;
  }

  forEach(types, function(func, type) {
    unbindEvent(element, type, func);
  });
}

/**
 * Unbind DOM events
 * If a handler function is not passed, remove all events of that type.
 * @param {HTMLElement} element - element to unbind events
 * @param {string} type - events name
 * @param {function} [handler] - handler function
 * @private
 */
function unbindEvent(element, type, handler) {
  var events = safeEvent(element, type);
  var index;

  if (!handler) {
    forEach(events, function(item) {
      removeHandler(element, type, item.wrappedHandler);
    });
    events.splice(0, events.length);
  } else {
    forEach(events, function(item, idx) {
      if (handler === item.handler) {
        removeHandler(element, type, item.wrappedHandler);
        index = idx;

        return false;
      }

      return true;
    });
    events.splice(index, 1);
  }
}

/**
 * Remove an event handler
 * @param {HTMLElement} element - An element to remove an event
 * @param {string} type - event type
 * @param {function} handler - event handler
 * @private
 */
function removeHandler(element, type, handler) {
  if ('removeEventListener' in element) {
    element.removeEventListener(type, handler);
  } else if ('detachEvent' in element) {
    element.detachEvent('on' + type, handler);
  }
}

module.exports = off;


/***/ }),
/* 34 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";
/**
 * @fileoverview The entry file of DatePicker components
 * @author NHN. FE Development Team
 */



var DatePicker = __webpack_require__(21);
var DateRangePicker = __webpack_require__(60);
var Calendar = __webpack_require__(29);

__webpack_require__(61);

/**
 * Create a calendar.
 * @see {@link Calendar}
 * @see {@link /tutorial-example07-calendar Calendar example}
 * @static
 * @param {HTMLElement|string} wrapperElement - Container element or selector of the Calendar
 * @param {Object} [options] - {@link Calendar} options. Refer to the {@link Calendar Calendar instance's options}.
 * @returns {Calendar}
 * @example
 * const calendar = DatePicker.createCalendar('#calendar-wrapper', {
 *    language: 'en',
 *    showToday: true,
 *    showJumpButtons: false,
 *    date: new Date(),
 *    type: 'date'
 * });
 */
DatePicker.createCalendar = function(wrapperElement, options) {
  return new Calendar(wrapperElement, options);
};

/**
 * Create a date-range picker.
 * @see {@link DateRangePicker}
 * @see {@link /tutorial-example08-daterangepicker DateRangePicker example}
 * @static
 * @param {object} options - {@link DateRangePicker} options. Refer to the {@link DateRangePicker DateRangePicker instance's options}.
 * @returns {DateRangePicker}
 * @example
 * const rangepicker = DatePicker.createRangePicker({
 *     startpicker: {
 *         input: '#start-input',
 *         container: '#start-container'
 *     },
 *     endpicker: {
 *         input: '#end-input',
 *         container: '#end-container'
 *     },
 *     type: 'date',
 *     format: 'yyyy-MM-dd'
 *     selectableRanges: [
 *         [new Date(2017, 3, 1), new Date(2017, 5, 1)],
 *         [new Date(2017, 6, 3), new Date(2017, 10, 5)]
 *     ]
 * });
 */
DatePicker.createRangePicker = function(options) {
  return new DateRangePicker(options);
};

module.exports = DatePicker;


/***/ }),
/* 35 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";
/**
 * @fileoverview Provide a simple inheritance in prototype-oriented.
 * @author NHN FE Development Lab <dl_javascript@nhn.com>
 */



var createObject = __webpack_require__(36);

/**
 * Provide a simple inheritance in prototype-oriented.
 * Caution :
 *  Don't overwrite the prototype of child constructor.
 *
 * @param {function} subType Child constructor
 * @param {function} superType Parent constructor
 * @memberof module:inheritance
 * @example
 * var inherit = require('tui-code-snippet/inheritance/inherit'); // node, commonjs
 *
 * // Parent constructor
 * function Animal(leg) {
 *     this.leg = leg;
 * }
 * Animal.prototype.growl = function() {
 *     // ...
 * };
 *
 * // Child constructor
 * function Person(name) {
 *     this.name = name;
 * }
 *
 * // Inheritance
 * inherit(Person, Animal);
 *
 * // After this inheritance, please use only the extending of property.
 * // Do not overwrite prototype.
 * Person.prototype.walk = function(direction) {
 *     // ...
 * };
 */
function inherit(subType, superType) {
  var prototype = createObject(superType.prototype);
  prototype.constructor = subType;
  subType.prototype = prototype;
}

module.exports = inherit;


/***/ }),
/* 36 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";
/**
 * @fileoverview Create a new object with the specified prototype object and properties.
 * @author NHN FE Development Lab <dl_javascript@nhn.com>
 */



/**
 * @module inheritance
 */

/**
 * Create a new object with the specified prototype object and properties.
 * @param {Object} obj This object will be a prototype of the newly-created object.
 * @returns {Object}
 * @memberof module:inheritance
 */
function createObject(obj) {
  function F() {} // eslint-disable-line require-jsdoc
  F.prototype = obj;

  return new F();
}

module.exports = createObject;


/***/ }),
/* 37 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";
/**
 * @fileoverview Check whether the given variable is existing or not.
 * @author NHN FE Development Lab <dl_javascript@nhn.com>
 */



var isUndefined = __webpack_require__(12);
var isNull = __webpack_require__(38);

/**
 * Check whether the given variable is existing or not.
 * If the given variable is not null and not undefined, returns true.
 * @param {*} param - Target for checking
 * @returns {boolean} Is existy?
 * @memberof module:type
 * @example
 * var isExisty = require('tui-code-snippet/type/isExisty'); // node, commonjs
 *
 * isExisty(''); //true
 * isExisty(0); //true
 * isExisty([]); //true
 * isExisty({}); //true
 * isExisty(null); //false
 * isExisty(undefined); //false
*/
function isExisty(param) {
  return !isUndefined(param) && !isNull(param);
}

module.exports = isExisty;


/***/ }),
/* 38 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";
/**
 * @fileoverview Check whether the given variable is null or not.
 * @author NHN FE Development Lab <dl_javascript@nhn.com>
 */



/**
 * Check whether the given variable is null or not.
 * If the given variable(arguments[0]) is null, returns true.
 * @param {*} obj - Target for checking
 * @returns {boolean} Is null?
 * @memberof module:type
 */
function isNull(obj) {
  return obj === null;
}

module.exports = isNull;


/***/ }),
/* 39 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";
/**
 * @fileoverview Check whether the given variable is a function or not.
 * @author NHN FE Development Lab <dl_javascript@nhn.com>
 */



/**
 * Check whether the given variable is a function or not.
 * If the given variable is a function, return true.
 * @param {*} obj - Target for checking
 * @returns {boolean} Is function?
 * @memberof module:type
 */
function isFunction(obj) {
  return obj instanceof Function;
}

module.exports = isFunction;


/***/ }),
/* 40 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";
/**
 * @fileoverview Check element match selector
 * @author NHN FE Development Lab <dl_javascript@nhn.com>
 */



var inArray = __webpack_require__(3);
var toArray = __webpack_require__(41);

var elProto = Element.prototype;
var matchSelector = elProto.matches ||
    elProto.webkitMatchesSelector ||
    elProto.mozMatchesSelector ||
    elProto.msMatchesSelector ||
    function(selector) {
      var doc = this.document || this.ownerDocument;

      return inArray(this, toArray(doc.querySelectorAll(selector))) > -1;
    };

/**
 * Check element match selector
 * @param {HTMLElement} element - element to check
 * @param {string} selector - selector to check
 * @returns {boolean} is selector matched to element?
 * @memberof module:domUtil
 */
function matches(element, selector) {
  return matchSelector.call(element, selector);
}

module.exports = matches;


/***/ }),
/* 41 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";
/**
 * @fileoverview Transform the Array-like object to Array.
 * @author NHN FE Development Lab <dl_javascript@nhn.com>
 */



var forEachArray = __webpack_require__(2);

/**
 * Transform the Array-like object to Array.
 * In low IE (below 8), Array.prototype.slice.call is not perfect. So, try-catch statement is used.
 * @param {*} arrayLike Array-like object
 * @returns {Array} Array
 * @memberof module:collection
 * @example
 * var toArray = require('tui-code-snippet/collection/toArray'); // node, commonjs
 *
 * var arrayLike = {
 *     0: 'one',
 *     1: 'two',
 *     2: 'three',
 *     3: 'four',
 *     length: 4
 * };
 * var result = toArray(arrayLike);
 *
 * alert(result instanceof Array); // true
 * alert(result); // one,two,three,four
 */
function toArray(arrayLike) {
  var arr;
  try {
    arr = Array.prototype.slice.call(arrayLike);
  } catch (e) {
    arr = [];
    forEachArray(arrayLike, function(value) {
      arr.push(value);
    });
  }

  return arr;
}

module.exports = toArray;


/***/ }),
/* 42 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";
/**
 * @fileoverview Convert kebab-case
 * @author NHN FE Development Lab <dl_javascript@nhn.com>
 */



/**
 * Convert kebab-case
 * @param {string} key - string to be converted to Kebab-case
 * @private
 */
function convertToKebabCase(key) {
  return key.replace(/([A-Z])/g, function(match) {
    return '-' + match.toLowerCase();
  });
}

module.exports = convertToKebabCase;


/***/ }),
/* 43 */
/***/ (function(module, exports) {

module.exports = __WEBPACK_EXTERNAL_MODULE__43__;

/***/ }),
/* 44 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";
/**
 * @fileoverview Calendar Header
 * @author NHN. FE dev Lab <dl_javascript@nhn.com>
 */



var defineClass = __webpack_require__(0);
var CustomEvents = __webpack_require__(8);
var closest = __webpack_require__(25);
var removeElement = __webpack_require__(14);

var localeTexts = __webpack_require__(10);
var headerTmpl = __webpack_require__(45);
var DateTimeFormatter = __webpack_require__(30);
var constants = __webpack_require__(1);
var util = __webpack_require__(4);
var mouseTouchEvent = __webpack_require__(19);

var TYPE_DATE = constants.TYPE_DATE;
var TYPE_MONTH = constants.TYPE_MONTH;
var TYPE_YEAR = constants.TYPE_YEAR;

var CLASS_NAME_TITLE_MONTH = 'tui-calendar-title-month';
var CLASS_NAME_TITLE_YEAR = 'tui-calendar-title-year';
var CLASS_NAME_TITLE_YEAR_TO_YEAR = 'tui-calendar-title-year-to-year';

var SELECTOR_INNER_ELEM = '.tui-calendar-header-inner';
var SELECTOR_INFO_ELEM = '.tui-calendar-header-info';
var SELECTOR_BTN = '.tui-calendar-btn';

var YEAR_TITLE_FORMAT = 'yyyy';

/**
 * @ignore
 * @class
 * @param {string|HTMLElement} container - Header container or selector
 * @param {object} option - Header option
 * @param {string} option.language - Header language
 * @param {boolean} option.showToday - Has today box or not.
 * @param {boolean} option.showJumpButtons - Has jump buttons or not.
 */
var Header = defineClass(
  /** @lends Header.prototype */ {
    init: function(container, option) {
      /**
       * Container element
       * @type {HTMLElement}
       * @private
       */
      this._container = util.getElement(container);

      /**
       * Header inner element
       * @type {HTMLElement}
       * @private
       */
      this._innerElement = null;

      /**
       * Header info element
       * @type {HTMLElement}
       * @private
       */
      this._infoElement = null;

      /**
       * Render today box or not
       * @type {boolean}
       * @private
       */
      this._showToday = option.showToday;

      /**
       * Render jump buttons or not (next,prev year on date calendar)
       * @type {boolean}
       * @private
       */
      this._showJumpButtons = option.showJumpButtons;

      /**
       * Year_Month title formatter
       * @type {DateTimeFormatter}
       * @private
       */
      this._yearMonthTitleFormatter = null;

      /**
       * Year title formatter
       * @type {DateTimeFormatter}
       * @private
       */
      this._yearTitleFormatter = null;

      /**
       * Today formatter
       * @type {DateTimeFormatter}
       * @private
       */
      this._todayFormatter = null;

      this._setFormatters(localeTexts[option.language]);
      this._setEvents(option);
    },

    /**
     * @param {object} localeText - Locale text
     * @private
     */
    _setFormatters: function(localeText) {
      this._yearMonthTitleFormatter = new DateTimeFormatter(
        localeText.titleFormat,
        localeText.titles
      );
      this._yearTitleFormatter = new DateTimeFormatter(YEAR_TITLE_FORMAT, localeText.titles);
      this._todayFormatter = new DateTimeFormatter(localeText.todayFormat, localeText.titles);
    },

    /**
     * @param {object} option - Constructor option
     * @private
     */
    _setEvents: function() {
      mouseTouchEvent.on(this._container, 'click', this._onClickHandler, this);
    },

    /**
     * @private
     */
    _removeEvents: function() {
      this.off();
      mouseTouchEvent.off(this._container, 'click', this._onClickHandler);
    },

    /**
     * Fire customEvents
     * @param {Event} ev An event object
     * @private
     */
    _onClickHandler: function(ev) {
      var target = util.getTarget(ev);

      if (closest(target, SELECTOR_BTN)) {
        this.fire('click', ev);
      }
    },

    /**
     * @param {string} type - Calendar type
     * @returns {string}
     * @private
     */
    _getTitleClass: function(type) {
      switch (type) {
        case TYPE_DATE:
          return CLASS_NAME_TITLE_MONTH;
        case TYPE_MONTH:
          return CLASS_NAME_TITLE_YEAR;
        case TYPE_YEAR:
          return CLASS_NAME_TITLE_YEAR_TO_YEAR;
        default:
          return '';
      }
    },

    /**
     * @param {Date} date - date
     * @param {string} type - Calendar type
     * @returns {string}
     * @private
     */
    _getTitleText: function(date, type) {
      var currentYear, start, end;

      switch (type) {
        case TYPE_DATE:
          return this._yearMonthTitleFormatter.format(date);
        case TYPE_MONTH:
          return this._yearTitleFormatter.format(date);
        case TYPE_YEAR:
          currentYear = date.getFullYear();
          start = new Date(currentYear - 4, 0, 1);
          end = new Date(currentYear + 4, 0, 1);

          return (
            this._yearTitleFormatter.format(start) + ' - ' + this._yearTitleFormatter.format(end)
          );
        default:
          return '';
      }
    },

    /**
     * Change langauge
     * @param {string} language - Language
     */
    changeLanguage: function(language) {
      this._setFormatters(localeTexts[language]);
    },

    /**
     * Render header
     * @param {Date} date - date
     * @param {string} type - Calendar type
     */
    render: function(date, type) {
      var context = {
        showToday: this._showToday,
        showJumpButtons: this._showJumpButtons,
        todayText: this._todayFormatter.format(new Date()),
        isDateCalendar: type === TYPE_DATE,
        titleClass: this._getTitleClass(type),
        title: this._getTitleText(date, type)
      };

      this._container.innerHTML = headerTmpl(context).replace(/^\s+|\s+$/g, '');
      this._innerElement = this._container.querySelector(SELECTOR_INNER_ELEM);
      if (context.showToday) {
        this._infoElement = this._container.querySelector(SELECTOR_INFO_ELEM);
      }
    },

    /**
     * Destroy header
     */
    destroy: function() {
      this._removeEvents();
      removeElement(this._innerElement);
      removeElement(this._infoElement);
      this._container = this._showToday = this._showJumpButtons = this._yearMonthTitleFormatter = this._yearTitleFormatter = this._todayFormatter = this._innerElement = this._infoElement = null;
    }
  }
);

CustomEvents.mixin(Header);
module.exports = Header;


/***/ }),
/* 45 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var template = __webpack_require__(11);

module.exports = function(context) {
  var source =
    '{{if isDateCalendar}}' +
    '  {{if showJumpButtons}}' +
    '    <div class="tui-calendar-header-inner tui-calendar-has-btns">' +
    '      <button class="tui-calendar-btn tui-calendar-btn-prev-year">Prev year</button>' +
    '      <button class="tui-calendar-btn tui-calendar-btn-prev-month">Prev month</button>' +
    '      <em class="tui-calendar-title {{titleClass}}">{{title}}</em>' +
    '      <button class="tui-calendar-btn tui-calendar-btn-next-month">Next month</button>' +
    '      <button class="tui-calendar-btn tui-calendar-btn-next-year">Next year</button>' +
    '    </div>' +
    '  {{else}}' +
    '    <div class="tui-calendar-header-inner">' +
    '      <button class="tui-calendar-btn tui-calendar-btn-prev-month">Prev month</button>' +
    '      <em class="tui-calendar-title {{titleClass}}">{{title}}</em>' +
    '      <button class="tui-calendar-btn tui-calendar-btn-next-month">Next month</button>' +
    '    </div>' +
    '  {{/if}}' +
    '{{else}}' +
    '  <div class="tui-calendar-header-inner">' +
    '    <button class="tui-calendar-btn tui-calendar-btn-prev-year">Prev year</button>' +
    '    <em class="tui-calendar-title {{titleClass}}">{{title}}</em>' +
    '    <button class="tui-calendar-btn tui-calendar-btn-next-year">Next year</button>' +
    '  </div>' +
    '{{/if}}' +
    '{{if showToday}}' +
    '  <div class="tui-calendar-header-info">' +
    '    <p class="tui-calendar-title-today">{{todayText}}</p>' +
    '  </div>' +
    '{{/if}}';

  return template(source, context);
};


/***/ }),
/* 46 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";
/**
 * @fileoverview Check whether the given variable is a instance of HTMLNode or not.
 * @author NHN FE Development Lab <dl_javascript@nhn.com>
 */



/**
 * Check whether the given variable is a instance of HTMLNode or not.
 * If the given variables is a instance of HTMLNode, return true.
 * @param {*} html - Target for checking
 * @returns {boolean} Is HTMLNode ?
 * @memberof module:type
 */
function isHTMLNode(html) {
  if (typeof HTMLElement === 'object') {
    return (html && (html instanceof HTMLElement || !!html.nodeType));
  }

  return !!(html && html.nodeType);
}

module.exports = isHTMLNode;


/***/ }),
/* 47 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";
/**
 * @fileoverview Send hostname on DOMContentLoaded.
 * @author NHN FE Development Lab <dl_javascript@nhn.com>
 */



var isUndefined = __webpack_require__(12);
var imagePing = __webpack_require__(48);

var ms7days = 7 * 24 * 60 * 60 * 1000;

/**
 * Check if the date has passed 7 days
 * @param {number} date - milliseconds
 * @returns {boolean}
 * @private
 */
function isExpired(date) {
  var now = new Date().getTime();

  return now - date > ms7days;
}

/**
 * Send hostname on DOMContentLoaded.
 * To prevent hostname set tui.usageStatistics to false.
 * @param {string} appName - application name
 * @param {string} trackingId - GA tracking ID
 * @ignore
 */
function sendHostname(appName, trackingId) {
  var url = 'https://www.google-analytics.com/collect';
  var hostname = location.hostname;
  var hitType = 'event';
  var eventCategory = 'use';
  var applicationKeyForStorage = 'TOAST UI ' + appName + ' for ' + hostname + ': Statistics';
  var date = window.localStorage.getItem(applicationKeyForStorage);

  // skip if the flag is defined and is set to false explicitly
  if (!isUndefined(window.tui) && window.tui.usageStatistics === false) {
    return;
  }

  // skip if not pass seven days old
  if (date && !isExpired(date)) {
    return;
  }

  window.localStorage.setItem(applicationKeyForStorage, new Date().getTime());

  setTimeout(function() {
    if (document.readyState === 'interactive' || document.readyState === 'complete') {
      imagePing(url, {
        v: 1,
        t: hitType,
        tid: trackingId,
        cid: hostname,
        dp: hostname,
        dh: appName,
        el: appName,
        ec: eventCategory
      });
    }
  }, 1000);
}

module.exports = sendHostname;


/***/ }),
/* 48 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";
/**
 * @fileoverview Request image ping.
 * @author NHN FE Development Lab <dl_javascript@nhn.com>
 */



var forEachOwnProperties = __webpack_require__(23);

/**
 * @module request
 */

/**
 * Request image ping.
 * @param {String} url url for ping request
 * @param {Object} trackingInfo infos for make query string
 * @returns {HTMLElement}
 * @memberof module:request
 * @example
 * var imagePing = require('tui-code-snippet/request/imagePing'); // node, commonjs
 *
 * imagePing('https://www.google-analytics.com/collect', {
 *     v: 1,
 *     t: 'event',
 *     tid: 'trackingid',
 *     cid: 'cid',
 *     dp: 'dp',
 *     dh: 'dh'
 * });
 */
function imagePing(url, trackingInfo) {
  var trackingElement = document.createElement('img');
  var queryString = '';
  forEachOwnProperties(trackingInfo, function(value, key) {
    queryString += '&' + key + '=' + value;
  });
  queryString = queryString.substring(1);

  trackingElement.src = url + '?' + queryString;

  trackingElement.style.display = 'none';
  document.body.appendChild(trackingElement);
  document.body.removeChild(trackingElement);

  return trackingElement;
}

module.exports = imagePing;


/***/ }),
/* 49 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";
/**
 * @fileoverview Calendar body
 * @author NHN. FE Development Lab <dl_javascript@nhn.com>
 */



var forEachArray = __webpack_require__(2);
var defineClass = __webpack_require__(0);

var DateLayer = __webpack_require__(50);
var MonthLayer = __webpack_require__(52);
var YearLayer = __webpack_require__(54);
var constants = __webpack_require__(1);

var TYPE_DATE = constants.TYPE_DATE;
var TYPE_MONTH = constants.TYPE_MONTH;
var TYPE_YEAR = constants.TYPE_YEAR;

/**
 * @ignore
 * @class
 */
var Body = defineClass(
  /** @lends Body.prototype */ {
    init: function(bodyContainer, option) {
      var language = option.language;

      /**
       * Body container element
       * @type {HTMLElement}
       * @private
       */
      this._container = bodyContainer;

      /**
       * DateLayer
       * @type {DateLayer}
       * @private
       */
      this._dateLayer = new DateLayer(language);

      /**
       * MonthLayer
       * @type {MonthLayer}
       * @private
       */
      this._monthLayer = new MonthLayer(language);

      /**
       * YearLayer
       * @type {YearLayer}
       * @private
       */
      this._yearLayer = new YearLayer(language);

      /**
       * Current Layer
       * @type {DateLayer|MonthLayer|YearLayer}
       * @private
       */
      this._currentLayer = this._dateLayer;
    },

    /**
     * Returns matched layer
     * @param {string} type - Layer type
     * @returns {Base} - Layer
     * @private
     */
    _getLayer: function(type) {
      switch (type) {
        case TYPE_DATE:
          return this._dateLayer;
        case TYPE_MONTH:
          return this._monthLayer;
        case TYPE_YEAR:
          return this._yearLayer;
        default:
          return this._currentLayer;
      }
    },

    /**
     * Iterate each layer
     * @param {Function} fn - function
     * @private
     */
    _eachLayer: function(fn) {
      forEachArray([this._dateLayer, this._monthLayer, this._yearLayer], fn);
    },

    /**
     * Change language
     * @param {string} language - Language
     */
    changeLanguage: function(language) {
      this._eachLayer(function(layer) {
        layer.changeLanguage(language);
      });
    },

    /**
     * Render body
     * @param {Date} date - date
     * @param {string} type - Layer type
     */
    render: function(date, type) {
      var nextLayer = this._getLayer(type);
      var prevLayer = this._currentLayer;

      prevLayer.remove();
      nextLayer.render(date, this._container);

      this._currentLayer = nextLayer;
    },

    /**
     * Returns date elements
     * @returns {HTMLElement[]}
     */
    getDateElements: function() {
      return this._currentLayer.getDateElements();
    },

    /**
     * Destory
     */
    destroy: function() {
      this._eachLayer(function(layer) {
        layer.remove();
      });

      this._container = this._currentLayer = this._dateLayer = this._monthLayer = this._yearLayer = null;
    }
  }
);

module.exports = Body;


/***/ }),
/* 50 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";
/**
 * @fileoverview Date layer
 * @author NHN. FE Development Lab <dl_javascript@nhn.com>
 */



var defineClass = __webpack_require__(0);

var dateUtil = __webpack_require__(5);
var bodyTmpl = __webpack_require__(51);
var LayerBase = __webpack_require__(20);
var TYPE_DATE = __webpack_require__(1).TYPE_DATE;

var DATE_SELECTOR = '.tui-calendar-date';

/**
 * @ignore
 * @class
 * @extends LayerBase
 * @param {string} language - Initial language
 */
var DateLayer = defineClass(
  LayerBase,
  /** @lends DateLayer.prototype */ {
    init: function(language) {
      LayerBase.call(this, language);
    },

    /**
     * Layer type
     * @type {string}
     * @private
     */
    _type: TYPE_DATE,

    /**
     * @override
     * @private
     * @returns {object} Template context
     */
    _makeContext: function(date) {
      var daysShort = this._localeText.titles.D;
      var year, month;

      date = date || new Date();
      year = date.getFullYear();
      month = date.getMonth() + 1;

      return {
        Sun: daysShort[0],
        Mon: daysShort[1],
        Tue: daysShort[2],
        Wed: daysShort[3],
        Thu: daysShort[4],
        Fri: daysShort[5],
        Sat: daysShort[6],
        year: year,
        month: month,
        weeks: this._getWeeks(year, month)
      };
    },

    /**
     * weeks (templating) for date-calendar
     * @param {number} year - Year
     * @param {number} month - Month
     * @returns {Array.<Array.<Date>>}
     * @private
     */
    _getWeeks: function(year, month) {
      var weekNumber = 0;
      var weeksCount = 6; // Fix for no changing height
      var weeks = [];
      var dates, i;

      for (; weekNumber < weeksCount; weekNumber += 1) {
        dates = [];
        for (i = 0; i < 7; i += 1) {
          dates.push(dateUtil.getDateOfWeek(year, month, weekNumber, i));
        }
        weeks.push(this._getWeek(year, month, dates));
      }

      return weeks;
    },

    /**
     * week (templating) for date-calendar
     * @param {number} currentYear
     * @param {number} currentMonth
     * @param {Array.<Date>} dates
     * @private
     */
    _getWeek: function(currentYear, currentMonth, dates) {
      var firstDateOfCurrentMonth = new Date(currentYear, currentMonth - 1, 1);
      var lastDateOfCurrentMonth = new Date(currentYear, currentMonth, 0);
      var contexts = [];
      var i = 0;
      var length = dates.length;
      var date, className;

      for (; i < length; i += 1) {
        className = 'tui-calendar-date';
        date = dates[i];

        if (date < firstDateOfCurrentMonth) {
          className += ' tui-calendar-prev-month';
        }

        if (date > lastDateOfCurrentMonth) {
          className += ' tui-calendar-next-month';
        }

        if (date.getDay() === 0) {
          className += ' tui-calendar-sun';
        } else if (date.getDay() === 6) {
          className += ' tui-calendar-sat';
        }

        contexts.push({
          dayInMonth: date.getDate(),
          className: className,
          timestamp: date.getTime()
        });
      }

      return contexts;
    },

    /**
     * Render date-layer
     * @override
     * @param {Date} date Date to render
     * @param {HTMLElement} container A container element for the rendered element
     */
    render: function(date, container) {
      var context = this._makeContext(date);

      container.innerHTML = bodyTmpl(context);
      this._element = container.firstChild;
    },

    /**
     * Return date elements
     * @override
     * @returns {HTMLElement[]}
     */
    getDateElements: function() {
      return this._element.querySelectorAll(DATE_SELECTOR);
    }
  }
);

module.exports = DateLayer;


/***/ }),
/* 51 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var template = __webpack_require__(11);

module.exports = function(context) {
  var source =
    '<table class="tui-calendar-body-inner" cellspacing="0" cellpadding="0">' +
    '  <caption><span>Dates</span></caption>' +
    '  <thead class="tui-calendar-body-header">' +
    '    <tr>' +
    '      <th class="tui-sun" scope="col">{{Sun}}</th>' +
    '      <th scope="col">{{Mon}}</th>' +
    '      <th scope="col">{{Tue}}</th>' +
    '      <th scope="col">{{Wed}}</th>' +
    '      <th scope="col">{{Thu}}</th>' +
    '      <th scope="col">{{Fri}}</th>' +
    '      <th class="tui-sat" scope="col">{{Sat}}</th>' +
    '    </tr>' +
    '  </thead>' +
    '  <tbody>' +
    '    {{each weeks}}' +
    '    <tr class="tui-calendar-week">' +
    '      {{each @this}}' +
    '      <td class="{{@this["className"]}}" data-timestamp="{{@this["timestamp"]}}">{{@this["dayInMonth"]}}</td>' +
    '      {{/each}}' +
    '    </tr>' +
    '    {{/each}}' +
    '  </tbody>' +
    '</table>';

  return template(source, context);
};


/***/ }),
/* 52 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";
/**
 * @fileoverview Month layer
 * @author NHN. FE Development Lab <dl_javascript@nhn.com>
 */



var defineClass = __webpack_require__(0);

var bodyTmpl = __webpack_require__(53);
var LayerBase = __webpack_require__(20);
var TYPE_MONTH = __webpack_require__(1).TYPE_MONTH;
var dateUtil = __webpack_require__(5);

var DATE_SELECTOR = '.tui-calendar-month';

/**
 * @class
 * @extends LayerBase
 * @param {string} language - Initial language
 * @ignore
 */
var MonthLayer = defineClass(
  LayerBase,
  /** @lends MonthLayer.prototype */ {
    init: function(language) {
      LayerBase.call(this, language);
    },

    /**
     * Layer type
     * @type {string}
     * @private
     */
    _type: TYPE_MONTH,

    /**
     * @override
     * @returns {object} Template context
     * @private
     */
    _makeContext: function(date) {
      var monthsShort = this._localeText.titles.MMM;

      return {
        year: date.getFullYear(),
        Jan: monthsShort[0],
        Feb: monthsShort[1],
        Mar: monthsShort[2],
        Apr: monthsShort[3],
        May: monthsShort[4],
        Jun: monthsShort[5],
        Jul: monthsShort[6],
        Aug: monthsShort[7],
        Sep: monthsShort[8],
        Oct: monthsShort[9],
        Nov: monthsShort[10],
        Dec: monthsShort[11],
        getFirstDayTimestamp: dateUtil.getFirstDayTimestamp
      };
    },

    /**
     * Render month-layer element
     * @override
     * @param {Date} date Date to render
     * @param {HTMLElement} container A container element for the rendered element
     */
    render: function(date, container) {
      var context = this._makeContext(date);

      container.innerHTML = bodyTmpl(context);
      this._element = container.firstChild;
    },

    /**
     * Returns month elements
     * @override
     * @returns {HTMLElement[]}
     */
    getDateElements: function() {
      return this._element.querySelectorAll(DATE_SELECTOR);
    }
  }
);

module.exports = MonthLayer;


/***/ }),
/* 53 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var template = __webpack_require__(11);

module.exports = function(context) {
  var source =
    '<table class="tui-calendar-body-inner">' +
    '  <caption><span>Months</span></caption>' +
    '  <tbody>' +
    '    <tr class="tui-calendar-month-group">' +
    '      <td class="tui-calendar-month" data-timestamp={{getFirstDayTimestamp year 0}}>{{Jan}}</td>' +
    '      <td class="tui-calendar-month" data-timestamp={{getFirstDayTimestamp year 1}}>{{Feb}}</td>' +
    '      <td class="tui-calendar-month" data-timestamp={{getFirstDayTimestamp year 2}}>{{Mar}}</td>' +
    '      <td class="tui-calendar-month" data-timestamp={{getFirstDayTimestamp year 3}}>{{Apr}}</td>' +
    '    </tr>' +
    '    <tr class="tui-calendar-month-group">' +
    '      <td class="tui-calendar-month" data-timestamp={{getFirstDayTimestamp year 4}}>{{May}}</td>' +
    '      <td class="tui-calendar-month" data-timestamp={{getFirstDayTimestamp year 5}}>{{Jun}}</td>' +
    '      <td class="tui-calendar-month" data-timestamp={{getFirstDayTimestamp year 6}}>{{Jul}}</td>' +
    '      <td class="tui-calendar-month" data-timestamp={{getFirstDayTimestamp year 7}}>{{Aug}}</td>' +
    '    </tr>' +
    '    <tr class="tui-calendar-month-group">' +
    '      <td class="tui-calendar-month" data-timestamp={{getFirstDayTimestamp year 8}}>{{Sep}}</td>' +
    '      <td class="tui-calendar-month" data-timestamp={{getFirstDayTimestamp year 9}}>{{Oct}}</td>' +
    '      <td class="tui-calendar-month" data-timestamp={{getFirstDayTimestamp year 10}}>{{Nov}}</td>' +
    '      <td class="tui-calendar-month" data-timestamp={{getFirstDayTimestamp year 11}}>{{Dec}}</td>' +
    '    </tr>' +
    '  </tbody>' +
    '</table>';

  return template(source, context);
};


/***/ }),
/* 54 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";
/**
 * @fileoverview Year layer
 * @author NHN. FE Development Lab <dl_javascript@nhn.com>
 */



var defineClass = __webpack_require__(0);

var bodyTmpl = __webpack_require__(55);
var LayerBase = __webpack_require__(20);
var TYPE_YEAR = __webpack_require__(1).TYPE_YEAR;
var dateUtil = __webpack_require__(5);

var DATE_SELECTOR = '.tui-calendar-year';

/**
 * @class
 * @extends LayerBase
 * @param {string} language - Initial language
 * @ignore
 */
var YearLayer = defineClass(
  LayerBase,
  /** @lends YearLayer.prototype */ {
    init: function(language) {
      LayerBase.call(this, language);
    },

    /**
     * Layer type
     * @type {string}
     * @private
     */
    _type: TYPE_YEAR,

    /**
     * @override
     * @returns {object} Template context
     * @private
     */
    _makeContext: function(date) {
      var year = date.getFullYear();

      return {
        yearGroups: [
          dateUtil.getRangeArr(year - 4, year - 2),
          dateUtil.getRangeArr(year - 1, year + 1),
          dateUtil.getRangeArr(year + 2, year + 4)
        ],
        getFirstDayTimestamp: dateUtil.getFirstDayTimestamp
      };
    },

    /**
     * Render year-layer element
     * @override
     * @param {Date} date Date to render
     * @param {HTMLElement} container A container element for the rendered element
     */
    render: function(date, container) {
      var context = this._makeContext(date);

      container.innerHTML = bodyTmpl(context);
      this._element = container.firstChild;
    },

    /**
     * Returns year elements
     * @override
     * @returns {HTMLElement[]}
     */
    getDateElements: function() {
      return this._element.querySelectorAll(DATE_SELECTOR);
    }
  }
);

module.exports = YearLayer;


/***/ }),
/* 55 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var template = __webpack_require__(11);

module.exports = function(context) {
  var source =
    '<table class="tui-calendar-body-inner">' +
    '  <caption><span>Years</span></caption>' +
    '  <tbody>' +
    '    {{each yearGroups}}' +
    '    <tr class="tui-calendar-year-group">' +
    '      {{each @this}}' +
    '      <td class="tui-calendar-year" data-timestamp={{getFirstDayTimestamp @this 0}}>' +
    '        {{@this}}' +
    '      </td>' +
    '      {{/each}}' +
    '    </tr>' +
    '    {{/each}}' +
    '  </tbody>' +
    '</table>';

  return template(source, context);
};


/***/ }),
/* 56 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";
/**
 * @fileoverview RangeModel
 * @author NHN. FE Development Lab <dl_javascript@nhn.com>
 */



var forEachArray = __webpack_require__(2);
var defineClass = __webpack_require__(0);
var isNumber = __webpack_require__(15);

var Range = __webpack_require__(57);
var util = __webpack_require__(4);

/**
 * @class
 * @ignore
 * @param {Array.<Array.<number>>} ranges - Ranges
 */
var RangeModel = defineClass(
  /** @lends RangeModel.prototype */ {
    init: function(ranges) {
      ranges = ranges || [];

      /**
       * @type {Array.<Range>}
       * @private
       */
      this._ranges = [];

      forEachArray(
        ranges,
        function(range) {
          this.add(range[0], range[1]);
        },
        this
      );
    },

    /**
     * Whether the ranges contain a time or time-range
     * @param {number} start - Start
     * @param {number} [end] - End
     * @returns {boolean}
     */
    contains: function(start, end) {
      var i = 0;
      var length = this._ranges.length;
      var range;

      for (; i < length; i += 1) {
        range = this._ranges[i];
        if (range.contains(start, end)) {
          return true;
        }
      }

      return false;
    },

    /**
     * Whether overlaps with a point or range
     * @param {number} start - Start
     * @param {number} [end] - End
     * @returns {boolean}
     */
    hasOverlap: function(start, end) {
      var i = 0;
      var length = this._ranges.length;
      var range;

      for (; i < length; i += 1) {
        range = this._ranges[i];
        if (range.isOverlapped(start, end)) {
          return true;
        }
      }

      return false;
    },

    /**
     * Add range
     * @param {number} start - Start
     * @param {number} [end] - End
     */
    add: function(start, end) {
      var overlapped = false;
      var i = 0;
      var len = this._ranges.length;
      var range;

      for (; i < len; i += 1) {
        range = this._ranges[i];
        overlapped = range.isOverlapped(start, end);

        if (overlapped) {
          range.merge(start, end);
          break;
        }

        if (start < range.start) {
          break;
        }
      }

      if (!overlapped) {
        this._ranges.splice(i, 0, new Range(start, end));
      }
    },

    /**
     * Returns minimum value in ranges
     * @returns {number}
     */
    getMinimumValue: function() {
      return this._ranges[0].start;
    },

    /**
     * Returns maximum value in ranges
     * @returns {number}
     */
    getMaximumValue: function() {
      var length = this._ranges.length;

      return this._ranges[length - 1].end;
    },

    /**
     * @param {number} start - Start
     * @param {number} [end] - End
     */
    exclude: function(start, end) {
      if (!isNumber(end)) {
        end = start;
      }

      forEachArray(
        this._ranges,
        function(range) {
          var rangeEnd;

          if (range.isOverlapped(start, end)) {
            rangeEnd = range.end; // Save before excluding
            range.exclude(start, end);

            if (end + 1 <= rangeEnd) {
              this.add(end + 1, rangeEnd); // Add split range
            }
          }
        },
        this
      );

      // Reduce empty ranges
      this._ranges = util.filter(this._ranges, function(range) {
        return !range.isEmpty();
      });
    },

    /**
     * Returns the first overlapped range from the point or range
     * @param {number} start - Start
     * @param {number} end - End
     * @returns {Array.<number>} - [start, end]
     */
    findOverlappedRange: function(start, end) {
      var i = 0;
      var len = this._ranges.length;
      var range;

      for (; i < len; i += 1) {
        range = this._ranges[i];
        if (range.isOverlapped(start, end)) {
          return [range.start, range.end];
        }
      }

      return null;
    }
  }
);

module.exports = RangeModel;


/***/ }),
/* 57 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";
/**
 * @fileoverview Range (in RangeModel)
 * @author NHN. FE Development Lab <dl_javascript@nhn.com>
 */



var defineClass = __webpack_require__(0);
var isNumber = __webpack_require__(15);

/**
 * @class
 * @ignore
 * @param {number} start - Start of range
 * @param {number} [end] - End of range
 */
var Range = defineClass(
  /** @lends Range.prototype */ {
    init: function(start, end) {
      this.setRange(start, end);
    },

    /**
     * Set range
     * @param {number} start - Start number
     * @param {number} [end] - End number
     */
    setRange: function(start, end) {
      if (!isNumber(end)) {
        end = start;
      }

      this.start = Math.min(start, end);
      this.end = Math.max(start, end);
    },

    /**
     * Merge range
     * @param {number} start - Start
     * @param {number} [end] - End
     */
    merge: function(start, end) {
      if (!isNumber(start) || !isNumber(end) || !this.isOverlapped(start, end)) {
        return;
      }

      this.start = Math.min(start, this.start);
      this.end = Math.max(end, this.end);
    },

    /**
     * Whether being empty.
     * @returns {boolean}
     */
    isEmpty: function() {
      return !isNumber(this.start) || !isNumber(this.end);
    },

    /**
     * Set empty
     */
    setEmpty: function() {
      this.start = this.end = null;
    },

    /**
     * Whether containing a range.
     * @param {number} start - Start
     * @param {number} [end] - End
     * @returns {boolean}
     */
    contains: function(start, end) {
      if (!isNumber(end)) {
        end = start;
      }

      return this.start <= start && end <= this.end;
    },

    /**
     * Whether overlaps with a range
     * @param {number} start - Start
     * @param {number} [end] - End
     * @returns {boolean}
     */
    isOverlapped: function(start, end) {
      if (!isNumber(end)) {
        end = start;
      }

      return this.start <= end && this.end >= start;
    },

    /**
     * Exclude a range
     * @param {number} start - Start
     * @param {number} end - End
     */
    exclude: function(start, end) {
      if (start <= this.start && end >= this.end) {
        // Excluding range contains this
        this.setEmpty();
      } else if (this.contains(start)) {
        this.setRange(this.start, start - 1);
      } else if (this.contains(end)) {
        this.setRange(end + 1, this.end);
      }
    }
  }
);

module.exports = Range;


/***/ }),
/* 58 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var template = __webpack_require__(11);

module.exports = function(context) {
  var source =
    '<div class="tui-datepicker">' +
    '  {{if timePicker}}' +
    '    {{if isTab}}' +
    '      <div class="tui-datepicker-selector">' +
    '        <button type="button" class="tui-datepicker-selector-button tui-is-checked" aria-label="selected">' +
    '          <span class="tui-ico-date"></span>{{localeText["date"]}}' +
    '        </button>' +
    '        <button type="button" class="tui-datepicker-selector-button">' +
    '          <span class="tui-ico-time"></span>{{localeText["time"]}}' +
    '        </button>' +
    '      </div>' +
    '      <div class="tui-datepicker-body">' +
    '        <div class="tui-calendar-container"></div>' +
    '        <div class="tui-timepicker-container"></div>' +
    '      </div>' +
    '    {{else}}' +
    '      <div class="tui-datepicker-body">' +
    '        <div class="tui-calendar-container"></div>' +
    '      </div>' +
    '      <div class="tui-datepicker-footer">' +
    '        <div class="tui-timepicker-container"></div>' +
    '      </div>' +
    '    {{/if}}' +
    '  {{else}}' +
    '    <div class="tui-datepicker-body">' +
    '      <div class="tui-calendar-container"></div>' +
    '    </div>' +
    '  {{/if}}' +
    '</div>';

  return template(source, context);
};


/***/ }),
/* 59 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";
/**
 * @fileoverview DatePicker input(element) component
 * @author NHN. FE Development Lab <dl_javascript@nhn.com>
 */



var defineClass = __webpack_require__(0);
var CustomEvents = __webpack_require__(8);
var on = __webpack_require__(31);
var off = __webpack_require__(33);

var DateTimeFormatter = __webpack_require__(30);
var mouseTouchEvent = __webpack_require__(19);
var util = __webpack_require__(4);

var DEFAULT_FORMAT = 'yyyy-MM-dd';

/**
 * DatePicker Input
 * @ignore
 * @class
 * @param {string|HTMLElement} inputElement - Input element or selector
 * @param {object} option - Option
 * @param {string} option.id - Id
 * @param {string} option.format - Text format
 */
var DatePickerInput = defineClass(
  /** @lends DatePickerInput.prototype */ {
    init: function(inputElement, option) {
      option.format = option.format || DEFAULT_FORMAT;

      /**
       * Input element
       * @type {HTMLElement}
       * @private
       */
      this._input = util.getElement(inputElement);

      /**
       * Id
       * @type {string}
       * @private
       */
      this._id = option.id;

      /**
       * LocaleText titles
       * @type {Object}
       * @private
       */
      this._titles = option.localeText.titles;

      /**
       * Text<->DateTime Formatter
       * @type {DateTimeFormatter}
       * @private
       */
      this._formatter = new DateTimeFormatter(option.format, this._titles);

      this._setEvents();
    },

    /**
     * Change locale titles
     * @param {object} titles - locale text in format
     */
    changeLocaleTitles: function(titles) {
      this._titles = titles;
    },

    /**
     * Set input 'click', 'change' event
     * @private
     */
    _setEvents: function() {
      if (this._input) {
        on(this._input, 'change', this._onChangeHandler, this);
        mouseTouchEvent.on(this._input, 'click', this._onClickHandler, this);
      }
    },

    /**
     * Remove events
     * @private
     */
    _removeEvents: function() {
      this.off();

      if (this._input) {
        off(this._input, 'change', this._onChangeHandler);
        mouseTouchEvent.off(this._input, 'click', this._onClickHandler);
      }
    },

    /**
     * Onchange handler
     */
    _onChangeHandler: function() {
      this.fire('change');
    },

    /**
     * Onclick handler
     */
    _onClickHandler: function() {
      this.fire('click');
    },

    /**
     * Check element is same as the input element.
     * @param {HTMLElement} el - To check matched set of elements
     * @returns {boolean}
     */
    is: function(el) {
      return this._input === el;
    },

    /**
     * Enable input
     */
    enable: function() {
      if (this._input) {
        this._input.removeAttribute('disabled');
      }
    },

    /**
     * Disable input
     */
    disable: function() {
      if (this._input) {
        this._input.setAttribute('disabled', true);
      }
    },

    /**
     * Return format
     * @returns {string}
     */
    getFormat: function() {
      return this._formatter.getRawString();
    },

    /**
     * Set format
     * @param {string} format - Format
     */
    setFormat: function(format) {
      if (!format) {
        return;
      }

      this._formatter = new DateTimeFormatter(format, this._titles);
    },

    /**
     * Clear text
     */
    clearText: function() {
      if (this._input) {
        this._input.value = '';
      }
    },

    /**
     * Set value from date
     * @param {Date} date - Date
     */
    setDate: function(date) {
      if (this._input) {
        this._input.value = this._formatter.format(date);
      }
    },

    /**
     * Returns date from input-text
     * @returns {Date}
     * @throws {Error}
     */
    getDate: function() {
      var value = '';

      if (this._input) {
        value = this._input.value;
      }

      return this._formatter.parse(value);
    },

    /**
     * Destroy
     */
    destroy: function() {
      this._removeEvents();

      this._input = this._id = this._formatter = null;
    }
  }
);

CustomEvents.mixin(DatePickerInput);
module.exports = DatePickerInput;


/***/ }),
/* 60 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";
/**
 * @fileoverview Date-Range picker
 * @author NHN. FE Development Lab <dl_javascript@nhn.com>
 */



var forEachArray = __webpack_require__(2);
var defineClass = __webpack_require__(0);
var CustomEvents = __webpack_require__(8);
var addClass = __webpack_require__(16);
var getData = __webpack_require__(26);
var removeClass = __webpack_require__(18);
var extend = __webpack_require__(7);

var DatePicker = __webpack_require__(21);
var dateUtil = __webpack_require__(5);
var constants = __webpack_require__(1);
var util = __webpack_require__(4);

var CLASS_NAME_RANGE_PICKER = 'tui-rangepicker';
var CLASS_NAME_SELECTED = constants.CLASS_NAME_SELECTED;
var CLASS_NAME_SELECTED_RANGE = 'tui-is-selected-range';

/**
 * @class
 * @description
 * Create a date-range picker by {@link DatePicker#createRangePicker DatePicker.createRangePicker()}.
 * @see {@link /tutorial-example08-daterangepicker DateRangePicker example}
 * @param {object} options - DateRangePicker options
 *     @param {object} options.startpicker - Startpicker options
 *         @param {HTMLElement|string} options.startpicker.input - Startpicker input element or selector
 *         @param {HTMLElement|string} options.startpicker.container - Startpicker container element or selector
 *         @param {Date|number} [options.startpicker.date] - Initial date of the start picker. Set by a Date instance or a number(timestamp). (default: no initial date)
 *     @param {object} options.endpicker - Endpicker options
 *         @param {HTMLElement|string} options.endpicker.input - Endpicker input element or selector
 *         @param {HTMLElement|string} options.endpicker.container - Endpicker container element or selector
 *         @param {Date|number} [options.endpicker.date] - Initial date of the end picker. Set by a Date instance or a number(timestamp). (default: no initial date)
 *     @param {('date'|'month'|'year')} [options.type = 'date'] - DatePicker type. Determine whether to choose a date, month, or year.
 *     @param {string} [options.language='en'] - Language code. English('en') and Korean('ko') are provided as default. To use the other languages, use {@link DatePicker#localeTexts DatePicker.localeTexts}.
 *     @param {object|boolean} [options.timePicker] - [TimePicker](https://nhn.github.io/tui.time-picker/latest) options. Refer to the [TimePicker instance's options](https://nhn.github.io/tui.time-picker/latest/TimePicker). To create the TimePicker without customization, set to true.
 *     @param {object} [options.calendar] - {@link Calendar} options. Refer to the {@link Calendar Calendar instance's options}.
 *     @param {string} [options.format = 'yyyy-mm-dd'] - Format of the Date string
 *     @param {Array.<Array.<Date|number>>} [options.selectableRanges] - Ranges of selectable date. Set by Date instances or numbers(timestamp).
 *     @param {boolean} [options.showAlways = false] - Show the DateRangePicker always
 *     @param {boolean} [options.autoClose = true] - Close the DateRangePicker after clicking the date
 *     @param {boolean} [options.usageStatistics = true] - Send a hostname to Google Analytics (default: true)
 * @example
 * import DatePicker from 'tui-date-picker' // ES6
 * // const DatePicker = require('tui-date-picker'); // CommonJS
 * // const DatePicker = tui.DatePicker;
 *
 * const rangePicker = DatePicker.createRangePicker({
 *     startpicker: {
 *         input: '#start-input',
 *         container: '#start-container'
 *         date: new Date(2019, 3, 1)
 *     },
 *     endpicker: {
 *         input: '#end-input',
 *         container: '#end-container'
 *     },
 *     type: 'date',
 *     format: 'yyyy-MM-dd'
 *     selectableRanges: [
 *         [new Date(2017, 3, 1), new Date(2017, 5, 1)],
 *         [new Date(2017, 6, 3), new Date(2017, 10, 5)]
 *     ]
 * });
 */
var DateRangePicker = defineClass(
  /** @lends DateRangePicker.prototype */ {
    init: function(options) {
      var startpickerOpt, endpickerOpt;

      options = options || {};
      startpickerOpt = options.startpicker;
      endpickerOpt = options.endpicker;

      if (!startpickerOpt) {
        throw new Error('The "startpicker" option is required.');
      }
      if (!endpickerOpt) {
        throw new Error('The "endpicker" option is required.');
      }

      /**
       * Start picker
       * @type {DatePicker}
       * @private
       */
      this._startpicker = null;

      /**
       * End picker
       * @type {DatePicker}
       * @private
       */
      this._endpicker = null;

      this._initializePickers(options);
      this._syncRangesToEndpicker();
    },

    /**
     * Create picker
     * @param {Object} options - DatePicker options
     * @private
     */
    _initializePickers: function(options) {
      var startpickerContainer = util.getElement(options.startpicker.container);
      var endpickerContainer = util.getElement(options.endpicker.container);
      var startInput = util.getElement(options.startpicker.input);
      var endInput = util.getElement(options.endpicker.input);

      var startpickerOpt = extend({}, options, {
        input: {
          element: startInput,
          format: options.format
        },
        date: options.startpicker.date
      });
      var endpickerOpt = extend({}, options, {
        input: {
          element: endInput,
          format: options.format
        },
        date: options.endpicker.date
      });

      this._startpicker = new DatePicker(startpickerContainer, startpickerOpt);
      this._startpicker.addCssClass(CLASS_NAME_RANGE_PICKER);
      this._startpicker.on('change', this._onChangeStartpicker, this);
      this._startpicker.on('draw', this._onDrawPicker, this);

      this._endpicker = new DatePicker(endpickerContainer, endpickerOpt);
      this._endpicker.addCssClass(CLASS_NAME_RANGE_PICKER);
      this._endpicker.on('change', this._onChangeEndpicker, this);
      this._endpicker.on('draw', this._onDrawPicker, this);
    },

    /**
     * Set selection-class to elements after calendar drawing
     * @param {Object} eventData - Event data {@link DatePicker#event:draw}
     * @private
     */
    _onDrawPicker: function(eventData) {
      var calendarType = eventData.type;
      var startDate = this._startpicker.getDate();
      var endDate = this._endpicker.getDate();

      if (!startDate) {
        return;
      }

      if (!endDate) {
        // Convert null to invaild date.
        endDate = new Date(NaN);
      }

      forEachArray(
        eventData.dateElements,
        function(el) {
          var elDate = new Date(Number(getData(el, 'timestamp')));
          var isInRange = dateUtil.inRange(startDate, endDate, elDate, calendarType);
          var isSelected =
            dateUtil.isSame(startDate, elDate, calendarType) ||
            dateUtil.isSame(endDate, elDate, calendarType);

          this._setRangeClass(el, isInRange);
          this._setSelectedClass(el, isSelected);
        },
        this
      );
    },

    /**
     * Set range class to element
     * @param {HTMLElement} el - Element
     * @param {boolean} isInRange - In range
     * @private
     */
    _setRangeClass: function(el, isInRange) {
      if (isInRange) {
        addClass(el, CLASS_NAME_SELECTED_RANGE);
      } else {
        removeClass(el, CLASS_NAME_SELECTED_RANGE);
      }
    },

    /**
     * Set selected class to element
     * @param {HTMLElement} el - Element
     * @param {boolean} isSelected - Is selected
     * @private
     */
    _setSelectedClass: function(el, isSelected) {
      if (isSelected) {
        addClass(el, CLASS_NAME_SELECTED);
      } else {
        removeClass(el, CLASS_NAME_SELECTED);
      }
    },

    /**
     * Sync ranges to endpicker
     * @private
     */
    _syncRangesToEndpicker: function() {
      var startDate = this._startpicker.getDate();
      var overlappedRange;

      if (startDate) {
        overlappedRange = this._startpicker.findOverlappedRange(
          dateUtil.cloneWithStartOf(startDate).getTime(),
          dateUtil.cloneWithEndOf(startDate).getTime()
        );

        this._endpicker.enable();
        this._endpicker.setRanges([[startDate.getTime(), overlappedRange[1].getTime()]]);
      } else {
        this._endpicker.setNull();
        this._endpicker.disable();
      }
    },

    /**
     * After change on start-picker
     * @private
     */
    _onChangeStartpicker: function() {
      this._syncRangesToEndpicker();
      /**
       * Occur after the start date is changed.
       * @event DateRangePicker#change:start
       * @see {@link https://nhn.github.io/tui.code-snippet/latest/CustomEvents#on rangePicker.on()} to bind event handlers.
       * @see {@link https://nhn.github.io/tui.code-snippet/latest/CustomEvents#off rangePicker.off()} to unbind event handlers.
       * @see Refer to {@link https://nhn.github.io/tui.code-snippet/latest/CustomEvents CustomEvents} for more methods. DateRangePicker mixes in the methods from CustomEvents.
       * @example
       * // bind the 'change:start' event
       * rangePicker.on('change:start', function() {
       *     console.log(`Start date: ${rangePicker.getStartDate()}`);
       * });
       *
       * // unbind the 'change:start' event
       * rangePicker.off('change:start');
       */
      this.fire('change:start');
    },

    /**
     * After change on end-picker
     * @private
     */
    _onChangeEndpicker: function() {
      /**
       * Occur after the end date is changed.
       * @event DateRangePicker#change:end
       * @see {@link https://nhn.github.io/tui.code-snippet/latest/CustomEvents#on rangePicker.on()} to bind event handlers.
       * @see {@link https://nhn.github.io/tui.code-snippet/latest/CustomEvents#off rangePicker.off()} to unbind event handlers.
       * @see Refer to {@link https://nhn.github.io/tui.code-snippet/latest/CustomEvents CustomEvents} for more methods. DateRangePicker mixes in the methods from CustomEvents.
       * @example
       * // bind the 'change:end' event
       * rangePicker.on('change:end', function() {
       *     console.log(`End date: ${rangePicker.getEndDate()}`);
       * });
       *
       * // unbind the 'change:end' event
       * rangePicker.off('change:end');
       */
      this.fire('change:end');
    },

    /**
     * Return a start-datepicker.
     * @returns {DatePicker}
     */
    getStartpicker: function() {
      return this._startpicker;
    },

    /**
     * Return a end-datepicker.
     * @returns {DatePicker}
     */
    getEndpicker: function() {
      return this._endpicker;
    },

    /**
     * Set the start date.
     * @param {Date} date - Start date
     */
    setStartDate: function(date) {
      this._startpicker.setDate(date);
    },

    /**
     * Return the start date.
     * @returns {?Date}
     */
    getStartDate: function() {
      return this._startpicker.getDate();
    },

    /**
     * Return the end date.
     * @returns {?Date}
     */
    getEndDate: function() {
      return this._endpicker.getDate();
    },

    /**
     * Set the end date.
     * @param {Date} date - End date
     */
    setEndDate: function(date) {
      this._endpicker.setDate(date);
    },

    /**
     * Set selectable ranges.
     * @param {Array.<Array.<number|Date>>} ranges - Selectable ranges. Use Date instances or numbers(timestamp).
     */
    setRanges: function(ranges) {
      this._startpicker.setRanges(ranges);
      this._syncRangesToEndpicker();
    },

    /**
     * Add a selectable range. Use Date instances or numbers(timestamp).
     * @param {Date|number} start - the start date
     * @param {Date|number} end - the end date
     */
    addRange: function(start, end) {
      this._startpicker.addRange(start, end);
      this._syncRangesToEndpicker();
    },

    /**
     * Remove a range. Use Date instances or numbers(timestamp).
     * @param {Date|number} start - the start date
     * @param {Date|number} end - the end date
     * @param {null|'date'|'month'|'year'} type - Range type. If falsy, start and end values are considered as timestamp
     */
    removeRange: function(start, end, type) {
      this._startpicker.removeRange(start, end, type);
      this._syncRangesToEndpicker();
    },

    /**
     * Change language.
     * @param {string} language - Language code. English('en') and Korean('ko') are provided as default.
     * @see To set to the other languages, use {@link DatePicker#localeTexts DatePicker.localeTexts}.
     */
    changeLanguage: function(language) {
      this._startpicker.changeLanguage(language);
      this._endpicker.changeLanguage(language);
    },

    /**
     * Destroy the date-range picker.
     */
    destroy: function() {
      this.off();
      this._startpicker.destroy();
      this._endpicker.destroy();
      this._startpicker = this._endpicker = null;
    }
  }
);

CustomEvents.mixin(DateRangePicker);
module.exports = DateRangePicker;


/***/ }),
/* 61 */
/***/ (function(module, exports, __webpack_require__) {

// extracted by mini-css-extract-plugin

/***/ })
/******/ ]);
});

/***/ }),

/***/ "./node_modules/tui-time-picker/dist/tui-time-picker.css":
/*!***************************************************************!*\
  !*** ./node_modules/tui-time-picker/dist/tui-time-picker.css ***!
  \***************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {


var content = __webpack_require__(/*! !../../css-loader??ref--5-1!../../postcss-loader/src??ref--5-2!./tui-time-picker.css */ "./node_modules/css-loader/index.js?!./node_modules/postcss-loader/src/index.js?!./node_modules/tui-time-picker/dist/tui-time-picker.css");

if(typeof content === 'string') content = [[module.i, content, '']];

var transform;
var insertInto;



var options = {"hmr":true}

options.transform = transform
options.insertInto = undefined;

var update = __webpack_require__(/*! ../../style-loader/lib/addStyles.js */ "./node_modules/style-loader/lib/addStyles.js")(content, options);

if(content.locals) module.exports = content.locals;

if(false) {}

/***/ }),

/***/ "./node_modules/tui-time-picker/dist/tui-time-picker.js":
/*!**************************************************************!*\
  !*** ./node_modules/tui-time-picker/dist/tui-time-picker.js ***!
  \**************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

/*!
 * TOAST UI Time Picker
 * @version 2.0.3
 * @author NHN FE Development Lab <dl_javascript@nhn.com>
 * @license MIT
 */
(function webpackUniversalModuleDefinition(root, factory) {
	if(true)
		module.exports = factory();
	else {}
})(window, function() {
return /******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, { enumerable: true, get: getter });
/******/ 		}
/******/ 	};
/******/
/******/ 	// define __esModule on exports
/******/ 	__webpack_require__.r = function(exports) {
/******/ 		if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 			Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 		}
/******/ 		Object.defineProperty(exports, '__esModule', { value: true });
/******/ 	};
/******/
/******/ 	// create a fake namespace object
/******/ 	// mode & 1: value is a module id, require it
/******/ 	// mode & 2: merge all properties of value into the ns
/******/ 	// mode & 4: return value when already ns object
/******/ 	// mode & 8|1: behave like require
/******/ 	__webpack_require__.t = function(value, mode) {
/******/ 		if(mode & 1) value = __webpack_require__(value);
/******/ 		if(mode & 8) return value;
/******/ 		if((mode & 4) && typeof value === 'object' && value && value.__esModule) return value;
/******/ 		var ns = Object.create(null);
/******/ 		__webpack_require__.r(ns);
/******/ 		Object.defineProperty(ns, 'default', { enumerable: true, value: value });
/******/ 		if(mode & 2 && typeof value != 'string') for(var key in value) __webpack_require__.d(ns, key, function(key) { return value[key]; }.bind(null, key));
/******/ 		return ns;
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "dist";
/******/
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 20);
/******/ })
/************************************************************************/
/******/ ([
/* 0 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";
/* eslint-disable complexity */
/**
 * @fileoverview Returns the first index at which a given element can be found in the array.
 * @author NHN FE Development Lab <dl_javascript@nhn.com>
 */



var isArray = __webpack_require__(2);

/**
 * @module array
 */

/**
 * Returns the first index at which a given element can be found in the array
 * from start index(default 0), or -1 if it is not present.
 * It compares searchElement to elements of the Array using strict equality
 * (the same method used by the ===, or triple-equals, operator).
 * @param {*} searchElement Element to locate in the array
 * @param {Array} array Array that will be traversed.
 * @param {number} startIndex Start index in array for searching (default 0)
 * @returns {number} the First index at which a given element, or -1 if it is not present
 * @memberof module:array
 * @example
 * var inArray = require('tui-code-snippet/array/inArray'); // node, commonjs
 *
 * var arr = ['one', 'two', 'three', 'four'];
 * var idx1 = inArray('one', arr, 3); // -1
 * var idx2 = inArray('one', arr); // 0
 */
function inArray(searchElement, array, startIndex) {
  var i;
  var length;
  startIndex = startIndex || 0;

  if (!isArray(array)) {
    return -1;
  }

  if (Array.prototype.indexOf) {
    return Array.prototype.indexOf.call(array, searchElement, startIndex);
  }

  length = array.length;
  for (i = startIndex; startIndex >= 0 && i < length; i += 1) {
    if (array[i] === searchElement) {
      return i;
    }
  }

  return -1;
}

module.exports = inArray;


/***/ }),
/* 1 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";
/**
 * @fileoverview Extend the target object from other objects.
 * @author NHN FE Development Lab <dl_javascript@nhn.com>
 */



/**
 * @module object
 */

/**
 * Extend the target object from other objects.
 * @param {object} target - Object that will be extended
 * @param {...object} objects - Objects as sources
 * @returns {object} Extended object
 * @memberof module:object
 */
function extend(target, objects) { // eslint-disable-line no-unused-vars
  var hasOwnProp = Object.prototype.hasOwnProperty;
  var source, prop, i, len;

  for (i = 1, len = arguments.length; i < len; i += 1) {
    source = arguments[i];
    for (prop in source) {
      if (hasOwnProp.call(source, prop)) {
        target[prop] = source[prop];
      }
    }
  }

  return target;
}

module.exports = extend;


/***/ }),
/* 2 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";
/**
 * @fileoverview Check whether the given variable is an instance of Array or not.
 * @author NHN FE Development Lab <dl_javascript@nhn.com>
 */



/**
 * Check whether the given variable is an instance of Array or not.
 * If the given variable is an instance of Array, return true.
 * @param {*} obj - Target for checking
 * @returns {boolean} Is array instance?
 * @memberof module:type
 */
function isArray(obj) {
  return obj instanceof Array;
}

module.exports = isArray;


/***/ }),
/* 3 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";
/**
 * @fileoverview Execute the provided callback once for each element present in the array(or Array-like object) in ascending order.
 * @author NHN FE Development Lab <dl_javascript@nhn.com>
 */



/**
 * Execute the provided callback once for each element present
 * in the array(or Array-like object) in ascending order.
 * If the callback function returns false, the loop will be stopped.
 * Callback function(iteratee) is invoked with three arguments:
 *  1) The value of the element
 *  2) The index of the element
 *  3) The array(or Array-like object) being traversed
 * @param {Array|Arguments|NodeList} arr The array(or Array-like object) that will be traversed
 * @param {function} iteratee Callback function
 * @param {Object} [context] Context(this) of callback function
 * @memberof module:collection
 * @example
 * var forEachArray = require('tui-code-snippet/collection/forEachArray'); // node, commonjs
 *
 * var sum = 0;
 *
 * forEachArray([1,2,3], function(value){
 *     sum += value;
 * });
 * alert(sum); // 6
 */
function forEachArray(arr, iteratee, context) {
  var index = 0;
  var len = arr.length;

  context = context || null;

  for (; index < len; index += 1) {
    if (iteratee.call(context, arr[index], index, arr) === false) {
      break;
    }
  }
}

module.exports = forEachArray;


/***/ }),
/* 4 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";
/**
 * @fileoverview Execute the provided callback once for each property of object(or element of array) which actually exist.
 * @author NHN FE Development Lab <dl_javascript@nhn.com>
 */



var isArray = __webpack_require__(2);
var forEachArray = __webpack_require__(3);
var forEachOwnProperties = __webpack_require__(16);

/**
 * @module collection
 */

/**
 * Execute the provided callback once for each property of object(or element of array) which actually exist.
 * If the object is Array-like object(ex-arguments object), It needs to transform to Array.(see 'ex2' of example).
 * If the callback function returns false, the loop will be stopped.
 * Callback function(iteratee) is invoked with three arguments:
 *  1) The value of the property(or The value of the element)
 *  2) The name of the property(or The index of the element)
 *  3) The object being traversed
 * @param {Object} obj The object that will be traversed
 * @param {function} iteratee Callback function
 * @param {Object} [context] Context(this) of callback function
 * @memberof module:collection
 * @example
 * var forEach = require('tui-code-snippet/collection/forEach'); // node, commonjs
 *
 * var sum = 0;
 *
 * forEach([1,2,3], function(value){
 *     sum += value;
 * });
 * alert(sum); // 6
 *
 * // In case of Array-like object
 * var array = Array.prototype.slice.call(arrayLike); // change to array
 * forEach(array, function(value){
 *     sum += value;
 * });
 */
function forEach(obj, iteratee, context) {
  if (isArray(obj)) {
    forEachArray(obj, iteratee, context);
  } else {
    forEachOwnProperties(obj, iteratee, context);
  }
}

module.exports = forEach;


/***/ }),
/* 5 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";
/**
 * @fileoverview Check whether the given variable is undefined or not.
 * @author NHN FE Development Lab <dl_javascript@nhn.com>
 */



/**
 * Check whether the given variable is undefined or not.
 * If the given variable is undefined, returns true.
 * @param {*} obj - Target for checking
 * @returns {boolean} Is undefined?
 * @memberof module:type
 */
function isUndefined(obj) {
  return obj === undefined; // eslint-disable-line no-undefined
}

module.exports = isUndefined;


/***/ }),
/* 6 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";
/**
 * @fileoverview Check whether the given variable is a string or not.
 * @author NHN FE Development Lab <dl_javascript@nhn.com>
 */



/**
 * Check whether the given variable is a string or not.
 * If the given variable is a string, return true.
 * @param {*} obj - Target for checking
 * @returns {boolean} Is string?
 * @memberof module:type
 */
function isString(obj) {
  return typeof obj === 'string' || obj instanceof String;
}

module.exports = isString;


/***/ }),
/* 7 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";
/**
 * @fileoverview Convert text by binding expressions with context.
 * @author NHN FE Development Lab <dl_javascript@nhn.com>
 */



var inArray = __webpack_require__(0);
var forEach = __webpack_require__(4);
var isArray = __webpack_require__(2);
var isString = __webpack_require__(6);
var extend = __webpack_require__(1);

// IE8 does not support capture groups.
var EXPRESSION_REGEXP = /{{\s?|\s?}}/g;
var BRACKET_NOTATION_REGEXP = /^[a-zA-Z0-9_@]+\[[a-zA-Z0-9_@"']+\]$/;
var BRACKET_REGEXP = /\[\s?|\s?\]/;
var DOT_NOTATION_REGEXP = /^[a-zA-Z_]+\.[a-zA-Z_]+$/;
var DOT_REGEXP = /\./;
var STRING_NOTATION_REGEXP = /^["']\w+["']$/;
var STRING_REGEXP = /"|'/g;
var NUMBER_REGEXP = /^-?\d+\.?\d*$/;

var EXPRESSION_INTERVAL = 2;

var BLOCK_HELPERS = {
  'if': handleIf,
  'each': handleEach,
  'with': handleWith
};

var isValidSplit = 'a'.split(/a/).length === 3;

/**
 * Split by RegExp. (Polyfill for IE8)
 * @param {string} text - text to be splitted\
 * @param {RegExp} regexp - regular expression
 * @returns {Array.<string>}
 */
var splitByRegExp = (function() {
  if (isValidSplit) {
    return function(text, regexp) {
      return text.split(regexp);
    };
  }

  return function(text, regexp) {
    var result = [];
    var prevIndex = 0;
    var match, index;

    if (!regexp.global) {
      regexp = new RegExp(regexp, 'g');
    }

    match = regexp.exec(text);
    while (match !== null) {
      index = match.index;
      result.push(text.slice(prevIndex, index));

      prevIndex = index + match[0].length;
      match = regexp.exec(text);
    }
    result.push(text.slice(prevIndex));

    return result;
  };
})();

/**
 * Find value in the context by an expression.
 * @param {string} exp - an expression
 * @param {object} context - context
 * @returns {*}
 * @private
 */
// eslint-disable-next-line complexity
function getValueFromContext(exp, context) {
  var splitedExps;
  var value = context[exp];

  if (exp === 'true') {
    value = true;
  } else if (exp === 'false') {
    value = false;
  } else if (STRING_NOTATION_REGEXP.test(exp)) {
    value = exp.replace(STRING_REGEXP, '');
  } else if (BRACKET_NOTATION_REGEXP.test(exp)) {
    splitedExps = exp.split(BRACKET_REGEXP);
    value = getValueFromContext(splitedExps[0], context)[getValueFromContext(splitedExps[1], context)];
  } else if (DOT_NOTATION_REGEXP.test(exp)) {
    splitedExps = exp.split(DOT_REGEXP);
    value = getValueFromContext(splitedExps[0], context)[splitedExps[1]];
  } else if (NUMBER_REGEXP.test(exp)) {
    value = parseFloat(exp);
  }

  return value;
}

/**
 * Extract elseif and else expressions.
 * @param {Array.<string>} ifExps - args of if expression
 * @param {Array.<string>} sourcesInsideBlock - sources inside if block
 * @returns {object} - exps: expressions of if, elseif, and else / sourcesInsideIf: sources inside if, elseif, and else block.
 * @private
 */
function extractElseif(ifExps, sourcesInsideBlock) {
  var exps = [ifExps];
  var sourcesInsideIf = [];
  var otherIfCount = 0;
  var start = 0;

  // eslint-disable-next-line complexity
  forEach(sourcesInsideBlock, function(source, index) {
    if (source.indexOf('if') === 0) {
      otherIfCount += 1;
    } else if (source === '/if') {
      otherIfCount -= 1;
    } else if (!otherIfCount && (source.indexOf('elseif') === 0 || source === 'else')) {
      exps.push(source === 'else' ? ['true'] : source.split(' ').slice(1));
      sourcesInsideIf.push(sourcesInsideBlock.slice(start, index));
      start = index + 1;
    }
  });

  sourcesInsideIf.push(sourcesInsideBlock.slice(start));

  return {
    exps: exps,
    sourcesInsideIf: sourcesInsideIf
  };
}

/**
 * Helper function for "if". 
 * @param {Array.<string>} exps - array of expressions split by spaces
 * @param {Array.<string>} sourcesInsideBlock - array of sources inside the if block
 * @param {object} context - context
 * @returns {string}
 * @private
 */
function handleIf(exps, sourcesInsideBlock, context) {
  var analyzed = extractElseif(exps, sourcesInsideBlock);
  var result = false;
  var compiledSource = '';

  forEach(analyzed.exps, function(exp, index) {
    result = handleExpression(exp, context);
    if (result) {
      compiledSource = compile(analyzed.sourcesInsideIf[index], context);
    }

    return !result;
  });

  return compiledSource;
}

/**
 * Helper function for "each".
 * @param {Array.<string>} exps - array of expressions split by spaces
 * @param {Array.<string>} sourcesInsideBlock - array of sources inside the each block
 * @param {object} context - context
 * @returns {string}
 * @private
 */
function handleEach(exps, sourcesInsideBlock, context) {
  var collection = handleExpression(exps, context);
  var additionalKey = isArray(collection) ? '@index' : '@key';
  var additionalContext = {};
  var result = '';

  forEach(collection, function(item, key) {
    additionalContext[additionalKey] = key;
    additionalContext['@this'] = item;
    extend(context, additionalContext);

    result += compile(sourcesInsideBlock.slice(), context);
  });

  return result;
}

/**
 * Helper function for "with ... as"
 * @param {Array.<string>} exps - array of expressions split by spaces
 * @param {Array.<string>} sourcesInsideBlock - array of sources inside the with block
 * @param {object} context - context
 * @returns {string}
 * @private
 */
function handleWith(exps, sourcesInsideBlock, context) {
  var asIndex = inArray('as', exps);
  var alias = exps[asIndex + 1];
  var result = handleExpression(exps.slice(0, asIndex), context);

  var additionalContext = {};
  additionalContext[alias] = result;

  return compile(sourcesInsideBlock, extend(context, additionalContext)) || '';
}

/**
 * Extract sources inside block in place.
 * @param {Array.<string>} sources - array of sources
 * @param {number} start - index of start block
 * @param {number} end - index of end block
 * @returns {Array.<string>}
 * @private
 */
function extractSourcesInsideBlock(sources, start, end) {
  var sourcesInsideBlock = sources.splice(start + 1, end - start);
  sourcesInsideBlock.pop();

  return sourcesInsideBlock;
}

/**
 * Handle block helper function
 * @param {string} helperKeyword - helper keyword (ex. if, each, with)
 * @param {Array.<string>} sourcesToEnd - array of sources after the starting block
 * @param {object} context - context
 * @returns {Array.<string>}
 * @private
 */
function handleBlockHelper(helperKeyword, sourcesToEnd, context) {
  var executeBlockHelper = BLOCK_HELPERS[helperKeyword];
  var helperCount = 1;
  var startBlockIndex = 0;
  var endBlockIndex;
  var index = startBlockIndex + EXPRESSION_INTERVAL;
  var expression = sourcesToEnd[index];

  while (helperCount && isString(expression)) {
    if (expression.indexOf(helperKeyword) === 0) {
      helperCount += 1;
    } else if (expression.indexOf('/' + helperKeyword) === 0) {
      helperCount -= 1;
      endBlockIndex = index;
    }

    index += EXPRESSION_INTERVAL;
    expression = sourcesToEnd[index];
  }

  if (helperCount) {
    throw Error(helperKeyword + ' needs {{/' + helperKeyword + '}} expression.');
  }

  sourcesToEnd[startBlockIndex] = executeBlockHelper(
    sourcesToEnd[startBlockIndex].split(' ').slice(1),
    extractSourcesInsideBlock(sourcesToEnd, startBlockIndex, endBlockIndex),
    context
  );

  return sourcesToEnd;
}

/**
 * Helper function for "custom helper".
 * If helper is not a function, return helper itself.
 * @param {Array.<string>} exps - array of expressions split by spaces (first element: helper)
 * @param {object} context - context
 * @returns {string}
 * @private
 */
function handleExpression(exps, context) {
  var result = getValueFromContext(exps[0], context);

  if (result instanceof Function) {
    return executeFunction(result, exps.slice(1), context);
  }

  return result;
}

/**
 * Execute a helper function.
 * @param {Function} helper - helper function
 * @param {Array.<string>} argExps - expressions of arguments
 * @param {object} context - context
 * @returns {string} - result of executing the function with arguments
 * @private
 */
function executeFunction(helper, argExps, context) {
  var args = [];
  forEach(argExps, function(exp) {
    args.push(getValueFromContext(exp, context));
  });

  return helper.apply(null, args);
}

/**
 * Get a result of compiling an expression with the context.
 * @param {Array.<string>} sources - array of sources split by regexp of expression.
 * @param {object} context - context
 * @returns {Array.<string>} - array of sources that bind with its context
 * @private
 */
function compile(sources, context) {
  var index = 1;
  var expression = sources[index];
  var exps, firstExp, result;

  while (isString(expression)) {
    exps = expression.split(' ');
    firstExp = exps[0];

    if (BLOCK_HELPERS[firstExp]) {
      result = handleBlockHelper(firstExp, sources.splice(index, sources.length - index), context);
      sources = sources.concat(result);
    } else {
      sources[index] = handleExpression(exps, context);
    }

    index += EXPRESSION_INTERVAL;
    expression = sources[index];
  }

  return sources.join('');
}

/**
 * Convert text by binding expressions with context.
 * <br>
 * If expression exists in the context, it will be replaced.
 * ex) '{{title}}' with context {title: 'Hello!'} is converted to 'Hello!'.
 * An array or object can be accessed using bracket and dot notation.
 * ex) '{{odds\[2\]}}' with context {odds: \[1, 3, 5\]} is converted to '5'.
 * ex) '{{evens\[first\]}}' with context {evens: \[2, 4\], first: 0} is converted to '2'.
 * ex) '{{project\["name"\]}}' and '{{project.name}}' with context {project: {name: 'CodeSnippet'}} is converted to 'CodeSnippet'.
 * <br>
 * If replaced expression is a function, next expressions will be arguments of the function.
 * ex) '{{add 1 2}}' with context {add: function(a, b) {return a + b;}} is converted to '3'.
 * <br>
 * It has 3 predefined block helpers '{{helper ...}} ... {{/helper}}': 'if', 'each', 'with ... as ...'.
 * 1) 'if' evaluates conditional statements. It can use with 'elseif' and 'else'.
 * 2) 'each' iterates an array or object. It provides '@index'(array), '@key'(object), and '@this'(current element).
 * 3) 'with ... as ...' provides an alias.
 * @param {string} text - text with expressions
 * @param {object} context - context
 * @returns {string} - text that bind with its context
 * @memberof module:domUtil
 * @example
 * var template = require('tui-code-snippet/domUtil/template');
 * 
 * var source = 
 *     '<h1>'
 *   +   '{{if isValidNumber title}}'
 *   +     '{{title}}th'
 *   +   '{{elseif isValidDate title}}'
 *   +     'Date: {{title}}'
 *   +   '{{/if}}'
 *   + '</h1>'
 *   + '{{each list}}'
 *   +   '{{with addOne @index as idx}}'
 *   +     '<p>{{idx}}: {{@this}}</p>'
 *   +   '{{/with}}'
 *   + '{{/each}}';
 * 
 * var context = {
 *   isValidDate: function(text) {
 *     return /^\d{4}-(0|1)\d-(0|1|2|3)\d$/.test(text);
 *   },
 *   isValidNumber: function(text) {
 *     return /^\d+$/.test(text);
 *   }
 *   title: '2019-11-25',
 *   list: ['Clean the room', 'Wash the dishes'],
 *   addOne: function(num) {
 *     return num + 1;
 *   }
 * };
 * 
 * var result = template(source, context);
 * console.log(result); // <h1>Date: 2019-11-25</h1><p>1: Clean the room</p><p>2: Wash the dishes</p>
 */
function template(text, context) {
  return compile(splitByRegExp(text, EXPRESSION_REGEXP), context);
}

module.exports = template;


/***/ }),
/* 8 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";
/**
 * @fileoverview This module provides some functions for custom events. And it is implemented in the observer design pattern.
 * @author NHN FE Development Lab <dl_javascript@nhn.com>
 */



var extend = __webpack_require__(1);
var isExisty = __webpack_require__(23);
var isString = __webpack_require__(6);
var isObject = __webpack_require__(25);
var isArray = __webpack_require__(2);
var isFunction = __webpack_require__(26);
var forEach = __webpack_require__(4);

var R_EVENTNAME_SPLIT = /\s+/g;

/**
 * @class
 * @example
 * // node, commonjs
 * var CustomEvents = require('tui-code-snippet/customEvents/customEvents');
 */
function CustomEvents() {
  /**
     * @type {HandlerItem[]}
     */
  this.events = null;

  /**
     * only for checking specific context event was binded
     * @type {object[]}
     */
  this.contexts = null;
}

/**
 * Mixin custom events feature to specific constructor
 * @param {function} func - constructor
 * @example
 * var CustomEvents = require('tui-code-snippet/customEvents/customEvents'); // node, commonjs
 *
 * var model;
 * function Model() {
 *     this.name = '';
 * }
 * CustomEvents.mixin(Model);
 *
 * model = new Model();
 * model.on('change', function() { this.name = 'model'; }, this);
 * model.fire('change');
 * alert(model.name); // 'model';
 */
CustomEvents.mixin = function(func) {
  extend(func.prototype, CustomEvents.prototype);
};

/**
 * Get HandlerItem object
 * @param {function} handler - handler function
 * @param {object} [context] - context for handler
 * @returns {HandlerItem} HandlerItem object
 * @private
 */
CustomEvents.prototype._getHandlerItem = function(handler, context) {
  var item = {handler: handler};

  if (context) {
    item.context = context;
  }

  return item;
};

/**
 * Get event object safely
 * @param {string} [eventName] - create sub event map if not exist.
 * @returns {(object|array)} event object. if you supplied `eventName`
 *  parameter then make new array and return it
 * @private
 */
CustomEvents.prototype._safeEvent = function(eventName) {
  var events = this.events;
  var byName;

  if (!events) {
    events = this.events = {};
  }

  if (eventName) {
    byName = events[eventName];

    if (!byName) {
      byName = [];
      events[eventName] = byName;
    }

    events = byName;
  }

  return events;
};

/**
 * Get context array safely
 * @returns {array} context array
 * @private
 */
CustomEvents.prototype._safeContext = function() {
  var context = this.contexts;

  if (!context) {
    context = this.contexts = [];
  }

  return context;
};

/**
 * Get index of context
 * @param {object} ctx - context that used for bind custom event
 * @returns {number} index of context
 * @private
 */
CustomEvents.prototype._indexOfContext = function(ctx) {
  var context = this._safeContext();
  var index = 0;

  while (context[index]) {
    if (ctx === context[index][0]) {
      return index;
    }

    index += 1;
  }

  return -1;
};

/**
 * Memorize supplied context for recognize supplied object is context or
 *  name: handler pair object when off()
 * @param {object} ctx - context object to memorize
 * @private
 */
CustomEvents.prototype._memorizeContext = function(ctx) {
  var context, index;

  if (!isExisty(ctx)) {
    return;
  }

  context = this._safeContext();
  index = this._indexOfContext(ctx);

  if (index > -1) {
    context[index][1] += 1;
  } else {
    context.push([ctx, 1]);
  }
};

/**
 * Forget supplied context object
 * @param {object} ctx - context object to forget
 * @private
 */
CustomEvents.prototype._forgetContext = function(ctx) {
  var context, contextIndex;

  if (!isExisty(ctx)) {
    return;
  }

  context = this._safeContext();
  contextIndex = this._indexOfContext(ctx);

  if (contextIndex > -1) {
    context[contextIndex][1] -= 1;

    if (context[contextIndex][1] <= 0) {
      context.splice(contextIndex, 1);
    }
  }
};

/**
 * Bind event handler
 * @param {(string|{name:string, handler:function})} eventName - custom
 *  event name or an object {eventName: handler}
 * @param {(function|object)} [handler] - handler function or context
 * @param {object} [context] - context for binding
 * @private
 */
CustomEvents.prototype._bindEvent = function(eventName, handler, context) {
  var events = this._safeEvent(eventName);
  this._memorizeContext(context);
  events.push(this._getHandlerItem(handler, context));
};

/**
 * Bind event handlers
 * @param {(string|{name:string, handler:function})} eventName - custom
 *  event name or an object {eventName: handler}
 * @param {(function|object)} [handler] - handler function or context
 * @param {object} [context] - context for binding
 * //-- #1. Get Module --//
 * var CustomEvents = require('tui-code-snippet/customEvents/customEvents'); // node, commonjs
 *
 * //-- #2. Use method --//
 * // # 2.1 Basic Usage
 * CustomEvents.on('onload', handler);
 *
 * // # 2.2 With context
 * CustomEvents.on('onload', handler, myObj);
 *
 * // # 2.3 Bind by object that name, handler pairs
 * CustomEvents.on({
 *     'play': handler,
 *     'pause': handler2
 * });
 *
 * // # 2.4 Bind by object that name, handler pairs with context object
 * CustomEvents.on({
 *     'play': handler
 * }, myObj);
 */
CustomEvents.prototype.on = function(eventName, handler, context) {
  var self = this;

  if (isString(eventName)) {
    // [syntax 1, 2]
    eventName = eventName.split(R_EVENTNAME_SPLIT);
    forEach(eventName, function(name) {
      self._bindEvent(name, handler, context);
    });
  } else if (isObject(eventName)) {
    // [syntax 3, 4]
    context = handler;
    forEach(eventName, function(func, name) {
      self.on(name, func, context);
    });
  }
};

/**
 * Bind one-shot event handlers
 * @param {(string|{name:string,handler:function})} eventName - custom
 *  event name or an object {eventName: handler}
 * @param {function|object} [handler] - handler function or context
 * @param {object} [context] - context for binding
 */
CustomEvents.prototype.once = function(eventName, handler, context) {
  var self = this;

  if (isObject(eventName)) {
    context = handler;
    forEach(eventName, function(func, name) {
      self.once(name, func, context);
    });

    return;
  }

  function onceHandler() { // eslint-disable-line require-jsdoc
    handler.apply(context, arguments);
    self.off(eventName, onceHandler, context);
  }

  this.on(eventName, onceHandler, context);
};

/**
 * Splice supplied array by callback result
 * @param {array} arr - array to splice
 * @param {function} predicate - function return boolean
 * @private
 */
CustomEvents.prototype._spliceMatches = function(arr, predicate) {
  var i = 0;
  var len;

  if (!isArray(arr)) {
    return;
  }

  for (len = arr.length; i < len; i += 1) {
    if (predicate(arr[i]) === true) {
      arr.splice(i, 1);
      len -= 1;
      i -= 1;
    }
  }
};

/**
 * Get matcher for unbind specific handler events
 * @param {function} handler - handler function
 * @returns {function} handler matcher
 * @private
 */
CustomEvents.prototype._matchHandler = function(handler) {
  var self = this;

  return function(item) {
    var needRemove = handler === item.handler;

    if (needRemove) {
      self._forgetContext(item.context);
    }

    return needRemove;
  };
};

/**
 * Get matcher for unbind specific context events
 * @param {object} context - context
 * @returns {function} object matcher
 * @private
 */
CustomEvents.prototype._matchContext = function(context) {
  var self = this;

  return function(item) {
    var needRemove = context === item.context;

    if (needRemove) {
      self._forgetContext(item.context);
    }

    return needRemove;
  };
};

/**
 * Get matcher for unbind specific hander, context pair events
 * @param {function} handler - handler function
 * @param {object} context - context
 * @returns {function} handler, context matcher
 * @private
 */
CustomEvents.prototype._matchHandlerAndContext = function(handler, context) {
  var self = this;

  return function(item) {
    var matchHandler = (handler === item.handler);
    var matchContext = (context === item.context);
    var needRemove = (matchHandler && matchContext);

    if (needRemove) {
      self._forgetContext(item.context);
    }

    return needRemove;
  };
};

/**
 * Unbind event by event name
 * @param {string} eventName - custom event name to unbind
 * @param {function} [handler] - handler function
 * @private
 */
CustomEvents.prototype._offByEventName = function(eventName, handler) {
  var self = this;
  var andByHandler = isFunction(handler);
  var matchHandler = self._matchHandler(handler);

  eventName = eventName.split(R_EVENTNAME_SPLIT);

  forEach(eventName, function(name) {
    var handlerItems = self._safeEvent(name);

    if (andByHandler) {
      self._spliceMatches(handlerItems, matchHandler);
    } else {
      forEach(handlerItems, function(item) {
        self._forgetContext(item.context);
      });

      self.events[name] = [];
    }
  });
};

/**
 * Unbind event by handler function
 * @param {function} handler - handler function
 * @private
 */
CustomEvents.prototype._offByHandler = function(handler) {
  var self = this;
  var matchHandler = this._matchHandler(handler);

  forEach(this._safeEvent(), function(handlerItems) {
    self._spliceMatches(handlerItems, matchHandler);
  });
};

/**
 * Unbind event by object(name: handler pair object or context object)
 * @param {object} obj - context or {name: handler} pair object
 * @param {function} handler - handler function
 * @private
 */
CustomEvents.prototype._offByObject = function(obj, handler) {
  var self = this;
  var matchFunc;

  if (this._indexOfContext(obj) < 0) {
    forEach(obj, function(func, name) {
      self.off(name, func);
    });
  } else if (isString(handler)) {
    matchFunc = this._matchContext(obj);

    self._spliceMatches(this._safeEvent(handler), matchFunc);
  } else if (isFunction(handler)) {
    matchFunc = this._matchHandlerAndContext(handler, obj);

    forEach(this._safeEvent(), function(handlerItems) {
      self._spliceMatches(handlerItems, matchFunc);
    });
  } else {
    matchFunc = this._matchContext(obj);

    forEach(this._safeEvent(), function(handlerItems) {
      self._spliceMatches(handlerItems, matchFunc);
    });
  }
};

/**
 * Unbind custom events
 * @param {(string|object|function)} eventName - event name or context or
 *  {name: handler} pair object or handler function
 * @param {(function)} handler - handler function
 * @example
 * //-- #1. Get Module --//
 * var CustomEvents = require('tui-code-snippet/customEvents/customEvents'); // node, commonjs
 *
 * //-- #2. Use method --//
 * // # 2.1 off by event name
 * CustomEvents.off('onload');
 *
 * // # 2.2 off by event name and handler
 * CustomEvents.off('play', handler);
 *
 * // # 2.3 off by handler
 * CustomEvents.off(handler);
 *
 * // # 2.4 off by context
 * CustomEvents.off(myObj);
 *
 * // # 2.5 off by context and handler
 * CustomEvents.off(myObj, handler);
 *
 * // # 2.6 off by context and event name
 * CustomEvents.off(myObj, 'onload');
 *
 * // # 2.7 off by an Object.<string, function> that is {eventName: handler}
 * CustomEvents.off({
 *   'play': handler,
 *   'pause': handler2
 * });
 *
 * // # 2.8 off the all events
 * CustomEvents.off();
 */
CustomEvents.prototype.off = function(eventName, handler) {
  if (isString(eventName)) {
    // [syntax 1, 2]
    this._offByEventName(eventName, handler);
  } else if (!arguments.length) {
    // [syntax 8]
    this.events = {};
    this.contexts = [];
  } else if (isFunction(eventName)) {
    // [syntax 3]
    this._offByHandler(eventName);
  } else if (isObject(eventName)) {
    // [syntax 4, 5, 6]
    this._offByObject(eventName, handler);
  }
};

/**
 * Fire custom event
 * @param {string} eventName - name of custom event
 */
CustomEvents.prototype.fire = function(eventName) {  // eslint-disable-line
  this.invoke.apply(this, arguments);
};

/**
 * Fire a event and returns the result of operation 'boolean AND' with all
 *  listener's results.
 *
 * So, It is different from {@link CustomEvents#fire}.
 *
 * In service code, use this as a before event in component level usually
 *  for notifying that the event is cancelable.
 * @param {string} eventName - Custom event name
 * @param {...*} data - Data for event
 * @returns {boolean} The result of operation 'boolean AND'
 * @example
 * var map = new Map();
 * map.on({
 *     'beforeZoom': function() {
 *         // It should cancel the 'zoom' event by some conditions.
 *         if (that.disabled && this.getState()) {
 *             return false;
 *         }
 *         return true;
 *     }
 * });
 *
 * if (this.invoke('beforeZoom')) {    // check the result of 'beforeZoom'
 *     // if true,
 *     // doSomething
 * }
 */
CustomEvents.prototype.invoke = function(eventName) {
  var events, args, index, item;

  if (!this.hasListener(eventName)) {
    return true;
  }

  events = this._safeEvent(eventName);
  args = Array.prototype.slice.call(arguments, 1);
  index = 0;

  while (events[index]) {
    item = events[index];

    if (item.handler.apply(item.context, args) === false) {
      return false;
    }

    index += 1;
  }

  return true;
};

/**
 * Return whether at least one of the handlers is registered in the given
 *  event name.
 * @param {string} eventName - Custom event name
 * @returns {boolean} Is there at least one handler in event name?
 */
CustomEvents.prototype.hasListener = function(eventName) {
  return this.getListenerLength(eventName) > 0;
};

/**
 * Return a count of events registered.
 * @param {string} eventName - Custom event name
 * @returns {number} number of event
 */
CustomEvents.prototype.getListenerLength = function(eventName) {
  var events = this._safeEvent(eventName);

  return events.length;
};

module.exports = CustomEvents;


/***/ }),
/* 9 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";
/**
 * @fileoverview
 * This module provides a function to make a constructor
 * that can inherit from the other constructors like the CLASS easily.
 * @author NHN FE Development Lab <dl_javascript@nhn.com>
 */



var inherit = __webpack_require__(27);
var extend = __webpack_require__(1);

/**
 * @module defineClass
 */

/**
 * Help a constructor to be defined and to inherit from the other constructors
 * @param {*} [parent] Parent constructor
 * @param {Object} props Members of constructor
 *  @param {Function} props.init Initialization method
 *  @param {Object} [props.static] Static members of constructor
 * @returns {*} Constructor
 * @memberof module:defineClass
 * @example
 * var defineClass = require('tui-code-snippet/defineClass/defineClass'); // node, commonjs
 *
 * //-- #2. Use property --//
 * var Parent = defineClass({
 *     init: function() { // constuructor
 *         this.name = 'made by def';
 *     },
 *     method: function() {
 *         // ...
 *     },
 *     static: {
 *         staticMethod: function() {
 *              // ...
 *         }
 *     }
 * });
 *
 * var Child = defineClass(Parent, {
 *     childMethod: function() {}
 * });
 *
 * Parent.staticMethod();
 *
 * var parentInstance = new Parent();
 * console.log(parentInstance.name); //made by def
 * parentInstance.staticMethod(); // Error
 *
 * var childInstance = new Child();
 * childInstance.method();
 * childInstance.childMethod();
 */
function defineClass(parent, props) {
  var obj;

  if (!props) {
    props = parent;
    parent = null;
  }

  obj = props.init || function() {};

  if (parent) {
    inherit(obj, parent);
  }

  if (props.hasOwnProperty('static')) {
    extend(obj, props['static']);
    delete props['static'];
  }

  extend(obj.prototype, props);

  return obj;
}

module.exports = defineClass;


/***/ }),
/* 10 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";
/**
 * @fileoverview Bind DOM events
 * @author NHN FE Development Lab <dl_javascript@nhn.com>
 */



var isString = __webpack_require__(6);
var forEach = __webpack_require__(4);

var safeEvent = __webpack_require__(17);

/**
 * Bind DOM events.
 * @param {HTMLElement} element - element to bind events
 * @param {(string|object)} types - Space splitted events names or eventName:handler object
 * @param {(function|object)} handler - handler function or context for handler method
 * @param {object} [context] context - context for handler method.
 * @memberof module:domEvent
 * @example
 * var div = document.querySelector('div');
 * 
 * // Bind one event to an element.
 * on(div, 'click', toggle);
 * 
 * // Bind multiple events with a same handler to multiple elements at once.
 * // Use event names splitted by a space.
 * on(div, 'mouseenter mouseleave', changeColor);
 * 
 * // Bind multiple events with different handlers to an element at once.
 * // Use an object which of key is an event name and value is a handler function.
 * on(div, {
 *   keydown: highlight,
 *   keyup: dehighlight
 * });
 * 
 * // Set a context for handler method.
 * var name = 'global';
 * var repository = {name: 'CodeSnippet'};
 * on(div, 'drag', function() {
 *  console.log(this.name);
 * }, repository);
 * // Result when you drag a div: "CodeSnippet"
 */
function on(element, types, handler, context) {
  if (isString(types)) {
    forEach(types.split(/\s+/g), function(type) {
      bindEvent(element, type, handler, context);
    });

    return;
  }

  forEach(types, function(func, type) {
    bindEvent(element, type, func, handler);
  });
}

/**
 * Bind DOM events
 * @param {HTMLElement} element - element to bind events
 * @param {string} type - events name
 * @param {function} handler - handler function or context for handler method
 * @param {object} [context] context - context for handler method.
 * @private
 */
function bindEvent(element, type, handler, context) {
  /**
     * Event handler
     * @param {Event} e - event object
     */
  function eventHandler(e) {
    handler.call(context || element, e || window.event);
  }

  if ('addEventListener' in element) {
    element.addEventListener(type, eventHandler);
  } else if ('attachEvent' in element) {
    element.attachEvent('on' + type, eventHandler);
  }
  memorizeHandler(element, type, handler, eventHandler);
}

/**
 * Memorize DOM event handler for unbinding.
 * @param {HTMLElement} element - element to bind events
 * @param {string} type - events name
 * @param {function} handler - handler function that user passed at on() use
 * @param {function} wrappedHandler - handler function that wrapped by domevent for implementing some features
 * @private
 */
function memorizeHandler(element, type, handler, wrappedHandler) {
  var events = safeEvent(element, type);
  var existInEvents = false;

  forEach(events, function(obj) {
    if (obj.handler === handler) {
      existInEvents = true;

      return false;
    }

    return true;
  });

  if (!existInEvents) {
    events.push({
      handler: handler,
      wrappedHandler: wrappedHandler
    });
  }
}

module.exports = on;


/***/ }),
/* 11 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";
/**
 * @fileoverview Unbind DOM events
 * @author NHN FE Development Lab <dl_javascript@nhn.com>
 */



var isString = __webpack_require__(6);
var forEach = __webpack_require__(4);

var safeEvent = __webpack_require__(17);

/**
 * Unbind DOM events
 * If a handler function is not passed, remove all events of that type.
 * @param {HTMLElement} element - element to unbind events
 * @param {(string|object)} types - Space splitted events names or eventName:handler object
 * @param {function} [handler] - handler function
 * @memberof module:domEvent
 * @example
 * // Following the example of domEvent#on
 * 
 * // Unbind one event from an element.
 * off(div, 'click', toggle);
 * 
 * // Unbind multiple events with a same handler from multiple elements at once.
 * // Use event names splitted by a space.
 * off(element, 'mouseenter mouseleave', changeColor);
 * 
 * // Unbind multiple events with different handlers from an element at once.
 * // Use an object which of key is an event name and value is a handler function.
 * off(div, {
 *   keydown: highlight,
 *   keyup: dehighlight
 * });
 * 
 * // Unbind events without handlers.
 * off(div, 'drag');
 */
function off(element, types, handler) {
  if (isString(types)) {
    forEach(types.split(/\s+/g), function(type) {
      unbindEvent(element, type, handler);
    });

    return;
  }

  forEach(types, function(func, type) {
    unbindEvent(element, type, func);
  });
}

/**
 * Unbind DOM events
 * If a handler function is not passed, remove all events of that type.
 * @param {HTMLElement} element - element to unbind events
 * @param {string} type - events name
 * @param {function} [handler] - handler function
 * @private
 */
function unbindEvent(element, type, handler) {
  var events = safeEvent(element, type);
  var index;

  if (!handler) {
    forEach(events, function(item) {
      removeHandler(element, type, item.wrappedHandler);
    });
    events.splice(0, events.length);
  } else {
    forEach(events, function(item, idx) {
      if (handler === item.handler) {
        removeHandler(element, type, item.wrappedHandler);
        index = idx;

        return false;
      }

      return true;
    });
    events.splice(index, 1);
  }
}

/**
 * Remove an event handler
 * @param {HTMLElement} element - An element to remove an event
 * @param {string} type - event type
 * @param {function} handler - event handler
 * @private
 */
function removeHandler(element, type, handler) {
  if ('removeEventListener' in element) {
    element.removeEventListener(type, handler);
  } else if ('detachEvent' in element) {
    element.detachEvent('on' + type, handler);
  }
}

module.exports = off;


/***/ }),
/* 12 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";
/**
 * @fileoverview Find parent element recursively
 * @author NHN FE Development Lab <dl_javascript@nhn.com>
 */



var matches = __webpack_require__(30);

/**
 * Find parent element recursively
 * @param {HTMLElement} element - base element to start find
 * @param {string} selector - selector string for find
 * @returns {HTMLElement} - element finded or null
 * @memberof module:domUtil
 */
function closest(element, selector) {
  var parent = element.parentNode;

  if (matches(element, selector)) {
    return element;
  }

  while (parent && parent !== document) {
    if (matches(parent, selector)) {
      return parent;
    }

    parent = parent.parentNode;
  }

  return null;
}

module.exports = closest;


/***/ }),
/* 13 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";
/**
 * @fileoverview Remove element from parent node.
 * @author NHN FE Development Lab <dl_javascript@nhn.com>
 */



/**
 * Remove element from parent node.
 * @param {HTMLElement} element - element to remove.
 * @memberof module:domUtil
 */
function removeElement(element) {
  if (element && element.parentNode) {
    element.parentNode.removeChild(element);
  }
}

module.exports = removeElement;


/***/ }),
/* 14 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";
/**
 * @fileoverview Check whether the given variable is a instance of HTMLNode or not.
 * @author NHN FE Development Lab <dl_javascript@nhn.com>
 */



/**
 * Check whether the given variable is a instance of HTMLNode or not.
 * If the given variables is a instance of HTMLNode, return true.
 * @param {*} html - Target for checking
 * @returns {boolean} Is HTMLNode ?
 * @memberof module:type
 */
function isHTMLNode(html) {
  if (typeof HTMLElement === 'object') {
    return (html && (html instanceof HTMLElement || !!html.nodeType));
  }

  return !!(html && html.nodeType);
}

module.exports = isHTMLNode;


/***/ }),
/* 15 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";
/**
 * @fileoverview Utils for Timepicker component
 * @author NHN. FE dev Lab. <dl_javascript@nhn.com>
 */



var inArray = __webpack_require__(0);
var sendHostname = __webpack_require__(35);

var uniqueId = 0;

/**
 * Utils
 * @namespace util
 * @ignore
 */
var utils = {
  /**
   * Returns unique id
   * @returns {number}
   */
  getUniqueId: function() {
    uniqueId += 1;

    return uniqueId;
  },

  /**
   * Convert a value to meet the format
   * @param {number|string} value 
   * @param {string} format - ex) hh, h, mm, m
   * @returns {string}
   */
  formatTime: function(value, format) {
    var PADDING_ZERO_TYPES = ['hh', 'mm'];
    value = String(value);

    return inArray(format, PADDING_ZERO_TYPES) >= 0
      && value.length === 1
      ? '0' + value
      : value;
  },

  /**
   * Get meridiem hour
   * @param {number} hour - Original hour
   * @returns {number} Converted meridiem hour
   */
  getMeridiemHour: function(hour) {
    hour %= 12;

    if (hour === 0) {
      hour = 12;
    }

    return hour;
  },

  /**
   * Returns range arr
   * @param {number} start - Start value
   * @param {number} end - End value
   * @param {number} [step] - Step value
   * @returns {Array}
   */
  getRangeArr: function(start, end, step) {
    var arr = [];
    var i;

    step = step || 1;

    if (start > end) {
      for (i = end; i >= start; i -= step) {
        arr.push(i);
      }
    } else {
      for (i = start; i <= end; i += step) {
        arr.push(i);
      }
    }

    return arr;
  },

  /**
   * Get a target element
   * @param {Event} ev Event object
   * @returns {HTMLElement} An event target element
   */
  getTarget: function(ev) {
    return ev.target || ev.srcElement;
  },

  /**
   * send host name
   * @ignore
   */
  sendHostName: function() {
    sendHostname('time-picker', 'UA-129987462-1');
  }
};

module.exports = utils;


/***/ }),
/* 16 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";
/**
 * @fileoverview Execute the provided callback once for each property of object which actually exist.
 * @author NHN FE Development Lab <dl_javascript@nhn.com>
 */



/**
 * Execute the provided callback once for each property of object which actually exist.
 * If the callback function returns false, the loop will be stopped.
 * Callback function(iteratee) is invoked with three arguments:
 *  1) The value of the property
 *  2) The name of the property
 *  3) The object being traversed
 * @param {Object} obj The object that will be traversed
 * @param {function} iteratee  Callback function
 * @param {Object} [context] Context(this) of callback function
 * @memberof module:collection
 * @example
 * var forEachOwnProperties = require('tui-code-snippet/collection/forEachOwnProperties'); // node, commonjs
 *
 * var sum = 0;
 *
 * forEachOwnProperties({a:1,b:2,c:3}, function(value){
 *     sum += value;
 * });
 * alert(sum); // 6
 */
function forEachOwnProperties(obj, iteratee, context) {
  var key;

  context = context || null;

  for (key in obj) {
    if (obj.hasOwnProperty(key)) {
      if (iteratee.call(context, obj[key], key, obj) === false) {
        break;
      }
    }
  }
}

module.exports = forEachOwnProperties;


/***/ }),
/* 17 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";
/**
 * @fileoverview Get event collection for specific HTML element
 * @author NHN FE Development Lab <dl_javascript@nhn.com>
 */



var EVENT_KEY = '_feEventKey';

/**
 * Get event collection for specific HTML element
 * @param {HTMLElement} element - HTML element
 * @param {string} type - event type
 * @returns {array}
 * @private
 */
function safeEvent(element, type) {
  var events = element[EVENT_KEY];
  var handlers;

  if (!events) {
    events = element[EVENT_KEY] = {};
  }

  handlers = events[type];
  if (!handlers) {
    handlers = events[type] = [];
  }

  return handlers;
}

module.exports = safeEvent;


/***/ }),
/* 18 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";
/**
 * @fileoverview Get HTML element's design classes.
 * @author NHN FE Development Lab <dl_javascript@nhn.com>
 */



var isUndefined = __webpack_require__(5);

/**
 * Get HTML element's design classes.
 * @param {(HTMLElement|SVGElement)} element target element
 * @returns {string} element css class name
 * @memberof module:domUtil
 */
function getClass(element) {
  if (!element || !element.className) {
    return '';
  }

  if (isUndefined(element.className.baseVal)) {
    return element.className;
  }

  return element.className.baseVal;
}

module.exports = getClass;


/***/ }),
/* 19 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";
/**
 * @fileoverview Set className value
 * @author NHN FE Development Lab <dl_javascript@nhn.com>
 */



var isArray = __webpack_require__(2);
var isUndefined = __webpack_require__(5);

/**
 * Set className value
 * @param {(HTMLElement|SVGElement)} element - target element
 * @param {(string|string[])} cssClass - class names
 * @private
 */
function setClassName(element, cssClass) {
  cssClass = isArray(cssClass) ? cssClass.join(' ') : cssClass;

  cssClass = cssClass.replace(/^[\s\uFEFF\xA0]+|[\s\uFEFF\xA0]+$/g, '');

  if (isUndefined(element.className.baseVal)) {
    element.className = cssClass;

    return;
  }

  element.className.baseVal = cssClass;
}

module.exports = setClassName;


/***/ }),
/* 20 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";
/**
 * @fileoverview The entry file of TimePicker components
 * @author NHN. FE Development Lab <dl_javascript@nhn.com>
 */



__webpack_require__(21);

module.exports = __webpack_require__(22);


/***/ }),
/* 21 */
/***/ (function(module, exports, __webpack_require__) {

// extracted by mini-css-extract-plugin

/***/ }),
/* 22 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";
/**
 * @fileoverview TimePicker component
 * @author NHN. FE Development Lab <dl_javascript@nhn.com>
 */



var inArray = __webpack_require__(0);
var forEachArray = __webpack_require__(3);
var CustomEvents = __webpack_require__(8);
var defineClass = __webpack_require__(9);
var extend = __webpack_require__(1);
var on = __webpack_require__(10);
var off = __webpack_require__(11);
var addClass = __webpack_require__(29);
var closest = __webpack_require__(12);
var removeElement = __webpack_require__(13);
var removeClass = __webpack_require__(32);
var isHTMLNode = __webpack_require__(14);
var isNumber = __webpack_require__(33);

var Spinbox = __webpack_require__(34);
var Selectbox = __webpack_require__(38);
var util = __webpack_require__(15);
var localeTexts = __webpack_require__(40);
var tmpl = __webpack_require__(41);
var meridiemTmpl = __webpack_require__(42);

var SELECTOR_HOUR_ELEMENT = '.tui-timepicker-hour';
var SELECTOR_MINUTE_ELEMENT = '.tui-timepicker-minute';
var SELECTOR_MERIDIEM_ELEMENT = '.tui-timepicker-meridiem';
var CLASS_NAME_LEFT_MERIDIEM = 'tui-has-left';
var CLASS_NAME_HIDDEN = 'tui-hidden';
var CLASS_NAME_CHECKED = 'tui-timepicker-meridiem-checked';
var INPUT_TYPE_SPINBOX = 'spinbox';
var INPUT_TYPE_SELECTBOX = 'selectbox';

/**
 * Merge default options
 * @ignore
 * @param {object} options - options
 * @returns {object} Merged options
 */
var mergeDefaultOptions = function(options) {
  return extend(
    {
      language: 'en',
      initialHour: 0,
      initialMinute: 0,
      showMeridiem: true,
      inputType: 'selectbox',
      hourStep: 1,
      minuteStep: 1,
      meridiemPosition: 'right',
      format: 'h:m',
      disabledHours: [],
      usageStatistics: true
    },
    options
  );
};

/**
 * @class
 * @param {string|HTMLElement} container - Container element or selector
 * @param {Object} [options] - Options for initialization
 * @param {number} [options.initialHour = 0] - Initial setting value of hour
 * @param {number} [options.initialMinute = 0] - Initial setting value of minute
 * @param {number} [options.hourStep = 1] - Step value of hour
 * @param {number} [options.minuteStep = 1] - Step value of minute
 * @param {string} [options.inputType = 'selectbox'] - 'selectbox' or 'spinbox'
 * @param {string} [options.format = 'h:m'] - hour, minute format for display
 * @param {boolean} [options.showMeridiem = true] - Show meridiem expression?
 * @param {Array} [options.disabledHours = []] - Registered Hours is disabled.
 * @param {string} [options.meridiemPosition = 'right'] - Set location of the meridiem element.
 *                 If this option set 'left', the meridiem element is created in front of the hour element.
 * @param {string} [options.language = 'en'] Set locale texts
 * @param {Boolean} [options.usageStatistics=true|false] send hostname to google analytics [default value is true]
 * @example
 * var timepicker = new tui.TimePicker('#timepicker-container', {
 *     initialHour: 15,
 *     initialMinute: 13,
 *     inputType: 'selectbox',
 *     showMeridiem: false
 * });
 */
var TimePicker = defineClass(
  /** @lends TimePicker.prototype */ {
    static: {
      /**
       * Locale text data
       * @type {object}
       * @memberof TimePicker
       * @static
       * @example
       * var TimePicker = tui.TimePicker; // or require('tui-time-picker');
       *
       * TimePicker.localeTexts['customKey'] = {
       *     am: 'a.m.',
       *     pm: 'p.m.'
       * };
       *
       * var instance = new tui.TimePicker('#timepicker-container', {
       *     language: 'customKey',
       * });
       */
      localeTexts: localeTexts
    },
    init: function(container, options) {
      options = mergeDefaultOptions(options);

      /**
       * @type {number}
       * @private
       */
      this._id = util.getUniqueId();

      /**
       * @type {HTMLElement}
       * @private
       */
      this._container = isHTMLNode(container)
        ? container
        : document.querySelector(container);

      /**
       * @type {HTMLElement}
       * @private
       */
      this._element = null;

      /**
       * @type {HTMLElement}
       * @private
       */
      this._meridiemElement = null;

      /**
       * @type {HTMLElement}
       * @private
       */
      this._amEl = null;

      /**
       * @type {HTMLElement}
       * @private
       */
      this._pmEl = null;

      /**
       * @type {boolean}
       * @private
       */
      this._showMeridiem = options.showMeridiem;

      /**
       * Meridiem postion
       * @type {'left'|'right'}
       * @private
       */
      this._meridiemPosition = options.meridiemPosition;

      /**
       * @type {Spinbox|Selectbox}
       * @private
       */
      this._hourInput = null;

      /**
       * @type {Spinbox|Selectbox}
       * @private
       */
      this._minuteInput = null;

      /**
       * @type {number}
       * @private
       */
      this._hour = options.initialHour;

      /**
       * @type {number}
       * @private
       */
      this._minute = options.initialMinute;

      /**
       * @type {number}
       * @private
       */
      this._hourStep = options.hourStep;

      /**
       * @type {number}
       * @private
       */
      this._minuteStep = options.minuteStep;

      /**
       * @type {Array}
       * @private
       */
      this._disabledHours = options.disabledHours;

      /**
       * TimePicker inputType
       * @type {'spinbox'|'selectbox'}
       * @private
       */
      this._inputType = options.inputType;

      /**
       * Locale text for meridiem
       * @type {string}
       * @private
       */
      this._localeText = localeTexts[options.language];

      /**
       * Time format for output
       * @type {string}
       * @private
       */
      this._format = this._getValidTimeFormat(options.format);

      this._render();
      this._setEvents();

      if (options.usageStatistics) {
        util.sendHostName();
      }
    },

    /**
     * Set event handlers to selectors, container
     * @private
     */
    _setEvents: function() {
      this._hourInput.on('change', this._onChangeTimeInput, this);
      this._minuteInput.on('change', this._onChangeTimeInput, this);

      if (this._showMeridiem) {
        if (this._inputType === INPUT_TYPE_SELECTBOX) {
          on(
            this._meridiemElement.querySelector('select'),
            'change',
            this._onChangeMeridiem,
            this
          );
        } else if (this._inputType === INPUT_TYPE_SPINBOX) {
          on(this._meridiemElement, 'click', this._onChangeMeridiem, this);
        }
      }
    },

    /**
     * Remove events
     * @private
     */
    _removeEvents: function() {
      this.off();

      this._hourInput.destroy();
      this._minuteInput.destroy();

      if (this._showMeridiem) {
        if (this._inputType === INPUT_TYPE_SELECTBOX) {
          off(
            this._meridiemElement.querySelector('select'),
            'change',
            this._onChangeMeridiem,
            this
          );
        } else if (this._inputType === INPUT_TYPE_SPINBOX) {
          off(this._meridiemElement, 'click', this._onChangeMeridiem, this);
        }
      }
    },

    /**
     * Render element
     * @private
     */
    _render: function() {
      var context = {
        showMeridiem: this._showMeridiem,
        isSpinbox: this._inputType === 'spinbox'
      };

      if (this._showMeridiem) {
        extend(context, {
          meridiemElement: this._makeMeridiemHTML()
        });
      }

      if (this._element) {
        removeElement(this._element);
      }
      this._container.innerHTML = tmpl(context);
      this._element = this._container.firstChild;

      this._renderTimeInputs();

      if (this._showMeridiem) {
        this._setMeridiemElement();
      }
    },

    /**
     * Set meridiem element on timepicker
     * @private
     */
    _setMeridiemElement: function() {
      if (this._meridiemPosition === 'left') {
        addClass(this._element, CLASS_NAME_LEFT_MERIDIEM);
      }
      this._meridiemElement = this._element.querySelector(SELECTOR_MERIDIEM_ELEMENT);
      this._amEl = this._meridiemElement.querySelector('[value="AM"]');
      this._pmEl = this._meridiemElement.querySelector('[value="PM"]');
      this._syncToMeridiemElements();
    },

    /**
     * Make html for meridiem element
     * @returns {HTMLElement} Meridiem element
     * @private
     */
    _makeMeridiemHTML: function() {
      var localeText = this._localeText;

      return meridiemTmpl({
        am: localeText.am,
        pm: localeText.pm,
        radioId: this._id,
        isSpinbox: this._inputType === 'spinbox'
      });
    },

    /**
     * Render time selectors
     * @private
     */
    _renderTimeInputs: function() {
      var hour = this._hour;
      var showMeridiem = this._showMeridiem;
      var hourElement = this._element.querySelector(SELECTOR_HOUR_ELEMENT);
      var minuteElement = this._element.querySelector(SELECTOR_MINUTE_ELEMENT);
      var BoxComponent = this._inputType.toLowerCase() === 'selectbox' ? Selectbox : Spinbox;
      var formatExplode = this._format.split(':');
      var hourItems = this._getHourItems();

      if (showMeridiem) {
        hour = util.getMeridiemHour(hour);
      }

      this._hourInput = new BoxComponent(hourElement, {
        initialValue: hour,
        items: hourItems,
        format: formatExplode[0],
        disabledItems: this._makeDisabledStatItems(hourItems)
      });

      this._minuteInput = new BoxComponent(minuteElement, {
        initialValue: this._minute,
        items: this._getMinuteItems(),
        format: formatExplode[1]
      });
    },

    _makeDisabledStatItems: function(hourItems) {
      var result = [];
      var disabledHours = this._disabledHours.concat();

      if (this._showMeridiem) {
        disabledHours = this._meridiemableTime(disabledHours);
      }

      forEachArray(hourItems, function(hour) {
        result.push(inArray(hour, disabledHours) >= 0);
      });

      return result;
    },

    _meridiemableTime: function(disabledHours) {
      var diffHour = 0;
      var startHour = 0;
      var endHour = 11;
      var result = [];

      if (this._hour >= 12) {
        diffHour = 12;
        startHour = 12;
        endHour = 23;
      }

      forEachArray(disabledHours, function(hour) {
        if (hour >= startHour && hour <= endHour) {
          result.push(hour - diffHour === 0 ? 12 : hour - diffHour);
        }
      });

      return result;
    },

    /**
     * Return formatted format.
     * @param {string} format - format option
     * @returns {string}
     * @private
     */
    _getValidTimeFormat: function(format) {
      if (!format.match(/^[h]{1,2}:[m]{1,2}$/i)) {
        return 'h:m';
      }

      return format.toLowerCase();
    },

    /**
     * Initialize meridiem elements
     * @private
     */
    _syncToMeridiemElements: function() {
      var selectedEl = this._hour >= 12 ? this._pmEl : this._amEl;
      var notSelectedEl = selectedEl === this._pmEl ? this._amEl : this._pmEl;

      selectedEl.setAttribute('selected', true);
      selectedEl.setAttribute('checked', true);
      addClass(selectedEl, CLASS_NAME_CHECKED);
      notSelectedEl.removeAttribute('selected');
      notSelectedEl.removeAttribute('checked');
      removeClass(notSelectedEl, CLASS_NAME_CHECKED);
    },

    /**
     * Set values in spinboxes from time
     * @private
     */
    _syncToInputs: function() {
      var hour = this._hour;
      var minute = this._minute;

      if (this._showMeridiem) {
        hour = util.getMeridiemHour(hour);
      }

      this._hourInput.setValue(hour);
      this._minuteInput.setValue(minute);
    },

    /**
     * DOM event handler
     * @param {Event} ev - Change event on meridiem element
     * @private
     */
    _onChangeMeridiem: function(ev) {
      var hour = this._hour;
      var target = util.getTarget(ev);

      if (target.value && closest(target, SELECTOR_MERIDIEM_ELEMENT)) {
        hour = this._to24Hour(target.value === 'PM', hour);
        this.setTime(hour, this._minute);
        this._setDisabledHours();
      }
    },

    /**
     * Time change event handler
     * @private
     */
    _onChangeTimeInput: function() {
      var hour = this._hourInput.getValue();
      var minute = this._minuteInput.getValue();
      var isPM = this._hour >= 12;

      if (this._showMeridiem) {
        hour = this._to24Hour(isPM, hour);
      }
      this.setTime(hour, minute);
    },

    /**
     * 12Hour-expression to 24Hour-expression
     * @param {boolean} isPM - Is pm?
     * @param {number} hour - Hour
     * @returns {number}
     * @private
     */
    _to24Hour: function(isPM, hour) {
      hour %= 12;
      if (isPM) {
        hour += 12;
      }

      return hour;
    },

    _setDisabledHours: function() {
      var hourItems = this._getHourItems();
      var disabledItems = this._makeDisabledStatItems(hourItems);

      this._hourInput.setDisabledItems(disabledItems);
    },

    /**
     * Get items of hour
     * @returns {array} Hour item list
     * @private
     */
    _getHourItems: function() {
      var step = this._hourStep;

      return this._showMeridiem ? util.getRangeArr(1, 12, step) : util.getRangeArr(0, 23, step);
    },

    /**
     * Get items of minute
     * @returns {array} Minute item list
     * @private
     */
    _getMinuteItems: function() {
      return util.getRangeArr(0, 59, this._minuteStep);
    },

    /**
     * Whether the hour and minute are in valid items or not
     * @param {number} hour - Hour value
     * @param {number} minute - Minute value
     * @returns {boolean} State
     * @private
     */
    _validItems: function(hour, minute) {
      if (!isNumber(hour) || !isNumber(minute)) {
        return false;
      }

      if (this._showMeridiem) {
        hour = util.getMeridiemHour(hour);
      }

      return (
        inArray(hour, this._getHourItems()) > -1 &&
        inArray(minute, this._getMinuteItems()) > -1
      );
    },

    /**
     * Set step of hour
     * @param {array} step - Step to create items of hour
     */
    setHourStep: function(step) {
      this._hourStep = step;
      this._hourInput.fire('changeItems', this._getHourItems());
    },

    /**
     * Get step of hour
     * @returns {number} Step of hour
     */
    getHourStep: function() {
      return this._hourStep;
    },

    /**
     * Set step of minute
     * @param {array} step - Step to create items of minute
     */
    setMinuteStep: function(step) {
      this._minuteStep = step;
      this._minuteInput.fire('changeItems', this._getMinuteItems());
    },

    /**
     * Get step of minute
     * @returns {number} Step of minute
     */
    getMinuteStep: function() {
      return this._minuteStep;
    },

    /**
     * Show time picker element
     */
    show: function() {
      removeClass(this._element, CLASS_NAME_HIDDEN);
    },

    /**
     * Hide time picker element
     */
    hide: function() {
      addClass(this._element, CLASS_NAME_HIDDEN);
    },

    /**
     * Set hour
     * @param {number} hour for time picker - (0~23)
     * @returns {boolean} result of set time
     */
    setHour: function(hour) {
      return this.setTime(hour, this._minute);
    },

    /**
     * Set minute
     * @param {number} minute for time picker
     * @returns {boolean} result of set time
     */
    setMinute: function(minute) {
      return this.setTime(this._hour, minute);
    },

    /**
     * Set time
     * @param {number} hour for time picker - (0~23)
     * @param {number} minute for time picker
     */
    setTime: function(hour, minute) {
      if (!this._validItems(hour, minute)) {
        return;
      }

      this._hour = hour;
      this._minute = minute;

      this._syncToInputs();
      if (this._showMeridiem) {
        this._syncToMeridiemElements();
      }

      /**
       * Change event - TimePicker
       * @event TimePicker#change
       */
      this.fire('change', {
        hour: this._hour,
        minute: this._minute
      });
    },

    /**
     * Get hour
     * @returns {number} hour - (0~23)
     */
    getHour: function() {
      return this._hour;
    },

    /**
     * Get minute
     * @returns {number} minute
     */
    getMinute: function() {
      return this._minute;
    },

    /**
     * Change locale text of meridiem by language code
     * @param {string} language - Language code
     */
    changeLanguage: function(language) {
      this._localeText = localeTexts[language];
      this._render();
    },

    /**
     * Destroy
     */
    destroy: function() {
      this._removeEvents();
      removeElement(this._element);

      this._container
        = this._showMeridiem
        = this._hourInput
        = this._minuteInput
        = this._hour
        = this._minute
        = this._inputType
        = this._element
        = this._meridiemElement
        = this._amEl
        = this._pmEl
        = null;
    }
  }
);

CustomEvents.mixin(TimePicker);
module.exports = TimePicker;


/***/ }),
/* 23 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";
/**
 * @fileoverview Check whether the given variable is existing or not.
 * @author NHN FE Development Lab <dl_javascript@nhn.com>
 */



var isUndefined = __webpack_require__(5);
var isNull = __webpack_require__(24);

/**
 * Check whether the given variable is existing or not.
 * If the given variable is not null and not undefined, returns true.
 * @param {*} param - Target for checking
 * @returns {boolean} Is existy?
 * @memberof module:type
 * @example
 * var isExisty = require('tui-code-snippet/type/isExisty'); // node, commonjs
 *
 * isExisty(''); //true
 * isExisty(0); //true
 * isExisty([]); //true
 * isExisty({}); //true
 * isExisty(null); //false
 * isExisty(undefined); //false
*/
function isExisty(param) {
  return !isUndefined(param) && !isNull(param);
}

module.exports = isExisty;


/***/ }),
/* 24 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";
/**
 * @fileoverview Check whether the given variable is null or not.
 * @author NHN FE Development Lab <dl_javascript@nhn.com>
 */



/**
 * Check whether the given variable is null or not.
 * If the given variable(arguments[0]) is null, returns true.
 * @param {*} obj - Target for checking
 * @returns {boolean} Is null?
 * @memberof module:type
 */
function isNull(obj) {
  return obj === null;
}

module.exports = isNull;


/***/ }),
/* 25 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";
/**
 * @fileoverview Check whether the given variable is an object or not.
 * @author NHN FE Development Lab <dl_javascript@nhn.com>
 */



/**
 * Check whether the given variable is an object or not.
 * If the given variable is an object, return true.
 * @param {*} obj - Target for checking
 * @returns {boolean} Is object?
 * @memberof module:type
 */
function isObject(obj) {
  return obj === Object(obj);
}

module.exports = isObject;


/***/ }),
/* 26 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";
/**
 * @fileoverview Check whether the given variable is a function or not.
 * @author NHN FE Development Lab <dl_javascript@nhn.com>
 */



/**
 * Check whether the given variable is a function or not.
 * If the given variable is a function, return true.
 * @param {*} obj - Target for checking
 * @returns {boolean} Is function?
 * @memberof module:type
 */
function isFunction(obj) {
  return obj instanceof Function;
}

module.exports = isFunction;


/***/ }),
/* 27 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";
/**
 * @fileoverview Provide a simple inheritance in prototype-oriented.
 * @author NHN FE Development Lab <dl_javascript@nhn.com>
 */



var createObject = __webpack_require__(28);

/**
 * Provide a simple inheritance in prototype-oriented.
 * Caution :
 *  Don't overwrite the prototype of child constructor.
 *
 * @param {function} subType Child constructor
 * @param {function} superType Parent constructor
 * @memberof module:inheritance
 * @example
 * var inherit = require('tui-code-snippet/inheritance/inherit'); // node, commonjs
 *
 * // Parent constructor
 * function Animal(leg) {
 *     this.leg = leg;
 * }
 * Animal.prototype.growl = function() {
 *     // ...
 * };
 *
 * // Child constructor
 * function Person(name) {
 *     this.name = name;
 * }
 *
 * // Inheritance
 * inherit(Person, Animal);
 *
 * // After this inheritance, please use only the extending of property.
 * // Do not overwrite prototype.
 * Person.prototype.walk = function(direction) {
 *     // ...
 * };
 */
function inherit(subType, superType) {
  var prototype = createObject(superType.prototype);
  prototype.constructor = subType;
  subType.prototype = prototype;
}

module.exports = inherit;


/***/ }),
/* 28 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";
/**
 * @fileoverview Create a new object with the specified prototype object and properties.
 * @author NHN FE Development Lab <dl_javascript@nhn.com>
 */



/**
 * @module inheritance
 */

/**
 * Create a new object with the specified prototype object and properties.
 * @param {Object} obj This object will be a prototype of the newly-created object.
 * @returns {Object}
 * @memberof module:inheritance
 */
function createObject(obj) {
  function F() {} // eslint-disable-line require-jsdoc
  F.prototype = obj;

  return new F();
}

module.exports = createObject;


/***/ }),
/* 29 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";
/**
 * @fileoverview Add css class to element
 * @author NHN FE Development Lab <dl_javascript@nhn.com>
 */



var forEach = __webpack_require__(4);
var inArray = __webpack_require__(0);
var getClass = __webpack_require__(18);
var setClassName = __webpack_require__(19);

/**
 * domUtil module
 * @module domUtil
 */

/**
 * Add css class to element
 * @param {(HTMLElement|SVGElement)} element - target element
 * @param {...string} cssClass - css classes to add
 * @memberof module:domUtil
 */
function addClass(element) {
  var cssClass = Array.prototype.slice.call(arguments, 1);
  var classList = element.classList;
  var newClass = [];
  var origin;

  if (classList) {
    forEach(cssClass, function(name) {
      element.classList.add(name);
    });

    return;
  }

  origin = getClass(element);

  if (origin) {
    cssClass = [].concat(origin.split(/\s+/), cssClass);
  }

  forEach(cssClass, function(cls) {
    if (inArray(cls, newClass) < 0) {
      newClass.push(cls);
    }
  });

  setClassName(element, newClass);
}

module.exports = addClass;


/***/ }),
/* 30 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";
/**
 * @fileoverview Check element match selector
 * @author NHN FE Development Lab <dl_javascript@nhn.com>
 */



var inArray = __webpack_require__(0);
var toArray = __webpack_require__(31);

var elProto = Element.prototype;
var matchSelector = elProto.matches ||
    elProto.webkitMatchesSelector ||
    elProto.mozMatchesSelector ||
    elProto.msMatchesSelector ||
    function(selector) {
      var doc = this.document || this.ownerDocument;

      return inArray(this, toArray(doc.querySelectorAll(selector))) > -1;
    };

/**
 * Check element match selector
 * @param {HTMLElement} element - element to check
 * @param {string} selector - selector to check
 * @returns {boolean} is selector matched to element?
 * @memberof module:domUtil
 */
function matches(element, selector) {
  return matchSelector.call(element, selector);
}

module.exports = matches;


/***/ }),
/* 31 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";
/**
 * @fileoverview Transform the Array-like object to Array.
 * @author NHN FE Development Lab <dl_javascript@nhn.com>
 */



var forEachArray = __webpack_require__(3);

/**
 * Transform the Array-like object to Array.
 * In low IE (below 8), Array.prototype.slice.call is not perfect. So, try-catch statement is used.
 * @param {*} arrayLike Array-like object
 * @returns {Array} Array
 * @memberof module:collection
 * @example
 * var toArray = require('tui-code-snippet/collection/toArray'); // node, commonjs
 *
 * var arrayLike = {
 *     0: 'one',
 *     1: 'two',
 *     2: 'three',
 *     3: 'four',
 *     length: 4
 * };
 * var result = toArray(arrayLike);
 *
 * alert(result instanceof Array); // true
 * alert(result); // one,two,three,four
 */
function toArray(arrayLike) {
  var arr;
  try {
    arr = Array.prototype.slice.call(arrayLike);
  } catch (e) {
    arr = [];
    forEachArray(arrayLike, function(value) {
      arr.push(value);
    });
  }

  return arr;
}

module.exports = toArray;


/***/ }),
/* 32 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";
/**
 * @fileoverview Remove css class from element
 * @author NHN FE Development Lab <dl_javascript@nhn.com>
 */



var forEachArray = __webpack_require__(3);
var inArray = __webpack_require__(0);
var getClass = __webpack_require__(18);
var setClassName = __webpack_require__(19);

/**
 * Remove css class from element
 * @param {(HTMLElement|SVGElement)} element - target element
 * @param {...string} cssClass - css classes to remove
 * @memberof module:domUtil
 */
function removeClass(element) {
  var cssClass = Array.prototype.slice.call(arguments, 1);
  var classList = element.classList;
  var origin, newClass;

  if (classList) {
    forEachArray(cssClass, function(name) {
      classList.remove(name);
    });

    return;
  }

  origin = getClass(element).split(/\s+/);
  newClass = [];
  forEachArray(origin, function(name) {
    if (inArray(name, cssClass) < 0) {
      newClass.push(name);
    }
  });

  setClassName(element, newClass);
}

module.exports = removeClass;


/***/ }),
/* 33 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";
/**
 * @fileoverview Check whether the given variable is a number or not.
 * @author NHN FE Development Lab <dl_javascript@nhn.com>
 */



/**
 * Check whether the given variable is a number or not.
 * If the given variable is a number, return true.
 * @param {*} obj - Target for checking
 * @returns {boolean} Is number?
 * @memberof module:type
 */
function isNumber(obj) {
  return typeof obj === 'number' || obj instanceof Number;
}

module.exports = isNumber;


/***/ }),
/* 34 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";
/**
 * @fileoverview Spinbox (in TimePicker)
 * @author NHN. FE Development Lab <dl_javascript@nhn.com>
 */



var inArray = __webpack_require__(0);
var forEachArray = __webpack_require__(3);
var CustomEvents = __webpack_require__(8);
var defineClass = __webpack_require__(9);
var extend = __webpack_require__(1);
var on = __webpack_require__(10);
var off = __webpack_require__(11);
var closest = __webpack_require__(12);
var removeElement = __webpack_require__(13);
var isHTMLNode = __webpack_require__(14);

var util = __webpack_require__(15);
var tmpl = __webpack_require__(37);

var SELECTOR_UP_BUTTON = '.tui-timepicker-btn-up';
var SELECTOR_DOWN_BUTTON = '.tui-timepicker-btn-down';

/**
 * @class
 * @ignore
 * @param {String|HTMLElement} container - Container of spinbox or selector
 * @param {Object} [options] - Options for initialization
 * @param {number} [options.initialValue] - initial setting value
 * @param {Array.<number>} items - Items
 */
var Spinbox = defineClass(
  /** @lends Spinbox.prototype */ {
    init: function(container, options) {
      options = extend(
        {
          items: []
        },
        options
      );

      /**
       * @type {HTMLElement}
       * @private
       */
      this._container = isHTMLNode(container)
        ? container
        : document.querySelector(container);

      /**
       * Spinbox element
       * @type {HTMLElement}
       * @private
       */
      this._element = null;

      /**
       * @type {HTMLElement}
       * @private
       */
      this._inputElement = null;

      /**
       * Spinbox value items
       * @type {Array.<number>}
       * @private
       */
      this._items = options.items;

      /**
       * Selectbox disabled items info
       * @type {Array.<number>}
       * @private
       */
      this._disabledItems = options.disabledItems || [];

      /**
       * @type {number}
       * @private
       */
      this._selectedIndex = Math.max(0, inArray(options.initialValue, this._items));

      /**
       * Time format for output
       * @type {string}
       * @private
       */
      this._format = options.format;

      this._render();
      this._setEvents();
    },

    /**
     * Render spinbox
     * @private
     */
    _render: function() {
      var index = inArray(this.getValue(), this._items);
      var context;

      if (this._disabledItems[index]) {
        this._selectedIndex = this._findEnabledIndex();
      }
      context = {
        maxLength: this._getMaxLength(),
        initialValue: this.getValue(),
        format: this._format,
        formatTime: util.formatTime
      };

      this._container.innerHTML = tmpl(context);
      this._element = this._container.firstChild;
      this._inputElement = this._element.querySelector('input');
    },

    /**
     * Find the index of the enabled item
     * @returns {number} - find selected index
     * @private
     */
    _findEnabledIndex: function() {
      return inArray(false, this._disabledItems);
    },

    /**
     * Returns maxlength of value
     * @returns {number}
     * @private
     */
    _getMaxLength: function() {
      var lengths = [];

      forEachArray(this._items, function(item) {
        lengths.push(String(item).length);
      });

      return Math.max.apply(null, lengths);
    },

    /**
     * Set disabledItems
     * @param {object} disabledItems - disabled status of items
     */
    setDisabledItems: function(disabledItems) {
      this._disabledItems = disabledItems;
      this._changeToInputValue();
    },

    /**
     * Assign default events to up/down button
     * @private
     */
    _setEvents: function() {
      on(this._container, 'click', this._onClickHandler, this);
      on(this._inputElement, 'keydown', this._onKeydownInputElement, this);
      on(this._inputElement, 'change', this._onChangeHandler, this);

      this.on(
        'changeItems',
        function(items) {
          this._items = items;
          this._render();
        },
        this
      );
    },

    /**
     * Remove events to up/down button
     * @private
     */
    _removeEvents: function() {
      this.off();

      off(this._container, 'click', this._onClickHandler, this);
      off(this._inputElement, 'keydown', this._onKeydownInputElement, this);
      off(this._inputElement, 'change', this._onChangeHandler, this);
    },

    /**
     * Click event handler
     * @param {Event} ev - Change event on up/down buttons.
     */
    _onClickHandler: function(ev) {
      var target = util.getTarget(ev);

      if (closest(target, SELECTOR_DOWN_BUTTON)) {
        this._setNextValue(true);
      } else if (closest(target, SELECTOR_UP_BUTTON)) {
        this._setNextValue(false);
      }
    },

    /**
     * Set input value
     * @param {boolean} isDown - From down-action?
     * @private
     */
    _setNextValue: function(isDown) {
      var index = this._selectedIndex;

      if (isDown) {
        index = index ? index - 1 : this._items.length - 1;
      } else {
        index = index < this._items.length - 1 ? index + 1 : 0;
      }

      if (this._disabledItems[index]) {
        this._selectedIndex = index;
        this._setNextValue(isDown);
      } else {
        this.setValue(this._items[index]);
      }
    },

    /**
     * DOM(Input element) Keydown Event handler
     * @param {Event} ev event-object
     * @private
     */
    _onKeydownInputElement: function(ev) {
      var keyCode = ev.which || ev.keyCode;
      var isDown;

      if (closest(util.getTarget(ev), 'input')) {
        switch (keyCode) {
          case 38:
            isDown = false;
            break;
          case 40:
            isDown = true;
            break;
          default:
            return;
        }

        this._setNextValue(isDown);
      }
    },

    /**
     * DOM(Input element) Change Event handler
     * @param {Event} ev Change event on an input element.
     * @private
     */
    _onChangeHandler: function(ev) {
      if (closest(util.getTarget(ev), 'input')) {
        this._changeToInputValue();
      }
    },

    /**
     * Change value to input-box if it is valid.
     * @private
     */
    _changeToInputValue: function() {
      var newValue = Number(this._inputElement.value);
      var newIndex = inArray(newValue, this._items);

      if (this._disabledItems[newIndex]) {
        newIndex = this._findEnabledIndex();
        newValue = this._items[newIndex];
      } else if (newIndex === this._selectedIndex) {
        return;
      }

      if (newIndex === -1) {
        this.setValue(this._items[this._selectedIndex]);
      } else {
        this._selectedIndex = newIndex;
        this.fire('change', {
          value: newValue
        });
      }
    },

    /**
     * Set value to input-box.
     * @param {number} value - Value
     */
    setValue: function(value) {
      this._inputElement.value = util.formatTime(value, this._format);
      this._changeToInputValue();
    },

    /**
     * Returns current value
     * @returns {number}
     */
    getValue: function() {
      return this._items[this._selectedIndex];
    },

    /**
     * Destory
     */
    destroy: function() {
      this._removeEvents();
      removeElement(this._element);
      this._container = this._element = this._inputElement = this._items = this._selectedIndex = null;
    }
  }
);

CustomEvents.mixin(Spinbox);
module.exports = Spinbox;


/***/ }),
/* 35 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";
/**
 * @fileoverview Send hostname on DOMContentLoaded.
 * @author NHN FE Development Lab <dl_javascript@nhn.com>
 */



var isUndefined = __webpack_require__(5);
var imagePing = __webpack_require__(36);

var ms7days = 7 * 24 * 60 * 60 * 1000;

/**
 * Check if the date has passed 7 days
 * @param {number} date - milliseconds
 * @returns {boolean}
 * @private
 */
function isExpired(date) {
  var now = new Date().getTime();

  return now - date > ms7days;
}

/**
 * Send hostname on DOMContentLoaded.
 * To prevent hostname set tui.usageStatistics to false.
 * @param {string} appName - application name
 * @param {string} trackingId - GA tracking ID
 * @ignore
 */
function sendHostname(appName, trackingId) {
  var url = 'https://www.google-analytics.com/collect';
  var hostname = location.hostname;
  var hitType = 'event';
  var eventCategory = 'use';
  var applicationKeyForStorage = 'TOAST UI ' + appName + ' for ' + hostname + ': Statistics';
  var date = window.localStorage.getItem(applicationKeyForStorage);

  // skip if the flag is defined and is set to false explicitly
  if (!isUndefined(window.tui) && window.tui.usageStatistics === false) {
    return;
  }

  // skip if not pass seven days old
  if (date && !isExpired(date)) {
    return;
  }

  window.localStorage.setItem(applicationKeyForStorage, new Date().getTime());

  setTimeout(function() {
    if (document.readyState === 'interactive' || document.readyState === 'complete') {
      imagePing(url, {
        v: 1,
        t: hitType,
        tid: trackingId,
        cid: hostname,
        dp: hostname,
        dh: appName,
        el: appName,
        ec: eventCategory
      });
    }
  }, 1000);
}

module.exports = sendHostname;


/***/ }),
/* 36 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";
/**
 * @fileoverview Request image ping.
 * @author NHN FE Development Lab <dl_javascript@nhn.com>
 */



var forEachOwnProperties = __webpack_require__(16);

/**
 * @module request
 */

/**
 * Request image ping.
 * @param {String} url url for ping request
 * @param {Object} trackingInfo infos for make query string
 * @returns {HTMLElement}
 * @memberof module:request
 * @example
 * var imagePing = require('tui-code-snippet/request/imagePing'); // node, commonjs
 *
 * imagePing('https://www.google-analytics.com/collect', {
 *     v: 1,
 *     t: 'event',
 *     tid: 'trackingid',
 *     cid: 'cid',
 *     dp: 'dp',
 *     dh: 'dh'
 * });
 */
function imagePing(url, trackingInfo) {
  var trackingElement = document.createElement('img');
  var queryString = '';
  forEachOwnProperties(trackingInfo, function(value, key) {
    queryString += '&' + key + '=' + value;
  });
  queryString = queryString.substring(1);

  trackingElement.src = url + '?' + queryString;

  trackingElement.style.display = 'none';
  document.body.appendChild(trackingElement);
  document.body.removeChild(trackingElement);

  return trackingElement;
}

module.exports = imagePing;


/***/ }),
/* 37 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var template = __webpack_require__(7);

module.exports = function(context) {
  var source =
      '<div class="tui-timepicker-btn-area">'
    + '  <input type="text" class="tui-timepicker-spinbox-input"'
    + '        maxlength="{{maxLength}}"'
    + '        size="{{maxLength}}"'
    + '        value="{{formatTime initialValue format}}"'
    + '        aria-label="TimePicker spinbox value">'
    + '  <button type="button" class="tui-timepicker-btn tui-timepicker-btn-up">'
    + '    <span class="tui-ico-t-btn">Increase</span>'
    + '  </button>'
    + '  <button type="button" class="tui-timepicker-btn tui-timepicker-btn-down">'
    + '    <span class="tui-ico-t-btn">Decrease</span>'
    + '  </button>'
    + '</div>';

  return template(source, context);
};



/***/ }),
/* 38 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";
/**
 * @fileoverview Selectbox (in TimePicker)
 * @author NHN. FE Development Lab <dl_javascript@nhn.com>
 */



var inArray = __webpack_require__(0);
var CustomEvents = __webpack_require__(8);
var defineClass = __webpack_require__(9);
var extend = __webpack_require__(1);
var on = __webpack_require__(10);
var off = __webpack_require__(11);
var closest = __webpack_require__(12);
var removeElement = __webpack_require__(13);
var isHTMLNode = __webpack_require__(14);

var util = __webpack_require__(15);
var tmpl = __webpack_require__(39);

/**
 * @class
 * @ignore
 * @param {string|HTMLElement} container - Container element or selector
 * @param {object} options - Options
 * @param {Array.<number>} options.items - Items
 * @param {number} options.initialValue - Initial value
 */
var Selectbox = defineClass(
  /** @lends Selectbox.prototype */ {
    init: function(container, options) {
      options = extend(
        {
          items: []
        },
        options
      );

      /**
       * Container element
       * @type {HTMLElement}
       * @private
       */
      this._container = isHTMLNode(container)
        ? container
        : document.querySelector(container);

      /**
       * Selectbox items
       * @type {Array.<number>}
       * @private
       */
      this._items = options.items || [];

      /**
       * Selectbox disabled items info
       * @type {Array.<number>}
       * @private
       */
      this._disabledItems = options.disabledItems || [];

      /**
       * Selected index
       * @type {number}
       * @private
       */
      this._selectedIndex = Math.max(0, inArray(options.initialValue, this._items));

      /**
       * Time format for output
       * @type {string}
       * @private
       */
      this._format = options.format;

      /**
       * Select element
       * @type {HTMLElement}
       * @private
       */
      this._element = null;

      this._render();
      this._setEvents();
    },

    /**
     * Render selectbox
     * @private
     */
    _render: function() {
      var context;

      this._changeEnabledIndex();
      context = {
        items: this._items,
        format: this._format,
        initialValue: this.getValue(),
        disabledItems: this._disabledItems,
        formatTime: util.formatTime,
        equals: function(a, b) {
          return a === b;
        }
      };

      if (this._element) {
        this._removeElement();
      }

      this._container.innerHTML = tmpl(context);
      this._element = this._container.firstChild;
      on(this._element, 'change', this._onChangeHandler, this);
    },

    /**
     * Change the index of the enabled item
     * @private
     */
    _changeEnabledIndex: function() {
      var index = inArray(this.getValue(), this._items);
      if (this._disabledItems[index]) {
        this._selectedIndex = inArray(false, this._disabledItems);
      }
    },

    /**
     * Set disabledItems
     * @param {object} disabledItems - disabled status of items
     * @private
     */
    setDisabledItems: function(disabledItems) {
      this._disabledItems = disabledItems;
      this._render();
    },

    /**
     * Set events
     * @private
     */
    _setEvents: function() {
      this.on(
        'changeItems',
        function(items) {
          this._items = items;
          this._render();
        },
        this
      );
    },

    /**
     * Remove events
     * @private
     */
    _removeEvents: function() {
      this.off();
    },

    /**
     * Remove element
     * @private
     */
    _removeElement: function() {
      off(this._element, 'change', this._onChangeHandler, this);
      removeElement(this._element);
    },

    /**
     * Change event handler
     * @param {Event} ev Change event on a select element.
     * @private
     */
    _onChangeHandler: function(ev) {
      if (closest(util.getTarget(ev), 'select')) {
        this._setNewValue();
      }
    },

    /**
     * Set new value
     * @private
     */
    _setNewValue: function() {
      var newValue = Number(this._element.value);
      this._selectedIndex = inArray(newValue, this._items);
      this.fire('change', {
        value: newValue
      });
    },

    /**
     * Returns current value
     * @returns {number}
     */
    getValue: function() {
      return this._items[this._selectedIndex];
    },

    /**
     * Set value
     * @param {number} value - New value
     */
    setValue: function(value) {
      var newIndex = inArray(value, this._items);

      if (newIndex > -1 && newIndex !== this._selectedIndex) {
        this._selectedIndex = newIndex;
        this._element.value = value;
        this._setNewValue();
      }
    },

    /**
     * Destory
     */
    destroy: function() {
      this._removeEvents();
      this._removeElement();
      this._container = this._items = this._selectedIndex = this._element = null;
    }
  }
);

CustomEvents.mixin(Selectbox);
module.exports = Selectbox;


/***/ }),
/* 39 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var template = __webpack_require__(7);

module.exports = function(context) {
  var source =
      '<select class="tui-timepicker-select" aria-label="Time">'
    + '  {{each items}}'
    + '    {{if equals initialValue @this}}'
    + '      <option value="{{@this}}" selected {{if disabledItems[@index]}}disabled{{/if}}>{{formatTime @this format}}</option>'
    + '    {{else}}'
    + '      <option value="{{@this}}" {{if disabledItems[@index]}}disabled{{/if}}>{{formatTime @this format}}</option>'
    + '    {{/if}}'
    + '  {{/each}}'
    + '</select>';

  return template(source, context);
};



/***/ }),
/* 40 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";
/**
 * @fileoverview Default locale texts
 * @author NHN. FE Development Lab <dl_javascript@nhn.com>
 */



module.exports = {
  en: {
    am: 'AM',
    pm: 'PM'
  },
  ko: {
    am: '오전',
    pm: '오후'
  }
};


/***/ }),
/* 41 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var template = __webpack_require__(7);

module.exports = function(context) {
  var source =
      '<div class="tui-timepicker">'
    + '  <div class="tui-timepicker-body">'
    + '    <div class="tui-timepicker-row">'
    + '      {{if isSpinbox}}'
    + '        <div class="tui-timepicker-column tui-timepicker-spinbox tui-timepicker-hour"></div>'
    + '        <span class="tui-timepicker-column tui-timepicker-colon"><span class="tui-ico-colon">:</span></span>'
    + '        <div class="tui-timepicker-column tui-timepicker-spinbox tui-timepicker-minute"></div>'
    + '        {{if showMeridiem}}'
    + '          {{meridiemElement}}'
    + '        {{/if}}'
    + '      {{else}}'
    + '        <div class="tui-timepicker-column tui-timepicker-selectbox tui-timepicker-hour"></div>'
    + '        <span class="tui-timepicker-column tui-timepicker-colon"><span class="tui-ico-colon">:</span></span>'
    + '        <div class="tui-timepicker-column tui-timepicker-selectbox tui-timepicker-minute"></div>'
    + '        {{if showMeridiem}}'
    + '          {{meridiemElement}}'
    + '        {{/if}}'
    + '      {{/if}}'
    + '    </div>'
    + '  </div>'
    + '</div>';

  return template(source, context);
};



/***/ }),
/* 42 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var template = __webpack_require__(7);

module.exports = function(context) {
  var source =
      '{{if isSpinbox}}'
    + '  <div class="tui-timepicker-column tui-timepicker-checkbox tui-timepicker-meridiem">'
    + '    <div class="tui-timepicker-check-area">'
    + '      <ul class="tui-timepicker-check-lst">'
    + '        <li class="tui-timepicker-check">'
    + '          <div class="tui-timepicker-radio">'
    + '            <input type="radio"'
    + '                  name="optionsRadios-{{radioId}}"'
    + '                  value="AM"'
    + '                  class="tui-timepicker-radio-am"'
    + '                  id="tui-timepicker-radio-am-{{radioId}}">'
    + '            <label for="tui-timepicker-radio-am-{{radioId}}" class="tui-timepicker-radio-label">'
    + '              <span class="tui-timepicker-input-radio"></span>{{am}}'
    + '            </label>'
    + '          </div>'
    + '        </li>'
    + '        <li class="tui-timepicker-check">'
    + '          <div class="tui-timepicker-radio">'
    + '            <input type="radio"'
    + '                  name="optionsRadios-{{radioId}}"'
    + '                  value="PM"'
    + '                  class="tui-timepicker-radio-pm"'
    + '                  id="tui-timepicker-radio-pm-{{radioId}}">'
    + '            <label for="tui-timepicker-radio-pm-{{radioId}}" class="tui-timepicker-radio-label">'
    + '              <span class="tui-timepicker-input-radio"></span>{{pm}}'
    + '            </label>'
    + '          </div>'
    + '        </li>'
    + '      </ul>'
    + '    </div>'
    + '  </div>'
    + '{{else}}'
    + '  <div class="tui-timepicker-column tui-timepicker-selectbox tui-is-add-picker tui-timepicker-meridiem">'
    + '    <select class="tui-timepicker-select" aria-label="AM/PM">'
    + '      <option value="AM">{{am}}</option>'
    + '      <option value="PM">{{pm}}</option>'
    + '    </select>'
    + '  </div>'
    + '{{/if}}';

  return template(source, context);
};



/***/ })
/******/ ]);
});

/***/ }),

/***/ "./resources/js/custom-pages.js":
/*!**************************************!*\
  !*** ./resources/js/custom-pages.js ***!
  \**************************************/
/*! no static exports found */
/***/ (function(module, exports) {

$(document).ready(function () {
  //Form submit
  $("#response-form").submit(function (event) {
    //If form is empty
    if (!$('#contentMD').val()) {
      //Error
      Toastify({
        text: "Please write your response",
        duration: 5000,
        close: true,
        gravity: "bottom",
        // `top` or `bottom`
        position: 'right',
        // `left`, `center` or `right`
        backgroundColor: '#ff4444',
        offset: {
          x: 100,
          // horizontal axis - can be a number or a string indicating unity. eg: '2em'
          y: 50 // vertical axis - can be a number or a string indicating unity. eg: '2em'

        },
        stopOnFocus: true // Prevents dismissing of toast on hover

      }).showToast();
      event.preventDefault();
      return;
    } //Make ajax request


    $.ajax({
      type: 'POST',
      url: window.location.href + '/response-submit',
      data: {
        content: $('#contentMD').val()
      },
      dataType: 'json',
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      success: function success(data) {
        console.log(data); //Show saved toast

        Toastify({
          text: "Response submitted!",
          duration: 5000,
          close: true,
          gravity: "bottom",
          // `top` or `bottom`
          position: 'right',
          // `left`, `center` or `right`
          backgroundColor: '#00C851',
          offset: {
            x: 100,
            // horizontal axis - can be a number or a string indicating unity. eg: '2em'
            y: 50 // vertical axis - can be a number or a string indicating unity. eg: '2em'

          },
          stopOnFocus: true // Prevents dismissing of toast on hover

        }).showToast();
        location.reload();
      },
      error: function error(data) {
        console.log('Error');
        console.log(data); //Error

        Toastify({
          text: "Error (".concat(data.responseJSON.message, ")"),
          duration: 5000,
          close: true,
          gravity: "bottom",
          // `top` or `bottom`
          position: 'right',
          // `left`, `center` or `right`
          backgroundColor: '#ff4444',
          offset: {
            x: 100,
            // horizontal axis - can be a number or a string indicating unity. eg: '2em'
            y: 50 // vertical axis - can be a number or a string indicating unity. eg: '2em'

          },
          stopOnFocus: true // Prevents dismissing of toast on hover

        }).showToast();
      }
    });
    event.preventDefault();
  });
});

/***/ }),

/***/ "./resources/js/instructing.js":
/*!*************************************!*\
  !*** ./resources/js/instructing.js ***!
  \*************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

var Calendar = __webpack_require__(/*! tui-calendar */ "./node_modules/tui-calendar/dist/tui-calendar.js");
/* CommonJS */


__webpack_require__(/*! tui-calendar/dist/tui-calendar.css */ "./node_modules/tui-calendar/dist/tui-calendar.css");

__webpack_require__(/*! tui-date-picker/dist/tui-date-picker.css */ "./node_modules/tui-date-picker/dist/tui-date-picker.css");

__webpack_require__(/*! tui-time-picker/dist/tui-time-picker.css */ "./node_modules/tui-time-picker/dist/tui-time-picker.css");

var monthNames = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];

createCalendar = function createCalendar() {
  var calendar = new Calendar('#instructing-sessions-calendar', {
    defaultView: 'month',
    taskView: false,
    isReadOnly: true,
    usageStatistics: false,
    timezones: [{
      timezoneOffset: 0,
      displayLabel: 'UTC',
      tooltip: 'Zulu'
    }]
  });
  $("#instructing-sessions-calendar-range").text(monthNames[calendar.getDate().toDate().getMonth()] + " " + calendar.getDate().toDate().getFullYear());
  calendar.createSchedules([{
    id: '1',
    calendarId: '1',
    title: 'my schedule',
    category: 'time',
    dueDateClass: '',
    start: '2018-01-18T22:30:00+09:00',
    end: '2018-01-19T02:30:00+09:00'
  }, {
    id: '2',
    calendarId: '1',
    title: 'second schedule',
    category: 'time',
    dueDateClass: '',
    start: '2018-01-18T17:30:00+09:00',
    end: '2018-01-19T17:31:00+09:00',
    isReadOnly: true // schedule is read-only

  }]);
  $("#instructing-sessions-calendar-prev-button").click(function () {
    calendar.prev();
    $("#instructing-sessions-calendar-range").text(monthNames[calendar.getDate().toDate().getMonth()] + " " + calendar.getDate().toDate().getFullYear());
  });
  $("#instructing-sessions-calendar-next-button").click(function () {
    calendar.next();
    $("#instructing-sessions-calendar-range").text(monthNames[calendar.getDate().toDate().getMonth()] + " " + calendar.getDate().toDate().getFullYear());
  });
};

/***/ }),

/***/ "./resources/js/maps.js":
/*!******************************!*\
  !*** ./resources/js/maps.js ***!
  \******************************/
/*! no exports provided */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/regenerator */ "./node_modules/@babel/runtime/regenerator/index.js");
/* harmony import */ var _babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0__);


function asyncGeneratorStep(gen, resolve, reject, _next, _throw, key, arg) { try { var info = gen[key](arg); var value = info.value; } catch (error) { reject(error); return; } if (info.done) { resolve(value); } else { Promise.resolve(value).then(_next, _throw); } }

function _asyncToGenerator(fn) { return function () { var self = this, args = arguments; return new Promise(function (resolve, reject) { var gen = fn.apply(self, args); function _next(value) { asyncGeneratorStep(gen, resolve, reject, _next, _throw, "next", value); } function _throw(err) { asyncGeneratorStep(gen, resolve, reject, _next, _throw, "throw", err); } _next(undefined); }); }; }

function createMapPointsBoundaries(map) {
  var icon = L.icon({
    iconUrl: '/img/oep.png',
    iconAnchor: [5, 5]
  });
  L.marker([54, -23], {
    opacity: 0
  }).bindTooltip('Shanwick OCA', {
    permanent: true,
    direction: 'center',
    opacity: 0.5
  }).addTo(map);
  L.marker([54, -45], {
    opacity: 0
  }).bindTooltip('Gander OCA', {
    permanent: true,
    direction: 'center',
    opacity: 0.5
  }).addTo(map);
  L.latlngGraticule({
    showLabel: true,
    dashArray: [5, 5],
    zoomInterval: [{
      start: 0,
      end: 10,
      interval: 5
    }]
  }).addTo(map); // Gander OEP's

  var pointsGander = [['AVPUT', ['65.03333333333333', '-60']], ['CLAVY', ['64.23333333333333', '-59']], ['EMBOK', ['63.46666666666667', '-58']], ['KETLA', ['62.46666666666667', '-58']], ['LIBOR', ['61.96666666666667', '-58']], ['MAXAR', ['61.46666666666667', '-58']], ['NIFTY', ['60.96666666666667', '-58']], ['PIDSO', ['60.46666666666667', '-58']], ['RADUN', ['59.96666666666667', '-58']], ['SAVRY', ['59.46666666666667', '-58']], ['TOXIT', ['58.96666666666667', '-58']], ['URTAK', ['58.46666666666667', '-58']], ['VESMI', ['57.96666666666667', '-58']], ['AVUTI', ['57.46666666666667', '-58']], ['BOKTO', ['56.96666666666667', '-58']], ['CUDDY', ['56.7', '-57']], ['DORYY', ['56.03333333333333', '-57']], ['ENNSO', ['55.53333333333333', '-57']], ['HOIST', ['55.03333333333333', '-57']], ['IRLOK', ['54.53333333333333', '-57']], ['JANJO', ['54.03333333333333', '-57']], ['KODIK', ['53.46666666666667', '-57.2']], ['LOMSI', ['53.1', '-56.78333333333333']], ['MELDI', ['52.733333333333334', '-56.35']], ['NEEKO', ['52.4', '-55.833333333333336']], ['PELTU', ['52.1', '-55.166666666666664']], ['RIKAL', ['51.8', '-54.53333333333333']], ['SAXAN', ['51.483333333333334', '-53.85']], ['TUDEP', ['51.166666666666664', '-53.233333333333334']], ['UMESI', ['50.833333333333336', '-52.6']], ['ALLRY', ['50.5', '-52']], ['BUDAR', ['50', '-52']], ['ELSIR', ['49.5', '-52']], ['IBERG', ['49', '-52']], ['JOOPY', ['48.5', '-52']], ['MUSAK', ['48', '-52']], ['NICSO', ['47.5', '-52']], ['OMSAT', ['47', '-52']], ['PORTI', ['46.5', '-52']], ['RELIC', ['46', '-52']], ['SUPRY', ['45.5', '-52']], ['RAFIN', ['44.88333333333333', '-51.80472222222222']], ['JAROM', ['44.166666666666664', '-54.88333333333333']], ['BOBTU', ['44.117222222222225', '-52.82222222222222']]];
  pointsGander.forEach(function (point) {
    L.marker([parseFloat(point[1][0]), parseFloat(point[1][1])], {
      icon: icon,
      opacity: 0.3
    }).addTo(map).bindPopup(point[0]);
  }); // Shanwick OEP's

  var pointsShanwick = [['RATSU', ['61', '-10']], ['LUSEN', ['60.5', '-10']], ['ATSIX', ['60', '-10']], ['ORTAV', ['59.5', '-10']], ['BALIX', ['59', '-10']], ['ADODO', ['58.5', '-10']], ['ERAKA', ['58', '-10']], ['ETILO', ['57.5', '-10']], ['GOMUP', ['57', '-10']], ['AGORI', ['57', '-13']], ['SUNOT', ['57', '-15']], ['BILTO', ['56.5', '-15']], ['PIKIL', ['56', '-15']], ['ETARI', ['55.5', '-15']], ['RESNO', ['55', '-15']], ['VENER', ['54.5', '-15']], ['DOGAL', ['54', '-15']], ['NEBIN', ['53.5', '-15']], ['MALOT', ['53', '-15']], ['TOBOR', ['52.5', '-15']], ['LIMRI', ['52', '-15']], ['ADARA', ['51.5', '-15']], ['DINIM', ['51', '-15']], ['RODEL', ['50.5', '-15']], ['SOMAX', ['50', '-15']], ['KOGAD', ['49.5', '-15']], ['BEDRA', ['49', '-15']], ['NERTU', ['49', '-14']], ['NASBA', ['49', '-13']], ['OMOKO', ['48.83888888888889', '-12']], ['TAMEL', ['48.728611111111114', '-10.497222222222222']], ['GELPO', ['48.64416666666666', '-9.5025']], ['LASNO', ['48.598333333333336', '-9']], ['ETIKI', ['48', '-8.75']], ['UMLER', ['47.5', '-8.75']], ['SEPAL', ['47', '-8.75']], ['BUNAV', ['46.5', '-8.75']], ['SIVIR', ['46', '-8.75']], ['BEGAS', ['45', '-9']], ['DIVAT', ['45', '-9.469722222222222']], ['DIXIS', ['45', '-10']], ['BERUX', ['45', '-11']], ['PITAX', ['45', '-12']], ['PASAS', ['45', '-13']], ['NILAV', ['45', '-13.416666666666666']], ['GONAN', ['45', '-14']]];
  pointsShanwick.forEach(function (point) {
    L.marker([parseFloat(point[1][0]), parseFloat(point[1][1])], {
      icon: icon,
      opacity: 0.3
    }).addTo(map).bindPopup(point[0], {
      permanent: true
    });
  }); // Coordinate grid

  L.latlngGraticule({
    showLabel: true,
    dashArray: [5, 5],
    zoomInterval: [{
      start: 0,
      end: 10,
      interval: 5
    }]
  }).addTo(map); // OCA's, FIR's and delegated areas

  var Gander = [['45', '-51'], ['45', '-50'], ['44', '-50'], ['44', '-40'], ['45', '-40'], ['45', '-30'], ['61', '-30'], ['63.5', '-39'], ['58.5', '-43'], ['58.5', '-50'], ['65', '-57.75'], ['65', '-60'], ['64', '-63'], ['61', '-63'], ['58.471111111111114', '-60.35111111111111'], ['57', '-59'], ['53', '-54'], ['49', '-51'], ['45', '-51']];
  L.polyline(Gander, {
    color: '#777',
    weight: 0.5
  }).addTo(map);
  var Shanwick = [['45', '-30'], ['45', '-8'], ['51', '-8'], ['51', '-15'], ['54', '-15'], ['54.56666666666667', '-10'], ['61', '-10'], ['61', '-30'], ['45', '-30']];
  L.polyline(Shanwick, {
    color: '#777',
    weight: 0.5
  }).addTo(map);
  var NOTA = [['54', '-15'], ['54.56666666666667', '-10'], ['57', '-10'], ['57', '-15'], ['54', '-15']];
  L.polyline(NOTA, {
    color: '#777',
    weight: 0.5
  }).addTo(map);
  var SOTA = [['49', '-15'], ['48.5769444444', '-8.75'], ['48.5769444444', '-8'], ['51', '-8'], ['51', '-15'], ['49', '-15']];
  L.polyline(SOTA, {
    color: '#777',
    weight: 0.5
  }).addTo(map);
  var BOTA = [['45', '-8.75'], ['45', '-8'], ['48.5769444444', '-8'], ['48.5769444444', '-8.75'], ['45', '-8.75']];
  L.polyline(BOTA, {
    color: '#777',
    weight: 0.5
  }).addTo(map);
  var GOTA = [['53.8', '-55'], ['62.85', '-55'], ['65', '-57.75'], ['65', '-60'], ['64', '-63'], ['61', '-63'], ['57', '-59'], ['53.8', '-55']];
  L.polyline(GOTA, {
    color: '#777',
    weight: 0.5
  }).addTo(map);
  var Nuuk = [['58.5', '-50'], ['58.5', '-43'], ['63.5', '-39'], ['63.5', '-55.80928'], ['58.5', '-50']];
  L.polyline(Nuuk, {
    color: '#777',
    weight: 0.5
  }).addTo(map);
  var GanderDomestic = [['45', '-51'], ['45', '-53'], ['44.446666666666665', '-56.05166666666666'], ['45.61194444444445', '-56.47361111111111'], ['48.5', '-62'], ['49.3', '-61'], ['49.53333333333333', '-61'], ['51', '-58'], ['51.28333333333333', '-57'], ['51.735', '-57'], ['52.19638888888888', '-58.14277777777778'], ['51.63333333333333', '-59.5'], ['51.333333333333336', '-59.5'], ['50.833333333333336', '-60'], ['50.833333333333336', '-62.083333333333336'], ['51.416666666666664', '-64'], ['53.7', '-64.91666666666667'], ['54.416666666666664', '-65.33333333333333'], ['55.083333333333336', '-65.08333333333333'], ['55.355555555555554', '-64'], ['57.55', '-64'], ['58.471111111111114', '-60.35111111111111'], ['57', '-59'], ['53', '-54'], ['49', '-51'], ['45', '-51']];
  L.polyline(GanderDomestic, {
    color: '#777',
    weight: 0.5
  }).addTo(map);
  var GanderDomesticDelegated = [['53.0833333333', '-54.0833333333'], ['49', '-51'], ['45', '-51'], ['45', '-53'], ['44.446666666666665', '-56.05166666666666'], ['43.446666666666665', '-56.05166666666666'], ['44', '-50'], ['50', '-50'], ['53.0833333333', '-54.0833333333']];
  L.polyline(GanderDomesticDelegated, {
    color: '#777',
    weight: 0.5
  }).addTo(map);
  var Moncton = [['44.446666666666665', '-56.05166666666666'], ['43.6', '-60'], ['41.86666666666667', '-67'], ['44.5', '-67'], ['44.5', '-67.11666666666666'], ['44.776666666666664', '-66.9025'], ['47.2875', '-68.57666666666667'], ['47.525277777777774', '-68'], ['47.733333333333334', '-67.95'], ['47.88333333333333', '-66.89666666666668'], ['48', '-65.94111111111111'], ['47.848333333333336', '-64.62222222222222'], ['48.5', '-62'], ['45.61194444444445', '-56.47361111111111'], ['44.446666666666665', '-56.05166666666666']];
  L.polyline(Moncton, {
    color: '#777',
    weight: 0.5
  }).addTo(map);
  var Montreal = [['47.459833333333336', '-69.22444444444444'], ['44.22141666666667', '-76.19172222222223'], ['45.837500000000006', '-76.26666666666667'], ['45.961111111111116', '-76.92777777777778'], ['46.13333333333333', '-77.25'], ['46.94688055555555', '-77.25'], ['47.11110277777778', '-77.54586388888889'], ['47.55425833333333', '-78.11756944444444'], ['47.84006388888889', '-78.56570555555555'], ['48.587047222222225', '-79'], ['49', '-79'], ['53.46666666666667', '-80'], ['62.75', '-80'], ['65', '-68'], ['65', '-60'], ['64', '-63'], ['61', '-63'], ['58.471111111111114', '-60.35111111111111'], ['57.55', '-64'], ['55.355555555555554', '-64'], ['55.083333333333336', '-65.08333333333333'], ['54.416666666666664', '-65.33333333333333'], ['53.7', '-64.91666666666667'], ['51.416666666666664', '-64'], ['50.833333333333336', '-62.083333333333336'], ['50.833333333333336', '-60'], ['51.333333333333336', '-59.5'], ['51.63333333333333', '-59.5'], ['52.19638888888888', '-58.14277777777778'], ['51.735', '-57'], ['51.28333333333333', '-57'], ['51', '-58'], ['49.53333333333333', '-61'], ['49.3', '-61'], ['48.5', '-62'], ['47.848333333333336', '-64.62222222222222'], ['48', '-65.94111111111111'], ['47.88333333333333', '-66.89666666666668']];
  L.polyline(Montreal, {
    color: '#777',
    weight: 0.5
  }).addTo(map);
  var Edmonton = [['65', '-57.75'], ['73', '-69.92'], ['73', '-80'], ['64.40833333333335', '-80'], ['62.75', '-80'], ['65', '-68'], ['65', '-60']];
  L.polyline(Edmonton, {
    color: '#777',
    weight: 0.5
  }).addTo(map);
  var Reykjavik = [['63.5', '-55.80928'], ['63.5', '-39'], ['61', '-30'], ['61', '0'], ['73', '0'], ['73', '-69.92'], ['63.5', '-55.80928']];
  L.polyline(Reykjavik, {
    color: '#777',
    weight: 0.5
  }).addTo(map);
  var Scottish = [['61', '0'], ['60', '0'], ['57', '5'], ['55', '5'], ['55', '-5.5'], ['53.916666666666664', '-5.5'], ['54.416666666666664', '-8.166666666666666'], ['55.333333333333336', '-6.916666666666667'], ['55.416666666666664', '-7.333333333333333'], ['55.333333333333336', '-8.25'], ['54.75', '-9'], ['54.56666666666667', '-10'], ['61', '-10'], ['61', '0']];
  L.polyline(Scottish, {
    color: '#777',
    weight: 0.5
  }).addTo(map);
  var London = [['55', '5'], ['51.5', '2'], ['51.11666666666667', '2'], ['51', '1.4666666666666668'], ['50.666666666666664', '1.4666666666666668'], ['50', '-0.25'], ['50', '-2'], ['48.833333333333336', '-8'], ['51', '-8'], ['52.333333333333336', '-5.5'], ['55', '-5.5'], ['55', '5']];
  L.polyline(London, {
    color: '#777',
    weight: 0.5
  }).addTo(map);
  var Brest = [['50', '-0.25'], ['46.5', '-0.25'], ['46.5', '-1.6333333333333333'], ['43.583333333333336', '-1.7833333333333332'], ['44.333333333333336', '-4'], ['45', '-8'], ['48.833333333333336', '-8'], ['50', '-2'], ['50', '-0.25']];
  L.polyline(Brest, {
    color: '#777',
    weight: 0.5
  }).addTo(map);
  var Madrid = [['45', '-13'], ['45', '-8'], ['44.333333333333336', '-4'], ['43.583333333333336', '-1.7833333333333332'], ['43.38333333333333', '-1.7833333333333332'], ['42.7', '-0.06666666666666667'], ['39.733333333333334', '-1.1'], ['35.833333333333336', '-2.1'], ['35.833333333333336', '-7.383333333333334'], ['35.96666666666667', '-7.383333333333334'], ['42', '-10'], ['43', '-13'], ['45', '-13']];
  L.polyline(Madrid, {
    color: '#777',
    weight: 0.5
  }).addTo(map);
  var Lisbon = [['43', '-13'], ['42', '-10'], ['35.96666666666667', '-7.383333333333334'], ['35.96666666666667', '-12'], ['32.25', '-14.633333333333333'], ['33.92500000', '-18.06916667'], ['36.5', '-15'], ['42', '-15'], ['43', '-13']];
  L.polyline(Lisbon, {
    color: '#777',
    weight: 0.5
  }).addTo(map);
  var SantaMaria = [['45', '-40'], ['45', '-13'], ['43', '-13'], ['42', '-15'], ['36.5', '-15'], ['33.92500000', '-18.06916667'], ['30', '-20'], ['30', '-25'], ['24', '-25'], ['17', '-37.5'], ['22.3', '-40'], ['44', '-40']];
  L.polyline(SantaMaria, {
    color: '#777',
    weight: 0.5
  }).addTo(map);
  var NewYork = [['44', '-40'], ['22.3', '-40'], ['18', '-45'], ['18', '-61.5'], ['38.331222', '-70.059528'], ['39', '-67'], ['42', '-67'], ['43.446666666666665', '-56.05166666666666'], ['44', '-50'], ['44', '-40']];
  L.polyline(NewYork, {
    color: '#777',
    weight: 0.5
  }).addTo(map);
}

function checkIfNatProcessed(ident) {
  if (processedNats.indexOf(ident) > -1) {
    return true;
  } else {
    return false;
  }
}

function createMapTrackPointMarker(point, track, map) {
  //Create marker icon
  var markerIcon = L.icon({
    iconUrl: 'https://ganderoceanicoca.ams3.digitaloceanspaces.com/resources/dot-point-map.png',
    iconSize: [10, 10],
    iconAnchor: [2, 4]
  }); //Create marker object

  var marker = L.marker([point.latitude, point.longitude], {
    icon: markerIcon
  }).addTo(map); //Bind popup

  marker.bindPopup("<b>" + point.name + "</b><br>Track " + track.id);
  marker.on('mouseover', function (e) {
    this.openPopup();
  });
  marker.on('mouseout', function (e) {
    this.closePopup();
  });
} //Create Current NAT Tracks map


function createNatTrackMap() {
  return _createNatTrackMap.apply(this, arguments);
} //Create big map


function _createNatTrackMap() {
  _createNatTrackMap = _asyncToGenerator( /*#__PURE__*/_babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0___default.a.mark(function _callee() {
    var map, table, OpenStreetMap_Mapnik, endpoint, response, data;
    return _babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0___default.a.wrap(function _callee$(_context) {
      while (1) {
        switch (_context.prev = _context.next) {
          case 0:
            //Create map
            map = L.map('map', {
              minZoom: 4,
              maxZoom: 7
            }).setView([52, -35], 1); //Define table

            table = $("#natTrackTable"); //Create OSM Layer

            OpenStreetMap_Mapnik = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
              maxZoom: 19,
              attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map); //Get tracks

            endpoint = "https://tracks.ganderoceanic.com/data";
            _context.next = 6;
            return fetch(endpoint);

          case 6:
            response = _context.sent;
            _context.next = 9;
            return response.json();

          case 9:
            data = _context.sent;
            //Go through each NAT Track
            data.forEach(function (track) {
              //Create array of points latitudes/longitudes
              var pointsLatLon = [];
              track.route.forEach(function (point) {
                //Add point to array
                pointsLatLon.push([point.latitude, point.longitude]); //Create map marker

                createMapTrackPointMarker(point, track, map);
              }); //Get colour for the polyline depending on track direction

              var colour = '#00000';

              if (track.direction == 1) {
                colour = '#1c5fc9';
              } else {
                colour = '#c92d1c';
              } //Create polylines


              var line = new L.Polyline(pointsLatLon, {
                color: colour,
                weight: 2,
                opacity: 1,
                smoothFactor: 1
              }).addTo(map); //Create row

              var row = $("<tr></tr>"); //Add track id

              var idCell = $("<td scope='row'></td>").text(track.id);
              $(row).append(idCell); //Add points

              var pointsText = [];
              track.route.forEach(function (point) {
                pointsText.push(" " + point.name);
              });
              var pointsCell = $("<td></td>").text(pointsText);
              $(row).append(pointsCell); //Add direction

              var directionCell = $("<td></td>");

              if (track.direction == 1) {
                $(directionCell).text('Westbound');
              } else {
                $(directionCell).text('Eastbound');
              }

              $(row).append(directionCell); //Add levels

              var levelsText = [];
              track.flightLevels.forEach(function (level) {
                levelsText.push(" " + level / 100);
              });
              var levelsCell = $("<td></td>").text(levelsText);
              $(row).append(levelsCell); //validity

              var validityCell = $("<td></td>").text("".concat(track.validFrom, " to ").concat(track.validTo));
              $(row).append(validityCell); //Add row to table

              $(table).append(row);
            }); //Add points and boundaries

            createMapPointsBoundaries(map);

          case 12:
          case "end":
            return _context.stop();
        }
      }
    }, _callee);
  }));
  return _createNatTrackMap.apply(this, arguments);
}

function createMap(_x, _x2) {
  return _createMap.apply(this, arguments);
}

function _createMap() {
  _createMap = _asyncToGenerator( /*#__PURE__*/_babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0___default.a.mark(function _callee2(planes, controllerOnline) {
    var map, OpenStreetMap_Mapnik, endpoint, response, data, ganderOca, Shanwick;
    return _babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0___default.a.wrap(function _callee2$(_context2) {
      while (1) {
        switch (_context2.prev = _context2.next) {
          case 0:
            //Create map and layer
            map = L.map('map', {
              minZoom: 4,
              maxZoom: 7
            }).setView([55, -30], 4.5);
            OpenStreetMap_Mapnik = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
              maxZoom: 19,
              attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map); //Create boundaries and points

            createMapPointsBoundaries(map); //Create plane markers and controllers

            planes.forEach(function (plane) {
              var markerIcon = L.icon({
                iconUrl: '/img/planes/base.png',
                iconSize: [30, 30],
                iconAnchor: [2, 4]
              });
              var marker = L.marker([plane.latitude, plane.longitude], {
                rotationAngle: plane.heading,
                icon: markerIcon
              }).addTo(map);
              marker.bindPopup("<h4>".concat(plane.callsign, "</h4><br>").concat(plane.realname, " ").concat(plane.cid, "<br>").concat(plane.planned_depairport, " to ").concat(plane.planned_destairport, "<br>").concat(plane.planned_aircraft));
            }); //Add tracks
            //Get tracks

            endpoint = "https://tracks.ganderoceanic.com/data";
            _context2.next = 7;
            return fetch(endpoint);

          case 7:
            response = _context2.sent;
            _context2.next = 10;
            return response.json();

          case 10:
            data = _context2.sent;
            //Go through each NAT Track
            data.forEach(function (track) {
              //Create array of points latitudes/longitudes
              var pointsLatLon = [];
              track.route.forEach(function (point) {
                //Add point to array
                pointsLatLon.push([point.latitude, point.longitude]); //Create map marker

                createMapTrackPointMarker(point, track, map);
              }); //Get colour for the polyline depending on track direction

              var colour = '#00000';

              if (track.direction == 1) {
                colour = '#1c5fc9';
              } else {
                colour = '#c92d1c';
              } //Create polylines


              var line = new L.Polyline(pointsLatLon, {
                color: colour,
                weight: 2,
                opacity: 1,
                smoothFactor: 1
              }).addTo(map);
            }); //Add Gander/Shanwick bubbles if they're online

            if (!controllerOnline) {
              ganderOca = L.polygon([[45.0, -30], [45.0, -40], [45, -51], [49, -51], [52.39, -53.44], [53, -54], [57, -59], [58.28, -60.21], [64, -63], [65, -60], [65, -57.45], [63.3, -55.5], [58.3, -50], [58.3, -43], [63.3, -39], [61, -30], ['45', '-30'], ['45', '-8'], ['51', '-8'], ['51', '-15'], ['54', '-15'], ['54.56666666666667', '-10'], ['61', '-10'], ['61', '-30'], ['45', '-30']]).addTo(map).bindPopup("Gander/Shanwick OCA online");
              Shanwick = [];
              L.polyline(Shanwick, {
                color: '#777',
                weight: 0.5
              }).addTo(map);
            }

          case 13:
          case "end":
            return _context2.stop();
        }
      }
    }, _callee2);
  }));
  return _createMap.apply(this, arguments);
}

/***/ }),

/***/ "./resources/js/myczqo.js":
/*!********************************!*\
  !*** ./resources/js/myczqo.js ***!
  \********************************/
/*! no static exports found */
/***/ (function(module, exports) {

tabs = ['yourProfileTab', 'supportTab', 'certificationTrainingTab', 'instructingTab', 'staffTab'];
$(document).ready(function () {
  $(document).on('click', '.myczqo-tab', function (element) {
    tab = $(this).data("myczqo-tab");

    if (tab === "none") {
      return;
    } //Hide every other tab


    tabs.forEach(function (element) {
      $("#".concat(element)).hide();
    }); //Show the tab

    $("#" + tab).show(); //Make the current tab inactive

    $(".myczqo-tab.active").removeClass('active'); //make new tab active

    $(".myczqo-tab[data-myczqo-tab=" + tab + ']').addClass('active');
  });
});

/***/ }),

/***/ "./resources/js/pilot-tools.js":
/*!*************************************!*\
  !*** ./resources/js/pilot-tools.js ***!
  \*************************************/
/*! no static exports found */
/***/ (function(module, exports) {

/*
Format of clearance request:
CALLSIGN request clearance via Track LETTER|route ROUTE. Estimating
ENTRY at TIME. Request Flight Level FLIGHTLEVEL, Mach MACHSPEED.
(Result will say 'Readback TMI [TMI] on readback of clearance from controller.)
*/
//Generate result
function generateOceanicClearance() {
  //Get variables from form
  var callsign = document.getElementById('callsignB').value;
  var flightLevel = document.getElementById('flightLevelB').value;
  var mach = document.getElementById('machB').value;
  var nat = document.getElementById('natB').value;
  var route = document.getElementById('routeB').value;
  var entry = document.getElementById('entryB').value;
  var time = document.getElementById('timeB').value;
  var tmi = document.getElementById('tmiB').value; //In case there are errors...

  var errors = []; //Check if fields aren't filled.
  //First, NAT/Route because there might be a reason for it not being filled.

  var routeMode;

  if (document.getElementById('natRoutePanel').style.display == 'block') {
    if (nat == '') {
      errors.push('NAT track not filled.');
    } else {
      routeMode = 0;
    }
  } else {
    if (route == '') {
      errors.push('Random route not filled.');
    } else {
      routeMode = 1;
    }
  } //Callsign, flight level, mach, entry, estimating


  if (callsign == '') {
    errors.push('Callsign not filled');
  }

  if (flightLevel == '') {
    errors.push('Flight level not filled.');
  }

  if (mach == '') {
    errors.push('Mach speed not filled.');
  }

  if (entry == '') {
    errors.push('Entry fix not filled.');
  }

  if (time == '') {
    errors.push('Estimating time not filled.');
  } //There are errors... tell the user to fix 'em!


  if (errors.length >= 1) {
    return invalidSubmission(errors);
  } //No errors? March on!
  //Generate main request transcript.


  var transcript; //Nat routing

  if (routeMode == 0) {
    transcript = callsign + " request clearance via Track " + nat + ". Estimating " + entry + " at " + time + ". Request Flight Level " + flightLevel + ", Mach " + mach + ".";
  } else {
    transcript = callsign + " request clearance via route " + route + ". Estimating " + entry + " at" + time + ". Request Flight Level " + flightLevel + ", Mach " + mach + ".";
  } //Display it!


  document.getElementById('errorA').style.display = 'none';
  document.getElementById('results').innerHTML = transcript;

  if (tmi !== '') {
    document.getElementById('results').innerHTML = document.getElementById('results').innerHTML + "<br/><strong>On readback, state you have TMI " + tmi + ".</strong>";
  }
} //Nat/random routing select


function routingSelect(value) {
  if (value == 'nat') {
    document.getElementById('natRoutePanel').style.display = 'block';
    document.getElementById('randomRoutePanel').style.display = 'none';
  } else {
    document.getElementById('natRoutePanel').style.display = 'none';
    document.getElementById('randomRoutePanel').style.display = 'block';
  }
}
/*
Format of clearance request:
CALLSIGN request clearance via Track LETTER|route ROUTE. Estimating
ENTRY at TIME. Request Flight Level FLIGHTLEVEL, Mach MACHSPEED.
(Result will say 'Readback TMI [TMI] on readback of clearance from controller.)
*/
//Generate results


function generatePositionReport() {
  //Get variables from form
  var callsign = document.getElementById('callsignB').value;
  var reporting = document.getElementById('reportingB').value;
  var time = document.getElementById('timeB').value;
  var flightLevel = document.getElementById('flightLevelB').value;
  var next = document.getElementById('nextB').value;
  var estimating = document.getElementById('estimatingB').value;
  var thereafter = document.getElementById('thereafterB').value; //In case there are errors...

  var errors = []; //Check if fields aren't filled

  if (callsign == '') {
    errors.push('Callsign not filled');
  }

  if (reporting == '') {
    errors.push('Reporting fix not filled');
  }

  if (time == '') {
    errors.push('Time not filled');
  }

  if (flightLevel == '') {
    errors.push('Flight level not filled');
  }

  if (next == '') {
    errors.push('Next fix not filled');
  }

  if (estimating == '') {
    errors.push('Estimating next fix time not filled');
  }

  if (thereafter == '') {
    errors.push('Fix thereafter not filled');
  } //There are errors... tell the user to fix 'em!


  if (errors.length >= 1) {
    return invalidSubmission(errors);
  } //No errors? March on!
  //Generate main request transcript.


  var transcript; //Create transcript

  transcript = callsign + ', position ' + reporting + ' at ' + time + ', Flight Level ' + flightLevel + ', Estimating ' + next + ' at ' + estimating + ', ' + thereafter + ' thereafter.'; //Display it!

  document.getElementById('errorA').style.display = 'none';
  document.getElementById('results').innerHTML = transcript;
} //Deal with invalid submission


function invalidSubmission(errors) {
  document.getElementById('errorContent').innerHTML = "";
  document.getElementById('errorA').style.display = 'block';

  for (i = 0; i < errors.length; i++) {
    document.getElementById('errorContent').innerHTML = document.getElementById('errorContent').innerHTML + '<br/>' + errors[i];
  }
}

/***/ }),

/***/ "./resources/js/policies.js":
/*!**********************************!*\
  !*** ./resources/js/policies.js ***!
  \**********************************/
/*! no static exports found */
/***/ (function(module, exports) {

/*
Function to expand and hide policy embeds on /policies
*/
$(document).ready(function () {
  $(".expandHidePolicyButton").on('click', function () {
    //Get policy id
    policyId = $(this).data("policy-id"); //Toggle the embed

    $("#policyEmbed".concat(policyId)).toggleClass('d-none');
  });
});

/***/ }),

/***/ "./resources/js/preferences.js":
/*!*************************************!*\
  !*** ./resources/js/preferences.js ***!
  \*************************************/
/*! no static exports found */
/***/ (function(module, exports) {

$(document).ready(function () {
  //Dropdown change
  $(".pref-dropdown").change(function () {
    //Check whether the field name is present in the data
    var preferenceName = this.name;

    if (!preferenceName || preferenceName == '') {
      //Error
      Toastify({
        text: "Error changing preference (data 'name' not found)",
        duration: 5000,
        close: true,
        gravity: "bottom",
        // `top` or `bottom`
        position: 'right',
        // `left`, `center` or `right`
        backgroundColor: '#ff4444',
        offset: {
          x: 100,
          // horizontal axis - can be a number or a string indicating unity. eg: '2em'
          y: 50 // vertical axis - can be a number or a string indicating unity. eg: '2em'

        },
        stopOnFocus: true // Prevents dismissing of toast on hover

      }).showToast(); //Enable and hide loading icon

      $(select).toggleClass('d-none');
      $("#".concat(preferenceName, "_loading")).toggleClass('d-none');
      return;
    } //Disable and show loading icon


    var select = this;
    $(select).toggleClass('d-none');
    $("#".concat(preferenceName, "_loading")).toggleClass('d-none'); //Make ajax request

    $.ajax({
      type: 'POST',
      url: '/my/preferences',
      data: {
        preference_name: select.name,
        value: select.value,
        table: $(select).data('table')
      },
      dataType: 'json',
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      success: function success(data) {
        console.log(data); //Enable and hide loading icon

        $(select).toggleClass('d-none');
        $("#".concat(preferenceName, "_loading")).toggleClass('d-none'); //Show saved toast

        Toastify({
          text: "".concat($(select).data('pretty-name'), " saved!"),
          duration: 5000,
          close: true,
          gravity: "bottom",
          // `top` or `bottom`
          position: 'right',
          // `left`, `center` or `right`
          backgroundColor: '#00C851',
          offset: {
            x: 100,
            // horizontal axis - can be a number or a string indicating unity. eg: '2em'
            y: 50 // vertical axis - can be a number or a string indicating unity. eg: '2em'

          },
          stopOnFocus: true // Prevents dismissing of toast on hover

        }).showToast(); //If it's UI mode or accent colour...

        if (select.name == 'ui_mode') {
          $('body').attr('data-theme', select.value);
        } else if (select.name == 'accent_colour') {
          $('body').attr('data-accent', select.value);
        }
      },
      error: function error(data) {
        console.log('Error');
        console.log(data); //Error

        Toastify({
          text: "Error changing '".concat($(select).data('pretty-name'), "' preference (Request failed)"),
          duration: 5000,
          close: true,
          gravity: "bottom",
          // `top` or `bottom`
          position: 'right',
          // `left`, `center` or `right`
          backgroundColor: '#ff4444',
          offset: {
            x: 100,
            // horizontal axis - can be a number or a string indicating unity. eg: '2em'
            y: 50 // vertical axis - can be a number or a string indicating unity. eg: '2em'

          },
          stopOnFocus: true // Prevents dismissing of toast on hover

        }).showToast(); //Enable and hide loading icon

        $(select).toggleClass('d-none');
        $("#".concat(preferenceName, "_loading")).toggleClass('d-none');
      }
    });
  });
});

/***/ }),

/***/ 0:
/*!*****************************************************************************************************************************************************************************************************************!*\
  !*** multi ./resources/js/pilot-tools.js ./resources/js/policies.js ./resources/js/maps.js ./resources/js/myczqo.js ./resources/js/preferences.js ./resources/js/custom-pages.js ./resources/js/instructing.js ***!
  \*****************************************************************************************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

__webpack_require__(/*! C:\Users\cocoi\Git\czqo-core\resources\js\pilot-tools.js */"./resources/js/pilot-tools.js");
__webpack_require__(/*! C:\Users\cocoi\Git\czqo-core\resources\js\policies.js */"./resources/js/policies.js");
__webpack_require__(/*! C:\Users\cocoi\Git\czqo-core\resources\js\maps.js */"./resources/js/maps.js");
__webpack_require__(/*! C:\Users\cocoi\Git\czqo-core\resources\js\myczqo.js */"./resources/js/myczqo.js");
__webpack_require__(/*! C:\Users\cocoi\Git\czqo-core\resources\js\preferences.js */"./resources/js/preferences.js");
__webpack_require__(/*! C:\Users\cocoi\Git\czqo-core\resources\js\custom-pages.js */"./resources/js/custom-pages.js");
module.exports = __webpack_require__(/*! C:\Users\cocoi\Git\czqo-core\resources\js\instructing.js */"./resources/js/instructing.js");


/***/ })

/******/ });