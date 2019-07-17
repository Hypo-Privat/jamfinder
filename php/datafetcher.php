<?php
session_start();
// session_set_save_handler("oeffne", "schliesse", "lese", "schreibe", "loesche", "gc");
// session_id();

header('content-type: application/json; charset=utf-8');
date_default_timezone_set('Europe/Berlin');
// require_once ('connJAM.inc');
// Create Database connection

//umwandeln sonderzeichen aus db WICHTIG �berall
include ('encode.inc');

$db = mysqli_connect("server56.hostfactory.ch", "jamfinder_usr", "Name0815", "jamfinder");
if (mysqli_connect_errno()) {
	printf("Verbindung fehlgeschlagen: %s\n", mysqli_connect_error());
	exit();
}

if (isset($_GET["function"])) {
	$function = strip_tags($_GET['function']);
}
// f�r test $function = 'getEvents' ;
if ($function === 'getEvents') {
	$longitude = strip_tags($_GET['longitude']);
	$latitude = strip_tags($_GET['latitude']);
	$date = strip_tags($_GET['date']);

	/*
	 * 47.229563, 8.674967 wädenswil zu 47.377602, 8.539149 zürich HB
	 * latitude + - 0.15 ergibt einen Radius von 24km in welchem die positionen angezeigt werden.
	 */
	$range = 4; 
	// 7 ist bis Hamburg
	/*
	 * $sql = "select * from location , termin, actor
	 * where (location.location_id > 0)
	 *   and (longitude <= (8.5270899 + 1.01)  and longitude >= (8.5270899 - 1.01))
	 *   and (latitude <= (47.383080199999995 + 1.02) and latitude >= (47.383080199999995 - 1.01))
	 *   and (termin.t_location_id = location.location_id) and (termin.t_location_id = band_id) ORDER BY `location_id` ";
	 /*
	 * Einschr�nken bereich wenn testdaten geladen sind da sonnst alle angezeigt werden.
	 * and current_date >= DATE_SUB(t_date, INTERVAL 90 DAY)
	 */
	$sql = ("select location_id , Kategorie, Firma, Country, City,Postcode,Street,
                    email,url,Phone,Longitude,Latitude,Comment,
                    band_id ,band_name ,band_url ,band_mail ,band_logo ,band_text ,
                    t_location_id ,t_band_id,t_date,t_day,t_iteration,t_text,t_kategorie,t_eventname 
              from location , termin t, actor
              where (location.location_id > 0) 
               and (longitude <= (" . $longitude . " + " . $range . ") and longitude >= ( " . $longitude . " - " . $range . "))
	          and (latitude  <= (" . $latitude . " + " . $range . ")  and latitude  >= ( " . $latitude . " - " . $range . "))
	 
              and t_date >= current_date
              and t_date = (select MIN(t_date) from termin b where t.t_location_id = b.t_location_id)              
              and (t.t_location_id = location.location_id) and (t.t_band_id = band_id) 	          
              order by t_date ,  Postcode  , Kategorie limit 200  ");
			  
			  
	// current_date >= DATE_SUB(t_date, INTERVAL 90 DAY) range einschr�nken wenn mehr Nutzer da sind
	/*    and (longitude <= (" . $longitude . " + " . $range . ") and longitude >= ( " . $longitude . " - " . $range . "))
	          and (latitude  <= (" . $latitude . " + " . $range . ")  and latitude  >= ( " . $latitude . " - " . $range . "))	 
              and t_date >= current_date
	 */

	/*
	 * alle Daten anzeigen
	 * union
	 * select location_id ,Kategorie,Firma,Country,City,Postcode,Street,l_email,l_url,Phone,Longitude,Latitude,Comment,
	 * '' as band_id ,'' as band_name ,'' as band_url ,'' as band_mail ,'' as band_logo ,'' as band_text ,
	 * '' as t_location_id ,'' as t_band_id, '2019-01-31' as t_date,'' as t_day,'' as t_iteration,'' as t_text, Kategorie as t_kategorie, '' as t_eventname
	 * from location
	 * where location.location_id > 0 order by t_date , Country, Postcode , Kategorie
	 */

	// f�r test echo $sql;

	echo getEvents($sql, $db);
} elseif ($function === 'getEventsDetail') {

	$location_id = strip_tags($_GET['location_id']);
	If ($location_id === '') {
		$location_id = 1;
	}
	$sqlDetail = ("select location_id , Kategorie, Firma, Country, City,Postcode,Street,email,url,Phone,Longitude,Latitude,Comment,
                    band_id ,band_name ,band_url ,band_mail ,band_logo ,band_text ,
                    t_location_id ,t_band_id,t_date,t_day,t_iteration,t_text,t_kategorie,t_eventname 
              from location , termin, actor
              where (location.location_id = " . $location_id . ")  
              and (termin.t_location_id = location.location_id) and (termin.t_band_id = band_id) order by t_date
			   ");;

	// $sqlDetail = "select * from location where location_id = " . $location_id;
	// $sqlDetail = "select * from location where location_id = " . $location_id . " and ( " . $t_termin . " >= current date ";
	echo getEventsDetail($sqlDetail, $db);
}
function getEventsDetail($sqlDetail, $db) {
	// $result = get_MY_connect($sqlDetail);
	$result = mysqli_query($db, $sqlDetail) or die(mysqli_error($db));

	// Create an array
	$json_response = array();

	while ($row = mysqli_fetch_array($result)) {
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

		utf8_encode_deep($row_array);
		// push the values in the array
		array_push($json_response, $row_array);
	}
	// Close the database connection
	// mysqli_close($db);
	echo json_encode($json_response);
}

function getEvents($sql, $db) {
	// $result = get_MY_connect($sql);
	$result = mysqli_query($db, $sql) or die(mysqli_error($db));

	// Create an array
	$json_response = array();
	// $row_array['band_logo'] = $row['band_logo'];
	// ????Picture?
	while ($row = mysqli_fetch_array($result)) {
		$row_array['location_id'] = htmlentities($row['location_id']);
		$row_array['company'] = htmlentities($row['Firma']);
		$row_array['country'] = htmlentities($row['Country']);
		$row_array['city'] = htmlentities($row['City']);
		$row_array['postcode'] = htmlentities($row['Postcode']);
		$row_array['street'] = htmlentities($row['Street']);
		$row_array['url'] = htmlentities($row['url']);
		$row_array['longitude'] = htmlentities($row['Longitude']);
		$row_array['latitude'] = htmlentities($row['Latitude']);
		$row_array['comment'] = htmlentities($row['Comment']);

		$row_array['band_name'] = htmlentities($row['band_name']);
		$row_array['band_url'] = htmlentities($row['band_url']);
		$row_array['band_text'] = htmlentities($row['band_text']);

		$row_array['t_date'] = htmlentities($row['t_date']);
		$row_array['t_kategorie'] = htmlentities($row['t_kategorie']);
		$row_array['t_eventname'] = htmlentities($row['t_eventname']);
		$row_array['t_text'] = htmlentities($row['t_text']);

		utf8_encode_deep($row_array);
		// push the values in the array
		array_push($json_response, $row_array);

	}
	// Close the database connection
	mysqli_close($db);

	echo json_encode($json_response);

}
?>
