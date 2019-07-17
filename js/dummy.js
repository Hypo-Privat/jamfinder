					
// Ausgabe UPDATE Actor  Profile
$('#UpdateActor').on("pageshow", function() {
	//alert(" index: " + (daten.INDEXKEY));
	var UserMail = window.localStorage.getItem('UserMail');
	alert(" #UpdateActor " + UserMail);
	$.getJSON('php/tActor.php?function=getActorDetail&band_usermail=' + UserMail, function(data) {
		$.each(data, function(counter, daten) {
			alert(" aten.band_name: " + (daten.band_name));
 
			//band_id`, `band_name`, `band_url`, `band_mail`, `band_logo`, `band_text` , usermail?
			
			var ActorData = '<h2>Hello ' + daten.band_name  + ' your ID is ' + daten.band_id  +' </h2>'+ daten.band_logo  + ' <p> Here you can update your Actor or Band profile.<br> If you provide exact informations, about YOU, the band or group what you do. So we will deliver a proffessional correct service to your audience.</p>';
			$(ActorData).appendTo('#ActorDetailHead');
			alert(" #ActorData " + ActorData);
			// eventuell aufteilen in 3 teile
			$('.ActorDetail').html('<p><form id="ActorUpdate" ction="javascript:;" enctype="multipart/form-data" method="get" accept-charset="utf-8">'
			+ '<table data-role="table">' 	 
			//+ '<thead><tr><th colspan="1"></th><th colspan="1"></th></tr></thead>' 
			+ '<tbody><tr>' 
			+ '<td> <label for="band_name">Group Name</label></td><td><input data-mini="true" type="text" name="band_name" id="band_name" value="' + daten.band_name + '" size="10" maxlength="64"/></td>' 
			+ '</tr><tr>' 
			+ '<td><label for="band_mail">Mail</label></td><td><input data-mini="true" type="text" name="band_mail" id="band_mail" value="' + daten.band_mail + '" size="10" maxlength="64" /></td>' 
			+ '<td></td>' 
			+ '<td><label for="band_url">Homepage</label></td><td><input data-mini="true" type="text" name="band_url" id="band_url" value="' + daten.band_url + '" size="10" maxlength="64" /></td>'
			+ '</tr><tr>' 
			+ '<td><label for="band_text">band_text</label> <textarea data-mini="true" name="band_text" id="band_text" cols="50" rows="20" value="' + daten.band_text + '"></textarea>' 
			+ '<td></td>' 
			+ '<td><label for="band_logo">band_logo</label></td><td><input data-mini="true" type="text" name="band_logo" id="band_logo" value="' + daten.band_logo + '"size="10" maxlength="64"/></td>' 
			+ '</tr></tbody></table>'  
			+ '<div class="center-wrapper" data-role="controlgroup" data-type="horizontal">' 
			+ '<button data-mini="true" type="reset"  data-icon="delete" data-theme="c" id="reset">reset</button>'    	
			+ '<button data-mini="true" type="submit" data-icon="check"  data-theme="c" id="submitform">submit</button></div>' 
			+ '</form></p>');

			//Formdaten an PHP-Datei senden
			$("form#ActorUpdate").submit(function() {
				// we want to store the values from the form input box, then send via ajax below

				//alert("ActorUpdate Form");
				var band_name = $('#band_name').attr('value');
				var band_mail = $('#band_mail').attr('value');
				var band_url = $('#band_url').attr('value');
				var band_text = $('#band_text').attr('value');
				var band_logo = $('#band_logo').attr('value');
				var band_usermail = $UserKey;
				var band_id = $band_id;

				alert("function=ActorUpdate&band_usermail=" + UserMail + "&band_name=" + band_name + "&band_mail=" + band_mail + "&band_url=" + band_url + "&band_text=" + band_text + "&band_logo=" + band_logo+ "&band_id=" + band_id );
				
				$.ajax({
					type : "get",
					url : "php/tActor.php",
					data : "function=ActorUpdate&band_usermail=" + UserMail + "&band_name=" + band_name + "&band_mail=" + band_mail + "&band_url=" + band_url + "&band_text=" + band_text + "&band_logo=" + band_logo+ "&band_id=" + band_id  ,
					success : function() {
						$.mobile.changePage("#login-ok", {
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
	$('#ActorDetailHead').empty();
});
