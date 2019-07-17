<?php

/**
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

	public function getLatLngByAddress($location_id, $address) {
		if (!is_object($result = self::_callAPI(array('address' => $address))))
			return false;

		if (!isset($result -> results[0]))
			return false;
		//auslesen der werte für speichern in DB
		$latlng = array('lat' => $result -> results[0] -> geometry -> location -> lat, 'lng' => $result -> results[0] -> geometry -> location -> lng);

		foreach ($latlng as $wert_des_array => $value) {
			//	echo 'Schlüssel: ' . $wert_des_array. '; Wert: ' . $value . '<br />' . "\n";
			//echo $location_id;
			if ($wert_des_array == 'lat') {
				$lat = $value;
				$float_lat = floatval($lat);
				//	echo $wert_des_array . ': ' . $float_lat;
			}
			if ($wert_des_array == 'lng') {
				$lng = $value;
				$float_lng = floatval($lng);
				//	echo $wert_des_array . ': ' . $float_lng . '<br />';

			}
			//schreiben der Daten
			update_location($float_lat, $float_lng, $location_id);
		}
		//return array('lat' => $result -> results[0] -> geometry -> location -> lat, 'lng' => $result -> results[0] -> geometry -> location -> lng);
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
?>