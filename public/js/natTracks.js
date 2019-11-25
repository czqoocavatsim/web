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
