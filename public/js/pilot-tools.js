/******/ (function(modules) { // webpackBootstrap
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
/******/ 	__webpack_require__.p = "/";
/******/
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 0);
/******/ })
/************************************************************************/
/******/ ({

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
function generateOcenaicClearance() {
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
  var mach = document.getElementById('machB').value;
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

  if (mach == '') {
    transcript = callsign + ', position ' + reporting + ' at ' + time + ', Flight Level ' + flightLevel + ', Estimating ' + next + ' at ' + estimating + ', ' + thereafter + ' thereafter.';
  } else {
    transcript = callsign + ', position ' + reporting + ' at ' + time + ', Flight Level ' + flightLevel + ', Mach ' + mach + ', Estimating ' + next + ' at ' + estimating + ', ' + thereafter + ' thereafter.';
  } //Display it!


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

function createNatTrakMap() {
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

  var api = "https://api.flightplandatabase.com/nav/NATS";
  var xmlHttp = new XMLHttpRequest();
  xmlHttp.open("GET", api, false);
  xmlHttp.send(null);
  var apiString = xmlHttp.responseText;
  var apiJson = JSON.parse(apiString);
  console.log(apiJson);
  var processedNats = []; //Go through all the tracks

  for (track in apiJson) {
    //Go through the tracks and only use the good ones...
    if (checkIfNatProcessed(apiJson[track].ident) == false) {
      processedNats.push(apiJson[track].ident); //Create some markers

      var fixArray = [];

      for (n in apiJson[track].route.nodes) {
        if (apiJson[track].route.eastLevels.length == 0) {
          createMarker(apiJson[track].route.nodes[n], apiJson[track].ident, 'orange');
        } else {
          createMarker(apiJson[track].route.nodes[n], apiJson[track].ident, 'blue');
        }

        fixArray.push([apiJson[track].route.nodes[n].lat, apiJson[track].route.nodes[n].lon]);
      }

      var polyline = new L.Polyline(fixArray, {
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
    }

    ;
  }

  var table = document.getElementById('tableBody');
  processedNats = [];

  for (track in apiJson) {
    if (checkIfNatProcessed(apiJson[track].ident) == false) {
      (function () {
        processedNats.push(apiJson[track].ident); //Create a row

        var row = document.createElement('tr');
        table.appendChild(row); //Get the track ID

        var identCol = document.createElement('th');
        identCol.scope = 'row';
        identCol.innerHTML = apiJson[track].ident;
        row.appendChild(identCol); //Get the fixes

        var fixArray = [];

        for (n in apiJson[track].route.nodes) {
          fixArray.push(" " + apiJson[track].route.nodes[n].ident);
        }

        var fixesCol = document.createElement('td');
        fixesCol.innerHTML = fixArray;
        row.appendChild(fixesCol); //figure out the direction and get levels

        var levelArray = [];
        var directionCol = document.createElement('td');
        var levelsCol = document.createElement('td');

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
        row.appendChild(levelsCol); //validity

        var validityCol = document.createElement('td');
        var validFrom = " " + apiJson[track].validFrom;
        var validTo = apiJson[track].validTo;
        validityCol.innerHTML = validFrom + " to " + validTo;
        row.appendChild(validityCol);
      })();
    }

    ;
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
  var markerIcon = L.icon({
    iconUrl: 'https://nesa.com.au/wp-content/uploads/2017/05/Dot-points-1.png',
    iconSize: [10, 10],
    iconAnchor: [2, 4]
  });
  var marker = L.marker([node.lat, node.lon], {
    icon: markerIcon
  }).addTo(map);
  marker.bindPopup("<b>" + node.ident + "</b><br/>" + node.type + "<br/>" + node.lat + " " + node.lon);
}

/***/ }),

/***/ 0:
/*!*******************************************!*\
  !*** multi ./resources/js/pilot-tools.js ***!
  \*******************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! C:\Users\Liesel\Documents\Git\czqo-core\resources\js\pilot-tools.js */"./resources/js/pilot-tools.js");


/***/ })

/******/ });