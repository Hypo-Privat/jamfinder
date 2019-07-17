//Ausgabe Marker
$('#map').on("pageshow", function() {
	// check that browere givegeolocation
	// else give to user search mask

	var UserMail = window.localStorage.getItem('UserMail');
	var UserKey = window.localStorage.getItem('UserKey');
	var UserFirstname = window.localStorage.getItem('UserFirstname');
	var UserLastname = window.localStorage.getItem('UserLastname');
	var meineLatitude = window.localStorage.getItem('UserLatitude');
	var meineLongitude = window.localStorage.getItem('UserLongitude');

	var map;
	var marker;
	//var meineLongitude = 11.076750, meineLatitude = 49.452030;
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
			alert("Error: Position is unavailable!- Last location used");
			var meineLatitude = window.localStorage.getItem('UserLatitude');
			var meineLongitude = window.localStorage.getItem('UserLongitude');
		}
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

	function initialize() {

		var mapOptionen = {
			zoom : 12,
			center : new Microsoft.Maps.LatLng(meineLatitude, meineLongitude),
			mapTypeId : Microsoft.Maps.MapTypeId.ROADMAP
		};

		var map = new Microsoft.Maps.Map(document.getElementById('map-container'), mapOptionen);

		// Create the search box and link it to the UI element.
		var input = document.getElementById('pac-input');
		var searchBox = new Microsoft.Maps.places.SearchBox(input);
		map.controls[Microsoft.Maps.ControlPosition.TOP_LEFT].push(input);

		// Bias the SearchBox results towards current map's viewport.
		map.addListener('bounds_changed', function() {
			searchBox.setBounds(map.getBounds());
		});

		var markers = [];
		// [START region_getplaces]
		// Listen for the event fired when the user selects a prediction and retrieve
		// more details for that place.
		searchBox.addListener('places_changed', function() {

			//alert('searchBox 1');
			var places = searchBox.getPlaces();

			if (places.length == 0) {
				return;
			}

			// For each place, get the icon, name and location.
			var bounds = new Microsoft.Maps.LatLngBounds();

			//alert('before places.forEach');
			places.forEach(function(place) {
				//alert( place.geometry.viewport);
				//alert( place.geometry.location);

				meineLatitude = place.geometry.location.lat();
				meineLongitude = place.geometry.location.lng();

				alert("Latitude : " + meineLatitude + " Longitude: " + meineLongitude);

				//setzen letzte position auf ger�t
				setLocation(meineLongitude, meineLatitude);

				$.getJSON('php/datafetcher.php?function=getEvents&longitude=' + meineLongitude + '& latitude=' + meineLatitude + '&date=' + Date.now(), function(data) {
					// schleife mit counter kein for notwendig
					$.each(data, function(counter, daten) {

						// http://mapicons.nicolasmollet.com/
						// http://mapicons.nicolasmollet.com/
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

						// alert(" id: " + (daten.location_id) + " company: " +

						var contentString = '<dl id="c2"><dt>' + daten.t_date + ' ' + daten.t_kategorie + ' ' + daten.t_eventname + '</dt><dd> <a href="' + daten.url + '" target="_blank"><img src="' + defaultpic + '" style="width: 100px;height:100px "align="middle" /></a><br>' + daten.t_text + ' <br><p><b> Location: </b>' + daten.company + '  ' + daten.city + '  ' + daten.postcode + '  ' + daten.street + '</p></dd></dl>';

						var infowindow = new Microsoft.Maps.InfoWindow({
							content : contentString
							// content : daten.company + " " + daten.comment
						});

						markers = new Microsoft.Maps.Marker({
							map : map,
							icon : color1,
							animation : Microsoft.Maps.Animation.DROP,
							position : new Microsoft.Maps.LatLng(daten.latitude, daten.longitude),
							title : daten.t_kategorie + " " + daten.company + " " + daten.url,

						});

						var LatLng = new Microsoft.Maps.LatLng(daten.latitude, daten.longitude);
						Microsoft.Maps.event.addListener(markers, 'click', function() {
							infowindow.setPosition(LatLng);
							infowindow.open(map, markers[counter]);
							// close infowindow after 10sec
							setTimeout(function() {
								infowindow.close();
							}, 5000);

						});
					});
				});

				if (place.geometry.viewport) {
					// Only geocodes have viewport.
					bounds.union(place.geometry.viewport);
				} else {
					bounds.extend(place.geometry.location);
				}
			});

			map.fitBounds(bounds);
		});

		// [END region_getplaces]
		//alert('out of searchBox');

		//setzen letzte position auf ger�t
		setLocation(meineLongitude, meineLatitude);

		$.getJSON('php/datafetcher.php?function=getEvents&longitude=' + meineLongitude + '& latitude=' + meineLatitude + '&date=' + Date.now(), function(data) {
			// schleife mit counter kein for notwendig
			$.each(data, function(counter, daten) {
				// http://mapicons.nicolasmollet.com/
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

				var contentString = '<dl id="c2"><dt>' + daten.t_date + ' ' + daten.t_kategorie + ' ' + daten.t_eventname + '</dt><dd> <a href="' + daten.url + '" target="_blank"><img src="' + defaultpic + '" style="width: 100px;height:100px "align="middle" /></a><br>' + daten.t_text + ' <br><p><b> Location: </b>' + daten.company + '  ' + daten.city + '  ' + daten.postcode + '  ' + daten.street + '</p></dd></dl>';

				var infowindow = new Microsoft.Maps.InfoWindow({
					content : contentString
					// content : daten.company + " " + daten.comment
				});

				markers = new Microsoft.Maps.Marker({
					map : map,
					icon : color1,
					animation : Microsoft.Maps.Animation.DROP,
					position : new Microsoft.Maps.LatLng(daten.latitude, daten.longitude),
					title : daten.t_kategorie + " " + daten.company + " " + daten.url,

				});

				var LatLng = new Microsoft.Maps.LatLng(daten.latitude, daten.longitude);
				Microsoft.Maps.event.addListener(markers, 'click', function() {
					infowindow.setPosition(LatLng);
					infowindow.open(map, markers[counter]);
					// close infowindow after 10sec
					setTimeout(function() {
						infowindow.close();
					}, 5000);

				});
			});

		});

	}/*You should put your initialization code inside an onload function or at the bottom of your HTML file,

	 just before the tag, so the DOM is completely rendered before it executes
	 (note that the second option is more sensitive to invalid HTML).*/
	Microsoft.Maps.event.addDomListener(window, "load", initialize);
});
//END #map

// Schliessen Detail Ausgabe
$('#map').on("pagehide", function() {
	// $('#eventlist').empty();
});

var setLocation = function(meineLongitude, meineLatitude) {
	//setzen letzte position auf ger�t
	if ( typeof (window.localStorage) != 'undefined') {
		//alert(" window.localStorage" + (daten.INDEXKEY));
		window.localStorage.setItem('UserLongitude', meineLongitude);
		window.localStorage.setItem('UserLatitude', meineLatitude);
	} else {
		//alert(" window.localStorage, not defined" + (daten.INDEXKEY));
		throw "window.localStorage, not defined";
	}
};

