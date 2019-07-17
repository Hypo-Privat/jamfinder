<?php
session_start();
//echo 'hallo : tJamContacts <br>';
header('content-type: application/json; charset=utf-8');
date_default_timezone_set('Europe/Berlin');

//umwandeln sonderzeichen aus db WICHTIG �berall
require ('encode.inc');

$timestamp = time();
$datum = date("Ymd", $timestamp);
$uhrzeit = date("His", $timestamp);
//echo $datum, " - ", $uhrzeit, " Uhr  <br>";

$db = mysqli_connect("server56.hostfactory.ch", "jamfinder_usr", "Name0815", "jamfinder");
if (mysqli_connect_errno()) {
	printf("Verbindung fehlgeschlagen: %s\n", mysqli_connect_error());
	exit();
}
mysqli_query($db, "SET NAMES 'utf8'");

if (isset($_GET["function"])) {
	$function = strip_tags($_GET['function']);
}
//echo 'Function' , $function;

if ($function === 'LocationNew') {
	// echo 'hier' , $function;

	$kategorie = strip_tags($_GET['kategorie']);
	$firma = strip_tags($_GET['firma']);
	$country = strip_tags($_GET['country']);
	$street = strip_tags($_GET['adr']);
	$telefon = strip_tags($_GET['telefon']);
	$postcode = strip_tags($_GET['plz']);
	$city = strip_tags($_GET['city']);
	$country = strip_tags($_GET['country']);
	$url = strip_tags($_GET['url']);
	$email = strip_tags($_GET['email']);
	$comment = strip_tags($_GET['comment']);
	$usermail = strip_tags($_GET['usermail']);

	//echo 'location New :', $Firma;

	$sql = ("INSERT INTO location(location_id, Kategorie, Firma, Country, City, Postcode, Street, email, url, Phone, Longitude, Latitude, Comment, usermail)
         VALUES ('" . $timestamp . "' , '" . $kategorie . "' , '" . $firma . "' , '" . $country . "' , '" . $city . "' , '" . $postcode . "' , '" . $street . "' , '" . $email . "' , '" . $url . "' , '" . $telefon . " ', 0 ,  0 , ' " . $comment . "' , '" . $email . "')");

	//echo 'location New :', $sql;
	$_SESSION['parm'] = 'location';
	require ('getLatLngByAddress.php');

	echo LocationInsUpd($db, $sql);

	//JamContacts
	$sql = "INSERT INTO JamContacts ( USERTYPE, EMAILADDRESS , URL, INDEXKEY, PWD, ADDRESS, POSTALCODE , CITY, COUNTRY)
                        VALUES( '" . $kategorie . "' ,  '" . $email . "' ,  '" . $url . "' , '" . $timestamp . "', '" . $timestamp . "' , '" . $street . "', '" . $postcode . "', '" . $city . "', '" . $country . "')";
	//echo '<br>termin JamContacts:  ' . $sql;
	//$_SESSION['parm'] = 'JamContacts';
	//require ('getLatLngByAddress.php');
	//$_SESSION['parm'] = 'location';
	//require ('getLatLngByAddress.php');


	echo json_decode('success');

} elseif ($function === 'getLocationDetail') {
	//  echo "function hier getUserDetail ";
	$location_id = strip_tags($_GET['location_id']);
	$sql = " SELECT  location_id, Kategorie, Firma, Country, City, Postcode, Street, email, url, Phone, Longitude, Latitude, Comment, usermail
    FROM location     where (location_id = " . $location_id . ") ";
	//zum testen       echo $sql ;
	echo getLocationDetail($db, $sql);

} elseif ($function === 'getLocationList') {
	// DONE echo "function hier getLocationList ";
	$usermail = strip_tags($_GET['usermail']);

	//zum testen    echo $usermail;
	$sql = " SELECT  location_id, Kategorie, Firma, Country, City, Postcode, Street, email, url, Phone, Longitude, Latitude, Comment, usermail
    FROM location     where (usermail = '" . $usermail . "') ";

	//echo $sql ;
	echo getLocationList($db, $sql);

} elseif ($function === 'getLocationListAll') {
	// DONE echo "function hier getLocationList ";
	$usermail = strip_tags($_GET['usermail']);
	if (isset($usermail)) {
		//zum testen    echo $usermail;
		$sql = " (SELECT  location_id, Kategorie, Firma, Country, City, Postcode, Street, email, url, Phone, Longitude, Latitude, Comment, usermail
    FROM location     where (usermail = '" . $usermail . "'))
	union
	(SELECT  location_id, Kategorie, Firma, Country, City, Postcode, Street, email, url, Phone, Longitude, Latitude, Comment, usermail
    FROM location where location_id > 0)
    order by  Country, City, Postcode
	 ";
	} else {
		$sql = " SELECT  location_id, Kategorie, Firma, Country, City, Postcode, Street, email, url, Phone, Longitude, Latitude, Comment, usermail
    FROM location where location_id > 0   order by  Country, City, Postcode  ";
	}

	//echo $sql ;
	echo getLocationList($db, $sql);

} elseif ($function === 'LocationUpdate') {
	//echo "function hier LocationUpdate ";
	$location_id = strip_tags($_GET['location_id']);
	$Kategorie = strip_tags($_GET['kategorie']);
	$Firma = strip_tags($_GET['firma']);
	$Country = strip_tags($_GET['country']);
	$Street = strip_tags($_GET['street']);
	$Phone = strip_tags($_GET['phone']);
	$Postcode = strip_tags($_GET['postcode']);
	$City = strip_tags($_GET['city']);
	$country = strip_tags($_GET['country']);
	$url = strip_tags($_GET['url']);
	$email = strip_tags($_GET['email']);
	$Comment = strip_tags($_GET['comment']);
	$usermail = strip_tags($_GET['usermail']);

	$sql = " UPDATE location SET Firma='" . $Firma . "',Country='" . $Country . "',City='" . $City . "',Postcode='" . $Postcode . "',Street='" . $Street . "' ,email='" . $email . "',url='" . $url . "', Kategorie='" . $Kategorie . "', Phone='" . $Phone . "', Comment='" . $Comment . "', usermail='" . $email . "'
    WHERE location_id = '" . $location_id . "' ";

	// echo "function hier LocationUpdate ", $sql;
	$_SESSION['parm'] = 'location';
	// Aufruf include('getLatLngByAddress.php');
	echo LocationInsUpd($db, $sql);

} elseif ($function === 'LocationFree') {
	// insert unregistert user info of EVENT
	$artanfrage = strip_tags($_GET['artanfrage']);
	$event = strip_tags($_GET['event']);
	$location = strip_tags($_GET['location']);
	$when = strip_tags($_GET['when']);
	$adr = strip_tags($_GET['adr']);
	$plz = strip_tags($_GET['plz']);
	$city = strip_tags($_GET['city']);
	$country = strip_tags($_GET['country']);
	$url = strip_tags($_GET['url']);
	$email = strip_tags($_GET['email']);
	$telefon = strip_tags($_GET['telefon']);
	$text = strip_tags($_GET['text']);
	$day = strip_tags($_GET['day']);
	$iteration = strip_tags($_GET['iteration']);
	$band_id = strip_tags($_GET['band_id']);
	$location_id = strip_tags($_GET['location_id']);
	// $header = "Von: " .  $location . " <" . $l_email . ">rn";

	//var_dump($_GET);
	//location
	if ($location_id === '0') {
		$sql = ("INSERT INTO location (location_id, Kategorie, Firma, Country, City, Postcode, Street, email, url, Phone, Longitude, Latitude, Comment, usermail)
         VALUES ('" . $timestamp . "' , '" . $artanfrage . "' , '" . $location . "' , '" . $country . "' , '" . $city . "' , '" . $plz . "' , '" . $adr . "' , '" . $email . "' , '" . $url . "' , '" . $telefon . " ', 0 , 0, 'No Location Info', '" . $email . "')");
	//	echo '<br>location: = 0 ' . $sql;

		mysqli_query($db, $sql) or die(mysqli_error($db));

		// update position der LangLet
		$_SESSION['parm'] = 'location';

		//Nachricht an location manager
		$_SESSION['loc_mail'] = $email;
		$_SESSION['function'] = 'mail_new_event';
		include ('sendmail.php');
		//b>Fatal error</b>:  Cannot redeclare Google\update_location() (previously declared in C:\Apache24\htdocs\buch81\php\getLatLngByAddress.php:163) in <b>C:\Apache24\htdocs\buch81\php\getLatLngByAddress.php</b> on line <b>179</b><br />

	 	//require ('getLatLngByAddress.php');
	} else {
		//	echo '<br>location:  = 0 ';
	}

	if ($band_id === '0') {
		$sql = ("INSERT INTO actor (band_id, band_name, band_url, band_mail, band_logo, band_text, band_usermail)
             VALUES ('" . $timestamp . "' , '" . $event . "' , SUBSTRING_INDEX('" . $email . "', '@', -1) , '" . $email . "' , '0' , 'No Text' ,  '" . $email . "')");
			echo '<br>Actor:  ' . $sql;

		mysqli_query($db, $sql) or die(mysqli_error($db));
	} else {
		//	echo '<br>Actor: > 0  ';
			//Nachricht an Actor 		;
	}

	//termin
	// solange die Auawahl wiederholt f�r Concert und Event ist abfangen und einschr�nken
	/*if ($artanfrage == 'Concert') { $iteration = 0;
	 } else if ($artanfrage == 'Event') { $iteration = 0;
	 }*/

	if ($band_id === '0') {$band_id = $timestamp;
	} else {
	}
	if ($location_id === '0') {$location_id = $timestamp;
	} else {
	}

	$sql = ("INSERT INTO termin (t_location_id, t_band_id, t_date , t_day , t_iteration, t_text, t_kategorie , t_eventname , t_usermail)
            VALUES ('" . $location_id . "' , '" . $band_id . "' , '" . $when . "' , '" . $day . "', '" . $iteration . "' , '" . $text . "','" . $artanfrage . "' , '" . $event . "',  '" . $email . "')");

	//echo '<br>termin:' . $sql  ;
	mysqli_query($db, $sql) or die(mysqli_error($db)); ;

	//JamContacts
	$sql = "INSERT INTO JamContacts ( USERTYPE, EMAILADDRESS , URL, INDEXKEY, PWD, ADDRESS, POSTALCODE , CITY, COUNTRY)
                        VALUES( 'finder' ,  '" . $email . "' ,  '" . $url . "' , '" . $timestamp . "', '" . $timestamp . "' , '" . $adr . "', '" . $plz . "', '" . $city . "', '" . $country . "')";
	//echo '<br>termin JamContacts:  ' . $sql;

	mysqli_query($db, $sql) or die(mysqli_error($db));

	$_SESSION['parm'] = 'JamContacts';
	//b>Fatal error</b>:  Cannot redeclare Google\update_location() (previously declared in C:\Apache24\htdocs\buch81\php\getLatLngByAddress.php:163) in <b>C:\Apache24\htdocs\buch81\php\getLatLngByAddress.php</b> on line <b>179</b><br />
	//require ('getLatLngByAddress.php');

	//Close the database connection
	mysqli_close($db);
	//hier ERROR �bergeben
	echo json_encode('success');
}

function getLocationList($db, $sql) {
	// echo ' in getLocationList' ,$sql;

	$result = mysqli_query($db, $sql);
	//Create an array
	$json_response = array();

	while ($row = mysqli_fetch_array($result)) {
		$row_array['location_id'] = htmlentities($row['location_id']);
		$row_array['Firma'] = htmlentities($row['Firma']);
		$row_array['Country'] = htmlentities($row['Country']);
		$row_array['City'] = htmlentities($row['City']);
		$row_array['Postcode'] = htmlentities($row['Postcode']);
		$row_array['Street'] = htmlentities($row['Street']);
		$row_array['url'] = htmlentities($row['url']);

		//utf8_encode_deep($row_array);
		array_push($json_response, $row_array);
	}
	//Close the database connection
	mysqli_close($db);
	echo json_encode($json_response , JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK);
}

function getLocationDetail($db, $sql) {
	// echo ' in getLocationDetail', $sql;

	$result = mysqli_query($db, $sql);
	//Create an array
	$json_response = array();

	while ($row = mysqli_fetch_array($result)) {
		//echo ' in getLocationDetail', $row['location_id'];
		if ($location_id = $row['location_id']) {
			$row_array['location_id'] = htmlentities($row['location_id']);
			$row_array['kategorie'] = htmlentities($row['Kategorie']);
			$row_array['firma'] = htmlentities($row['Firma']);
			$row_array['country'] = htmlentities($row['Country']);
			$row_array['city'] = htmlentities($row['City']);
			$row_array['postcode'] = htmlentities($row['Postcode']);
			$row_array['street'] = htmlentities($row['Street']);
			$row_array['url'] = htmlentities($row['url']);
			$row_array['email'] = htmlentities($row['email']);
			$row_array['phone'] = htmlentities($row['Phone']);
			$row_array['comment'] = htmlentities($row['Comment']);

		} else {
			//echo "keinen datensatz gefunden", $sql;

		}

		//utf8_encode_deep($row_array);
		array_push($json_response, $row_array);
	}
	//Close the database connection
	//mysqli_close($db);
	echo json_encode($json_response , JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK);
}

function LocationInsUpd($db, $sql) {
	//echo ' in function UserInsUpd', $sql;
	//utf8_encode($sql);
	mysqli_query($db, $sql);

	require ('getLatLngByAddress.php');
	//Close the database connection
	//mysqli_close($db);
	echo json_encode('success');

}

/*
 $m = mail_reguser($_REQUEST);
 mail('gd@jamfinder.info', $sql, "From: new-event@jamfinder.info");
 */
?>
