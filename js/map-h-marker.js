$('#map').on("pageshow", function() {
		var UserMail = window.localStorage.getItem('UserMail');
		var UserKey = window.localStorage.getItem('UserKey');
		var UserFirstname = window.localStorage.getItem('UserFirstname');
		var UserLastname = window.localStorage.getItem('UserLastname');
		var meineLatitude = window.localStorage.getItem('UserLatitude');
		var meineLongitude = window.localStorage.getItem('UserLongitude');

		var meineLongitude = 11.076750, meineLatitude = 49.452030;
		var self = this;
		var infowindow = null;

		
		/**
		 * Boilerplate map initialization code starts below:
		 */

		// Step 1: initialize communication with the platform
		var platform = new H.service.Platform({
				app_id: '0qM1dei4RH6PbceH963Y',
				app_code: 'D2dCSy4yZ9cXzmeeWwWeHQ',
				useHTTPS: true
			});
		var pixelRatio = window.devicePixelRatio || 1;
		var defaultLayers = platform.createDefaultLayers({
			tileSize: pixelRatio === 1 ? 512 : 512,
			ppi: pixelRatio === 1 ? undefined : 320
		});

		// Step 2: initialize a map - not specificing a location will give a
		// whole world view.
		var map = new H.Map(document.getElementById('map-container'),
				defaultLayers.normal.map, {
						pixelRatio : pixelRatio
				});

		// Step 3: make the map interactive
		// MapEvents enables the event system
		// Behavior implements default interactions for pan/zoom (also on mobile
		// touch environments)
		var behavior = new H.mapevents.Behavior(new H.mapevents.MapEvents(map));

		// Create the default UI components
		var ui = H.ui.UI.createDefault(map, defaultLayers);

		function createMarker(map){
			var markers = [];
			var icon='';
			
			$.getJSON('php/datafetcher.php?function=getEvents&longitude=' + meineLongitude + '& latitude=' + meineLatitude + '&date=' + Date.now(), function(data) {
				// schleife mit counter kein for notwendig
				$.each(data, function(counter, daten) {
					// http://mapicons.nicolasmollet.com/
					
					switch (daten.t_kategorie) {
					case "Jamsession":
						color1 = './img/music_rock.png';
						icon = new H.map.Icon('./img/default/jam1.jpg');
						break;
					case "Karaoke":
						color1 = './img/comedyclub.png';
						icon = new H.map.Icon('./img/default/karaoke3.jpg');
						break;
					case "Concert":
						color1 = './img/jazzclub.png';
						icon = new H.map.Icon('./img/default/concert3.jpg');
						break;
					case "Event":
						color1 = './img/music_live.png';
						icon = new H.map.Icon('./img/default/event1.jpg');
						break;
					case "Poetry":
						color1 = './img/dragon.png';
						icon = new H.map.Icon('./img/default/poetry1.jpg');
						break;
					default:
						color1 = './img/magicshow.png';
						icon = new H.map.Icon('./img/default/show.jpg');
						break;
					} // switch
				
					//var contentString = '<dl id="c2"><dt>' + daten.t_date + ' ' + daten.t_kategorie + ' ' + daten.t_eventname + '</dt><dd> <a href="' + daten.url + '" target="_blank"><img src="' + defaultpic + '" style="width: 100px;height:100px "align="middle" /></a><br>' + daten.t_text + ' <br><p><b> Location: </b>' + daten.company + '  ' + daten.city + '  ' + daten.postcode + '  ' + daten.street + '</p></dd></dl>';

					
					/*
					var infowindow = new H.map.InfoWindow({
						content : contentString
						// content : daten.company + " " + daten.comment
					}); // infowindow
					*/
					// Create a marker icon from an image URL:
					var icon = new H.map.Icon('./img/comedyclub.png');
					
					// Create a marker using the previously instantiated icon:
					//meineLongitude = 11.076750, meineLatitude = 49.452030;
					var marker = new H.map.Marker({
						lat: daten.latitude,
						lng: daten.longitude ,
						icon: icon });
					
					meineLatitude = daten.latitude;
					meineLongitude = daten.longitude;
			
					markers = new H.map.Marker({
						lat: daten.latitude,
						lng: daten.longitude ,
						icon : icon,
					// animation : H.map.Animation.DROP,
					// position : new H.map.Marker({lat: daten.latitude, lng:
					// daten.longitude}),
						title : daten.t_kategorie + " " + daten.company + " " + daten.url
					});	
					map.addObject(marker);
				});
			
				//alert('hier ' + markers + '  '  + meineLatitude);
			});
		}

   		createMarker(map);
   		/**
		 * Moves the map to display over Berlin *
		 * 
		 * @param {H.Map}
		 *            map A HERE Map instance within the application
		 */
		function moveMapToregion(map) {
			map.setCenter({
				lat: meineLatitude,
				 lng: meineLongitude 
			});
			map.setZoom(12);
		}

		// Now use the map as required...
		moveMapToregion(map);
	});

