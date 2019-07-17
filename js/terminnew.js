$('#NewTermin').on("pageshow", function() {

	var UserMail = window.localStorage.getItem('UserMail');
	var UserKey = window.localStorage.getItem('UserKey');
	var UserFirstname = window.localStorage.getItem('UserFirstname');
	var UserLastname = window.localStorage.getItem('UserLastname');
	//alert(" NewTermin ");

	var AddLocation = ('<a href="#NewLocation" class="ui-shadow ui-btn ui-corner-all ui-btn-inline ui-btn-b ui-mini" data-theme="a" data-transition="slide" >New Location</a>');
	var AddActor = ('<a href="#NewActor" class="ui-shadow ui-btn ui-corner-all ui-btn-inline ui-btn-b ui-mini" data-transition="slide" >New Actor</a>');
	
	var TerminNewData = '<h2>Hello ' + UserFirstname + '  ' + UserLastname + '</h2> <p>Please add your next Events. You can use your existing Location and also <br>Bands - Formations - Actors - Eventtype info. <br><b>If you have another ' + AddLocation + ' or '+ AddActor + ' please add this before adding the event.</b> </p>';
	$(TerminNewData).appendTo('#TerminNewHead');

		
	var tloc = '<legend><b>Select existing Location</b></legend><select data-mini="true" name="termlocation" id="termlocation"><option selected="selected" value="0"></option>';
	$.getJSON('php/tLocation-all.php?function=getLocationListAll&usermail=' + UserMail, function(data) {
		$.each(data, function(counter, daten) {
			tloc += '<option  value="' + daten.location_id + '">' + daten.Country + '  ' + daten.City + '  ' + daten.Postcode  + '  ' + daten.Firma + '  ' + daten.Street + '</option>';
		});
		tloc += '</select">';
		$('#LList').append(tloc);
	});

	var tactor = '<legend><b>Select existing Band/Act/Formation</b></legend><select data-mini="true" name="termactor" id="termactor"><option selected="selected" value="0"></option>';
	$.getJSON('php/tActor.php?function=getActorListAll&band_usermail='+ UserMail, function(data) {
		$.each(data, function(counter, daten) {
			tactor += '<option  value="' + daten.band_id + '">' + daten.band_name + ' Web: '+ daten.band_url + '</option>';
		});
		tactor += '</select"> ';
		$('#AList').append(tactor);
	});


	$('#TerminNewForm').html( '<fieldset  data-role="controlgroup" data-type="horizontal" data-mini="true">' 
	+ '<legend ><b> Act / Day / Frequenz</b></legend>' 
	+ '<select  name="termartanfrage" id="termartanfrage" >' 
	+ '<option value="Jamsession">Jam Session</option> <option value="Karaoke">Karaoke/Open Mic</option> <option value="Poetry">Poetry Slam</option> <option value="Concert">Concert</option> <option selected="selected" value="Event">other Event</option>	</select>' 
	+ '<select name="termday" id="termday"><option selected="selected" value="2">Monday</option><option value="3">Tuesday</option><option value="4">Wednesday</option> <option value="5">Thursday</option> <option value="6">Friday</option> <option value="7 ">Saturday</option> <option value="1">Sunday</option> </select>' 
	+ '<select name="termiteration" id="termiteration"> <option selected="selected" value="0">only once</option> <option value="1">weekly</option> <option value="2">all two weeks</option> <option value="3">every month</option> <option value="4">first week a month</option> <option value="5">second week a month</option> <option value="6">third week a month</option> <option value="7">fourth week a month</option> <option value="8">last week a month</option> </select>' 
	+ '</fieldset>' 
	+ '<label for="termevent">Name of Act</label> <input data-mini="true" type="text" name="termevent" id="termevent" placeholder="My Special Act" />' 
	+ '<label for="termwhen">When</label> <input data-mini="true" type="date" name="termwhen" id="termwhen" >' 
	+ '<label for="termtext">Act Info</label>	<textarea data-mini="true" name="termtext" id="termtext" cols="20"	rows="5" wrap="virtual"></textarea>' 
	+  '');

	//Formdaten an PHP-Datei senden
	$("form#TerminNew").submit(function() {
		// we want to store the values from the form input box, then send via ajax below

		//alert("TerminUpdate Form");
		var t_kategorie = $('#termartanfrage').attr('value');
		var t_day = $('#termday').attr('value');
		var t_iteration = $('#termiteration').attr('value');
		var t_eventname = $('#termevent').attr('value');
		var t_date = $('#termwhen').attr('value');
		var t_text = $('#termtext').attr('value');
		var t_band_id = $('#termactor').attr('value');
		var t_location_id = $('#termlocation').attr('value');

		//alert("function=TerminNew&t_usermail=" + UserMail + "&t_kategorie=" + t_kategorie + "&t_day=" + t_day + "&t_Iteration=" + t_iteration + "&t_eventname=" + t_eventname + "&t_date=" + t_date + "&t_text=" + t_text + "&t_band_id=" + t_band_id + "&t_location_id=" + t_location_id  );
	
		$.ajax({
			type : "get",
			url : "php/tTermin.php",
			data : "function=TerminNew&t_usermail=" + UserMail + "&t_kategorie=" + t_kategorie + "&t_day=" + t_day + "&t_Iteration=" + t_iteration + "&t_eventname=" + t_eventname + "&t_date=" + t_date + "&t_text=" + t_text + "&t_band_id=" + t_band_id + "&t_location_id=" + t_location_id ,
			
			success : function() {
				$.mobile.changePage("#map", {
					transition : "flip"
				});
				//     error:  alert('login error you have an account ? Register NEW ; ');
			}
		});
		return false;
	});

});

// Schliessen Detail Ausgabe
$('#NewTermin').on("pagehide", function() {
	$('#TerminNewHead').empty();
	$('#TerminNew').empty();
	$('#AList').empty();
	$('#LList').empty();
	// Schliessen Detail Ausgabe
});

