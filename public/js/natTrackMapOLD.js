/*mapfit.apikey = "591dccc4e499ca0001a4c6a42b2b05e173834bab81a1dc8983449085";
    let map = mapfit.MapView('mapfit', {theme: 'day'});
    map.setZoom(2);
    // create marker
    position = mapfit.LatLng([50.198470, -32.708615]);
    myMarker = mapfit.Marker(position);

    //set the map center on marker position
    map.setCenter(position);

    //add marker to map
    //map.addMarker(myMarker);
    map.setRecenterButtonEnabled(true); */

    //Get tracks
    let api = "https://api.flightplandatabase.com/nav/NATS";
    let xmlHttp = new XMLHttpRequest();
    xmlHttp.open("GET", api, false);
    xmlHttp.send(null);
    let apiString = xmlHttp.responseText;
    let apiJson = JSON.parse(apiString);
    console.log(apiJson);

    var processedNats = [];
    /* let apiString2 = JSON.stringify(apiJson);
    console.log(apiJson[0].ident);
    for (i in apiJson){
        for (n in apiJson[i].route.nodes){
            console.log(apiJson[i].route.nodes[n].lat);
            position = mapfit.LatLng([apiJson[i].route.nodes[n].lat, apiJson[i].route.nodes[n].lon]);
            marker = mapfit.Marker(position);
            map.addMarker(marker);
        }
     */

    //Go through all the tracks
    for (track in apiJson){
        //Go through the tracks and only use the good ones...
        if (checkIfNatProcessed(apiJson[track].ident) == false){
            processedNats.push(apiJson[track].ident);
            //Create some markers
            /*
            let fixArray = [];
            for (n in apiJson[track].route.nodes){
                createMarker(apiJson[track].route.nodes[n], apiJson[track].ident);
                fixArray.push([apiJson[track].route.nodes[n].lat, apiJson[track].route.nodes[n].lon]);
            }
            let polyline = mapfit.Polyline(fixArray);
            if (apiJson[track].route.eastLevels.length == 0){
                polyline.setStrokeColor('orange');
            }
            map.addPolyline(polyline);*/
        };
    }

    console.log(processedNats);

    //Now lets load the table
    let table = document.getElementById('tableBody');
    processedNats = [];
    for (track in apiJson){
        if (checkIfNatProcessed(apiJson[track].ident) == false){
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
            for (n in apiJson[track].route.nodes){
                fixArray.push(" " + apiJson[track].route.nodes[n].ident);
            }
            let fixesCol = document.createElement('td');
            fixesCol.innerHTML = fixArray;
            row.appendChild(fixesCol);

            //figure out the direction and get levels
            let levelArray = [];
            let directionCol = document.createElement('td');
            let levelsCol = document.createElement('td');
            if (apiJson[track].route.eastLevels.length == 0){
                apiJson[track].route.westLevels.forEach(function(element) {
                    levelArray.push(" " + element);
                });
                directionCol.innerHTML = "West";
            }else{
                apiJson[track].route.eastLevels.forEach(function(element) {
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

    function checkIfNatProcessed(ident){
        if (processedNats.indexOf(ident) > -1){
            return true;
        }else{
            return false;
        }
    }

    function createMarker(node, trackId){
        let marker = mapfit.Marker([node.lat, node.lon]);
        let icon = mapfit.Icon();
        icon.setIconUrl('https://nesa.com.au/wp-content/uploads/2017/05/Dot-points-1.png');
        icon.setWidth(6);
        icon.setAnchorWidth(0);
        icon.setHeight(6);
        icon.setAnchorHeight(4);
        marker.setIcon(icon);
        let fixInfo = mapfit.PlaceInfo();
        fixInfo.setTitle(node.ident);
        fixInfo.setDescription("<p>" + node.type + "<br/>" + node.lat + " " + node.lon + "<br/>On Track " + trackId + "</p>");
        marker.setPlaceInfo(fixInfo);
        map.addMarker(marker);
    }