<?php
namespace Google;
session_start();

header('content-type: application/json; charset=utf-8');
date_default_timezone_set('Europe/Berlin');

$db = mysqli_connect("www.jamfinder.info", "jamfinder_usr", "Name0815", "jamfinder");
if (mysqli_connect_errno()) {
	printf("Verbindung fehlgeschlagen: %s\n", mysqli_connect_error());
	exit();
}

ini_set('max_execution_time', 300);

$api = new \Google\MapsAPI;


$function = $_SESSION['parm'] ;
//echo '$function ', $function, '<br>';

If ($function === 'location') {
	//bei table location
	$sql = ("SELECT location_id ,Country, City, Postcode, Street
 		from location where latitude = 0");
} elseif ($function === 'JamContacts') {
	//bei table tJamContacts
	$sql = ("SELECT   ADDRESS, CITY,  POSTALCODE, COUNTRY,  INDEXKEY
 		FROM JamContacts where  latitude = 0 and  ADDRESS != '' || ADDRESS = NULL limit 1000");
}
;

//echo $sql, '<br>';

$result = mysqli_query($db, $sql) or die(mysqli_error($db));

while ($row = mysqli_fetch_array($result, MYSQL_ASSOC)) {
	/*var_EXPORT($api -> getLatLngByAddress(
	 * (htmlentities($row['location_id'])),
	 *  (htmlentities($row['Postcode'])),
	 * (htmlentities($row['City'])),
	 * (htmlentities($row['Street']))));
	 */
	If ($function === 'location') {
		//bei table location
		$location_id = htmlentities($row['location_id']);
		$address = (htmlentities($row['Country']) . ',+' . htmlentities($row['City']) . ',+ ' . htmlentities($row['Postcode']) . ' ' . htmlentities($row['Street']));
		//echo $address;

	} elseif ($function === 'JamContacts') {
		//bei table tJamContacts
		$location_id = htmlentities($row['INDEXKEY']);
		$address = (htmlentities($row['COUNTRY']) . ',+' . htmlentities($row['ADDRESS']) . ',+ ' . htmlentities($row['POSTALCODE']) . ' ' . htmlentities($row['CITY']));
	}

	var_dump($api -> getLatLngByAddress($db, $location_id, $address, $function));

	//  if ($api !== 'false') {

	/*echo htmlentities($$row['indexkey']);
	 echo htmlentities($row['INDEXKEY']), ' ';
	 echo htmlentities($row['COUNTRY']), ' ';
	 echo htmlentities($row['POSTALCODE']), ' ';
	 echo htmlentities($row['CITY']), ' ';
	 echo htmlentities($row['ADDRESS']) . '<br />' . "\n";

	 } */

}

/*
 * A demo class for our Google Maps API tutorial
 *
 * @author Michael Sommerfeld
 * @version 0.0.1
 * @package Google\MapsAPI
 * @link http://phplernen.org
 * @see https://developers.google.com/maps/documentation/geocoding/?hl=de&csw=1
 * @todo Add more functions
 */

class MapsAPI {
	const URL = 'http://maps.googleapis.com/maps/api/geocode/json?';

	/**
	 * Get lat and lng values for an given address
	 *
	 * @param string $address The address you want to get geocoordinates for
	 * @return (mixed[]|bool) Array with lat and lng as keys and appropriate values or false on error
	 */

	public function getLatLngByAddress($db, $location_id, $address, $function) {
		//echo 'URL adr: ' . $address;
		if (!is_object($result = self::_callAPI(array('address' => $address))))
			return false;

		if (!isset($result -> results[0]))
			return false;
		//auslesen der werte für speichern in DB
		$latlng = array('lat' => $result -> results[0] -> geometry -> location -> lat, 'lng' => $result -> results[0] -> geometry -> location -> lng);

		foreach ($latlng as $wert_des_array => $value) {
			//echo 'Schlüssel: ' . $wert_des_array . '; Wert: ' . $value . '<br />' . "\n";
			//echo $location_id;
			$count++;
			//$count = da zwei werte aus der schleife gelesen werden WICHTIG !!!
			if ($wert_des_array == 'lat') {
				$lat = $value;
				$float_lat = floatval($lat);
				//echo $wert_des_array . '--: ' . $float_lat . ' count--: ' . $count;
			}
			if ($wert_des_array == 'lng') {
				$lng = $value;
				$float_lng = floatval($lng);
				//echo $wert_des_array . '--: ' . $float_lng . ' count--: ' . $count . '<br />';
			}

			if ($count == 2) {
				//schreiben der Daten
				update_location($db, $float_lat, $float_lng, $location_id, $function);
				$count = 0;
				$lat = 0;
				$lng = 0;
				$float_lat = 0;
				$float_lng = 0;
			}

		}
		return array('lat' => $result -> results[0] -> geometry -> location -> lat, 'lng' => $result -> results[0] -> geometry -> location -> lng);
	}

	private function _callAPI($params, $sensor = 'false') {
		$ch = curl_init();

		// set some default values for request
		$params['sensor'] = $sensor;

		curl_setopt($ch, CURLOPT_URL, self::URL . http_build_query($params));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		$body = curl_exec($ch);
		$info = curl_getinfo($ch);

		curl_close($ch);

		if ($info['http_code'] !== 200)// Google Maps API not available
			return false;

		$body = json_decode($body);

		if ($body -> status !== 'OK')// Google Maps returns status not equal to OK
			return false;

		return $body;
	}

}

function update_location($db, $float_lat, $float_lng, $location_id, $function) {
	/* update lat und lng on location */
	echo 'Location_id: ' . $location_id . '; float_lat: ' . $float_lat . 'float_lng: ' . $float_lng . '<br />' . "\n";
	//echo 'fumction' ,$function;
	If ($function === 'location') {
		//bei table location
		$sql = "UPDATE location SET   latitude =  '" . $float_lat . "',
	        longitude = '" . $float_lng . "'  WHERE location_id = '" . $location_id . "'";
	} elseif ($function === 'JamContacts') {
		// bei tJamContacts
		$sql = "UPDATE JamContacts SET   latitude =  '" . $float_lat . "',
	 longitude = '" . $float_lng . "'  WHERE indexkey = '" . $location_id . "'";
	}
	$result = mysqli_query($db, $sql) or die(mysqli_error($db));
	// $ergebnis = mysql_query($sql);
	//echo '  Ergebnis: ' . $ergebnis . '  ' . $sql . '<br />' . "\n";
}
?>