//Ausgabe details zum Event
$('#event-detail').on("pageshow", function() {
	var event = sessionStorage.event;

	$.getJSON('php/datafetcher.php?function=getEventsDetail&location_id=' + event, function(data) {
		$.each(data, function(counter, daten) {
			if (daten.location_id > 0) {
				//alert(" id: " + (daten.t_kategorie));
				switch (daten.t_kategorie) {
				case "Jamsession":
					defaultpic = './img/default/jam1.jpg';
					break;
				case "Karaoke":
					defaultpic = './img/default/karaoke3.jpg';
					break;
				case "Concert":
					defaultpic = './img/default/concert3.jpg';
					break;
				case "Event":
					defaultpic = './img/default/event1.jpg';
					break;
				case "Poetry":
					defaultpic = './img/default/poetry1.jpg';
					break;
				default:
					defaultpic = './img/default/show.jpg';
					break;
				}
				//alert(" id: " + (defaultpic));
			
/*
 $row_array['location_id'] = htmlentities($row['location_id']);
		$row_array['category'] = htmlentities($row['Kategorie']);
		$row_array['company'] = htmlentities($row['Firma']);
		$row_array['country'] = htmlentities($row['Country']);
		$row_array['city'] = htmlentities($row['City']);
		$row_array['postcode'] = htmlentities($row['Postcode']);
		$row_array['street'] = htmlentities($row['Street']);
		$row_array['email'] = htmlentities($row['email']);
		$row_array['url'] = htmlentities($row['url']);
		$row_array['phone'] = htmlentities($row['Phone']);
		$row_array['longitude'] = htmlentities($row['Longitude']);
		$row_array['latitude'] = htmlentities($row['Latitude']);
		$row_array['comment'] = htmlentities($row['Comment']);
		// band band_id ,band_name ,band_url ,band_mail ,band_logo ,band_text ,
		$row_array['band_id'] = htmlentities($row['band_id']);
		$row_array['band_name'] = htmlentities($row['band_name']);
		$row_array['band_url'] = htmlentities($row['band_url']);
		$row_array['band_mail'] = htmlentities($row['band_mail']);
		// $row_array['band_logo'] = $row['band_logo'];)
		$row_array['band_text'] = htmlentities($row['band_text']);

		// termin t_location_id ,t_band_id,t_date,t_day,t_iteration,t_text,t_kategorie,t_eventname
		$row_array['t_location_id'] = htmlentities($row['t_location_id']);
		$row_array['t_band_id'] = htmlentities($row['t_band_id']);
		$row_array['t_date'] = htmlentities($row['t_date']);
		$row_array['t_day'] = htmlentities($row['t_day']);
		$row_array['t_iteration'] = htmlentities($row['t_iteration']);
		$row_array['t_text'] = htmlentities($row['t_text']);
		$row_array['t_kategorie'] = htmlentities($row['t_kategorie']);
		$row_array['t_eventname'] = htmlentities($row['t_eventname']);
 */
	 	//alert(" id: " + (daten.band_name) + " t_text: " + daten.band_url + " t_comment: " + daten.comment);
				var detailString = '<dl id="c2"><dt>' + daten.t_date + ' ' + daten.t_kategorie + ' ' + daten.t_eventname + '</dt><dd> <a href="' + daten.url + '" target="_blank"><img src="' + defaultpic + '" style="width: 100px;height:100px "align="middle" /></a><p><b><br>Actor: </b>' + daten.band_name + '- ' + daten.band_url + '<br>'+ daten.t_text + ' <br><br><b> Location: </b>' + daten.company + '  ' + daten.city + '  ' + daten.postcode + '  ' + daten.street + '<br>   ' + daten.comment + '</p></dd></dl>';
				$(detailString).appendTo('#event-content');
			}
		});
	});

});

//Schliessen Detail Ausgabe
$('#event-detail').on("pagehide", function() {
	$('#event-content').empty();
});


