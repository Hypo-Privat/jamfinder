$('#NewActor').on("pageshow", function() {
	//alert(" #UpdateActor " + band_id  );
	var UserMail = window.localStorage.getItem('UserMail');
	var UserKey = window.localStorage.getItem('UserKey');
	var UserFirstname = window.localStorage.getItem('UserFirstname');
	var UserLastname = window.localStorage.getItem('UserLastname');
	//alert(" NewActor ");

	var ActorNewData = '<h2  >Hello ' + UserFirstname + '  ' + UserLastname + ' your logged in with ' + UserMail + '</h2> <p>Here you can add your Formantion, Actor or Band . Please check the list if the Formation, Actor or Band is in. If yes, please do not add.</p>';
	$(ActorNewData).appendTo('#ActorNewHead');

	var tactor = '<legend ><b>Select existing Band/Act/Formation</b></legend><select  data-mini="true" name="termactor" id="termactor">';
	$.getJSON('php/tActor.php?function=getActorListAll&band_usermail=' + UserMail, function(data) {
		$.each(data, function(counter, daten) {
			tactor += '<option  value="' + daten.band_id + '">' + daten.band_name + ' - Web: ' + daten.band_url + '</option>';
		});
		tactor += '</select"> ';
		$('#ActorNewList').append(tactor);
	});

	$('#ActorNewForm').html(
	+'<label for="band_name">Style of Actor</label><input data-mini="true" type="text" name="band_typ"  id="band_typ" placeholder="Classic ?" >' 
	+ '<label for="band_name">Formation Name</label><input data-mini="true" type="text" name="band_name"  id="band_name" placeholder="Name of Actor" >' 
	+ '<label for="band_mail">Mail</label><input data-mini="true" type="text" name="band_mail" id="band_mail" placeholder="actor@page.com" >' 
	+ '<label for="band_url">Homepage</label><input data-mini="true" type="text" name="band_url"  id="band_url" placeholder="http://" >' 
	+ '<label for="band_text">Description</label><textarea data-mini="true" name="band_text" id="band_text"  cols="20" rows="5" wrap="virtual" ></textarea>' 
	+ '<!-- 	<label for="bandlogo">Picture</label>	<input type="file" name="ufile" > -->' 
	+ '');
	$("form#ActorNew").submit(function() {

		//alert("ActorUpdate Form");
		var band_typ = $('#band_typ').attr('value');
		var band_name = $('#band_name').attr('value');
		var band_mail = $('#band_mail').attr('value');
		var band_url = $('#band_url').attr('value');
		var band_text = $('#band_text').attr('value');
		var band_logo = $('#band_logo').attr('value');
		var band_usermail = UserMail;

		//alert("function=ActorNew&band_usermail=" + UserMail + "&band_name=" + band_name + "&band_mail=" + band_mail + "&band_url=" + band_url + "&band_text=" + band_text + "&band_typ=" + band_typ  + "&band_logo=" + band_logo);
		//http://abandon.ie/notebook/simple-file-uploads-using-jquery-ajax
		$.ajax({
			type : "get",
			url : "php/tActor.php",
			data : "function=ActorNew&band_usermail=" + UserMail + "&band_name=" + band_name + "&band_mail=" + band_mail + "&band_url=" + band_url + "&band_text=" + band_text + "&band_typ=" + band_typ + "&band_logo=" + band_logo,
			dataType : 'json',
			processData : false, // Don't process the files
			contentType : false, // Set content type to false as jQuery will tell the server its a query string request

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
$('#NewActor').on("pagehide", function() {
	$('#ActorNewHead').empty();
	$('#ActorNew').empty();
	$('#ActorNewList').empty();
});

