// Ausgabe List of Location from one user
$('#Location').on("pageshow", function() {

	var UserMail = window.localStorage.getItem('UserMail');
	var UserKey = window.localStorage.getItem('UserKey');
	var UserFirstname = window.localStorage.getItem('UserFirstname');
	var UserLastname = window.localStorage.getItem('UserLastname');

	

	var AddLocation = ('<a href="#NewLocation" class="ui-shadow ui-btn ui-corner-all ui-btn-inline ui-btn-b ui-mini" data-transition="slide" >add a location</a>');
	var LocationData = '<h2>Hello ' + UserFirstname + '  ' + UserLastname  + ' </h2> <p>Here you can ' + AddLocation + ' or update your Location . </p>';
	$(LocationData).appendTo('#LocationHead');

	//alert(" #Location " + UserMail);
	$.getJSON('php/tLocation-all.php?function=getLocationList&usermail=' + UserMail, function(data) {
		$.each(data, function(counter, daten) {
			//Ausgabe des Links fuer die Listenansicht sollte eigentlich in List sein
			$('<li><a href="#UpdateLocation" class="ui-shadow ui-btn ui-corner-all ui-btn-inline ui-btn-a ui-mini" data-transition="slide" onClick="javascript:localStorage.location_id=\'' + daten.location_id + '\';">' + daten.Country + ' , '+ daten.City + ' , ' + daten.Firma + ' , ' + daten.Postcode + ' , ' + daten.Street +  '</a></li>').appendTo('#LocationList');
	});
	});
});

// Schliessen Detail Ausgabe
$('#Location').on("pagehide", function() {
	$('#LocationDetailHead').empty();
	$('#LocationList').empty();
});
