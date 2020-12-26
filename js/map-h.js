$('#map')
		.on(
				"pageshow",
				function() {
					var UserMail = window.localStorage.getItem('UserMail');
					var UserKey = window.localStorage.getItem('UserKey');
					var UserFirstname = window.localStorage
							.getItem('UserFirstname');
					var UserLastname = window.localStorage
							.getItem('UserLastname');
					var meineLatitude = window.localStorage
							.getItem('UserLatitude');
					var meineLongitude = window.localStorage
							.getItem('UserLongitude');

					// meineLongitude = 8.5, meineLatitude = 49;
					var self = this;
					var infowindow = null;

					var setLocation = function(meineLongitude, meineLatitude) {
						// setzen letzte position auf user

						if (typeof (window.localStorage) != 'undefined') {
							// alert(" window.localStorage" );
							// alert("set location = Latitude : " +
							// meineLatitude + " Longitude: " + meineLongitude);
							window.localStorage.setItem('UserLongitude',
									meineLongitude);
							window.localStorage.setItem('UserLatitude',
									meineLatitude);
						} else {
							// alert(" window.localStorage, not defined" );
							throw "window.localStorage, not defined";
						}
					};

					// funktioniert mit unfd ohne eingeschaltetet ortung
					function showLocation(position) {
						var meineLatitude = position.coords.latitude;
						var meineLongitude = position.coords.longitude;
						// alert("Latitude : " + meineLatitude + " Longitude: "
						// + meineLongitude);
						// setzen letzte position auf user
						setLocation(meineLongitude, meineLatitude);
					}

					function errorHandler(err) {
						if (err.code == 1) {
							// alert("Error: Access is denied! - Last location
							// used");
							meineLatitude = window.localStorage
									.getItem('UserLatitude');
							meineLongitude = window.localStorage
									.getItem('UserLongitude');
						} else if (err.code == 2) {
							// alert("Error: Position is unavailable!- Last
							// location used");
							meineLatitude = window.localStorage
									.getItem('UserLatitude');
							meineLongitude = window.localStorage
									.getItem('UserLongitude');
						}
					}

					if (navigator.geolocation) {
						// timeout at 60000 milliseconds (60 seconds)
						// alert("Browser support geolocation! - Last location
						// used");
						var options = {
							timeout : 60000
						};
					} else {
						// alert("Sorry, browser does not support geolocation! -
						// Last location used");
						var meineLatitude = window.localStorage
								.getItem('UserLatitude');
						var meineLongitude = window.localStorage
								.getItem('UserLongitude');
					}

					navigator.geolocation.getCurrentPosition(showLocation,
							errorHandler, options);

					if ((meineLatitude = 'undefind')) {
						// alert("Sorry, meineLatitude = 'undefind'");
						meineLongitude = 11.076750;
						meineLatitude = 49.452030;
						window.localStorage.setItem('UserLongitude',
								meineLongitude);
						window.localStorage.setItem('UserLatitude',
								meineLatitude);
					}

					/**
					 * HERE Boilerplate map initialization code starts below:
					 */

					// Step 1: initialize communication with the platform
					var platform = new H.service.Platform({
						app_id : ' ',
						app_code : ' ',
						useHTTPS : true
					});
					var pixelRatio = window.devicePixelRatio || 1;
					var defaultLayers = platform.createDefaultLayers({
						tileSize : pixelRatio === 1 ? 256 : 512,
						ppi : pixelRatio === 1 ? undefined : 320
					});

					// Step 2: initialize a map - not specificing a location
					// will give a
					// whole world view.
					var map = new H.Map(document
							.getElementById('map-container'),
							defaultLayers.satellite.map, {
								center : {
									lat : 50,
									lng : 5
								}, // eUROPA
								zoom : 4,
								pixelRatio : pixelRatio
							});

					// Step 3: make the map interactive
					// MapEvents enables the event system
					// Behavior implements default interactions for pan/zoom
					// (also on
					// mobile
					// touch environments)
					var behavior = new H.mapevents.Behavior(
							new H.mapevents.MapEvents(map));

					// Create the default UI components
					var ui = H.ui.UI.createDefault(map, defaultLayers);

					function createMarker(map) {

						var icon = '';
						var group = new H.map.Group();

						$
								.getJSON(
										'php/datafetcher.php?function=getEvents&longitude='
												+ meineLongitude
												+ '& latitude=' + meineLatitude
												+ '&date=' + Date.now(),
										function(data) {
											// schleife mit counter kein for
											// notwendig
											$
													.each(
															data,
															function(counter,
																	daten) {
																// http://mapicons.nicolasmollet.com/

																switch (daten.t_kategorie) {
																case "Jamsession":
																	color1 = 'img/music_rock.png';
																	var icon = new H.map.Icon(
																			'img/default/jam1.jpg');
																	break;
																case "Karaoke":
																	color1 = 'img/comedyclub.png';
																	var icon = new H.map.Icon(
																			'img/default/karaoke3.jpg');
																	break;
																case "Concert":
																	color1 = 'img/jazzclub.png';
																	var icon = new H.map.Icon(
																			'img/default/concert3.jpg');
																	break;
																case "Event":
																	color1 = 'img/music_live.png';
																	var icon = new H.map.Icon(
																			'img/default/event1.jpg');
																	break;
																case "Poetry":
																	color1 = '.img/dragon.png';
																	var icon = new H.map.Icon(
																			'img/default/poetry1.jpg');
																	break;
																default:
																	color1 = 'img/magicshow.png';
																	var icon = new H.map.Icon(
																			'img/default/show.jpg');
																	break;
																} // switch

																// Create a
																// marker using
																// the
																// previously

																var marker = new H.map.Marker(
																		{
																			lat : daten.latitude,
																			lng : daten.longitude,
																			icon : icon
																		});
																// ausgabe image
																// als icon +
																// ist objekt
																marker
																		.setData('<div> <a href="'
																				+ daten.url
																				+ '" target="_blank">'
																				+ daten.company
																				+ '</a></div>'
																				+ ' <div><p><b> Location: </b>'
																				+ daten.company
																				+ ' '
																				+ daten.city
																				+ ' '
																				+ daten.postcode
																				+ ' '
																				+ daten.street
																				+ '</div>');

																group
																		.addObject(marker);

																map
																		.addObject(group);

															});// end each
											// Ausgabe daten
											group
													.addEventListener(
															'tap',
															function(evt) {
																// event target
																// is the marker
																// itself, group
																// is a parent
																// event target
																// for all
																// objects that
																// it contains
																var bubble = new H.ui.InfoBubble(
																		evt.target
																				.getPosition(),
																		{
																			// read
																			// custom
																			// data
																			content : evt.target
																					.getData()
																		});
																// show info
																// bubble
																ui
																		.addBubble(bubble);
															}, false);

										});// end getJson
					}// end createMarker

					createMarker(map);
					/**
					 * Moves the map to display user location *
					 * 
					 * @param {H.Map}
					 *            map A HERE Map instance within the application
					 */
					function moveMapToregion(map) {
						map.setCenter({
							lat : meineLatitude,
							lng : meineLongitude
						});
						map.setZoom(12);
					}

					// Now use the map as required...
					moveMapToregion(map);
				});// END #map

