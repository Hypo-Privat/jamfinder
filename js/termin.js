// Ausgabe UPDATE Termin  Profile
$('#Termin').on("pageshow", function() {

	var UserMail = window.localStorage.getItem('UserMail');
	var UserKey = window.localStorage.getItem('UserKey');
	var UserFirstname = window.localStorage.getItem('UserFirstname');
	var UserLastname = window.localStorage.getItem('UserLastname');
	//alert("Termin ");

	var AddTermin = ('<a href="#NewTermin" class="ui-shadow ui-btn ui-corner-all ui-btn-inline ui-btn-b ui-mini"  data-transition="slide" >add a Event</a>');
	var TerminData = '<h2>Hello ' + UserFirstname + '  ' + UserLastname + '</h2> <p>Here you can ' + AddTermin + ' or update your Event dates. <br> If you provide exact information, over the band or group. So we can deliver a professional correct service to your audience.</p>';
	$(TerminData).appendTo('#TerminAllHead');

	$.getJSON('php/tTermin.php?function=getTerminAll&t_usermail=' + UserMail, function(data) {
		$.each(data, function(counter, daten) {
			//alert(" location: " + (daten.t_location_id));

			//Ausgabe des Links fuer die Listenansicht sollte eigentlich in List sein
			$('<li><a href="#UpdateTermin" class="ui-shadow ui-btn ui-corner-all ui-btn-inline ui-btn-a ui-mini" data-transition="slide" onClick="javascript:localStorage.id=\'' + daten.id + '\';">' + daten.t_date + ' , ' + daten.t_kategorie + ' , ' + daten.t_eventname + '  , ' + daten.t_day + '</a></li>').appendTo('#TerminList');

		});
	});

});

// Schliessen Detail Ausgabe
$('#Termin').on("pagehide", function() {
	$('#TerminAllHead').empty();
	$('#TerminList').empty();
});
