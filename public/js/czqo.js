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

function createMapPointsBoundaries(map) {
    const icon = L.icon({ iconUrl: '/img/oep.png', iconAnchor: [5, 5] });


    L.marker([54,-23], {opacity: 0})
	.bindTooltip('Shanwick OCA', { permanent: true, direction: 'center', opacity: 0.5 })
    .addTo(map);

    L.marker([54,-45], {opacity: 0})
	.bindTooltip('Gander OCA', { permanent: true, direction: 'center', opacity: 0.5 })
	.addTo(map);

    L.latlngGraticule({
        showLabel: true,
        dashArray: [5, 5],
        zoomInterval: [ { start: 0, end: 10, interval: 5 } ]
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

function parseTimeStamp(jsonDateStr)
{
    let datetime = new Date(jsonDateStr * 1000);
    let datetimeStr = datetime.getUTCFullYear().toString() + "-" +
        datetime.getUTCMonth().toString().padStart(2, '0') + "-" +
        datetime.getUTCDay().toString().padStart(2, '0') + " " +
        datetime.getUTCHours().toString().padStart(2, '0') + ":" +
        datetime.getUTCMinutes().toString().padStart(2, '0') + "Z";
    return datetimeStr;
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
    let markerIcon = L.icon({
        iconUrl: 'https://ganderoceanicoca.ams3.digitaloceanspaces.com/resources/dot-point-map.png',
        iconSize: [10, 10],
        iconAnchor: [2, 4]
    });

    //Create marker object
    let marker = L.marker([point.latitude, point.longitude], {icon: markerIcon}).addTo(map);

    //Bind popup
    marker.bindPopup("<b>"+point.name+"</b><br>Track "+track.id);
    marker.on('mouseover', function (e) {
        this.openPopup();
    });
    marker.on('mouseout', function (e) {
        this.closePopup();
    });
}

//Create Current NAT Tracks map
async function createNatTrackMap()
{
    //Create map
    const map = L.map('map', { minZoom: 4, maxZoom: 7 }).setView([52, -35], 1);

    //Define table
    let table = $("#natTrackTable")

    //Create OSM Layer
    var OpenStreetMap_Mapnik = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    //Get tracks
    let endpoint = "https://tracks.ganderoceanic.com/data"
    const response = await fetch(endpoint)

    //Create data object
    const data = await response.json()

    //Go through each NAT Track
    data.forEach(track => {
        //Create array of points latitudes/longitudes
        let pointsLatLon = []
        track.route.forEach(point => {
            //Add point to array
            pointsLatLon.push([point.latitude, point.longitude])
            //Create map marker
            createMapTrackPointMarker(point, track, map)
        })

        //Get colour for the polyline depending on track direction
        let colour = '#00000';
        if (track.direction == 1) {
            colour = '#1c5fc9'
        } else {
            colour = '#c92d1c'
        }

        //Create polylines
        let line = new L.Polyline(pointsLatLon, {
            color: colour,
            weight: 2,
            opacity: 1,
            smoothFactor: 1
        }).addTo(map)

        //Create row
        let row = $("<tr></tr>")

        //Add track id
        let idCell = $("<td scope='row'></td>").text(track.id)
        $(row).append(idCell)

        //Add points
        let pointsText = []
        track.route.forEach(point => {
            pointsText.push(" " + point.name)
        })
        let pointsCell = $("<td></td>").text(pointsText)
        $(row).append(pointsCell)

        //Add direction
        let directionCell = $("<td></td>")
        if (track.direction == 1) {
            $(directionCell).text('Westbound')
        } else {
            $(directionCell).text('Eastbound')
        }
        $(row).append(directionCell)

        //Add levels
        let levelsText = []
        track.flightLevels.forEach(level => {
            levelsText.push(" " + level / 100)
        })
        let levelsCell = $("<td></td>").text(levelsText)
        $(row).append(levelsCell)

        //validity
        let validityCell = $("<td></td>").text(
            `${parseTimeStamp(track.validFrom)} to ${parseTimeStamp(track.validTo)}`
        )
        $(row).append(validityCell)

        //Add row to table
        $(table).append(row)
    })

    //Add points and boundaries
    createMapPointsBoundaries(map)
}

//Create Event NAT Tracks map
async function createEventTrackMap()
{
    //Create map
    const map = L.map('map', { minZoom: 4, maxZoom: 7 }).setView([52, -35], 1);

    //Define table
    let table = $("#natTrackTable")

    //Create OSM Layer
    var OpenStreetMap_Mapnik = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    //Get tracks
    let endpoint = "https://tracks.ganderoceanic.com/event"
    const response = await fetch(endpoint)

    //Create data object
    const data = await response.json()

    //Go through each NAT Track
    data.forEach(track => {
        //Create array of points latitudes/longitudes
        let pointsLatLon = []
        track.route.forEach(point => {
            //Add point to array
            pointsLatLon.push([point.latitude, point.longitude])
            //Create map marker
            createMapTrackPointMarker(point, track, map)
        })

        //Get colour for the polyline depending on track direction
        let colour = '#00000';
        if (track.direction == 1) {
            colour = '#1c5fc9'
        } else {
            colour = '#c92d1c'
        }

        //Create polylines
        let line = new L.Polyline(pointsLatLon, {
            color: colour,
            weight: 2,
            opacity: 1,
            smoothFactor: 1
        }).addTo(map)

        //Create row
        let row = $("<tr></tr>")

        //Add track id
        let idCell = $("<td scope='row'></td>").text(track.id)
        $(row).append(idCell)

        //Add points
        let pointsText = []
        track.route.forEach(point => {
            pointsText.push(" " + point.name)
        })
        let pointsCell = $("<td></td>").text(pointsText)
        $(row).append(pointsCell)

        //Add direction
        let directionCell = $("<td></td>")
        if (track.direction == 1) {
            $(directionCell).text('Westbound')
        } else {
            $(directionCell).text('Eastbound')
        }
        $(row).append(directionCell)

        //Add levels
        let levelsText = []
        track.flightLevels.forEach(level => {
            levelsText.push(" " + level / 100)
        })
        let levelsCell = $("<td></td>").text(levelsText)
        $(row).append(levelsCell)

        //validity
        let validityCell = $("<td></td>").text(
            `${parseTimeStamp(track.validFrom)} to ${parseTimeStamp(track.validTo)}`
        )
        $(row).append(validityCell)

        //Add row to table
        $(table).append(row)
    })

    //Add points and boundaries
    createMapPointsBoundaries(map)
}

//Create big map
async function createMap(planes, controllerOnline) {
    //Create map and layer
    const map = L.map('map', { minZoom: 4, maxZoom: 7 }).setView([55, -30], 4.5);
    var OpenStreetMap_Mapnik = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    //Create boundaries and points
    createMapPointsBoundaries(map)

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

    //Add tracks
    //Get tracks
    let endpoint = "https://tracks.ganderoceanic.com/data"
    const response = await fetch(endpoint)

    //Create data object
    const data = await response.json()

    //Go through each NAT Track
    data.forEach(track => {
        //Create array of points latitudes/longitudes
        let pointsLatLon = []
        track.route.forEach(point => {
            //Add point to array
            pointsLatLon.push([point.latitude, point.longitude])
            //Create map marker
            createMapTrackPointMarker(point, track, map)
        })

        //Get colour for the polyline depending on track direction
        let colour = '#00000';
        if (track.direction == 1) {
            colour = '#1c5fc9'
        } else {
            colour = '#c92d1c'
        }

        //Create polylines
        let line = new L.Polyline(pointsLatLon, {
            color: colour,
            weight: 2,
            opacity: 1,
            smoothFactor: 1
        }).addTo(map)
    })

    //Add Gander/Shanwick bubbles if they're online
    if (!controllerOnline) {
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
            [61,-30],
            [ '45', '-30' ],
            [ '45', '-8' ],
            [ '51', '-8' ],
            [ '51', '-15' ],
            [ '54', '-15' ],
            [ '54.56666666666667', '-10' ],
            [ '61', '-10' ],
            [ '61', '-30' ],
            [ '45', '-30' ]
        ]).addTo(map).bindPopup("Gander/Shanwick OCA online")

        const Shanwick = [
        ];
        L.polyline(Shanwick, { color: '#777', weight: 0.5 }).addTo(map);
    }
}

//Create about page map
async function createAboutPageMap() {
    const map = L.map('aboutPageMap').setView([55, -30], 3.48);
    const icon = L.icon({ iconUrl: '/img/oep.png', iconAnchor: [5, 5] });

    var OpenStreetMap_Mapnik = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    //Add markers
    createMapPointsBoundaries(map)
}


tabs = [
    'yourProfileTab',
    'supportTab',
    'certificationTrainingTab',
    'instructingTab',
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

$(document).ready(function () {

    //Dropdown change
    $(".pref-dropdown").change(function(){

        //Check whether the field name is present in the data
        let preferenceName = this.name

        if (!preferenceName || preferenceName == '') {
            //Error
            Toastify({
                text: "Error changing preference (data 'name' not found)",
                duration: 5000,
                close: true,
                gravity: "bottom", // `top` or `bottom`
                position: 'right', // `left`, `center` or `right`
                backgroundColor: '#ff4444',
                offset: {
                    x: 100, // horizontal axis - can be a number or a string indicating unity. eg: '2em'
                    y: 50 // vertical axis - can be a number or a string indicating unity. eg: '2em'
                },
                stopOnFocus: true, // Prevents dismissing of toast on hover
            }).showToast();

            //Enable and hide loading icon
            $(select).toggleClass('d-none');
            $(`#${preferenceName}_loading`).toggleClass('d-none');

            return
        }

        //Disable and show loading icon
        let select = this
        $(select).toggleClass('d-none');
        $(`#${preferenceName}_loading`).toggleClass('d-none');

        //Make ajax request
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
                'X-CSRF-TOKEN':  $('meta[name="csrf-token"]').attr('content')
            },
            success: function(data) {
                console.log(data)

                //Enable and hide loading icon
                $(select).toggleClass('d-none');
                $(`#${preferenceName}_loading`).toggleClass('d-none');

                //Show saved toast
                Toastify({
                    text: `${$(select).data('pretty-name')} saved!`,
                    duration: 5000,
                    close: true,
                    gravity: "bottom", // `top` or `bottom`
                    position: 'right', // `left`, `center` or `right`
                    backgroundColor: '#00C851',
                    offset: {
                        x: 100, // horizontal axis - can be a number or a string indicating unity. eg: '2em'
                        y: 50 // vertical axis - can be a number or a string indicating unity. eg: '2em'
                    },
                    stopOnFocus: true, // Prevents dismissing of toast on hover
                }).showToast();

                //If it's UI mode or accent colour...
                if (select.name == 'ui_mode') {
                    $('body').attr('data-theme', select.value)
                } else if (select.name == 'accent_colour') {
                    $('body').attr('data-accent', select.value)
                }
            },
            error: function(data) {
                console.log('Error')
                console.log(data)

                //Error
                Toastify({
                    text: `Error changing '${$(select).data('pretty-name')}' preference (Request failed)`,
                    duration: 5000,
                    close: true,
                    gravity: "bottom", // `top` or `bottom`
                    position: 'right', // `left`, `center` or `right`
                    backgroundColor: '#ff4444',
                    offset: {
                        x: 100, // horizontal axis - can be a number or a string indicating unity. eg: '2em'
                        y: 50 // vertical axis - can be a number or a string indicating unity. eg: '2em'
                    },
                    stopOnFocus: true, // Prevents dismissing of toast on hover
                }).showToast();

                //Enable and hide loading icon
                $(select).toggleClass('d-none');
                $(`#${preferenceName}_loading`).toggleClass('d-none');
            }
        })
    });

});

