<?php session_start();

//echo 'hallo : tJamContacts <br>';
header('content-type: application/json; charset=utf-8');
date_default_timezone_set('Europe/Berlin');

//umwandeln sonderzeichen aus db WICHTIG �berall
include ('encode.inc');

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

foreach ($_POST as $key => $value) {
	//  echo "<p>" . $key . " = " . $value . "</p>";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	// echo "It works!";
}
//var_dump($_POST);

if (isset($_GET["function"])) {
	$function = strip_tags($_GET['function']);
} elseif (isset($_POST["function"])) {
	$function = strip_tags($_POST['function']);
}

/*
 //Test record
 echo '$_Get = ' , $_GET , ' <br>';
 var_dump($_GET);
 echo '$_Post = ' . $_POST['submit'] . '\n';
 var_dump($_POST);
 echo '$_Server = ' , $_SERVER , ' <br>';
 var_dump($_SERVER);
 echo $_SERVER['REQUEST_METHOD'];
 */
//$function = 'user_login_check'; echo '$function = ' , $function ;

if ($function === 'user_new') {
	$adr = strip_tags($_GET['adr']);
	$plz = strip_tags($_GET['plz']);
	$city = strip_tags($_GET['city']);
	$country = strip_tags($_GET['country']);
	$email = strip_tags($_GET['u_email']);
	$pwd = strip_tags($_GET['u_pwd']);

	$sql = "INSERT INTO JamContacts ( USERTYPE, EMAILADDRESS , INDEXKEY, PWD, ADDRESS, POSTALCODE , CITY, COUNTRY)
                   VALUES( 'finder' ,  '" . $email . "' , '" . $timestamp . "', '" . $pwd . "' ,
                    '" . $adr . "', '" . $plz . "', '" . $city . "', '" . $country . "')";

	echo UserInsUpd($db, $sql);

} elseif ($function === 'getUserDetail') {
	//echo "function hier getUserDetail ";
	$INDEXKEY = strip_tags($_GET['INDEXKEY']);

	//zum testen	 $email = 'demo@a-t-c.ch';     $pwd = 'demo1234';

	$sql = " SELECT  INDEXKEY , PREFIX, FIRSTNAME, LASTNAME, USERTYPE,
    EMAILADDRESS , COMPANYNAME, ADDRESS, CITY, STATEPROVINCE, URL ,
    POSTALCODE, COUNTRY, ADDRESSNUMBER, LANG, PWD , BIRTHDATE, REMINDME
    FROM JamContacts
    where (INDEXKEY = '" . $INDEXKEY . "') ";

	echo getUserDetail($db, $sql);
} elseif ($function === 'userUpdate') {
	//echo "function hier userUpdate ";
	$INDEXKEY = strip_tags($_GET['INDEXKEY']);
	$COMPANYNAME = strip_tags($_GET['COMPANYNAME']);
	$FIRSTNAME = strip_tags($_GET['FIRSTNAME']);
	$LASTNAME = strip_tags($_GET['LASTNAME']);
	$ADDRESS = strip_tags($_GET['ADDRESS']);
	$ADDRESSNUMBER = strip_tags($_GET['ADDRESSNUMBER']);
	$POSTALCODE = strip_tags($_GET['POSTALCODE']);
	$CITY = strip_tags($_GET['CITY']);
	$STATEPROVINCE = strip_tags($_GET['STATEPROVINCE']);
	$COUNTRY = strip_tags($_GET['COUNTRY']);
	$REMINDME = strip_tags($_GET['REMINDME']);
	$BIRTHDATE = strip_tags($_GET['BIRTHDATE']);
	$LANG = strip_tags($_GET['LANG']);
	$URL = strip_tags($_GET['URL']);

	$sql = " UPDATE JamContacts SET
	COMPANYNAME    =  '" . $COMPANYNAME . "' ,
	FIRSTNAME		= '" . $FIRSTNAME . "',
	LASTNAME		= '" . $LASTNAME . "'	,
	ADDRESS		=     '" . $ADDRESS . "'	,
	ADDRESSNUMBER =	  '" . $ADDRESSNUMBER . "',
	POSTALCODE		= '" . $POSTALCODE . "',
	CITY			= '" . $CITY . "',
	STATEPROVINCE	= '" . $STATEPROVINCE . "',
	COUNTRY		=     '" . $COUNTRY . "'	,
	REMINDME	 =	  '" . $REMINDME . "',
	BIRTHDATE		= '" . $BIRTHDATE . "',
	LANG	=         '" . $LANG . "',
	URL	=         '" . $URL . "'
	where (INDEXKEY = '" . $INDEXKEY . "') ";

	echo UserInsUpd($db, $sql);

	//echo '<br>termin JamContacts:  ' . $sql;
	$_SESSION['parm'] = 'JamContacts';
	//require ('getLatLngByAddress.php');
} elseif ($function === 'user_login_check') {
	//echo "function hier user_login_check";
	$email = strip_tags($_GET['l_email']);
	$pwd = strip_tags($_GET['l_pwd']);
	//zum testen				   $email = 'demo@a-t-c.ch';     $pwd = 'demo1234';

	$sql = " SELECT  INDEXKEY ,  FIRSTNAME, LASTNAME, EMAILADDRESS , LANG, PWD, CITY
    FROM JamContacts
    where (emailaddress = '" . $email . "' and pwd = '" . $pwd . "') ";

	//echo "function hier user_login_check", $sql;
	echo user_login_check($db, $sql, $email, $pwd);

} elseif ($function === 'pwdupdate') {

	//echo "function hier userUpdate ";
	$EMAILADDRESS = strip_tags($_POST['p_email']);
	$PWD = strip_tags($_POST['p_pwd']);

	$sql = " UPDATE JamContacts SET
	PWD	=         '" . $PWD . "'
	where (EMAILADDRESS = '" . $EMAILADDRESS . "') ";

	echo UserInsUpd($db, $sql);
}

