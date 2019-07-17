//Ausgabe details zum Event
$('#event-detail').on("pageshow", function() {
	var event = sessionStorage.event;

	$.getJSON('php/datafetcher.php?function=getEventsDetail&location_id=' + event, function(data) {
		$.each(data, function(counter, daten) {
			if (daten.location_id > 0) {
				//alert(" id: " + (daten.t_kategorie));
				switch (daten.t_kategorie) {
				case "Jamsession":
					defaultpic = './bilder/default/jam1.jpg';
					break;
				case "Karaoke":
					defaultpic = './bilder/default/karaoke3.jpg';
					break;
				case "Concert":
					defaultpic = './bilder/default/concert3.jpg';
					break;
				case "Event":
					defaultpic = './bilder/default/event1.jpg';
					break;
				case "Poetry":
					defaultpic = './bilder/default/poetry1.jpg';
					break;
				default:
					defaultpic = './bilder/default/show.jpg';
					break;
				}
				//alert(" id: " + (defaultpic));

				//alert(" id: " + (daten.location_id) + " t_text: " + daten.t_text + " t_comment: " + daten.comment);
				//location_id , Kategorie, Firma, Country, City,Postcode,Street,email,url,Phone,Longitude,Latitude,Comment,
				//    band_id ,band_name ,band_url ,band_mail ,band_logo ,band_text ,
				//    t_location_id ,t_band_id,t_date,t_day,t_iteration,t_text,t_kategorie,t_eventname

				//var detailString = '<h2>' + daten.t_date + ' ' + daten.t_kategorie + ' ' + daten.t_eventname + '</h2> <p> <a href="' + daten.url + '" target="_blank"><img src="' + defaultpic + '" style="width: 100px;height:100px "align="middle" /></a>' + daten.t_text + ' <br><b> Location: </b>' + daten.company + '  ' + daten.city + '  ' + daten.postcode + '  ' + daten.street + '   ' + daten.comment + '</p>';
				var detailString = '<dl id="c2"><dt>' + daten.t_date + ' ' + daten.t_kategorie + ' ' + daten.t_eventname + '</dt><dd> <a href="' + daten.url + '" target="_blank"><img src="' + defaultpic + '" style="width: 100px;height:100px "align="middle" /></a>' + daten.t_text + ' <br><p><b> Location: </b>' + daten.company + '  ' + daten.city + '  ' + daten.postcode + '  ' + daten.street + '   ' + daten.comment + '</p></dd></dl>';
				$(detailString).appendTo('#event-content');
			}
		});
	});

});

//Schliessen Detail Ausgabe
$('#event-detail').on("pagehide", function() {
	$('#event-content').empty();
});

// Liste aller Events anzeigen
$('#list').on("pagecreate", function() {

	var meineLongitude, meineLatitude;
	var positionsAusgabe = function(position) {
		meineLongitude = position.coords.longitude;
		meineLatitude = position.coords.latitude;
	};
	$.getJSON('php/datafetcher.php?function=getEvents&longitude=' + meineLongitude + '& latitude=' + meineLatitude + '&date=' + Date.now(), function(data) {
		// schleife mit counter kein for notwendig
		$.each(data, function(counter, daten) {

			//alert(" id: " + (daten.location_id) + " company: " + (daten.company) + " daten.t_kategorie: " + (daten.t_kategorie) + " color: " + (color1));
			//Ausgabe des Links fÃ¼r die Listenansicht
			//todo layour zoom zu gross
			$('<li><a href="#event-detail" data-transition="slide" onClick="javascript:sessionStorage.event=\'' + daten.location_id + '\';"><img src="http://maps.google.com/maps/api/staticmap?center=' + daten.latitude + ',' + daten.longitude + '&zoom=13&size=50x50&markers=color:red|size:tiny|' + daten.latitude + ',' + daten.longitude + '"/>' + daten.city + ' , ' + daten.postcode + ' , ' + daten.company + ' , next ' + entfernungBerechnen(meineLongitude, meineLatitude, daten.longitude, daten.latitude) + '</a></li>').appendTo('#eventlist');
		});
	});
	//todo
	// $('#eventlist').listview('refresh');
});

