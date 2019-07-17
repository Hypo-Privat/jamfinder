$('#UpdateActor').on("pageshow", function() {
	//alert(" #UpdateActor " + band_id  );

	var UserMail = window.localStorage.getItem('UserMail');
	var UserKey = window.localStorage.getItem('UserKey');
	var UserFirstname = window.localStorage.getItem('UserFirstname');
	var UserLastname = window.localStorage.getItem('UserLastname');
	var band_id = localStorage.band_id;
	//alert(" #UpdateActor " + band_id  );
	var ActorUpdateData = '<h2>Hello ' + UserFirstname + '  ' + UserLastname + '  </h2> <p>please update your Formation, Actor or Band. If you provide exact information, over the band or group. So we can deliver a professional correct service to your audience.</p>';
    $(ActorUpdateData).appendTo('#ActorUpdateHead');

	//alert(" #Actor " + UserMail);
	$.getJSON('php/tActor.php?function=getActorDetail&band_id=' + band_id, function(data) {
		$.each(data, function(counter, daten) {
			//alert(" #UpdateActor " + band_id);
				
			$('#ActorUpdateForm').html('<label for="u_band_name">Style of Actor</label><input data-mini="true" type="text" name="u_band_typ"  id="u_band_typ" value="' + daten.band_typ + '"  />' 
				+ '<label for="band_name">Band Name</label><input data-mini="true" type="text" name="band_name" id="u_band_name" value="' + daten.band_name + '"/>' 
			+ '<label for="band_mail">Band Mail</label><input data-mini="true" type="text" name="band_mail" id="u_band_mail" value="' + daten.band_mail + '"  />' 
			+ '<label for="band_url">Homepage</label><input data-mini="true" type="text" name="band_url" id="u_band_url" value="' + daten.band_url + '"  />' 
			+ '<label for="band_text">About you</label> <textarea data-mini="true" name="band_text" id="u_band_text" cols="20" rows="5" wrap="virtual">' + daten.band_text + '</textarea>' 
			//+ '<label for="band_logo">Picture</label><input id="band_logo" name="band_logo" type="file" accept="file_extension|audio/*|video/*|image/*|media_type" >'
			+ '<input type="hidden" id="u_band_id" name="band_id" value="' + daten.band_id + '">' );

			//Formdaten an PHP-Datei senden
			$("form#ActorUpdate").submit(function() {
				// we want to store the values from the form input box, then send via ajax below

				//alert("ActorUpdate Form");
				var band_typ = $('#u_band_typ').attr('value');
				var band_name = $('#u_band_name').attr('value');
				var band_mail = $('#u_band_mail').attr('value');
				var band_url = $('#u_band_url').attr('value');
				var band_text = $('#u_band_text').attr('value');
				var band_logo = $('#u_band_logo').attr('value');
				var band_usermail = UserKey;
				var band_id = $('#u_band_id').attr('value');

				//alert("function=ActorUpdate&band_usermail=" + UserMail + "&band_name=" + band_name + "&band_mail=" + band_mail + "&band_url=" + band_url + "&band_text=" + band_text + "&band_typ=" + band_typ + "&band_logo=" + band_logo + "&band_id=" + band_id);

				$.ajax({
					type : "get",
					url : "php/tActor.php",
					data : "function=ActorUpdate&band_usermail=" + UserMail + "&band_name=" + band_name + "&band_mail=" + band_mail + "&band_url=" + band_url + "&band_text=" + band_text + "&band_typ=" + band_typ + "&band_logo=" + band_logo + "&band_id=" + band_id,
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
	});

});


// Schliessen Detail Ausgabe
$('#UpdateActor').on("pagehide", function() {
	$('#ActorUpdateHead').empty();
});