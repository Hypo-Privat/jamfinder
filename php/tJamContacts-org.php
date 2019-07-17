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

if (isset($_GET["function"])) {
	$function = strip_tags($_GET['function']);
} elseif (isset($_POST["function"])) {
	$function = strip_tags($_POST['function']);
}

//Test record 
//echo $function , ' <br>'; 
//$function = 'user_login_check';

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
	
	
} elseif ($function === 'user_login_check') {
	//echo "function hier user_login_check"; 	
		$email = strip_tags($_GET['l_email']); 		
		$pwd = strip_tags($_GET['l_pwd']);
	//zum testen				   $email = 'demo@a-t-c.ch';     $pwd = 'demo1234';

	$sql = " SELECT  INDEXKEY ,  FIRSTNAME, LASTNAME, EMAILADDRESS , LANG, PWD
    FROM JamContacts
    where (emailaddress = '" . $email . "' and pwd = '" . $pwd . "') ";

	echo user_login_check($db, $sql);
}

elseif ($function === 'getUserDetail') {
	//echo "function hier getUserDetail "; 	
		$INDEXKEY = strip_tags($_GET['INDEXKEY']); 		
		
	//zum testen	 $email = 'demo@a-t-c.ch';     $pwd = 'demo1234';

	$sql = " SELECT  INDEXKEY , PREFIX, FIRSTNAME, LASTNAME, USERTYPE,
    EMAILADDRESS , COMPANYNAME, ADDRESS, CITY, STATEPROVINCE, URL ,
    POSTALCODE, COUNTRY, ADDRESSNUMBER, LANG, PWD , BIRTHDATE, REMINDME
    FROM JamContacts
    where (INDEXKEY = '" . $INDEXKEY. "') ";

	echo getUserDetail($db, $sql);
}

elseif ($function === 'userUpdate') {
	//echo "function hier userUpdate "; 	
	    $INDEXKEY	     = strip_tags($_GET['INDEXKEY']);       
		$COMPANYNAME     = strip_tags($_GET['COMPANYNAME']);
		$FIRSTNAME		 = strip_tags($_GET['FIRSTNAME']);
		$LASTNAME		 = strip_tags($_GET['LASTNAME']);
		$ADDRESS		 = strip_tags($_GET['ADDRESS']);
		$ADDRESSNUMBER	 = strip_tags($_GET['ADDRESSNUMBER']);
		$POSTALCODE		 = strip_tags($_GET['POSTALCODE']);
		$CITY			 = strip_tags($_GET['CITY']);
		$STATEPROVINCE	 = strip_tags($_GET['STATEPROVINCE']);
		$COUNTRY		 = strip_tags($_GET['COUNTRY']);
		$REMINDME	 	 = strip_tags($_GET['REMINDME']);
		$BIRTHDATE		 = strip_tags($_GET['BIRTHDATE']);
		$LANG			 = strip_tags($_GET['LANG']);
		$URL			 = strip_tags($_GET['URL']);
	
 $sql=" UPDATE JamContacts SET
	COMPANYNAME    =  '".$COMPANYNAME."' ,
	FIRSTNAME		= '".$FIRSTNAME."',
	LASTNAME		= '".$LASTNAME."'	,
	ADDRESS		=     '".$ADDRESS."'	,
	ADDRESSNUMBER =	  '".$ADDRESSNUMBER."',
	POSTALCODE		= '".$POSTALCODE."',
	CITY			= '".$CITY."',
	STATEPROVINCE	= '".$STATEPROVINCE."',
	COUNTRY		=     '".$COUNTRY."'	,
	REMINDME	 =	  '".$REMINDME."',
	IRTHDATE		= '".$BIRTHDATE."',
	LANG	=         '".$LANG."',
	URL	=         '".$URL."'
	where (INDEXKEY = '".$INDEXKEY."') ";

	echo UserInsUpd($db,$sql);

	//echo '<br>termin JamContacts:  ' . $sql;
	$_SESSION['parm'] = 'JamContacts';
	//require ('getLatLngByAddress.php');
}  

elseif ($function === 'user_login_check') {
	//echo "function hier user_login_check"; 	
		$email = strip_tags($_GET['l_email']); 		
		$pwd = strip_tags($_GET['l_pwd']);
	//zum testen				   $email = 'demo@a-t-c.ch';     $pwd = 'demo1234';

	$sql = " SELECT  INDEXKEY ,  FIRSTNAME, LASTNAME, EMAILADDRESS , LANG, PWD
    FROM JamContacts
    where (emailaddress = '" . $email . "' and pwd = '" . $pwd . "') ";

	echo user_login_check($db, $sql);


}

elseif ($function === 'pwupdate') {
	
	//echo "function hier userUpdate "; 	
	$EMAILADDRESS	     = strip_tags($_GET['p_email']);       
	$PWD			 = strip_tags($_GET['p_pwd']);
	
    $sql=" UPDATE JamContacts SET
	PWD	=         '".$PWD."'
	where (EMAILADDRESS = '".$EMAILADDRESS."') ";

echo UserInsUpd($db,$sql);
} 
			
