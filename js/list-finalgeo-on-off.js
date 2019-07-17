//Ausgabe Marker
$('#list').on("pageshow", function() {
	//check that browere givegeolocation
	// else give to user search mask

	var UserMail = window.localStorage.getItem('UserMail');
	var UserKey = window.localStorage.getItem('UserKey');
	var UserFirstname = window.localStorage.getItem('UserFirstname');
	var UserLastname = window.localStorage.getItem('UserLastname');
	var meineLatitude = window.localStorage.getItem('UserLatitude');
	var meineLongitude = window.localStorage.getItem('UserLongitude');

	var map;
	var markers;

	var self = this;
	var infowindow = null;
	$('#map-container').height($(window).height());

	//funktioniert mit unfd ohne eingeschaltetet ortung
	function showLocation(position) {
		meineLatitude = position.coords.latitude;
		meineLongitude = position.coords.longitude;
		//alert("Latitude : " + meineLatitude + " Longitude: " + meineLongitude);
	}

	function errorHandler(err) {
		if (err.code == 1) {
			alert("Error: Access is denied! - Last location used");
			var meineLatitude = window.localStorage.getItem('UserLatitude');
			var meineLongitude = window.localStorage.getItem('UserLongitude');
		} else if (err.code == 2) {
			//alert("Error: Position is unavailable! - Last location used");
			var meineLatitude = window.localStorage.getItem('UserLatitude');
			var meineLongitude = window.localStorage.getItem('UserLongitude');
		}
		alert("Latitude : " + meineLatitude + " Longitude: " + meineLongitude);
	}

	if (navigator.geolocation) {
		// timeout at 60000 milliseconds (60 seconds)
		var options = {
			timeout : 60000
		};
		navigator.geolocation.getCurrentPosition(showLocation, errorHandler, options);

	} else {
		alert("Sorry, browser does not support geolocation! - Last location used");
		var meineLatitude = window.localStorage.getItem('UserLatitude');
		var meineLongitude = window.localStorage.getItem('UserLongitude');
	}
	//alert("Latitude : " + meineLatitude + " Longitude: " + meineLongitude);

	/*
	if ((meineLatitude = 'undefind') || (!meineLatitude == 0)) {
	meineLongitude = 11.076750;
	meineLatitude = 49.452030;
	}
	alert("Latitude : " + meineLatitude + " Longitude: " + meineLongitude);
	*/

	//alert("Latitude : " + meineLatitude + " Longitude: " + meineLongitude);
	$.getJSON('php/datafetcher.php?function=getEvents&longitude=' + meineLongitude + '& latitude=' + meineLatitude + '&date=' + Date.now(), function(data) {
		// schleife mit counter kein for notwendig
		$.each(data, function(counter, daten) {
			switch (daten.t_kategorie) {
			case "Jamsession":
				color1 = './img/music_rock.png';
				defaultpic = './img/default/jam1.jpg';
				break;
			case "Karaoke":
				color1 = './img/comedyclub.png';
				defaultpic = './img/default/karaoke3.jpg';
				break;
			case "Concert":
				color1 = './img/jazzclub.png';
				defaultpic = './img/default/concert3.jpg';
				break;
			case "Event":
				color1 = './img/music_live.png';
				defaultpic = './img/default/event1.jpg';
				break;
			case "Poetry":
				color1 = './img/dragon.png';
				defaultpic = './img/default/poetry1.jpg';
				break;
			default:
				color1 = './img/magicshow.png';
				defaultpic = './img/default/show.jpg';
				break;
			}
			//alert(" id: " + (daten.location_id) + " company: " + (daten.company) + " daten.t_kategorie: " + (daten.t_kategorie) + " color: " + (color1));
			//Ausgabe des Links fÃ¼r die Listenansicht
			//todo layout zoom zu gross
			$('<li  class="ui-li-has-icon"><a href="#event-detail" data-transition="slide" onClick="javascript:sessionStorage.event=\'' + daten.location_id + '\';" class="ui-btn ui-btn-icon-right ui-icon-carat-r"><img src="' + defaultpic + '"  class="ui-li-icon ui-corner-none" />' + daten.t_date + '  , ' + daten.t_eventname + ' , ' + daten.company + ' , ' + daten.street + ' , ' + daten.city + ' , ' + daten.postcode + ' , ' + distance(meineLongitude, meineLatitude, daten.longitude, daten.latitude) + '</a></li>').appendTo('#eventlist');

		});
	});

});

//Schliessen Detail Ausgabe
$('#list').on("pagehide", function() {
	$('#eventlist').empty();
});

//Berechnung der Entfernung
var entfernungBerechnen = function(meineLongitude, meineLatitude, long1, lat1) {
	erdRadius = 40000;

	meineLongitude = meineLongitude * (Math.PI / 180);
	meineLatitude = meineLatitude * (Math.PI / 180);
	long1 = long1 * (Math.PI / 180);
	lat1 = lat1 * (Math.PI / 180);

	x0 = meineLongitude * erdRadius * Math.cos(meineLatitude);
	y0 = meineLatitude * erdRadius;

	x1 = long1 * erdRadius * Math.cos(lat1);
	y1 = lat1 * erdRadius;

	dx = x0 - x1;
	dy = y0 - y1;

	d = Math.sqrt((dx * dx) + (dy * dy));

	if (d < 1) {
		return Math.round(d * 1000) + " m";
	} else {
		return Math.round(d * 10) / 10 + " km";
	}
};

// neue berechnung
function distance(meineLongitude, meineLatitude, long1, lat1) {
	// Get numeric values out of form elements.
	var lat_1 = lat1;
	var lat_2 = meineLatitude;
	var lon_1 = long1;
	var lon_2 = meineLongitude;
	// Compute spherical coordinates
	var rho = 5000.0;
	// earth diameter in miles
	// convert latitude and longitude to spherical coordinates in radians
	// phi = 90 - latitude
	var phi_1 = (90.0 - lat_1) * Math.PI / 180.0;
	var phi_2 = (90.0 - lat_2) * Math.PI / 180.0;
	// theta = longitude
	var theta_1 = lon_1 * Math.PI / 180.0;
	var theta_2 = lon_2 * Math.PI / 180.0;
	// compute spherical distance from spherical coordinates
	// arc length = \arccos(\sin\phi\sin\phi'\cos(\theta-\theta') + \cos\phi\cos\phi')
	// distance = rho times arc length
	var d = rho * Math.acos(Math.sin(phi_1) * Math.sin(phi_2) * Math.cos(theta_1 - theta_2) + Math.cos(phi_1) * Math.cos(phi_2));
	// Display result in miles and in kilometers
	//var output = "Distance = " + d + " miles or " + 1.609344*d + " kilometers";
	var output = Math.round((1.609344 * d) * 100) / 100 + " km";
	//var output =  d + " miles";
	return output;
};