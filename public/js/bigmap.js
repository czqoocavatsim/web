var map = L.map('map').setView([53.198470, -32.708], 3);
var processedNats = [];
function createBigMap(planes, ganderControllers, shanwickControllers) {

    L.tileLayer('https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token={accessToken}', {
        attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, <a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="https://www.mapbox.com/">Mapbox</a>, NAT Track data from <a href="https://flightplandatabase.com/">Flight Plan Database</a>, Plane Icon from AccuMap Project',
        maxZoom: 100,
        zoom: 0,
        id: 'mapbox.light',
        accessToken: 'pk.eyJ1IjoiZWx0ZWNocm9uIiwiYSI6ImNqOTlydHR4czB4NG8ycWxzYXNla2pmOXcifQ.hBI3z2L84aiEDfp5H946_Q'
    }).addTo(map);

    planes.forEach(function (plane) {
        let markerIcon = L.icon({
            iconUrl: '/img/planes/base.png',
            iconSize: [30, 30],
            iconAnchor: [2,4]
        });
       var marker = L.marker([plane.latitude, plane.longitude], {rotationAngle: plane.heading, icon:markerIcon}).addTo(map);
       marker.bindPopup(`<h4>${plane.callsign}</h4><br>${plane['name']} ${plane.cid}<br>${plane['flight_plan']['departure']} to ${plane.planned_destairport}<br>${plane.planned_aircraft}`)
    });

    map.setZoom(3.6);
    console.log(ganderControllers)
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
