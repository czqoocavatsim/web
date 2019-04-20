/*s
Oceanic Clearance Generator Script
Written by Liesel Downes on 6 October 2018
Copyright (C) Liesel Downes 2018
github.com/lieselta
*/

/*
Format of clearance request:
CALLSIGN request clearance via Track LETTER|route ROUTE. Estimating
ENTRY at TIME. Request Flight Level FLIGHTLEVEL, Mach MACHSPEED.
(Result will say 'Readback TMI [TMI] on readback of clearance from controller.)
*/

//Generate result
function generate(){
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

//Deal with invalid submission
function invalidSubmission(errors){
    document.getElementById('errorContent').innerHTML = "";
    document.getElementById('errorA').style.display = 'block';
    for (i = 0; i < errors.length; i++) {
        document.getElementById('errorContent').innerHTML = document.getElementById('errorContent').innerHTML + '<br/>' + errors[i];
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