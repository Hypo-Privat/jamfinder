// Ausgabe UPDATE Location  Profile
$('#UpdateLocation').on("pageshow", function() {
    
	var UserMail = window.localStorage.getItem('UserMail');
	var UserKey = window.localStorage.getItem('UserKey');
	var UserFirstname = window.localStorage.getItem('UserFirstname');
	var UserLastname = window.localStorage.getItem('UserLastname');
	var location_id = window.localStorage.getItem('location_id');
	//alert(" #UpdateLocation " + UserKey);
	$.getJSON('php/tLocation-all.php?function=getLocationDetail&location_id=' + location_id, function(data) {
		$.each(data, function(counter, daten) {

			var LocationData = '<h2>Hello ' + UserFirstname + '  ' + UserLastname + '</h2> <p> Here you can update your Location.<br> If you provide exact information, about YOU, the or group what you do. So we will deliver a professional correct service to your audience.</p>';
			$(LocationData).appendTo('#LocationDetailHead');
			
			//	alert(" #kategorie" + daten.kategorie);
				
			$('.country').html('<label for="country">Country</label><select  data-mini="true" name="country" id="country" >'
                        +'<option selected="selected" value="Switzerland">Switzerland</option>'  + $country);
			$updatecountry =  ('<option selected="selected" value="' + daten.country + '">' + daten.country + '</option>');
			$('.updatecountry').html('<label for="ul_country">Country</label><select  data-mini="true" name="ul_country" id="ul_country" >' + $updatecountry + $country);

			//  alert(" #LocationData " + LocationData);
			// eventuell aufteilen in 3 teile
			$('#LocationUpdateForm1').html('<input type="hidden" id="location_id" name="location_id" value="' + location_id + '">' 
			+ '<input type="hidden" id="ul_usermail" name="ul_usermail" value="' + UserMail + '">' 
			+ '<label for="ul_kategorie">Type (Bar, Restaurant, ...)</label><input data-mini="true" type="text" name="ul_kategorie" id="ul_kategorie" value="' + daten.kategorie + '" />' 
			+ '<label for="ul_firma">Company</label><input data-mini="true" type="text" name="ul_firma" id="ul_firma" value="' + daten.firma + '" />' );
		
			$('#LocationUpdateForm2').html('<label for="ul_city">City</label><input data-mini="true" type="text" name="City" id="ul_city" value="' + daten.city + '"  />' 
			+ '<label for="ul_postcode">Postcode</label><input data-mini="true" type="text" name="ul_postcode" id="ul_postcode" value="' + daten.postcode + '"  />' 
			+ '<label for="ul_street">Street</label><input data-mini="true" type="text" name="ul_street" id="ul_street" value="' + daten.street + '"  />' 
			+ '<label for="ul_phone">Phone</label><input data-mini="true" type="text" name="ul_phone" id="ul_phone" value="' + daten.phone + '"/>' 
			+ '<label for="ul_email">Mail @</label><input data-mini="true" type="mail" name="ul_email" id="ul_email" value="' + daten.email + '"  />' 
			+ '<label for="ul_url">Homepage http://</label><input data-mini="true" type="ul_url" name="ul_url" id="ul_url" value="' + daten.url + '"  />' 
			+ '<label for="ul_comment">Comment</label> <textarea data-mini="true" name="ul_comment" id="ul_comment" cols="20" rows="5" wrap="virtual">' + daten.comment + '</textarea>');

			//Formdaten an PHP-Datei senden
			$("form#LocationUpdate").submit(function() {
				// we want to store the values from the form input box, then send via ajax below

				//alert("LocationUpdate Form");

				var location_id = $('#location_id').attr('value');
				var usermail = $('#ul_usermail').attr('value');
				var kategorie = $('#ul_kategorie').attr('value');
				var firma = $('#ul_firma').attr('value');
				var country = $('#ul_country').attr('value');
				var street = $('#ul_street').attr('value');
				var phone = $('#ul_phone').attr('value');
				var postcode = $('#ul_postcode').attr('value');
				var city = $('#ul_city').attr('value');
				var url = $('#ul_url').attr('value');
				var email = $('#ul_email').attr('value');
				var comment = $('#ul_comment').attr('value');

				// alert("function=LocationUpdate&location_id=" + location_id + "&firma=" + firma + "&country=" + country + "&street=" + street + "&phone=" + phone + "&postcode=" + postcode + "&City=" + city + "&url=" + url + "&email=" + email + "&comment=" + comment + "&usermail=" + usermail);
				$.ajax({
					type : "get",
					url : "php/tLocation-all.php",
					data : "function=LocationUpdate&location_id=" + location_id + "&kategorie=" + kategorie + "&firma=" + firma + "&country=" + country + "&street=" + street + "&phone=" + phone + "&postcode=" + postcode + "&city=" + city + "&url=" + url + "&email=" + email + "&comment=" + comment + "&usermail=" + usermail,
					error : function() {
						//UMGEHUNGSLÖSUNG DA FALSE VON getLatLngByAddress.php KOMMT WENN KEINEN lat lng ERMITTELT WERDEN KONNTE
					//	alert("FAIL Return php/tLocation-all.php?function=LocationFree");
						$.mobile.changePage("#map", {
							transition : "flip"
						});
					},
					success : function() {
						$.mobile.changePage("#map", {
							transition : "flip"
						});
					}
				});
				return false;
			});

		});
	});

});

// Schliessen Detail Ausgabe
$('#UpdateLocation').on("pagehide", function() {
	$('#LocationDetailHead').empty();
	$('#UpdateLocation').empty();
});
