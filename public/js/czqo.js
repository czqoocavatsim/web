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

    L.marker([31,-55], {opacity: 0})
	.bindTooltip('New York OCA', { permanent: true, direction: 'center', opacity: 0.5 })
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
        iconUrl: 'https://cdn.discordapp.com/attachments/1327202746616516629/1356790124251320360/image.png?ex=67edd8c5&is=67ec8745&hm=4bf8c0194a10112e263d7dc247d89485fadc29db99f336291ebdaddd93a03436&',
        iconSize: [10, 10],
        iconAnchor: [2, 4]
    });

    //Create marker object
    let marker = L.marker([point.latitude, point.longitude], {icon: markerIcon}).addTo(map);

    console.log(point); 

    //Bind popup
    marker.bindPopup("<b>"+point.latitude+"N/"+-point.longitude+"W</b><br>Track "+track.identifier);
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
    if ($('body').data('theme') === 'dark') {
        var OpenStreetMap_Mapnik = L.tileLayer('https://cartodb-basemaps-{s}.global.ssl.fastly.net/dark_all/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);
    } else {
        var OpenStreetMap_Mapnik = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);
    }

    //Get tracks
    let endpoint = "https://tracks.ganderoceanic.ca/data"
    const response = await fetch(endpoint)

    //Create data object
    const data = await response.json()

    //Go through each NAT Track
    data.forEach(track => {
        //Create array of points latitudes/longitudes
        let pointsLatLon = []
        track.route.forEach(point => {
            //Add point to array
            pointsLatLon.push([point.latitude, point.longitude,])
            //Create map marker
            createMapTrackPointMarker(point, track, map)
        })

        //Get colour for the polyline depending on track direction
        let colour = '#00000';
        if (track.direction == 1) {
            colour = 'rgba(28, 95, 201, 0.4)'; // #1c5fc9 with 40% opacity
        } else {
            colour = 'rgba(201, 45, 28, 0.4)'; // #c92d1c with 40% opacity
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
    if ($('body').data('theme') === 'dark') {
        var OpenStreetMap_Mapnik = L.tileLayer('https://cartodb-basemaps-{s}.global.ssl.fastly.net/dark_all/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);
    } else {
        var OpenStreetMap_Mapnik = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);
    }

    //Get tracks
    let endpoint = "https://tracks.ganderoceanic.ca/event"
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
            colour = 'rgba(28, 95, 201, 0.4)'; // #1c5fc9 with 40% opacity
        } else {
            colour = 'rgba(201, 45, 28, 0.4)'; // #c92d1c with 40% opacity
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

//Create concorde track map
async function createConcordeTrackMap()
{
    //Create map
    const map = L.map('map', { minZoom: 4, maxZoom: 7 }).setView([52, -35], 1);

    //Define table
    let table = $("#natTrackTable")

    //Create OSM Layer
    if ($('body').data('theme') === 'dark') {
        var OpenStreetMap_Mapnik = L.tileLayer('https://cartodb-basemaps-{s}.global.ssl.fastly.net/dark_all/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);
    } else {
        var OpenStreetMap_Mapnik = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);
    }

    //Get tracks
    let endpoint = "https://tracks.ganderoceanic.ca/concorde"
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
            colour = 'rgba(28, 95, 201, 0.4)'; // #1c5fc9 with 40% opacity
        } else {
            colour = 'rgba(201, 45, 28, 0.4)'; // #c92d1c with 40% opacity
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
            pointsText.push(" " + point.name + " (" + point.latitude + "N " + point.longitude + "W)")
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
async function createMap(planes, eggx, czqo, nat, kzny, lppo) {
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
            iconAnchor: [0,0]
        });
       var marker = L.marker([plane.latitude, plane.longitude], {rotationAngle: plane.heading, icon:markerIcon}).addTo(map);
       marker.bindPopup(`<h4>${plane['callsign']}</h4><br>${plane['name']} ${plane['cid']}<br>${plane['flight_plan'] ? plane['flight_plan']['departure'] : ''} to ${plane['flight_plan'] ? plane['flight_plan']['arrival'] : ''}<br>${plane['flight_plan'] ? plane['flight_plan']['aircraft'] : ''}`);
    });

    // Add tracks
    let endpoint = "https://nattrak.vatsim.net/api/tracks";
    const response = await fetch(endpoint);
    const data = await response.json();

    // Function to convert lat/lon (e.g., "59/20") to decimal
    function parseLatLon(coord) {
        let [lat, lon] = coord.split("/").map(Number);
    
        // Check if the longitude is west, and if so, make it negative
        if (lon < 0) {
            lon = -lon;  // Make sure it's negative
        } else if (lon > 180) {
            lon -= 360;  // Correct for longitudes over 180
        }
    
        // If the longitude value appears to be incorrectly positive, correct it
        return [lat, -lon];
    }

    // Process each NAT Track
//     data.forEach(track => {
//     let pointsLatLon = [];
//     let routePoints = track.last_routeing.split(" "); // Split by space

//     routePoints.forEach(point => {
//         if (point.includes("/")) { // Check if it's a lat/lon coordinate
//             let latLon = parseLatLon(point);  // Convert lat/lon to decimal
//             pointsLatLon.push(latLon);
//             createMapTrackPointMarker({ latitude: latLon[0], longitude: latLon[1] }, track, map);
//         } else {
//             // check pointsGander/pointsShanwick const variables to find
//         }
//     });

//     // Determine polyline color based on track direction
//     let colour = '#000000'; // Default black
//     if (track.identifier < "M") {
//         colour = '#1c5fc9'; // Eastbound (A-M)
//     } else {
//         colour = '#c92d1c'; // Westbound (N-Z)
//     }

//     // Create and add polyline to map
//     let line = new L.Polyline(pointsLatLon, {
//         color: colour,
//         weight: 2,
//         opacity: 1,
//         smoothFactor: 1
//     }).addTo(map);
// });

    //Add Gander Info if Online
    if (czqo || nat) {
        var ganderOca = L.polygon([
            [45.0, -30.0],
            [45.0, -40.0],
            [44.5, -40.0],
            [44.5, -50.0],
            [51.0, -50.0],
            [53.0, -54.0],
            [53.8, -55.0],
            [57.0, -59.0],
            [58.5, -60.4],
            [61.0, -63.0],
            [64.0, -63.0],
            [65.0, -60.0],
            [65.0, -57.8],
            [63.5, -55.7],
            [63.5, -39.0],
            [61.0, -30.0],
        ], {
            color: '#288a3a',
            fillColor: '#288a3a',
        }).addTo(map).bindPopup("Gander OCA online")
        

        const Gander = [
        ];
        L.polyline(Gander, { color: '#777', weight: 0.5 }).addTo(map);
    }

    //Add Shanwick Info if Online
    if (eggx || nat) {
        var shanwichOca = L.polygon([
            [45.0, -30.0],
            [61.0, -30.0],
            [61.0, -10.0],
            [57.0, -10.0],
            [57.0, -15.0],
            [49.0, -15.0],
            [48.5, -8.0],
            [45.0, -8.0]
        ], {
            color: '#9752ff',
            fillColor: '#9752ff',
        }).addTo(map).bindPopup("Shanwick OCA online (Partnership Position)")
        

        const Shanwick = [
        ];
        L.polyline(Shanwick, { color: '#777', weight: 0.5 }).addTo(map);
    }

    //Add New York Info if Online
    if (kzny) {
        var nycOca = L.polygon([
            [41.6, -67.0],
            [42.4, -61.2],
            [43.1, -57.9],
            [43.6, -55.8],
            [43.8, -54.9],
            [44.5, -50.0],
            [44.5, -40.0],
            [22.3, -40.0],
            [18.0, -45.0],
            [18.0, -61.5],
            [20.0, -61.9],
            [21.4, -63.4],
            [22.0, -64.0],
            [22.1, -65.1],
            [22.0, -66.7],
            [21.2, -67.7],
            [25.0, -68.5],
            [25.0, -73.2],
            [27.8, -74.8],
            [27.8, -76.3],
            [28.2, -76.4],
            [29.8, -76.9],
            [30.0, -77.0],
            [31.6, -77.0],
            [32.0, -77.0],
            [32.3, -77.0],
            [33.0, -76.8],
            [33.4, -76.5],
            [34.6, -75.7],
            [35.3, -75.2],
            [35.5, -74.9],
            [36.8, -74.6],
            [37.1, -74.7],
            [38.5, -74.0],
            [38.7, -73.9],
            [39.0, -73.7],
            [39.7, -73.2],
            [39.7, -73.2],
            [39.9, -73.0],
            [40.2, -72.8],
            [40.1, -72.5],
            [40.6, -70.9],
            [40.9, -69.3],
            [41.0, -69.0]
        ], {
            color: '#9752ff',
            fillColor: '#9752ff',
        }).addTo(map).bindPopup("New York OCA online (Partnership Postion)");
        

        const NewYork = [
        ];
        L.polyline(NewYork, { color: 'red', weight: 0.5 }).addTo(map);
    }



    // ALL THE DOMESTIC SECTORS TIME
    // BIRD FIR
    // if (kzny) {
    //     var birdFIR = L.polygon([
    //         [66.8, -30.0],
    //         [66.9, -31.0],
    //         [68.3, -40.0],
    //         [70.3, -50.0],
    //         [70.5, -64.0],
    //         [65.0, -57.8],
    //         [63.5, -55.7],
    //         [63.5, -39.0],
    //         [61.0, -30.0],
    //         [61.0, -10.0],
    //         [60.7, -10.0],
    //         [61.0, -7.0],
    //         [61.0, -5.5],
    //         [61.0, 0.0],
    //         [61.5, 0.0],
    //         [63.0, 0.0],
    //         [63.3, 0.0],
    //         [65.8, 0.0],
    //         [66.8, -10.0],
    //         [66.8, -11.0],
    //         [66.8, -15.2],
    //         [66.8, -23.0],
    //         [66.8, -26.0]
    //     ], {
    //         color: '#6b6b6b',
    //         fillColor: '#6b6b6b',
    //     }).addTo(map).bindPopup("Reykjavik Domestic Online (BIRD)");
    // }

    // // EGPX FIR
    // if (kzny) {
    //     var egpxFIR = L.polygon([
    //         [60.7, -10.0],
    //         [61.0, -7.0],
    //         [61.0, -5.5],
    //         [61.0, -5.0],
    //         [61.0, -4.0],
    //         [61.0, 0.0],
    //         [60.0, 0.0],
    //         [59.1, 1.7],
    //         [58.5, 2.6],
    //         [58.4, 2.8],
    //         [58.3, 3.0],
    //         [57.6, 4.1],
    //         [57.3, 4.5],
    //         [56.6, 3.6],
    //         [57.2, -1.9],
    //         [57.4, -4.2],
    //         [57.2, -4.5],
    //         [57.3, -5.6],
    //         [58.0, -6.5],
    //         [58.9, -8.3],
    //         [59.3, -10.0],
    //         [60.7, -10.0],
    //     ], {
    //         color: '#6b6b6b',
    //         fillColor: '#6b6b6b',
    //     }).addTo(map).bindPopup("Scottish Domestic Online (EGPX)");

    //     var egpxFIR = L.polygon([
    //         [54.5, -3.6],
    //         [54.2, -4.3],
    //         [54.2, -4.3],
    //         [54.2, -4.3],
    //         [54.2, -4.3],
    //         [54.2, -4.3],
    //         [54.2, -4.4],
    //         [54.2, -4.4],
    //         [54.2, -4.4],
    //         [54.2, -4.4],
    //         [54.2, -4.4],
    //         [54.2, -4.8],
    //         [54.3, -5.0],
    //         [54.1, -5.2],
    //         [54.1, -5.0],
    //         [54.1, -5.0],
    //         [54.1, -5.1],
    //         [54.1, -5.1],
    //         [54.0, -5.1],
    //         [53.8, -5.2],
    //         [53.8, -5.5],
    //         [53.9, -5.5],
    //         [54.1, -5.5],
    //         [54.2, -5.6],
    //         [54.4, -6.8],
    //         [55.3, -6.9],
    //         [55.4, -7.3],
    //         [55.3, -8.3],
    //         [54.8, -9.0],
    //         [54.6, -10.0],
    //         [56.6, -10.0],
    //         [59.3, -10.0],
    //         [58.9, -8.3],
    //         [58.0, -6.5],
    //         [57.3, -5.6],
    //         [56.3, -5.1],
    //         [55.9, -5.4],
    //         [55.6, -5.7]
    //     ], {
    //         color: '#6b6b6b',
    //         fillColor: '#6b6b6b',
    //     }).addTo(map).bindPopup("Scottish Domestic Online (EGPX)");
    // }

    // // EISN FIR
    // if (kzny) {
    //     var eisnFIR = L.polygon([
    //         [57.0, -15.0],
    //         [57.0, -10.0],
    //         [56.6, -10.0],
    //         [54.6, -10.0],
    //         [54.8, -9.0],
    //         [55.3, -8.3],
    //         [55.4, -7.8],
    //         [55.4, -7.3],
    //         [55.4, -7.1],
    //         [55.3, -6.9],
    //         [55.2, -7.1],
    //         [55.1, -7.2],
    //         [54.9, -7.5],
    //         [54.9, -7.6],
    //         [54.4, -8.2],
    //         [54.2, -6.8],
    //         [53.9, -5.5],
    //         [52.3, -5.5],
    //         [52.0, -6.1],
    //         [51.4, -7.2],
    //         [51.0, -8.0],
    //         [48.5, -8.0],
    //         [49.0, -15.0],
    //     ], {
    //         color: '#6b6b6b',
    //         fillColor: '#6b6b6b',
    //     }).addTo(map).bindPopup("Shannon Domestic Online (EISN)");
    // }

    // // LFRR FIR
    // if (kzny) {
    //     var lfrrFIR = L.polygon([
    //         [47.2, -0.3],
    //         [46.5, -0.3],
    //         [46.5, -1.6],
    //         [43.6, -1.8],
    //         [44.3, -4.0],
    //         [45.0, -8.0],
    //         [48.8, -8.0],
    //         [49.6, -8.0],
    //         [49.6, -6.9],
    //         [49.5, -4.9],
    //         [49.6, -4.5],
    //         [49.6, -4.2],
    //         [49.8, -3.2],
    //         [49.8, -3.0],
    //         [49.9, -2.5],
    //         [50.0, -2.0],
    //         [50.0, -0.3],
    //         [50.4, 0.8],
    //         [50.0, 1.3],
    //         [49.9, 1.4],
    //         [49.4, 2.1],
    //         [48.6, 3.0],
    //         [48.1, 2.6],
    //         [48.1, 1.8],
    //         [47.6, 1.6],
    //         [47.2, 1.5]
    //     ], {
    //         color: '#6b6b6b',
    //         fillColor: '#6b6b6b',
    //     }).addTo(map).bindPopup("Breast Domestic Control Online (LFRR)");
    // }

    // // LECM FIR
    // if (kzny) {
    //     var lecmFIR = L.polygon([
    //         [42.8, -0.6],
    //         [43.0, -0.8],
    //         [43.0, -0.9],
    //         [43.1, -1.3],
    //         [43.2, -1.3],
    //         [43.1, -1.4],
    //         [43.2, -1.4],
    //         [43.3, -1.6],
    //         [43.3, -1.7],
    //         [43.4, -1.8],
    //         [43.6, -1.8],
    //         [44.3, -4.0],
    //         [45.0, -8.0],
    //         [45.0, -13.0],
    //         [43.0, -13.0],
    //         [42.0, -10.0],
    //         [41.9, -8.9],
    //         [41.9, -8.7],
    //         [42.0, -8.7],
    //         [42.1, -8.7],
    //         [42.1, -8.6],
    //         [42.1, -8.5],
    //         [42.1, -8.3],
    //         [42.1, -8.2],
    //         [42.1, -8.1],
    //         [42.0, -8.1],
    //         [41.9, -8.2]
    //     ], {
    //         color: '#6b6b6b',
    //         fillColor: '#6b6b6b',
    //     }).addTo(map).bindPopup("Madrid Control Online (LECM)");
    // }

    // // LPPO FIR
    // if (kzny) {
    //     var lppoOca = L.polygon([
    //         [45.0, -40.0],
    //         [45.0, -13.0],
    //         [43.0, -13.0],
    //         [42.0, -15.0],
    //         [36.5, -15.0],
    //         [34.3, -17.8],
    //         [34.0, -18.0],
    //         [33.8, -18.1],
    //         [33.6, -18.3],
    //         [33.3, -18.3],
    //         [33.1, -18.3],
    //         [32.8, -18.3],
    //         [32.6, -18.2],
    //         [32.3, -18.1],
    //         [32.1, -18.0],
    //         [31.9, -17.8],
    //         [31.7, -17.5],
    //         [31.7, -17.4],
    //         [30.0, -20.0],
    //         [30.0, -20.4],
    //         [30.0, -25.0],
    //         [24.0, -25.0],
    //         [17.0, -37.5],
    //         [22.3, -40.0]
    //     ], {
    //         color: '#6b6b6b',
    //         fillColor: '#6b6b6b',
    //     }).addTo(map).bindPopup("Santa Maria OCA Online (LPPO Oceanic)");
    // }

    // // TTZO FIR
    // if (kzny) {
    //     var ttzoOca = L.polygon([
    //         [22.3, -40.0],
    //         [17.0, -37.5],
    //         [13.5, -37.5],
    //         [10.0, -48.0],
    //         [9.3, -54.0],
    //         [8.9, -57.0],
    //         [18.0, -57.0],
    //         [18.0, -45.0]
    //     ], {
    //         color: '#6b6b6b',
    //         fillColor: '#6b6b6b',
    //     }).addTo(map).bindPopup("Piarco OCA Online (TTZO Oceanic)");
    // }

    // // TTZP FIR
    // if (kzny) {
    //     var ttzpFIR = L.polygon([
    //         [15.0, -65.0],
    //         [15.0, -63.3],
    //         [15.3, -63.0],
    //         [17.4, -63.0],
    //         [18.0, -62.0],
    //         [18.0, -57.0],
    //         [8.9, -57.0],
    //         [8.9, -59.9],
    //         [10.0, -61.5],
    //         [10.0, -61.9],
    //         [10.1, -62.1],
    //         [10.7, -61.8],
    //         [11.0, -62.5]
    //     ], {
    //         color: '#6b6b6b',
    //         fillColor: '#6b6b6b',
    //     }).addTo(map).bindPopup("Piarco Domestic Online (TTZP)");
    // }

    // // TJZS FIR
    // if (kzny) {
    //     var tjzsFIR = L.polygon([
    //         [19.7, -69.2],
    //         [20.5, -68.3],
    //         [21.2, -67.7],
    //         [22.0, -66.7],
    //         [22.1, -65.1],
    //         [22.0, -64.0],
    //         [21.4, -63.4],
    //         [20.0, -61.9],
    //         [18.0, -61.5],
    //         [18.0, -62.0],
    //         [17.4, -63.0],
    //         [15.3, -63.0],
    //         [15.0, -63.3],
    //         [15.0, -65.0],
    //         [15.7, -67.1],
    //         [16.0, -68.0],
    //         [19.0, -68.0]
    //     ], {
    //         color: '#6b6b6b',
    //         fillColor: '#6b6b6b',
    //     }).addTo(map).bindPopup("San Juan Domestic Online (TJZS)");
    // }

    // // KZMO FIR
    // if (kzny) {
    //     var kzmoOca = L.polygon([
    //         [27.8, -74.8],
    //         [25.0, -73.2],
    //         [25.0, -72.6],
    //         [25.0, -68.5],
    //         [21.2, -67.7],
    //         [21.1, -67.8],
    //         [20.5, -68.3],
    //         [19.7, -69.2],
    //         [20.4, -70.5],
    //         [20.4, -71.7],
    //         [20.4, -71.7],
    //         [20.4, -72.0],
    //         [20.4, -73.0],
    //         [20.0, -73.3],
    //         [22.0, -75.2],
    //         [22.6, -76.0],
    //         [24.0, -78.0],
    //         [24.1, -78.1],
    //         [24.6, -77.9],
    //         [24.8, -77.8],
    //         [24.9, -77.7],
    //         [25.1, -77.8],
    //         [25.6, -77.8],
    //         [26.0, -78.5],
    //         [26.1, -78.6],
    //         [26.5, -78.6],
    //         [27.0, -78.3],
    //         [27.5, -78.1],
    //         [27.5, -77.0],
    //         [28.2, -76.4],
    //         [27.8, -76.3]
    //     ], {
    //         color: '#6b6b6b',
    //         fillColor: '#6b6b6b',
    //     }).addTo(map).bindPopup("Miami OCA Online (KZMO Oceanic)");
    // }

    // // KZMA FIR
    // if (kzny) {
    //     var kzmaFIR = L.polygon([
    //         [27.5, -78.1],
    //         [27.0, -78.3],
    //         [26.5, -78.6],
    //         [26.1, -78.6],
    //         [26.0, -78.5],
    //         [25.6, -77.8],
    //         [25.1, -77.8],
    //         [24.9, -77.7],
    //         [24.8, -77.8],
    //         [24.6, -77.9],
    //         [24.1, -78.1],
    //         [24.0, -78.0],
    //         [24.0, -80.0],
    //         [24.0, -81.3],
    //         [24.0, -85.0],
    //         [25.0, -85.0],
    //         [26.2, -85.1],
    //         [26.6, -85.4],
    //         [27.0, -86.0],
    //         [27.5, -85.3],
    //         [28.0, -85.0],
    //         [28.1, -84.6],
    //         [28.2, -84.5],
    //         [28.6, -84.0],
    //         [28.4, -83.5],
    //         [28.2, -83.2],
    //         [28.0, -82.9],
    //         [28.0, -82.4],
    //         [28.0, -82.2],
    //         [28.2, -81.9],
    //         [29.0, -81.7],
    //         [28.3, -81.6],
    //         [28.6, -81.0],
    //         [28.7, -81.0],
    //         [28.7, -81.0],
    //         [29.0, -80.7],
    //         [29.0, -80.2],
    //         [29.7, -80.1],
    //         [30.1, -79.3],
    //         [30.2, -79.2],
    //         [30.0, -77.0],
    //         [29.8, -76.9],
    //         [28.2, -76.4],
    //         [27.5, -77.0],
    //         [27.5, -78.1]
    //     ], {
    //         color: '#6b6b6b',
    //         fillColor: '#6b6b6b',
    //     }).addTo(map).bindPopup("Miami Domestic Online (KZMA)");
    // }

    // // KZJX FIR
    // if (kzny) {
    //     var kzjxFIR = L.polygon([
    //         [29.0, -80.2],
    //         [28.9, -80.7],
    //         [28.7, -81.0],
    //         [28.7, -81.0],
    //         [28.6, -81.0],
    //         [28.3, -81.6],
    //         [29.0, -81.7],
    //         [28.2, -81.9],
    //         [28.0, -82.2],
    //         [27.9, -82.4],
    //         [27.9, -82.9],
    //         [28.2, -83.2],
    //         [28.4, -83.5],
    //         [28.4, -83.5],
    //         [28.6, -84.0],
    //         [28.2, -84.5],
    //         [28.1, -84.6],
    //         [28.0, -85.0],
    //         [27.5, -85.3],
    //         [27.0, -86.0],
    //         [27.5, -87.7],
    //         [28.1, -88.0],
    //         [30.2, -88.0],
    //         [30.5, -87.9],
    //         [30.6, -87.9],
    //         [30.7, -87.8],
    //         [30.7, -87.7],
    //         [30.8, -87.7],
    //         [30.9, -87.7],
    //         [31.3, -87.4],
    //         [31.5, -87.0],
    //         [31.8, -85.6],
    //         [31.5, -85.3],
    //         [31.7, -84.2],
    //         [31.7, -84.1],
    //         [31.7, -83.1],
    //         [32.2, -82.3],
    //         [32.6, -81.9],
    //         [32.7, -81.9],
    //         [33.7, -81.6],
    //         [34.4, -81.3],
    //         [34.7, -80.5],
    //         [34.9, -80.1],
    //         [34.9, -79.9],
    //         [34.4, -79.3],
    //         [34.4, -78.8],
    //         [33.0, -76.8],
    //         [32.3, -77.0],
    //         [30.0, -77.0],
    //         [30.2, -79.2],
    //         [30.1, -79.3],
    //         [29.8, -79.9],
    //         [29.7, -80.1],
    //         [29.0, -80.2]
    //     ], {
    //         color: '#6b6b6b',
    //         fillColor: '#6b6b6b',
    //     }).addTo(map).bindPopup("Jacksonville Domestic Online (KZJX)");
    // }

    // // KZDC FIR
    // if (kzny) {
    //     var kzdcFIR = L.polygon([
    //         [39.2, -80.4],
    //         [39.2, -79.9],
    //         [39.4, -79.5],
    //         [39.9, -77.9],
    //         [39.9, -77.8],
    //         [39.8, -77.6],
    //         [39.8, -77.4],
    //         [39.7, -77.4],
    //         [39.7, -77.4],
    //         [39.7, -77.4],
    //         [39.6, -77.2],
    //         [39.6, -77.2],
    //         [39.5, -77.2],
    //         [39.5, -77.2],
    //         [39.5, -77.2],
    //         [39.5, -77.1],
    //         [39.5, -77.1],
    //         [39.4, -77.0],
    //         [39.4, -76.9],
    //         [39.4, -76.8],
    //         [39.4, -76.7],
    //         [39.4, -76.3],
    //         [39.6, -76.0],
    //         [39.6, -75.9],
    //         [39.7, -75.9],
    //         [39.8, -75.8],
    //         [39.8, -75.7],
    //         [39.9, -75.7],
    //         [39.9, -75.7],
    //         [40.0, -75.5],
    //         [40.1, -75.3],
    //         [40.1, -75.0],
    //         [40.1, -74.9],
    //         [40.1, -74.9],
    //         [40.1, -74.8],
    //         [40.1, -74.8],
    //         [40.1, -74.8],
    //         [40.1, -74.8],
    //         [40.0, -74.7],
    //         [39.9, -74.7],
    //         [40.2, -74.0],
    //         [40.2, -73.7],
    //         [39.9, -73.7],
    //         [39.7, -73.2],
    //         [39.0, -73.7],
    //         [38.7, -73.9],
    //         [38.5, -74.0],
    //         [37.1, -74.7],
    //         [36.8, -74.6],
    //         [35.5, -74.9],
    //         [35.3, -75.2],
    //         [34.6, -75.7],
    //         [33.4, -76.5],
    //         [33.0, -76.8],
    //         [34.4, -78.8],
    //         [34.4, -79.3],
    //         [34.9, -79.9],
    //         [34.9, -80.1],
    //         [35.1, -80.0],
    //         [35.4, -79.8],
    //         [36.1, -79.7],
    //         [36.2, -80.0],
    //         [37.3, -80.6],
    //         [37.3, -80.7],
    //         [37.5, -80.8],
    //         [38.0, -80.7],
    //         [38.8, -80.6],
    //         [39.2, -80.4]
    //     ], {
    //         color: '#6b6b6b',
    //         fillColor: '#6b6b6b',
    //     }).addTo(map).bindPopup("Washington DC Domestic Online (KZDC)");
    // }

    // // KZBW FIR
    // if (kzny) {
    //     var kzbwFIR = L.polygon([
    //         [43.6, -76.8],
    //         [44.1, -76.4],
    //         [44.2, -76.3],
    //         [44.2, -76.3],
    //         [44.2, -76.3],
    //         [44.2, -76.2],
    //         [44.2, -76.2],
    //         [44.3, -76.2],
    //         [44.3, -76.1],
    //         [44.3, -76.1],
    //         [44.3, -76.0],
    //         [44.3, -75.9],
    //         [44.4, -75.8],
    //         [44.5, -75.8],
    //         [44.7, -75.5],
    //         [44.7, -75.7],
    //         [44.8, -75.8],
    //         [44.9, -74.9],
    //         [45.1, -75.0],
    //         [45.1, -74.8],
    //         [45.2, -74.6],
    //         [45.0, -74.3],
    //         [45.0, -71.5],
    //         [45.3, -71.3],
    //         [45.3, -71.0],
    //         [45.7, -70.5],
    //         [45.9, -70.3],
    //         [46.3, -70.2],
    //         [46.4, -70.1],
    //         [46.7, -70.0],
    //         [47.5, -69.2],
    //         [47.4, -69.0],
    //         [47.3, -68.6],
    //         [47.3, -68.5],
    //         [47.4, -68.5],
    //         [47.4, -68.4],
    //         [47.4, -68.4],
    //         [47.4, -68.4],
    //         [47.4, -68.4],
    //         [47.4, -68.3],
    //         [47.5, -68.3],
    //         [47.5, -68.2],
    //         [47.5, -68.2],
    //         [47.5, -68.1],
    //         [47.5, -68.1],
    //         [47.5, -68.1],
    //         [47.5, -68.1],
    //         [47.5, -68.0],
    //         [47.5, -68.0],
    //         [47.5, -68.0],
    //         [47.5, -67.9],
    //         [46.8, -67.1],
    //         [46.8, -67.1],
    //         [46.7, -67.1],
    //         [46.7, -67.1],
    //         [46.6, -67.2],
    //         [46.6, -67.2],
    //         [46.6, -67.2],
    //         [46.6, -67.3],
    //         [46.5, -67.3],
    //         [46.2, -67.2],
    //         [46.1, -67.2],
    //         [45.8, -67.6],
    //         [45.8, -67.8],
    //         [45.6, -67.8],
    //         [44.9, -67.0],
    //         [41.6, -67.0],
    //         [41.0, -69.0],
    //         [40.9, -69.3],
    //         [40.6, -70.9],
    //         [40.1, -72.5],
    //         [40.2, -72.8],
    //         [39.99, -73.0],
    //         [39.7, -73.2],
    //         [39.7, -73.2],
    //         [39.9, -73.7],
    //         [40.2, -73.7],
    //         [40.3, -73.6],
    //         [40.5, -73.5],
    //         [40.6, -73.4],
    //         [40.8, -73.3],
    //         [40.8, -73.4],
    //         [40.9, -73.4],
    //         [41.1, -73.6],
    //         [41.0, -73.9],
    //         [41.3, -73.9],
    //         [41.3, -74.0],
    //         [41.3, -74.1],
    //         [41.4, -74.2],
    //         [41.5, -74.3],
    //         [41.5, -74.4],
    //         [41.6, -74.7],
    //         [41.7, -74.8],
    //         [41.8, -74.8],
    //         [41.8, -74.9],
    //         [41.9, -75.1],
    //         [41.9, -75.1],
    //         [42.0, -75.3],
    //         [42.1, -75.6],
    //         [42.3, -76.0],
    //         [42.4, -76.2],
    //         [42.6, -76.8],
    //         [42.7, -76.7],
    //         [42.8, -76.7],
    //         [42.9, -76.7],
    //         [43.1, -76.8],
    //         [43.6, -76.8]
    //     ], {
    //         color: '#6b6b6b',
    //         fillColor: '#6b6b6b',
    //     }).addTo(map).bindPopup("Boston Domestic Online (KZBW)");
    // }

    // // CZQM FIR
    // if (kzny) {
    //     var czqmFIR = L.polygon([
    //         [43.6, -55.8],
    //         [43.1, -57.9],
    //         [42.4, -61.2],
    //         [41.6, -67.0],
    //         [44.9, -67.0],
    //         [45.6, -67.8],
    //         [45.8, -67.8],
    //         [45.8, -67.6],
    //         [46.1, -67.2],
    //         [46.2, -67.2],
    //         [46.5, -67.3],
    //         [46.6, -67.2],
    //         [46.6, -67.2],
    //         [46.6, -67.2],
    //         [46.7, -67.1],
    //         [46.7, -67.1],
    //         [46.7, -67.1],
    //         [46.8, -67.1],
    //         [46.8, -67.1],
    //         [46.9, -67.0],
    //         [46.9, -67.0],
    //         [47.0, -67.0],
    //         [47.0, -67.1],
    //         [47.1, -67.1],
    //         [47.1, -67.1],
    //         [47.2, -67.1],
    //         [47.2, -67.1],
    //         [47.3, -67.2],
    //         [47.3, -67.2],
    //         [47.3, -67.2],
    //         [47.4, -67.3],
    //         [47.4, -67.3],
    //         [47.4, -67.3],
    //         [47.4, -67.3],
    //         [47.5, -67.3],
    //         [47.5, -67.4],
    //         [47.5, -67.5],
    //         [47.5, -67.5],
    //         [47.5, -67.6],
    //         [47.5, -67.6],
    //         [47.5, -67.6],
    //         [47.5, -67.7],
    //         [47.5, -67.8],
    //         [47.5, -67.9],
    //         [47.5, -68.0],
    //         [47.5, -68.1],
    //         [47.5, -68.2],
    //         [47.5, -68.3],
    //         [47.5, -68.4],
    //         [47.5, -68.5],
    //         [47.6, -69.0],
    //         [48.0, -69.0],
    //         [48.2, -69.3],
    //         [48.9, -69.5],
    //         [49.2, -68.7],
    //         [51.0, -68.7],
    //         [52.2, -64.3],
    //         [51.4, -64.0],
    //         [50.8, -62.1],
    //         [49.5, -61.0],
    //         [49.3, -61.0],
    //         [48.5, -62.0],
    //         [45.6, -56.5],
    //         [44.4, -56.1],
    //         [43.6, -55.8]
    //     ], {
    //         color: '#6b6b6b',
    //         fillColor: '#6b6b6b',
    //     }).addTo(map).bindPopup("Gander Domestic Online (CZQM)");
    // }

    // // CZQX FIR
    // if (kzny) {
    //     var czqxFIR = L.polygon([
    //         [53.8, -55.0],
    //         [53.0, -54.0],
    //         [51.0, -50.0],
    //         [44.5, -50.0],
    //         [43.8, -54.9],
    //         [43.6, -55.8],
    //         [44.4, -56.1],
    //         [45.6, -56.5],
    //         [48.5, -62.0],
    //         [49.3, -61.0],
    //         [49.5, -61.0],
    //         [50.8, -62.1],
    //         [51.4, -64.0],
    //         [52.2, -64.3],
    //         [51.0, -68.7],
    //         [53.5, -68.7],
    //         [55.3, -66.7],
    //         [56.6, -65.3],
    //         [57.6, -64.0],
    //         [58.5, -63.0],
    //         [61.0, -63.0],
    //         [57.0, -59.0],
            
    //     ], {
    //         color: '#6b6b6b',
    //         fillColor: '#6b6b6b',
    //     }).addTo(map).bindPopup("Gander Domestic Online (CZQX)");
    // }

    // // CZUL FIR
    // if (kzny) {
    //     var czulFIR = L.polygon([
    //         [45.8, -76.3],
    //         [46.0, -76.9],
    //         [46.1, -77.2],
    //         [46.9, -77.2],
    //         [47.1, -77.5],
    //         [47.8, -78.6],
    //         [47.8, -78.6],
    //         [47.8, -78.7],
    //         [47.8, -78.8],
    //         [47.8, -78.9],
    //         [47.8, -79.0],
    //         [47.8, -79.2],
    //         [47.8, -79.3],
    //         [47.9, -79.3],
    //         [47.9, -79.4],
    //         [48.0, -79.5],
    //         [48.0, -79.5],
    //         [48.1, -79.5],
    //         [48.1, -79.6],
    //         [48.2, -79.6],
    //         [48.3, -79.5],
    //         [48.3, -79.5],
    //         [48.4, -79.5],
    //         [48.4, -79.4],
    //         [48.5, -79.4],
    //         [48.5, -79.3],
    //         [48.5, -79.2],
    //         [48.6, -79.1],
    //         [48.6, -79.1],
    //         [48.6, -79.0],
    //         [49.0, -79.0],
    //         [53.5, -80.0],
    //         [62.8, -80.0],
    //         [65.0, -68.0],
    //         [65.0, -60.0],
    //         [64.0, -63.0],
    //         [61.0, -63.0],
    //         [58.5, -60.4],
    //         [57.5, -64.0],
    //         [55.4, -64.0],
    //         [55.1, -65.1],
    //         [54.4, -65.3],
    //         [53.7, -64.9],
    //         [52.2, -64.3],
    //         [51.4, -64.0],
    //         [50.8, -62.1],
    //         [50.8, -60.0],
    //         [51.3, -59.5],
    //         [51.6, -59.5],
    //         [52.2, -58.1],
    //         [51.7, -57.0],
    //         [51.3, -57.0],
    //         [51.0, -58.0],
    //         [49.5, -61.0],
    //         [49.3, -61.0],
    //         [48.5, -62.0],
    //         [47.8, -64.6],
    //         [48.0, -65.9],
    //         [48.1, -66.0],
    //         [48.2, -66.0],
    //         [48.2, -66.0],
    //         [48.3, -66.1],
    //         [48.3, -66.2],
    //         [48.3, -66.4],
    //         [48.3, -66.5],
    //         [48.3, -66.6],
    //         [48.3, -66.8],
    //         [48.2, -66.8],
    //         [48.2, -66.9],
    //         [48.1, -66.9],
    //         [48.0, -66.9],
    //         [47.9, -66.9],
    //         [47.9, -66.9],
    //         [47.7, -68.0],
    //         [47.5, -68.0],
    //         [47.5, -68.1],
    //         [47.5, -68.2],
    //         [47.5, -68.2],
    //         [47.5, -68.3],
    //         [47.4, -68.4],
    //         [47.4, -68.4],
    //         [47.4, -68.5],
    //         [47.3, -68.5],
    //         [47.3, -68.6],
    //         [47.4, -69.0],
    //         [47.5, -69.2],
    //         [46.8, -70.0],
    //         [46.4, -70.1],
    //         [46.3, -70.2],
    //         [45.9, -70.2],
    //         [45.7, -70.5],
    //         [45.3, -71.0],
    //         [45.3, -71.3],
    //         [45.0, -71.5],
    //         [45.0, -74.2],
    //         [45.2, -74.6],
    //         [45.1, -74.8],
    //         [45.1, -75.0],
    //         [45.0, -74.9],
    //         [44.8, -75.8],
    //         [44.7, -75.7],
    //         [44.7, -75.5],
    //         [44.5, -75.8],
    //         [44.4, -75.8],
    //         [44.3, -76.0],
    //         [44.4, -76.0],
    //         [44.3, -76.1],
    //         [44.3, -76.1],
    //         [44.2, -76.2],
    //         [45.8, -76.3],            
    //     ], {
    //         color: '#6b6b6b',
    //         fillColor: '#6b6b6b',
    //     }).addTo(map).bindPopup("Montreal Domestic Online (CZUL)");
    // }
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

                //If it's system UI mode...
                if ($('body').data('theme') == 'system') {
                    if (window.matchMedia) {
                        if(window.matchMedia('(prefers-color-scheme: dark)').matches){
                            $("body").attr("data-theme", "dark")
                        } else {
                            $("body").attr("data-theme", "light")
                        }
                    } else {
                        $("body").attr("data-theme", "light")
                    }
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

$(document).ready(function () {
    if ($("body").data("theme") == "system") {
        if (window.matchMedia) {
            if(window.matchMedia('(prefers-color-scheme: dark)').matches){
                $("body").attr("data-theme", "dark")
            } else {
                $("body").attr("data-theme", "light")
            }
        } else {
            $("body").attr("data-theme", "light")
        }
    }
})
