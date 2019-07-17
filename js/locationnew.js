
$('#NewLocation').on("pageshow", function() {
	//alert(" NewLocation ");
	var UserMail = window.localStorage.getItem('UserMail');
	var UserKey = window.localStorage.getItem('UserKey');
	var UserFirstname = window.localStorage.getItem('UserFirstname');
	var UserLastname = window.localStorage.getItem('UserLastname');
	
	//alert('UserMail ' , UserMail , ' Key  ' , UserKey);
	
	var LocationNewData = '<h2  >Hello ' + UserFirstname + '  ' + UserLastname + ' your logged in as ' + UserMail + '</h2> <p>please add a new location. Please check the list if the Location is in. If yes, please do not add.</p>';
	$(LocationNewData).appendTo('#LocationNewHead');

	var tloc = '<legend ><b>Select existing Location</b></legend><select data-mini="true" name="termlocation" id="termlocation">';
	$.getJSON('php/tLocation-all.php?function=getLocationListAll&usermail=' + UserMail, function(data) {
		$.each(data, function(counter, daten) {
			tloc += '<option  value="' + daten.location_id + '">' + daten.Country + '  ' + daten.City + '  ' + daten.Postcode + '  ' + daten.Firma + '  '  + daten.Street + ' Web: ' + daten.url + '</option>';
		});
		tloc += '</select">';
		$('#LocationNewList').append(tloc);
	});

	//Formdaten an PHP-Datei senden
	$("form#LocationNew").submit(function() {
		// we want to store the values from the form input box, then send via ajax below

		var location = $('#l_location').attr('value');
		var firma = $('#l_firma').attr('value');
		var kategorie = $('#l_kategorie').attr('value');
		var country = $('#u_country').attr('value'); //chek if that work u_
		var city = $('#l_city').attr('value');
		var plz = $('#l_plz').attr('value');
		var adr = $('#l_adr').attr('value');
	    var email = $('#l_email').attr('value');
		var url = $('#l_url').attr('value');		
		var telefon = $('#l_telefon').attr('value');
		var comment = $('#l_comment').attr('value');
		var usermail = UserMail;
alert("function=LocationNew&usermail=" + UserMail + "&firma=" + firma + "&location=" + location + "&adr=" + adr + "&plz=" + plz + "&city=" + city + "&country=" + country + "&url=" + url + "&email=" + email + "&telefon=" + telefon + "&comment=" + comment + "&kategorie=" + kategorie);
		$.ajax({
			type : "get",
			url : "php/tLocation-all.php",
			data : "function=LocationNew&usermail=" + UserMail + "&firma=" + firma + "&location=" + location + "&adr=" + adr + "&plz=" + plz + "&city=" + city + "&country=" + country + "&url=" + url + "&email=" + email + "&telefon=" + telefon + "&comment=" + comment + "&kategorie=" + kategorie,
			error : function() {
				//UMGEHUNGSLÖSUNG DA FALSE VON getLatLngByAddress.php KOMMT WENN KEINEN lat lng ERMITTELT WERDEN KONNTE
				//alert("FAIL Return php/tLocation-all.php?function=LocationFree");
				$.mobile.changePage("#map", {
					transition : "flip"
				});
			},
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
$('#NewLocation').on("pagehide", function() {
	$('#LocationNewHead').empty();
	$('#LocationNew').empty();
	$('#LocationNewList').empty();
});

