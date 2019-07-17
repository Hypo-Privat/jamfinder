/*
$('#InsertEvent2').html( '<br><label for="location"><b>Location</b></label>	<input data-mini="true"	type="text" name="location" id="location" placeholder="Location of Act" />'
+ '<label for="adr">Address</label>	<input	data-mini="true" type="text" name="adr" id="adr" placeholder="Street of event"  />'
+ '<label for="plz">Postalcode</label> <input data-mini="true" type="text"  name="plz" id="plz" placeholder="Postal Code" />'
+ '<label for="city">City</label>	<input	data-mini="true" type="text" name="city" id="city" placeholder="city" />'
+ '<label for="url">URL</label>	<input data-mini="true"	type="url" name="url" id="url" placeholder="http://" />'
+ '<label for="email">E-Mail</label><input data-mini="true" type="email" name="email" id="email" placeholder="a@event.com"  />'
+ '<label for="telefon">Phone</label>	<input data-mini="true" type="tel" name="telefon" id="telefon" placeholder="phone" />'  );

*/

//Formdaten an PHP-Datei senden
$("form#registerform").submit(function() {

	var u_pwd = $('#u_pwd').attr('value');
	var u_email = $('#u_email').attr('value');
	var u_adr = $('#u_adr').attr('value');
	var u_plz = $('#u_plz').attr('value');
	var u_city = $('#u_city').attr('value');
	var u_country = $('#u_country').attr('value');
	
	//alert("function=user_new&adr=" + u_adr + "&plz=" + u_plz + "&city=" + u_city + "&country=" + u_country + "&u_pwd=" + u_pwd + "&u_email=" + u_email);
	$.ajax({
		type : "get",
		url : "php/tJamContacts.php",
		data : "function=user_new&adr=" + u_adr + "&plz=" + u_plz + "&city=" + u_city + "&country=" + u_country + "&u_pwd=" + u_pwd + "&u_email=" + u_email,
		success : function() {
			$.mobile.changePage("#register-OK", {
				transition : "flip"
			});
		}
	});
	//return false;
});