$(document).ready(function () {

    //Form submit
    $("#response-form").submit(function(event){
        //If form is empty
        if (!$('#contentMD').val())
        {
            //Error
            Toastify({
                text: "Please write your response",
                duration: 5000,
                close: true,
                gravity: "bottom", // `top` or `bottom`
                position: 'right', // `left`, `center` or `right`
                backgroundColor: '#ff4444',
                offset: {
                    x: 100, // horizontal axis - can be a number or a string indicating unity. eg: '2em'
                    y: 50 // vertical axis - can be a number or a string indicating unity. eg: '2em'
                },
                stopOnFocus: true, // Prevents dismissing of toast on hover
            }).showToast()

            event.preventDefault();

            return
        }

        //Make ajax request
        $.ajax({
            type: 'POST',
            url: window.location.href + '/response-submit',
            data: {
                content: $('#contentMD').val(),
            },
            dataType: 'json',
            headers: {
                'X-CSRF-TOKEN':  $('meta[name="csrf-token"]').attr('content')
            },
            success: function(data) {
                console.log(data)

                //Show saved toast
                Toastify({
                    text: `Response submitted!`,
                    duration: 5000,
                    close: true,
                    gravity: "bottom", // `top` or `bottom`
                    position: 'right', // `left`, `center` or `right`
                    backgroundColor: '#00C851',
                    offset: {
                        x: 100, // horizontal axis - can be a number or a string indicating unity. eg: '2em'
                        y: 50 // vertical axis - can be a number or a string indicating unity. eg: '2em'
                    },
                    stopOnFocus: true, // Prevents dismissing of toast on hover
                }).showToast();

                location.reload();

            },
            error: function(data) {
                console.log('Error')
                console.log(data)

                //Error
                Toastify({
                    text: `Error (${data.responseJSON.message})`,
                    duration: 5000,
                    close: true,
                    gravity: "bottom", // `top` or `bottom`
                    position: 'right', // `left`, `center` or `right`
                    backgroundColor: '#ff4444',
                    offset: {
                        x: 100, // horizontal axis - can be a number or a string indicating unity. eg: '2em'
                        y: 50 // vertical axis - can be a number or a string indicating unity. eg: '2em'
                    },
                    stopOnFocus: true, // Prevents dismissing of toast on hover
                }).showToast();

            }
        })


        event.preventDefault();
    });

});

