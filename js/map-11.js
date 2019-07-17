//Ausgabe Marker
$('#map').on("pageshow", function() {
	// check that browere givegeolocation
	// else give to user search mask

	var map;
	var markers;
	var meineLatitude = 49.452030;
	var meineLongitude = 11.076750;
	var self = this;
	var infowindow = null;
	var mapOptionen;
/*
	var positionsAusgabe = function(position) {
		alert('in position');

		meineLongitude = position.coords.longitude;
		meineLatitude = position.coords.latitude;
		alert(meineLatitude);
		alert(meineLongitude);
		
		if ( meineLatitude = 'undefind') {
			alert('in if meineLat');
			meineLongitude = 11.076750;
			meineLatitude = 49.452030;
		};

	};
	*/
	
	// alert(meineLatitude);
	// alert(meineLongitude);

	$('#map-container').height($(window).height());

	// alert(meineLatitude);
	// alert(meineLongitude);
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

		// Clear out the old markers.
		/* markers.forEach(function(marker) {
			marker.setMap(null);
		});
		markers = [];
		alert('searchBox 2');
		
		meineLongitude = map.getBounds().getNorthEast().lng();
		meineLatitude = map.getBounds().getNorthEast().lat();
	
		*/
		// For each place, get the icon, name and location.
		var bounds = new Microsoft.Maps.LatLngBounds();
		
		
		places.forEach(function(place) {
			//alert( place.geometry.viewport);
			//alert( place.geometry.location);
			
			var meineLatitude = place.geometry.location.lat();
			var meineLongitude = place.geometry.location.lng();  
			//alert(meineLatitude);
			
			/*
			var icon = {
				url : place.icon,
				size : new Microsoft.Maps.Size(71, 71),
				origin : new Microsoft.Maps.Point(0, 0),
				anchor : new Microsoft.Maps.Point(17, 34),
				scaledSize : new Microsoft.Maps.Size(25, 25)
			};
		
			// Create a marker for each place.
			markers.push(new Microsoft.Maps.Marker({
				map : map,
				icon : icon,
				title : place.name,
				position : place.geometry.location
			}));
			/*
			 * 
			 */
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
				// (daten.company) + " daten.t_kategorie: " +
				// (daten.t_kategorie) + " color: " + (color1));
				// alert(" id: " + (daten.location_id) + meineLatitude +
				// daten.latitude+" t_kategorie: " + (daten.t_kategorie) );
				// alert(" id: " + daten.location_id + " " + daten.t_eventname )
				// ;
				// Ausgabe des Links fÃ¼r die Listenansicht sollte eigentlich in
				// List sein

				// $('<li class="ui-li-has-icon"><a href="#event-detail"
				// data-transition="slide"
				// onClick="javascript:sessionStorage.event=\'' +
				// daten.location_id + '\';" class="ui-btn ui-btn-icon-right
				// ui-icon-carat-r"><img src="' + defaultpic + '"
				// class="ui-li-icon ui-corner-none" />' + daten.t_date + ' , '
				// + daten.t_eventname + ' , ' + daten.company + ' , ' +
				// daten.street + ' , ' + daten.city + ' , ' + daten.postcode +
				// ' , next ' + entfernungBerechnen(meineLongitude,
				// meineLatitude, daten.longitude, daten.latitude) +
				// '</a></li>').appendTo('#eventlist');
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
	
	// var map = new Microsoft.Maps.Map(document.getElementById('map-container'),
	// mapOptionen);

	window.setTimeout(function() {

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
				// (daten.company) + " daten.t_kategorie: " +
				// (daten.t_kategorie) + " color: " + (color1));
				// alert(" id: " + (daten.location_id) + meineLatitude +
				// daten.latitude+" t_kategorie: " + (daten.t_kategorie) );
				// alert(" id: " + daten.location_id + " " + daten.t_eventname )
				// ;
				// Ausgabe des Links fÃ¼r die Listenansicht sollte eigentlich in
				// List sein

				// $('<li class="ui-li-has-icon"><a href="#event-detail"
				// data-transition="slide"
				// onClick="javascript:sessionStorage.event=\'' +
				// daten.location_id + '\';" class="ui-btn ui-btn-icon-right
				// ui-icon-carat-r"><img src="' + defaultpic + '"
				// class="ui-li-icon ui-corner-none" />' + daten.t_date + ' , '
				// + daten.t_eventname + ' , ' + daten.company + ' , ' +
				// daten.street + ' , ' + daten.city + ' , ' + daten.postcode +
				// ' , next ' + entfernungBerechnen(meineLongitude,
				// meineLatitude, daten.longitude, daten.latitude) +
				// '</a></li>').appendTo('#eventlist');
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
	}, 1);
	// window.scrollTo(0,1);
	// };
	// ende positionsAusgabe

	// navigator.geolocation.getCurrentPosition(positionsAusgabe);
	// navigator.geolocation.getCurrentPosition(positionsAusgabe, errorHandler);
	//Microsoft.Maps.event.addDomListener(window, 'load', positionsAusgabe);

	function errorHandler(err) {
		if (err.code == 1) {
			alert("Error: Access is denied!");
		} else if (err.code == 2) {
			alert("Error: Position is unavailable!");
		}
	}

});

// Schliessen Detail Ausgabe
$('#map').on("pagehide", function() {
	// $('#eventlist').empty();
});
// Berechnung der Entfernung
var entfernungBerechnen = function(meineLongitude, meineLatitude, long1, lat1) {
	erdRadius = 6371;

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


