function createHomePageMap(planes) {
var map = L.map('map', {zoomControl: false, dragging: false, boxZoom: false, doubleClickZoom: false, scrollWheelZoom: false}).setView([53.198470, -32.708], 3);

L.tileLayer('https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token={accessToken}', {
    attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, <a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
    maxZoom: 100,
    zoom: 0,
    id: 'mapbox.dark',
    accessToken: 'pk.eyJ1IjoiZWx0ZWNocm9uIiwiYSI6ImNqOTlydHR4czB4NG8ycWxzYXNla2pmOXcifQ.hBI3z2L84aiEDfp5H946_Q'
}).addTo(map);

planes.forEach(function (plane) {
   console.log(plane);
    let markerIcon = L.icon({
        iconUrl: '/img/planes/base.png',
        iconSize: [30, 30],
        iconAnchor: [2,4]
    });
   var marker = L.marker([plane.latitude, plane.longitude], {rotationAngle: plane.heading, icon:markerIcon}).addTo(map);
   marker.bindPopup(`<h5><b>${plane.callsign}</b></h5><br/>${plane.realname}<br/>${plane.planned_depairport} to ${plane.planned_destairport}`);
});

map.setZoom(3.6);

/*var ganderOca = L.polygon([
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
ganderOca.bindPopup('Gander OCA (CZQX)');*/

}
