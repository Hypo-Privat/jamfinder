<?php
//umwandeln sonderzeichen aus db WICHTIG �berall
include ('encode.inc');

$timestamp = time();
//Location_id

date_default_timezone_set('Europe/Berlin');

$db = mysqli_connect("server56.hostfactory.ch", "jamfinder_usr", "Name0815", "jamfinder");
if (mysqli_connect_errno()) {
	printf("Verbindung fehlgeschlagen: %s\n", mysqli_connect_error());
	exit();
}
mysqli_query($db, "SET NAMES 'utf8'");

if (isset($_GET["function"])) {
	$function = strip_tags($_GET['function']);
}

if ($function === 'getTerminAll') {
	//echo "function hier getUserDetail ";
	$t_usermail = strip_tags($_GET['t_usermail']);

	$sql = " SELECT distinct * FROM termin     where (t_usermail= '" . $t_usermail . "') order by t_date";

	echo getTerminList($db, $sql);

} elseif ($function === 'TerminNew') {
	//  echo 'TerminNew' , $function;

   $t_kategorie = strip_tags($_GET['t_kategorie']);
   $t_day       = strip_tags($_GET['t_day']);
   $t_iteration = strip_tags($_GET['t_iteration']);
   $t_eventname = strip_tags($_GET['t_eventname']);
   $t_date      = strip_tags($_GET['t_date']);
   $t_text      = strip_tags($_GET['t_text']);
   $t_band_id   = strip_tags($_GET['t_band_id']);
   $t_location_id  =  strip_tags($_GET['t_location_id']);
   $t_usermail  =  strip_tags($_GET['t_usermail']);


	$sql = ("INSERT INTO termin (t_location_id, t_band_id, t_date , t_day , t_iteration, t_text, t_kategorie , t_eventname , t_usermail)
            VALUES ('" . $t_location_id. "' , '" . $t_band_id  . "' , '" . $t_date . "' , '" . $t_day . "', '" . $t_iteration . "' , '" . $t_text . "','" .  $t_kategorie . "' , '" .  $t_eventname . "',  '" . $t_usermail . "')");


	$_SESSION['loc_mail'] = $t_usermail;
	$_SESSION['function'] = 'mail_new_event';
	include(sendmail.php);

	 echo TerminInsUpd($db, $sql);

} elseif ($function === 'TerminDetail') {

 	$id = strip_tags($_GET['id']);
	$sql = " SELECT distinct t.* , a.band_name , a.band_url, l.Firma FROM termin t , actor a , location l
	     where id= '" . $id . "'
	     and t.t_band_id = a.band_id
	     and t.t_location_id = l.location_id";


		//  echo ' in function TerminDetail' ,$sql;
	echo getTerminDetail($db, $sql);
}
elseif ($function === 'TerminUpdate') {
	$id  	= strip_tags($_GET['id']);

	$t_date 		 	= strip_tags($_GET['t_date']);
	$t_day 		  	= strip_tags($_GET['t_day']);
	$t_iteration	 	= strip_tags($_GET['t_iteration']);
	$t_text 		   	= strip_tags($_GET['t_text']);
	$t_kategorie 	= strip_tags($_GET['t_kategorie']);
	$t_eventname 	= strip_tags($_GET['t_eventname']);


    $sql = " UPDATE termin SET

	t_date 		    =  '" . $t_date . "' ,
	t_day 		    =  '" . $t_day . "' ,
	t_iteration		    =  '" . $t_iteration . "' ,
	t_text 		    =  '" . $t_text . "' ,
	t_kategorie 		    =  '" . $t_kategorie . "' ,
	t_eventname 		    =  '" . $t_eventname . "'
	where id= '$id' ";

	 echo TerminInsUpd($db, $sql);
}

function TerminInsUpd($db, $sql) {
  //  echo ' in function TerminInsUpd' ,$sql;

    mysqli_query($db, $sql);

    //Close the database connection
   // mysqli_close($db);
    echo json_decode('success');

}

function getTerminDetail($db, $sql) {//umwandeln sonderzeichen aus db WICHTIG �berall
	// echo ' in function getTerminList: ' ,$sql;

	$result = mysqli_query($db, $sql);
	//Create an array
	$json_response = array();
	while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
		// echo ' t_usermail: ' ,$row['t_usermail'] ;
		if ($id = $row['id']) {
			$row_array['id'] = htmlentities($row['id']);
			$row_array['t_date'] = htmlentities($row['t_date']);
			$row_array['t_day'] = htmlentities($row['t_day']);
			$row_array['t_iteration'] = htmlentities($row['t_iteration']);
			$row_array['t_text'] = htmlentities($row['t_text']);
			$row_array['t_kategorie'] = htmlentities($row['t_kategorie']);
			$row_array['t_eventname'] = htmlentities($row['t_eventname']);
			$row_array['t_location_id'] = htmlentities($row['t_location_id']);
			$row_array['t_location_name'] = htmlentities($row['Firma']);
			$row_array['t_band_id'] = htmlentities($row['t_band_id']);
			$row_array['t_band_name'] = htmlentities($row['band_name']);
			$row_array['t_band_url'] = htmlentities($row['band_url']);

			//    echo ' t_location_id: ' ,$row['t_location_id'];
		} else {
			//echo "keinen datensatz gefunden";
		}

		//utf8_encode_deep($row_array);
		array_push($json_response, $row_array);
	}
	//Close the database connection
	//mysqli_close($db);
	echo json_encode($json_response , JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK);
}

function getTerminList($db, $sql) {//umwandeln sonderzeichen aus db WICHTIG �berall
	//  echo ' in function getTerminList: ' ,$sql;

	$result = mysqli_query($db, $sql);
	//Create an array
	$json_response = array();
	while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
		// echo ' t_usermail: ' ,$row['t_usermail'] ;
		if ($t_usermail = $row['t_usermail']) {
			$row_array['id'] = htmlentities($row['id']);
			$row_array['t_location_id'] = htmlentities($row['t_location_id']);
			$row_array['t_band_id'] = htmlentities($row['t_band_id']);
			$row_array['t_day'] = htmlentities($row['t_day']);
			$row_array['t_date'] = htmlentities($row['t_date']);
			$row_array['t_iteration'] = htmlentities($row['t_iteration']);
			$row_array['t_text'] = htmlentities($row['t_text']);
			$row_array['t_kategorie'] = htmlentities($row['t_kategorie']);
			$row_array['t_eventname'] = htmlentities($row['t_eventname']);
			//    echo ' t_location_id: ' ,$row['t_location_id'];
		} else {
			//echo "keinen datensatz gefunden";
		}

		//utf8_encode_deep($row_array);
		array_push($json_response, $row_array);
	}
	//Close the database connection
	//mysqli_close($db);
	echo json_encode($json_response , JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK);
}
?>