function UserInsUpd($db, $sql) {
	//echo ' in function UserInsUpd' ,$sql;
	//utf8_encode($sql);
	mysqli_query($db, $sql);
    echo json_encode('success');
	// update position der LangLet
	//  include ('fertig/getLatLngByAddress.php');

	//Close the database connection
	//mysqli_close($db);

}

function getUserDetail($db, $sql) {
	//echo ' in function getUserDetail' ,$sql;

	$result = mysqli_query($db, $sql);
	//Create an array
	$json_response = array();
	while ($row = mysqli_fetch_array($result)) {
		if ($INDEXKEY = $row['INDEXKEY']) {
			//echo "hier $row[2] $row[3] $row[7] ";
			//$json_response[] = $row;
			$row_array['INDEXKEY'] = utf8_encode($row['INDEXKEY']);
			$row_array['PREFIX'] = utf8_encode($row['PREFIX']);
			$row_array['FIRSTNAME'] = utf8_encode($row['FIRSTNAME']);
			$row_array['LASTNAME'] = utf8_encode($row['LASTNAME']);
			$row_array['COMPANYNAME'] = utf8_encode($row['COMPANYNAME']);
			$row_array['ADDRESS'] = utf8_encode($row['ADDRESS']);
			$row_array['CITY'] = utf8_encode($row['CITY']);
			$row_array['STATEPROVINCE'] = utf8_encode($row['STATEPROVINCE']);
			$row_array['POSTALCODE'] = utf8_encode($row['POSTALCODE']);
			$row_array['COUNTRY'] = utf8_encode($row['COUNTRY']);
			$row_array['ADDRESSNUMBER'] = utf8_encode($row['ADDRESSNUMBER']);
			$row_array['LANG'] = utf8_encode($row['LANG']);
			$row_array['BIRTHDATE'] = utf8_encode($row['BIRTHDATE']);
			$row_array['PWD'] = utf8_encode($row['PWD']);
			$row_array['REMINDME'] = utf8_encode($row['REMINDME']);
			$row_array['URL'] = utf8_encode($row['URL']);
			//var_dump($json_response);
			$row_array['login'] = true;
		} else {

			//echo "keinen datensatz gefunden";
		}

		//utf8_encode_deep($row_array);
		array_push($json_response, $row_array);
		echo json_encode($json_response  ,  JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK);
			//Close the database connection
		//mysqli_close($db);
	}
}

function user_login_check($db, $sql, $email, $pwd) {

	$result = mysqli_query($db, $sql );
	//Create an array
	$json_response = array();

	//echo "hier user_login_check" . $sql . '  ' .  $email  . '  '. $pwd;

	while ($row = mysqli_fetch_array($result)) {
		if ($email = utf8_encode($row['EMAILADDRESS']) and $pwd = utf8_encode($row['PWD'])) {

			//$json_response[] = $row;
			$row_array['INDEXKEY'] =  utf8_encode(($row['INDEXKEY']));
			$row_array['FIRSTNAME'] = utf8_encode(($row['FIRSTNAME']));
			//echo "hier = " . $row_array['FIRSTNAME'] .   "\n";
			$row_array['LASTNAME'] =   utf8_encode(($row['LASTNAME']));
			$row_array['CITY'] =   utf8_encode(($row['CITY']));
			$row_array['EMAILADDRESS'] =  utf8_encode($row['EMAILADDRESS']);
			$row_array['LANG'] =  utf8_encode($row['LANG']);

			$index = utf8_encode($row['INDEXKEY']);
			$email = utf8_encode($row['EMAILADDRESS']);

			// in tabelle speichern
			save_login($db, $index, $email);


			//echo $row_array['FIRSTNAME'] . "\n";

			// best�tigt das user angemeldet ist
			$row_array['login'] = true;

			array_push($json_response, $row_array);
			//$json_response["success"] = 1;

			//http://php.net/manual/de/json.constants.php
			echo json_encode($json_response  ,  JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK);
		//	echo "hier" ;

		} else {
			$row_array['login'] = false;
			echo "keinen datensatz gefunden";
		}

	}
	//Close the database connection
	//mysqli_close($db);
}

function save_login($db, $index, $email) {

	// Userip lesen
	$ipaddress = '';
	if (getenv('HTTP_CLIENT_IP'))
		$ipaddress = getenv('HTTP_CLIENT_IP');
	else if (getenv('HTTP_X_FORWARDED_FOR'))
		$ipaddress = getenv('HTTP_X_FORWARDED_FOR');
	else if (getenv('HTTP_X_FORWARDED'))
		$ipaddress = getenv('HTTP_X_FORWARDED');
	else if (getenv('HTTP_FORWARDED_FOR'))
		$ipaddress = getenv('HTTP_FORWARDED_FOR');
	else if (getenv('HTTP_FORWARDED'))
		$ipaddress = getenv('HTTP_FORWARDED');
	else if (getenv('REMOTE_ADDR'))
		$ipaddress = getenv('REMOTE_ADDR');
	else
		$ipaddress = 'UNKNOWN';

	//echo " save_login ipaddress  ". $ipaddress . "\n";
	$sql = "INSERT INTO  login
	( EMAILADDRESS , INDEXKEY, IN_OUT )	VALUES
	('" . $email . "' , '" . $index . "', '" . $ipaddress . "')";

	// save logindata echo $sql;
	mysqli_query($db, $sql);

}
?>
