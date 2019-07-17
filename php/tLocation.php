<?php
//include ('connJAM.inc');
//require_once ('sendmail.inc');
//var_dump($_GET);
date_default_timezone_set('Europe/Berlin');

//umwandeln sonderzeichen aus db WICHTIG ?berall
include ('encode.inc');

$db = mysqli_connect("server56.hostfactory.ch", "jamfinder_usr", "Name0815", "jamfinder");
if (mysqli_connect_errno()) {
	printf("Verbindung fehlgeschlagen: %s\n", mysqli_connect_error());
	exit();
}

mysqli_query($db, "SET NAMES 'utf8'");

$timestamp = time();
//Location_id
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
$t_text = strip_tags($_GET['t_text']);
$t_day = strip_tags($_GET['t_day']);
$t_iteration = strip_tags($_GET['t_iteration']);
// $header = "Von: " .  $location . " <" . $l_email . ">rn";

//var_dump($_GET);
//location
$sql = ("INSERT INTO location(location_id, Kategorie, Firma, Country, City, Postcode, Street, email, url, Phone, Longitude, Latitude, Comment, usermail)
         VALUES ('" . $timestamp . "' , '" . $artanfrage . "' , '" . $location . "' , '" . $country . "' , '" . $city . "' , '" . $plz . "' , '" . $adr . "' , '" . $email . "' , '" . $url . "' , '" . $telefon . " ', 0 , 0, 'No Location Info', '" . $email . "')");
//echo '<br>location:  ' . $sql;

mysqli_query($db, $sql) or die(mysqli_error($db));

//termin
// solange die Auawahl wiederholt f?r Concert und Event ist abfangen und einschr?nken
if ($artanfrage == 'Concert') { $t_iteration = 0;
} else if ($artanfrage == 'Event') { $t_iteration = 0;
}

$sql = ("INSERT INTO termin (t_location_id, t_band_id, t_date , t_day , t_iteration, t_text, t_kategorie , t_eventname , t_usermail)
            VALUES ('" . $timestamp . "' , '" . $timestamp . "' , '" . $when . "' , '" . $t_day . "', '" . $t_iteration . "' , '" . $t_text . "','" . $artanfrage . "' , '" . $event . "',  '" . $email . "')");

//echo '<br>termin:' . $sql;
mysqli_query($db, $sql) or die(mysqli_error($db));
;

//Location
$sql = ("INSERT INTO actor (band_id, band_name, band_url, band_mail, band_logo, band_text, band_usermail)
             VALUES ('" . $timestamp . "' , '" . $event . "' , 'band url' , 'band mail' , '0' , 'band Text' ,  '" . $email . "')");

//echo '<br>Actor:  ' . $sql;

mysqli_query($db, $sql) or die(mysqli_error($db));
;


//JamContacts
$sql = "INSERT INTO JamContacts ( USERTYPE, EMAILADDRESS , URL, INDEXKEY, PWD, ADDRESS, POSTALCODE , CITY, COUNTRY)
                        VALUES( 'finder' ,  '" . $email . "' ,  '" . $url . "' , '" . $timestamp . "', '" . $timestamp . "' , '" . $adr . "', '" . $plz . "', '" . $city . "', '" . $country . "')";
//echo '<br>termin JamContacts:  ' . $sql;

mysqli_query($db, $sql) or die(mysqli_error($db));

//Close the database connection
//mysqli_close($db);
echo json_encode('success');

/*
 $m = mail_reguser($_REQUEST);
 mail('gd@jamfinder.info', $sql, "From: new-event@jamfinder.info");
 */
 // update position der LangLet
//	include ('fertig/getLatLngByAddress.php');
?>