//Ausgabe Marker
$('#map').on("pageshow", function() {
	//check that browere givegeolocation
	// else give to user search mask
	if (navigator.geolocation) {

		var map;
		var markers;
		var meineLongitude, meineLatitude;
		var self = this;
		var infowindow = null;

		var positionsAusgabe = function(position) {
			//  width = document.getElementById('map-container').offsetWidth;
			//height = document.getElementById('map-container').offsetHeight;

			meineLongitude = position.coords.longitude;
			meineLatitude = position.coords.latitude;

			$('#map-container').height($(window).height());

			var mapOptionen = {
				zoom : 12,
				center : new Microsoft.Maps.LatLng(meineLatitude, meineLongitude),
				mapTypeId : Microsoft.Maps.MapTypeId.ROADMAP
			};

			var map = new Microsoft.Maps.Map(document.getElementById('map-container'), mapOptionen);
			window.setTimeout(function() {

				$.getJSON('php/datafetcher.php?function=getEvents&longitude=' + meineLongitude + '& latitude=' + meineLatitude + '&date=' + Date.now(), function(data) {
					// schleife mit counter kein for notwendig
					$.each(data, function(counter, daten) {

						// http://mapicons.nicolasmollet.com/
						switch (daten.t_kategorie) {
						case "Jamsession":
							color1 = './bilder/music_rock.png';
							break;
						case "Karaoke":
							color1 = './bilder/comedyclub.png';
							break;
						case "Concert":
							color1 = './bilder/jazzclub.png';
							break;
						case "Event":
							color1 = './bilder/music_live.png';
							break;
						case "Poetry":
							color1 = './bilder/dragon.png';
							break;
						default:
							color1 = './bilder/magicshow.png';
							break;
						}

						//alert(" id: " + (daten.location_id) + " company: " + (daten.company) + " daten.t_kategorie: " + (daten.t_kategorie) + " color: " + (color1));
						//alert(" id: " + (daten.location_id) + meineLatitude + daten.latitude+" t_kategorie: " + (daten.t_kategorie)  );
						// alert(" id: " + daten.location_id + " " + daten.t_eventname ) ;
						//Ausgabe des Links fÃ¼r die Listenansicht sollte eigentlich in List sein
						$('<li><a href="#event-detail" data-transition="slide" onClick="javascript:sessionStorage.event=\'' + daten.location_id + '\';"><img src="http://maps.google.com/maps/api/staticmap?center=' + daten.latitude + ',' + daten.longitude + '&zoom=13&size=50x50&markers=color:red|size:tiny|' + daten.latitude + ',' + daten.longitude + '"/>' + daten.t_kategorie + ' , ' + daten.city + ' , ' + daten.postcode + ' , ' + daten.company + ' , next ' + entfernungBerechnen(meineLongitude, meineLatitude, daten.longitude, daten.latitude) + '</a></li>').appendTo('#eventlist');

						var contentString = '<h2>' + daten.t_date + ' ' + daten.t_kategorie + ' ' + daten.t_eventname + '</h2>  <b>' + daten.company + ' ' + daten.city + ' ' + daten.postcode + ' ' + daten.street + '  ' + daten.url + '</b><br><p>' + daten.t_text + ' ' + daten.comment + '</p>';

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

						});
					});
				});
			}, 1);
			//  window.scrollTo(0,1);
		};

		navigator.geolocation.getCurrentPosition(positionsAusgabe);
		// Microsoft.Maps.event.addDomListener(window, 'load', positionsAusgabe);

	} else {
		window.alert("Geolocation is not supported by this browser.");
		// Geolocation is not supported by this browser else give to user search mask
	}
});

//Berechnung der Entfernung
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

