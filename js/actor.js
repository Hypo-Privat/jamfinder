// Ausgabe UPDATE Actor  Profile
$('#Actor').on("pageshow", function() {
	var UserMail = window.localStorage.getItem('UserMail');
	var UserKey = window.localStorage.getItem('UserKey');
	var UserFirstname = window.localStorage.getItem('UserFirstname');
	var UserLastname = window.localStorage.getItem('UserLastname');

	var AddActor = ('<a href="#NewActor" class="ui-shadow ui-btn ui-corner-all ui-btn-inline ui-btn-b ui-mini" data-transition="slide" >Band - Formation - Eventtype</a>');
	var ActorData = '<h2>Hello ' + UserFirstname + '  ' + UserLastname + ' </h2> <p>Here you can add your ' + AddActor + ' or update existing information. <br></p>';
	$(ActorData).appendTo('#ActorAllHead');

	//alert(" #Actor " + UserMail);
	$.getJSON('php/tActor.php?function=getActorList&band_usermail=' + UserMail, function(data) {
		$.each(data, function(counter, daten) {
			//Ausgabe des Links fuer die Listenansicht sollte eigentlich in List sein
			$('<li><a href="#UpdateActor" class="ui-shadow ui-btn ui-corner-all ui-btn-inline ui-btn-a ui-mini" data-transition="slide" onClick="javascript:localStorage.band_id=\'' + daten.band_id + '\';">'  + daten.band_name + ' , ' + daten.band_mail + ' , ' + daten.band_url + '</a></li>').appendTo('#ActorList');

		});
	});

});

// Schliessen Detail Ausgabe
$('#Actor').on("pagehide", function() {
	$('#ActorAllHead').empty();
	$('#ActorList').empty();
});