function createInstructingSessionsCal() {
    var calendarEl = document.getElementById('instructing-sessions-calendar')

    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'timeGridWeek',
        headerToolbar: {
            start: 'title', // will normally be on the left. if RTL, will be on the right
            center: '',
            end: 'today prev,next timeGridWeek,dayGridMonth,list' // will normally be on the right. if RTL, will be on the left
          },
        firstDay: 1,
        nowIndicator: true,
        timeZone: 'UTC'
    });

    calendar.render();

    return calendar;
}

/** @license
 * DHTML Snowstorm! JavaScript-based snow for web pages
 * Making it snow on the internets since 2003. You're welcome.
 * -----------------------------------------------------------
 * Version 1.44.20131208 (Previous rev: 1.44.20131125)
 * Copyright (c) 2007, Scott Schiller. All rights reserved.
 * Code provided under the BSD License
 * http://schillmania.com/projects/snowstorm/license.txt
 */

/*jslint nomen: true, plusplus: true, sloppy: true, vars: true, white: true */
/*global window, document, navigator, clearInterval, setInterval */

var snowStorm = (function(window, document) {

    // --- common properties ---

    this.autoStart = true;          // Whether the snow should start automatically or not.
    this.excludeMobile = true;      // Snow is likely to be bad news for mobile phones' CPUs (and batteries.) Enable at your own risk.
    this.flakesMax = 128;           // Limit total amount of snow made (falling + sticking)
    this.flakesMaxActive = 128;      // Limit amount of snow falling at once (less = lower CPU use)
    this.animationInterval = 50;    // Theoretical "miliseconds per frame" measurement. 20 = fast + smooth, but high CPU use. 50 = more conservative, but slower
    this.useGPU = true;             // Enable transform-based hardware acceleration, reduce CPU load.
    this.className = null;          // CSS class name for further customization on snow elements
    this.excludeMobile = true;      // Snow is likely to be bad news for mobile phones' CPUs (and batteries.) By default, be nice.
    this.flakeBottom = null;        // Integer for Y axis snow limit, 0 or null for "full-screen" snow effect
    this.followMouse = false;        // Snow movement can respond to the user's mouse
    this.snowColor = '#fff';        // Don't eat (or use?) yellow snow.
    this.snowCharacter = '&bull;';  // &bull; = bullet, &middot; is square on some systems etc.
    this.snowStick = true;          // Whether or not snow should "stick" at the bottom. When off, will never collect.
    this.targetElement = 'homePageJarallax';      // element which snow will be appended to (null = document.body) - can be an element ID eg. 'myDiv', or a DOM node reference
    this.useMeltEffect = true;      // When recycling fallen snow (or rarely, when falling), have it "melt" and fade out if browser supports it
    this.useTwinkleEffect = false;  // Allow snow to randomly "flicker" in and out of view while falling
    this.usePositionFixed = false;  // true = snow does not shift vertically when scrolling. May increase CPU load, disabled by default - if enabled, used only where supported
    this.usePixelPosition = false;  // Whether to use pixel values for snow top/left vs. percentages. Auto-enabled if body is position:relative or targetElement is specified.

    // --- less-used bits ---

    this.freezeOnBlur = false;       // Only snow when the window is in focus (foreground.) Saves CPU.
    this.flakeLeftOffset = 0;       // Left margin/gutter space on edge of container (eg. browser window.) Bump up these values if seeing horizontal scrollbars.
    this.flakeRightOffset = 0;      // Right margin/gutter space on edge of container
    this.flakeWidth = 8;            // Max pixel width reserved for snow element
    this.flakeHeight = 8;           // Max pixel height reserved for snow element
    this.vMaxX = 5;                 // Maximum X velocity range for snow
    this.vMaxY = 4;                 // Maximum Y velocity range for snow
    this.zIndex = 0;                // CSS stacking order applied to each snowflake

    // --- "No user-serviceable parts inside" past this point, yadda yadda ---

    var storm = this,
    features,
    // UA sniffing and backCompat rendering mode checks for fixed position, etc.
    isIE = navigator.userAgent.match(/msie/i),
    isIE6 = navigator.userAgent.match(/msie 6/i),
    isMobile = navigator.userAgent.match(/mobile|opera m(ob|in)/i),
    isBackCompatIE = (isIE && document.compatMode === 'BackCompat'),
    noFixed = (isBackCompatIE || isIE6),
    screenX = null, screenX2 = null, screenY = null, scrollY = null, docHeight = null, vRndX = null, vRndY = null,
    windOffset = 1,
    windMultiplier = 2,
    flakeTypes = 6,
    fixedForEverything = false,
    targetElementIsRelative = false,
    opacitySupported = (function(){
      try {
        document.createElement('div').style.opacity = '0.5';
      } catch(e) {
        return false;
      }
      return true;
    }()),
    didInit = false,
    docFrag = document.createDocumentFragment();

    features = (function() {

      var getAnimationFrame;

      /**
       * hat tip: paul irish
       * http://paulirish.com/2011/requestanimationframe-for-smart-animating/
       * https://gist.github.com/838785
       */

      function timeoutShim(callback) {
        window.setTimeout(callback, 1000/(storm.animationInterval || 20));
      }

      var _animationFrame = (window.requestAnimationFrame ||
          window.webkitRequestAnimationFrame ||
          window.mozRequestAnimationFrame ||
          window.oRequestAnimationFrame ||
          window.msRequestAnimationFrame ||
          timeoutShim);

      // apply to window, avoid "illegal invocation" errors in Chrome
      getAnimationFrame = _animationFrame ? function() {
        return _animationFrame.apply(window, arguments);
      } : null;

      var testDiv;

      testDiv = document.createElement('div');

      function has(prop) {

        // test for feature support
        var result = testDiv.style[prop];
        return (result !== undefined ? prop : null);

      }

      // note local scope.
      var localFeatures = {

        transform: {
          ie:  has('-ms-transform'),
          moz: has('MozTransform'),
          opera: has('OTransform'),
          webkit: has('webkitTransform'),
          w3: has('transform'),
          prop: null // the normalized property value
        },

        getAnimationFrame: getAnimationFrame

      };

      localFeatures.transform.prop = (
        localFeatures.transform.w3 ||
        localFeatures.transform.moz ||
        localFeatures.transform.webkit ||
        localFeatures.transform.ie ||
        localFeatures.transform.opera
      );

      testDiv = null;

      return localFeatures;

    }());

    this.timer = null;
    this.flakes = [];
    this.disabled = false;
    this.active = false;
    this.meltFrameCount = 20;
    this.meltFrames = [];

    this.setXY = function(o, x, y) {

      if (!o) {
        return false;
      }

      if (storm.usePixelPosition || targetElementIsRelative) {

        o.style.left = (x - storm.flakeWidth) + 'px';
        o.style.top = (y - storm.flakeHeight) + 'px';

      } else if (noFixed) {

        o.style.right = (100-(x/screenX*100)) + '%';
        // avoid creating vertical scrollbars
        o.style.top = (Math.min(y, docHeight-storm.flakeHeight)) + 'px';

      } else {

        if (!storm.flakeBottom) {

          // if not using a fixed bottom coordinate...
          o.style.right = (100-(x/screenX*100)) + '%';
          o.style.bottom = (100-(y/screenY*100)) + '%';

        } else {

          // absolute top.
          o.style.right = (100-(x/screenX*100)) + '%';
          o.style.top = (Math.min(y, docHeight-storm.flakeHeight)) + 'px';

        }

      }

    };

    this.events = (function() {

      var old = (!window.addEventListener && window.attachEvent), slice = Array.prototype.slice,
      evt = {
        add: (old?'attachEvent':'addEventListener'),
        remove: (old?'detachEvent':'removeEventListener')
      };

      function getArgs(oArgs) {
        var args = slice.call(oArgs), len = args.length;
        if (old) {
          args[1] = 'on' + args[1]; // prefix
          if (len > 3) {
            args.pop(); // no capture
          }
        } else if (len === 3) {
          args.push(false);
        }
        return args;
      }

      function apply(args, sType) {
        var element = args.shift(),
            method = [evt[sType]];
        if (old) {
          element[method](args[0], args[1]);
        } else {
          element[method].apply(element, args);
        }
      }

      function addEvent() {
        apply(getArgs(arguments), 'add');
      }

      function removeEvent() {
        apply(getArgs(arguments), 'remove');
      }

      return {
        add: addEvent,
        remove: removeEvent
      };

    }());

    function rnd(n,min) {
      if (isNaN(min)) {
        min = 0;
      }
      return (Math.random()*n)+min;
    }

    function plusMinus(n) {
      return (parseInt(rnd(2),10)===1?n*-1:n);
    }

    this.randomizeWind = function() {
      var i;
      vRndX = plusMinus(rnd(storm.vMaxX,0.2));
      vRndY = rnd(storm.vMaxY,0.2);
      if (this.flakes) {
        for (i=0; i<this.flakes.length; i++) {
          if (this.flakes[i].active) {
            this.flakes[i].setVelocities();
          }
        }
      }
    };

    this.scrollHandler = function() {
      var i;
      // "attach" snowflakes to bottom of window if no absolute bottom value was given
      scrollY = (storm.flakeBottom ? 0 : parseInt(window.scrollY || document.documentElement.scrollTop || (noFixed ? document.body.scrollTop : 0), 10));
      if (isNaN(scrollY)) {
        scrollY = 0; // Netscape 6 scroll fix
      }
      if (!fixedForEverything && !storm.flakeBottom && storm.flakes) {
        for (i=0; i<storm.flakes.length; i++) {
          if (storm.flakes[i].active === 0) {
            storm.flakes[i].stick();
          }
        }
      }
    };

    this.resizeHandler = function() {
      if (window.innerWidth || window.innerHeight) {
        screenX = window.innerWidth - 16 - storm.flakeRightOffset;
        screenY = (storm.flakeBottom || window.innerHeight);
      } else {
        screenX = (document.documentElement.clientWidth || document.body.clientWidth || document.body.scrollWidth) - (!isIE ? 8 : 0) - storm.flakeRightOffset;
        screenY = storm.flakeBottom || document.documentElement.clientHeight || document.body.clientHeight || document.body.scrollHeight;
      }
      docHeight = document.body.offsetHeight;
      screenX2 = parseInt(screenX/2,10);
    };

    this.resizeHandlerAlt = function() {
      screenX = storm.targetElement.offsetWidth - storm.flakeRightOffset;
      screenY = storm.flakeBottom || storm.targetElement.offsetHeight;
      screenX2 = parseInt(screenX/2,10);
      docHeight = document.body.offsetHeight;
    };

    this.freeze = function() {
      // pause animation
      if (!storm.disabled) {
        storm.disabled = 1;
      } else {
        return false;
      }
      storm.timer = null;
    };

    this.resume = function() {
      if (storm.disabled) {
         storm.disabled = 0;
      } else {
        return false;
      }
      storm.timerInit();
    };

    this.toggleSnow = function() {
      if (!storm.flakes.length) {
        // first run
        storm.start();
      } else {
        storm.active = !storm.active;
        if (storm.active) {
          storm.show();
          storm.resume();
        } else {
          storm.stop();
          storm.freeze();
        }
      }
    };

    this.stop = function() {
      var i;
      this.freeze();
      for (i=0; i<this.flakes.length; i++) {
        this.flakes[i].o.style.display = 'none';
      }
      storm.events.remove(window,'scroll',storm.scrollHandler);
      storm.events.remove(window,'resize',storm.resizeHandler);
      if (storm.freezeOnBlur) {
        if (isIE) {
          storm.events.remove(document,'focusout',storm.freeze);
          storm.events.remove(document,'focusin',storm.resume);
        } else {
          storm.events.remove(window,'blur',storm.freeze);
          storm.events.remove(window,'focus',storm.resume);
        }
      }
    };

    this.show = function() {
      var i;
      for (i=0; i<this.flakes.length; i++) {
        this.flakes[i].o.style.display = 'block';
      }
    };

    this.SnowFlake = function(type,x,y) {
      var s = this;
      this.type = type;
      this.x = x||parseInt(rnd(screenX-20),10);
      this.y = (!isNaN(y)?y:-rnd(screenY)-12);
      this.vX = null;
      this.vY = null;
      this.vAmpTypes = [1,1.2,1.4,1.6,1.8]; // "amplification" for vX/vY (based on flake size/type)
      this.vAmp = this.vAmpTypes[this.type] || 1;
      this.melting = false;
      this.meltFrameCount = storm.meltFrameCount;
      this.meltFrames = storm.meltFrames;
      this.meltFrame = 0;
      this.twinkleFrame = 0;
      this.active = 1;
      this.fontSize = (10+(this.type/5)*10);
      this.o = document.createElement('div');
      this.o.innerHTML = storm.snowCharacter;
      if (storm.className) {
        this.o.setAttribute('class', storm.className);
      }
      this.o.style.color = storm.snowColor;
      this.o.style.position = (fixedForEverything?'fixed':'absolute');
      if (storm.useGPU && features.transform.prop) {
        // GPU-accelerated snow.
        this.o.style[features.transform.prop] = 'translate3d(0px, 0px, 0px)';
      }
      this.o.style.width = storm.flakeWidth+'px';
      this.o.style.height = storm.flakeHeight+'px';
      this.o.style.fontFamily = 'arial,verdana';
      this.o.style.cursor = 'default';
      this.o.style.overflow = 'hidden';
      this.o.style.fontWeight = 'normal';
      this.o.style.zIndex = storm.zIndex;
      docFrag.appendChild(this.o);

      this.refresh = function() {
        if (isNaN(s.x) || isNaN(s.y)) {
          // safety check
          return false;
        }
        storm.setXY(s.o, s.x, s.y);
      };

      this.stick = function() {
        if (noFixed || (storm.targetElement !== document.documentElement && storm.targetElement !== document.body)) {
          s.o.style.top = (screenY+scrollY-storm.flakeHeight)+'px';
        } else if (storm.flakeBottom) {
          s.o.style.top = storm.flakeBottom+'px';
        } else {
          s.o.style.display = 'none';
          s.o.style.bottom = '0%';
          s.o.style.position = 'fixed';
          s.o.style.display = 'block';
        }
      };

      this.vCheck = function() {
        if (s.vX>=0 && s.vX<0.2) {
          s.vX = 0.2;
        } else if (s.vX<0 && s.vX>-0.2) {
          s.vX = -0.2;
        }
        if (s.vY>=0 && s.vY<0.2) {
          s.vY = 0.2;
        }
      };

      this.move = function() {
        var vX = s.vX*windOffset, yDiff;
        s.x += vX;
        s.y += (s.vY*s.vAmp);
        if (s.x >= screenX || screenX-s.x < storm.flakeWidth) { // X-axis scroll check
          s.x = 0;
        } else if (vX < 0 && s.x-storm.flakeLeftOffset < -storm.flakeWidth) {
          s.x = screenX-storm.flakeWidth-1; // flakeWidth;
        }
        s.refresh();
        yDiff = screenY+scrollY-s.y+storm.flakeHeight;
        if (yDiff<storm.flakeHeight) {
          s.active = 0;
          if (storm.snowStick) {
            s.stick();
          } else {
            s.recycle();
          }
        } else {
          if (storm.useMeltEffect && s.active && s.type < 3 && !s.melting && Math.random()>0.998) {
            // ~1/1000 chance of melting mid-air, with each frame
            s.melting = true;
            s.melt();
            // only incrementally melt one frame
            // s.melting = false;
          }
          if (storm.useTwinkleEffect) {
            if (s.twinkleFrame < 0) {
              if (Math.random() > 0.97) {
                s.twinkleFrame = parseInt(Math.random() * 8, 10);
              }
            } else {
              s.twinkleFrame--;
              if (!opacitySupported) {
                s.o.style.visibility = (s.twinkleFrame && s.twinkleFrame % 2 === 0 ? 'hidden' : 'visible');
              } else {
                s.o.style.opacity = (s.twinkleFrame && s.twinkleFrame % 2 === 0 ? 0 : 1);
              }
            }
          }
        }
      };

      this.animate = function() {
        // main animation loop
        // move, check status, die etc.
        s.move();
      };

      this.setVelocities = function() {
        s.vX = vRndX+rnd(storm.vMaxX*0.12,0.1);
        s.vY = vRndY+rnd(storm.vMaxY*0.12,0.1);
      };

      this.setOpacity = function(o,opacity) {
        if (!opacitySupported) {
          return false;
        }
        o.style.opacity = opacity;
      };

      this.melt = function() {
        if (!storm.useMeltEffect || !s.melting) {
          s.recycle();
        } else {
          if (s.meltFrame < s.meltFrameCount) {
            s.setOpacity(s.o,s.meltFrames[s.meltFrame]);
            s.o.style.fontSize = s.fontSize-(s.fontSize*(s.meltFrame/s.meltFrameCount))+'px';
            s.o.style.lineHeight = storm.flakeHeight+2+(storm.flakeHeight*0.75*(s.meltFrame/s.meltFrameCount))+'px';
            s.meltFrame++;
          } else {
            s.recycle();
          }
        }
      };

      this.recycle = function() {
        s.o.style.display = 'none';
        s.o.style.position = (fixedForEverything?'fixed':'absolute');
        s.o.style.bottom = 'auto';
        s.setVelocities();
        s.vCheck();
        s.meltFrame = 0;
        s.melting = false;
        s.setOpacity(s.o,1);
        s.o.style.padding = '0px';
        s.o.style.margin = '0px';
        s.o.style.fontSize = s.fontSize+'px';
        s.o.style.lineHeight = (storm.flakeHeight+2)+'px';
        s.o.style.textAlign = 'center';
        s.o.style.verticalAlign = 'baseline';
        s.x = parseInt(rnd(screenX-storm.flakeWidth-20),10);
        s.y = parseInt(rnd(screenY)*-1,10)-storm.flakeHeight;
        s.refresh();
        s.o.style.display = 'block';
        s.active = 1;
      };

      this.recycle(); // set up x/y coords etc.
      this.refresh();

    };

    this.snow = function() {
      var active = 0, flake = null, i, j;
      for (i=0, j=storm.flakes.length; i<j; i++) {
        if (storm.flakes[i].active === 1) {
          storm.flakes[i].move();
          active++;
        }
        if (storm.flakes[i].melting) {
          storm.flakes[i].melt();
        }
      }
      if (active<storm.flakesMaxActive) {
        flake = storm.flakes[parseInt(rnd(storm.flakes.length),10)];
        if (flake.active === 0) {
          flake.melting = true;
        }
      }
      if (storm.timer) {
        features.getAnimationFrame(storm.snow);
      }
    };

    this.mouseMove = function(e) {
      if (!storm.followMouse) {
        return true;
      }
      var x = parseInt(e.clientX,10);
      if (x<screenX2) {
        windOffset = -windMultiplier+(x/screenX2*windMultiplier);
      } else {
        x -= screenX2;
        windOffset = (x/screenX2)*windMultiplier;
      }
    };

    this.createSnow = function(limit,allowInactive) {
      var i;
      for (i=0; i<limit; i++) {
        storm.flakes[storm.flakes.length] = new storm.SnowFlake(parseInt(rnd(flakeTypes),10));
        if (allowInactive || i>storm.flakesMaxActive) {
          storm.flakes[storm.flakes.length-1].active = -1;
        }
      }
      storm.targetElement.appendChild(docFrag);
    };

    this.timerInit = function() {
      storm.timer = true;
      storm.snow();
    };

    this.init = function() {
      var i;
      for (i=0; i<storm.meltFrameCount; i++) {
        storm.meltFrames.push(1-(i/storm.meltFrameCount));
      }
      storm.randomizeWind();
      storm.createSnow(storm.flakesMax); // create initial batch
      storm.events.add(window,'resize',storm.resizeHandler);
      storm.events.add(window,'scroll',storm.scrollHandler);
      if (storm.freezeOnBlur) {
        if (isIE) {
          storm.events.add(document,'focusout',storm.freeze);
          storm.events.add(document,'focusin',storm.resume);
        } else {
          storm.events.add(window,'blur',storm.freeze);
          storm.events.add(window,'focus',storm.resume);
        }
      }
      storm.resizeHandler();
      storm.scrollHandler();
      if (storm.followMouse) {
        storm.events.add(isIE?document:window,'mousemove',storm.mouseMove);
      }
      storm.animationInterval = Math.max(20,storm.animationInterval);
      storm.timerInit();
    };

    this.start = function(bFromOnLoad) {
      if (!didInit) {
        didInit = true;
      } else if (bFromOnLoad) {
        // already loaded and running
        return true;
      }
      if (typeof storm.targetElement === 'string') {
        var targetID = storm.targetElement;
        storm.targetElement = document.getElementById(targetID);
        if (!storm.targetElement) {
          throw new Error('Snowstorm: Unable to get targetElement "'+targetID+'"');
        }
      }
      if (!storm.targetElement) {
        storm.targetElement = (document.body || document.documentElement);
      }
      if (storm.targetElement !== document.documentElement && storm.targetElement !== document.body) {
        // re-map handler to get element instead of screen dimensions
        storm.resizeHandler = storm.resizeHandlerAlt;
        //and force-enable pixel positioning
        storm.usePixelPosition = true;
      }
      storm.resizeHandler(); // get bounding box elements
      storm.usePositionFixed = (storm.usePositionFixed && !noFixed && !storm.flakeBottom); // whether or not position:fixed is to be used
      if (window.getComputedStyle) {
        // attempt to determine if body or user-specified snow parent element is relatlively-positioned.
        try {
          targetElementIsRelative = (window.getComputedStyle(storm.targetElement, null).getPropertyValue('position') === 'relative');
        } catch(e) {
          // oh well
          targetElementIsRelative = false;
        }
      }
      fixedForEverything = storm.usePositionFixed;
      if (screenX && screenY && !storm.disabled) {
        storm.init();
        storm.active = true;
      }
    };

    function doDelayedStart() {
      window.setTimeout(function() {
        storm.start(true);
      }, 20);
      // event cleanup
      storm.events.remove(isIE?document:window,'mousemove',doDelayedStart);
    }

    function doStart() {
      if (!storm.excludeMobile || !isMobile) {
        doDelayedStart();
      }
      // event cleanup
      storm.events.remove(window, 'load', doStart);
    }

    // hooks for starting the snow
    if (storm.autoStart) {
      storm.events.add(window, 'load', doStart, false);
    }

    return this;

  }(window, document));
