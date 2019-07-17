$('#insertLOC').on("pageshow", function() {
	var toDate = new Date();
	//var jetzt = toDay.toLocaleFormat('%Y-%m-%d');
	var jetzt = toDate.getFullYear() + '-' + toDate.getMonth() + 1 + '-' + toDate.getDate();
	//alert ('jetzt : ' + jetzt);
    //var jetzt = toDate;
    
    var UserMail = window.localStorage.getItem('UserMail');
	var UserKey = window.localStorage.getItem('UserKey');
	var UserFirstname = window.localStorage.getItem('UserFirstname');
	var UserLastname = window.localStorage.getItem('UserLastname');
    
	
	var tloc = '<legend ><b>Select existing Location or add a new one</b></legend><select data-mini="true" name="location_id" id="location_id"><option selected="selected" value=0>no location selected</option> ';
	$.getJSON('php/tLocation-all.php?function=getLocationListAll&usermail=' + UserMail, function(data) {
		$.each(data, function(counter, daten) {
			tloc += '<option  value="' + daten.location_id + '">' + daten.Country + ' , ' + daten.City + ' , ' + daten.Firma + ' , ' + daten.Postcode + ' , ' + daten.Street + '</option>';
		});
		tloc += '</select">';
		$('#LocList').append(tloc);
	});

	var tactor = '<legend ><b>Select existing Act/Formation or add a new one</b></legend><select data-mini="true" name="band" id="band"><option selected="selected" value="0">no actor selected</option>';
	$.getJSON('php/tActor.php?function=getActorListAll&band_usermail=yourEmail@event.com', function(data) {
		$.each(data, function(counter, daten) {
			tactor += '<option  value="' + daten.band_id + '">' + daten.band_name + ' - ' + daten.band_url + '</option>';
		});
		tactor += '</select"> ';
		$('#ActList').append(tactor);
	});

	var InsertEventData = '<h2>Hello ' + UserFirstname + '  ' + UserLastname + '</h2> <p>Please add your favorite Events. You can use your existing Location and Act & Event info. If you have another location or new event type, please add details. <br>In future we will provide you the service to upload a list of all Events for your registration. </p>';
	$(InsertEventData).appendTo('#EventNewHead');

	$('#InsertEvent1').html('<fieldset  data-role="controlgroup" data-type="horizontal" data-mini="true">' 
	+ '<label ><b> Act / Day / Frequenz</b></label>' + '<select  name="artanfrage" id="artanfrage" >' 
	+ '<option value="Jamsession">Jam Session</option> <option value="Karaoke">Karaoke/Open Mic</option> <option value="Poetry">Poetry Slam</option> <option value="Concert">Concert</option> <option selected="selected" value="Event">other Event</option>	</select>' 
	+ '<select name="day" id="day"><option selected="selected" value="2">Monday</option><option value="3">Tuesday</option><option value="4">Wednesday</option> <option value="5">Thursday</option> <option value="6">Friday</option> <option value="7 ">Saturday</option> <option value="1">Sunday</option> </select>' 
	+ '<select name="iteration" id="iteration"> <option selected="selected" value="0">only once</option> <option value="1">weekly</option> <option value="2">all two weeks</option> <option value="3">every month</option> <option value="4">first week a month</option> <option value="5">second week a month</option> <option value="6">third week a month</option> <option value="7">fourth week a month</option> <option value="8">last week a month</option></select>' + '</fieldset>' 
	+ '<label for="event">Name of Act</label> <input data-mini="true" type="text" name="event" id="event" placeholder="My Special Act" />' 
	+ '<label for="when">When</label> <input data-mini="true" type="date" name="when" id="when" placeholder="jjjj-mm-dd">' 
	+ '<label for="text">Act Info</label>	<textarea data-mini="true" name="text" id="text" cols="20"	rows="5" wrap="virtual"></textarea>');

	$('#InsertEvent2').html('<br><label for="location"><b>Location</b></label>	<input data-mini="true"	type="text" name="location" id="location" placeholder="Location of Act" />' 
	+ '<label for="adr">Address</label>	<input	data-mini="true" type="text" name="adr" id="adr" placeholder="Street of event"  />' 
	+ '<label for="plz">Postalcode</label> <input data-mini="true" type="text"  name="plz" id="plz" placeholder="Postal Code" />' 
	+ '<label for="city">City</label>	<input	data-mini="true" type="text" name="city" id="city" placeholder="city" />' 
	+ '<label for="url">URL</label>	<input data-mini="true"	type="url" name="url" id="url" placeholder="http://" />' 
	+ '<label for="email">E-Mail</label><input data-mini="true" type="email" name="email" id="email" placeholder="a@event.com"  />' 
	+ '<label for="telefon">Phone</label>	<input data-mini="true" type="tel" name="telefon" id="telefon" placeholder="phone" />');

	//Formdaten an PHP-Datei senden
	$("form#locationform").submit(function() {
		// we want to store the values from the form input box, then send via ajax below
		//alert("1 artanfrage: " + ($('#artanfrage').attr('value')) + " event: " + (event) + " when: " + (when));

		var location_id = $('#location_id').attr('value');
		var band_id = $('#band').attr('value');

		var artanfrage = $('#artanfrage').attr('value');
		var event = $('#event').attr('value');
		var location = $('#location').attr('value');
		var when = $('#when').attr('value');
		var adr = $('#adr').attr('value');
		var plz = $('#plz').attr('value');
		var city = $('#city').attr('value');
		var country = $('#country').attr('value');
		var url = $('#url').attr('value');
		var email = $('#email').attr('value');
		var telefon = $('#telefon').attr('value');
		var text = $('#text').attr('value');
		var day = $('#day').attr('value');
		var iteration = $('#iteration').attr('value');

		if (location_id > 0) {
			//alert("&location_id=" + location_id);
		};
		if (band_id > 0) {
			//alert("band_id =" + band_id);
		};

		//alert("artanfrage=" + artanfrage + "&event=" + event + "&location=" + location + "&when=" + when + "&adr=" + adr + "&plz=" + plz + "&city=" + city + "&country=" + country + "&url=" + url + "&email=" + email + "&telefon=" + telefon + "&text=" + text + "&day=" + day + "&iteration=" + iteration);
		//	alert("band_id =" + band_id + "&location_id=" + location_id);

		$.ajax({
			type : "get",
			url : "php/tLocation-all.php?function=LocationFree",
			data : "artanfrage=" + artanfrage + "&event=" + event + "&location=" + location + "&when=" + when + "&adr=" + adr + "&plz=" + plz + "&city=" + city + "&country=" + country + "&url=" + url + "&email=" + email + "&telefon=" + telefon + "&text=" + text + "&day=" + day + "&iteration=" + iteration + "&band_id=" + band_id + "&location_id=" + location_id,
			error : function() {
				//UMGEHUNGSLÖSUNG DA FALSE VON getLatLngByAddress.php KOMMT WENN KEINEN lat lng ERMITTELT WERDEN KONNTE
				//alert("FAIL Return php/tLocation-all.php?function=LocationFree");
				$.mobile.changePage("#EventNoReg", {
					transition : "flip"
				});
			},
			success : function() {
				$.mobile.changePage("#EventNoReg", {
					transition : "flip"
				});
			}
		});
		//return false;
	});
});

// Schliessen Detail Ausgabe
$('#insertLOC').on("pagehide", function() {
	$('#EventNewHead').empty();
	$('#InsertEvent1').empty();
	$('#InsertEvent2').empty();
});
