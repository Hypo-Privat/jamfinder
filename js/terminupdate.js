// Ausgabe UPDATE Termin  Profile
$('#UpdateTermin').on("pageshow", function() {
	var UserMail = window.localStorage.getItem('UserMail');
	var UserKey = window.localStorage.getItem('UserKey');
	var UserFirstname = window.localStorage.getItem('UserFirstname');
	var UserLastname = window.localStorage.getItem('UserLastname');
	var id = window.localStorage.getItem('id');
	//alert(" Termin update ");

	$.getJSON('php/tTermin.php?function=TerminDetail&id=' + id, function(data) {
		$.each(data, function(counter, daten) {

			var TerminData = '<h2>Hello ' + UserFirstname + '  ' + UserLastname + '</h2> <p> Here you can update the EVENT with actor <b>' + daten.t_band_name + '</b> at location  <b>' + daten.t_location_name + ' </b>.</p>';
			$(TerminData).appendTo('#TerminDetailHead');
			//alert(" #TerminData " + TerminData);

			//alert(" daten.id " + daten.id + " daten.t_iteration " + daten.t_iteration + " daten.t_day " + daten.t_day);

			var tt_iteration = 'tt_iterarion';
			switch (daten.t_iteration) {
			case "0":
				tt_iteration = '<option selected="selected" value="0">only once</option>';
				break;
			case "1":
				tt_iteration = '<option selected="selected" value="1">weekly</option>';
				break;
			case "2":
				tt_iteration = ' <option  selected="selected" value="2">all two weeks</option>';
				break;
			case "3":
				tt_iteration = '<option selected="selected"  value="3">every month</option>';
				break;
			case "4":
				tt_iteration = '<option selected="selected" value="4">first week a month</option>';
				break;
			case "5":
				tt_iteration = '<option selected="selected" value="5">second week a month</option>';
				break;
			case "6":
				tt_iteration = '<option selected="selected" value="6">third week a month</option>';
				break;
			case "7":
				tt_iteration = ' <option selected="selected" value="7">fourth week a month</option>';
				break;
			case "8":
				tt_iteration = ' <option selected="selected" value="8">last week a month</option>';
				break;

			}

			if ((daten.t_day < 1)) {
				var d = new Date(daten.t_date);
				daten.t_day = d.getDay();
				//alert ("t_day = " + t_day);
			}

			var tt_day = 'tt_day';
			switch (daten.t_day) {
			case  "1":
				tt_day = '<option selected="selected" value="1">Sunday</option>';
				break;
			case  "2":
				tt_day = ' <option  selected="selected" value="2">Monday</option>';
				break;
			case  "3":
				tt_day = '<option selected="selected"  value="3">Tuesday</option>';
				break;
			case  "4":
				tt_day = '<option selected="selected" value="4">Wednesday</option>';
				break;
			case  "5":
				tt_day = '<option selected="selected" value="5">Thursday</option>';
				break;
			case  "6":
				tt_day = '<option selected="selected" value="6">Friday</option>';
				break;
			case  "7":
				tt_day = ' <option selected="selected" value="7">Saturday</option>';
				break;

			}

			var tt_kategorie = 'tt_kategorie';
			switch (daten.t_kategorie) {
			case  "Jamsession":
				tt_kategorie = '<option selected="selected" value="Jamsession">Jam Session</option>';
				break;
			case  "Karaoke":
				tt_kategorie = '<option selected="selected" value="Karaoke">Karaoke</option>';
				break;
			case  "Poetry":
				tt_kategorie = '<option selected="selected" value="Poetry">Poetry Slam</option>';
				break;
			case   "Concert":
				tt_kategorie = '<option selected="selected" value="Concert">Concert</option>';
				break;
			case  "Event":
				tt_kategorie = '<option selected="selected" value="Event">other Event</option>';
				break;
			}

			//alert(" tt_iteration " + (tt_iteration) + " tt_day " + (tt_day));

			$('#TerminUpdateForm').html('<label for="tt_eventname">Eventname</label><input data-mini="true" type="text" name="tt_eventname" id="tt_eventname" value="' + daten.t_eventname + '" />' 
			+ '<label for="tt_kategorie">Kategorie</label><select name="tt_kategorie" id="tt_kategorie">' + tt_kategorie + '<option value="Jamsession">Jam Session</option><option value="Karaoke">Karaoke/Open Mic</option><option value="Poetry">Poetry Slam</option><option value="Concert">Concert</option><option  value="Event">other Event</option></select>' 
			+ '<label for="tt_day">Day</label><select name="tt_day" id="tt_day">' + tt_day + '<option value="2">Monday</option><option value="3">Tuesday</option><option value="4">Wednesday</option><option value="5">Thursday</option>	<option value="6">Friday</option><option value="7 ">Saturday</option><option value="1">Sunday</option> </select>' 
			+ '<label for="tt_iteration">Iteration</label><select name="tt_iteration" id="tt_iteration">' + tt_iteration + '<option value="0">only once</option> <option value="1">weekly</option>	 <option value="2">all two weeks</option> <option value="3">every month</option> <option value="4">first week a month</option> <option value="5">second week a month</option> <option value="6">third week a month</option> <option value="7">fourth week a month</option> <option value="8">last week a month</option> </select>' 
			+ '<label for="tt_date">when</label><input data-mini="true" type="date" name="tt_date" id="tt_date" value="' + daten.t_date + '" />' 
			+ '<label for="tt_text">Act Info</label><textarea data-mini="true" name="tt_text" id="tt_text" cols="20"rows="5" wrap="virtual">' + daten.t_text + '</textarea>');

			//Formdaten an PHP-Datei senden
			$("form#TerminUpdate").submit(function() {

				//alert("TerminUpdate Form");
				var t_eventname = $('#tt_eventname').attr('value');
				var t_kategorie = $('#tt_kategorie').attr('value');
				var t_date = $('#tt_date').attr('value');
				var t_day = $('#tt_day').attr('value');
				var t_iteration = $('#tt_iteration').attr('value');
				var t_text = $('#tt_text').attr('value');

				//alert("function=TerminUpdate&id=" + daten.id + "&t_eventname=" + t_eventname + "&t_day=" + t_day + "&t_iteration=" + t_iteration + "&t_text=" + t_text + "&t_kategorie=" + t_kategorie + "&t_date=" + t_date );
				$.ajax({
					type : "get",
					url : "php/tTermin.php",
					data : "function=TerminUpdate&id=" + id + "&t_eventname=" + t_eventname + "&t_day=" + t_day + "&t_iteration=" + t_iteration + "&t_text=" + t_text + "&t_kategorie=" + t_kategorie + "&t_date=" + t_date,
					success : function() {
						$('#TerminUpdateForm').empty();
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
$('#UpdateTermin').on("pagehide", function() {
	$('#TerminDetailHead').empty();
	$('#TerminDetail').empty();
	$('#TerminUpdateForm').empty();
	$('#TermActorList').empty();
	$('#TermLocList').empty();
});