function UserInsUpd($db, $sql) {
	//echo ' in function UserInsUpd' ,$sql;
	//utf8_encode($sql);
	mysqli_query($db, $sql);

	// update position der LangLet
	//  include ('fertig/getLatLngByAddress.php');

	//Close the database connection
	//mysqli_close($db);
	echo json_decode('success');

	/*
	 $m = mail_reguser($_REQUEST);
	 mail('gert.dorn@a-t-c.ch', $sql, "From: new-kunde@a-t-c.ch");
	 */
}	
		
function getUserDetail($db, $sql) {
	//echo ' in function getUserDetail' ,$sql;
	
	$result = mysqli_query($db, $sql);
	//Create an array
	$json_response = array();
		while ($row= mysqli_fetch_array($result)) {
		if ($INDEXKEY = $row['INDEXKEY'] ) {
			//echo "hier $row[2] $row[3] $row[7] ";
			//$json_response[] = $row;
			$row_array['INDEXKEY'] = htmlentities($row['INDEXKEY']);
			$row_array['PREFIX'] = htmlentities($row['PREFIX']);
			$row_array['FIRSTNAME'] = htmlentities($row['FIRSTNAME']);
			$row_array['LASTNAME'] = htmlentities($row['LASTNAME']);
			$row_array['COMPANYNAME'] = htmlentities($row['COMPANYNAME']);
			$row_array['ADDRESS'] = htmlentities($row['ADDRESS']);
			$row_array['CITY'] = htmlentities($row['CITY']);
			$row_array['STATEPROVINCE'] = htmlentities($row['STATEPROVINCE']);
			$row_array['POSTALCODE'] = htmlentities($row['POSTALCODE']);
			$row_array['COUNTRY'] = htmlentities($row['COUNTRY']);
			$row_array['ADDRESSNUMBER'] = htmlentities($row['ADDRESSNUMBER']);
			$row_array['LANG'] = htmlentities($row['LANG']);
			$row_array['BIRTHDATE'] = htmlentities($row['BIRTHDATE']);
			$row_array['PWD'] = htmlentities($row['PWD']);
 			$row_array['REMINDME'] = htmlentities($row['REMINDME']);
			$row_array['URL'] = htmlentities($row['URL']);
			//var_dump($json_response);
			$row_array['login'] = true;
		} else {
			
			//echo "keinen datensatz gefunden";			
		}
	
		utf8_encode_deep($row_array);
		array_push($json_response, $row_array);		
		echo json_encode($json_response);
		//echo "hier" ;
		//Close the database connection
		//mysqli_close($db);
	}	
}


function user_login_check($db, $sql) {
	//echo "hier user_login_check", $sql;
	$result = mysqli_query($db, $sql);
	//Create an array
	$json_response = array();

	while ($row = mysqli_fetch_array($result)) {
		if ($email = htmlentities($row['EMAILADDRESS']) and $pwd = htmlentities($row['PWD'])) {
			//echo "hier $row[2] $row[3] $row[7] ";
			//$json_response[] = $row;
			$row_array['INDEXKEY'] = htmlentities($row['INDEXKEY']);			
			$row_array['FIRSTNAME'] = htmlentities($row['FIRSTNAME']);
			$row_array['LASTNAME'] = htmlentities($row['LASTNAME']);
			$row_array['EMAILADDRESS'] = htmlentities($row['EMAILADDRESS']);			
			$row_array['LANG'] = htmlentities($row['LANG']);

			$index =  htmlentities($row['INDEXKEY']);
			$email =  htmlentities($row['EMAILADDRESS']);			
			save_login($db, $index, $email); 			// in tabelle speichern
			//echo $row_array['INDEXKEY'];   		
			//var_dump($json_response);
			// best�tigt das user angemeldet ist
			$row_array['login'] = true;
			
			
		} else {
			//$row_array['login'] = false;
			//echo "keinen datensatz gefunden";			
		}
		utf8_encode_deep($row_array);
		array_push($json_response, $row_array);
		echo json_encode($json_response);
		//echo "hier" ;
	}
	//Close the database connection
	//mysqli_close($db);
}

function save_login($db, $index, $email) {
	
	// Userip lesen
	 $ipaddress = '';	 
    if (getenv('HTTP_CLIENT_IP'))
        $ipaddress = getenv('HTTP_CLIENT_IP');
    else if(getenv('HTTP_X_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
    else if(getenv('HTTP_X_FORWARDED'))
        $ipaddress = getenv('HTTP_X_FORWARDED');
    else if(getenv('HTTP_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_FORWARDED_FOR');
    else if(getenv('HTTP_FORWARDED'))
        $ipaddress = getenv('HTTP_FORWARDED');
    else if(getenv('REMOTE_ADDR'))
        $ipaddress = getenv('REMOTE_ADDR');
    else
        $ipaddress = 'UNKNOWN';
	
	//echo "ipaddress  ", $ipaddress;
	$sql = "INSERT INTO  login
	( EMAILADDRESS , INDEXKEY, IN_OUT )	VALUES
	('" . $email . "' , '" . $index . "', '" . $ipaddress . "')";
       
	// save logindata echo $sql;
	mysqli_query($db, $sql);
	return;
}

?>
