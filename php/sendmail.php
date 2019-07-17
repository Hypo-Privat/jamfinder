<?php

date_default_timezone_set('Europe/Berlin');

$db = mysqli_connect("server56.hostfactory.ch", "jamfinder_usr", "Name0815", "jamfinder");
if (mysqli_connect_errno()) {
	printf("Verbindung fehlgeschlagen: %s\n", mysqli_connect_error());
	exit();
}
mysqli_query($db, "SET NAMES 'utf8'");
/*
 * echo "hier sendmail = " . $_SESSION['function'];
echo "_GET['function'] = " . $_GET['function'];
echo "_POST['function'] = " . $_POST['function'];
*/
if (isset($_GET['function'])) {
	$function = strip_tags($_GET['function']);
} elseif (isset($_POST["function"])) {
	$function = strip_tags($_POST['function']);
}
//echo 'hier sendmail = ' . $_SESSION['function'];
if (isset($_SESSION['function'])) {
	$function = $_SESSION['function'];
}

//$function = 'mail_new_event';
if ($function === 'mail_pwd') {
	//echo "function hier mail_pwd ";
	$p_email = strip_tags($_GET['p_email']);
	//$email = 'yourEmail@event.com';
	$sql = " SELECT  INDEXKEY ,  FIRSTNAME, LASTNAME, EMAILADDRESS , LANG, PWD
    FROM JamContacts      where (emailaddress = '" . $p_email . "') ";

	echo mail_pwd($db, $sql, $p_email);

} elseif ($function === 'mail_new_event') {
	//echo "function mail_new_even ";
	// use t_location email and T_actor E-mail
	$loc_mail = $_SESSION['loc_mail'];

	$sql = " SELECT   Firma,  email, usermail
    FROM location     where email = '" . $loc_mail . "' ";

	//echo "function mail_new_even " . $sql;
	echo mail_new_event($db, $sql, $loc_mail);
} elseif ($function === 'mail_reguser') {

}

function mail_pwd($db, $sql, $p_email) {

	//echo "mail_pwd", $sql;
	$result = mysqli_query($db, $sql);
	//Create an array
	$json_response = array();

	while ($row = mysqli_fetch_array($result)) {
		if ($email = htmlentities($row['EMAILADDRESS'])) {
			$pwd = htmlentities($row['PWD']);
			$email = htmlentities($row['EMAILADDRESS']);
			$fname = htmlentities($row['FIRSTNAME']);
			$lname = htmlentities($row['LASTNAME']);

		} else {
			//$row_array['login'] = false;
			echo "keinen datensatz gefunden";
		}
		echo "hier PWD = ", $pwd;
	}
	// Bitte passen Sie die folgenden Werte an, bevor Sie das Script benutzen!
	// An welche Adresse sollen die Mails gesendet werden?
	$to = $p_email;
	//noch einbauen
	//mehrere empfaenger
	//"Bcc: infos@infos24.de\r\nBcc:regionalportal@infos24.de\r\nfrom:schulungen@infos24.de\r\n");

	// Welchen Betreff sollen die Mails erhalten?
	$subject = 'Password Service Jamfinder.info';

	// Zu welcher Seite soll als "Danke-Seite" weitergeleitet werden?
	// Wichtig: Sie muessen hier eine gueltige HTTP-Adresse angeben!
	$strReturnhtml = "www.jamfinder.info#login";

	// Welche(s) Zeichen soll(en) zwischen dem Feldnamen und dem angegebenen Wert stehen?
	$strDelimiter = ":\t";

	If (isset($pwd)) {
		$mail_text = '<h1 align="center"><br> Hello ' . $fname . '  ' . $lname . ' , <br> you have requested your password for Jamfinder.info. <br><br><b>  Your password is : ' . $pwd . '<br><br></b></h1>';
		$login_link = '<h3 align="center"><a href="http://jamfinder.info#login">Login to Jamfinder.info</a></h3>';

	} else {
		$mail_text = '<h1 align="center"> Hello ' . $p_email . ' , <br> you have not registerd at Jamfinder.info.</h1> <h3 align="center"><a href="http://jamfinder.info/#register">Register on Jamfinder</a></h3>';
		$login_link = '';
	}

	//echo $mail_text ;

	$mail_footer = '<h1 align="center">Welcome to Jamfinder</h1><p align="center">We provide you daily<br><b>what is going on where you stay</b><br><b> Musicans </b>find information where is the next <b>JamSessions</b> in Town<br><b>Karaoke </b>fans will find next <b>Open Mic</b> location<br><b>Everyone</b> see where is a <b>concertor event</b> tonight.<br>Find <b>new guests</b> for yourlocation or <b>visitors</b> for your event.<br>Support us andadd your favorite event and share <b>JamFinder</b> with friends.<br><br><font color="red">Help us to make out Application better<br><a href="mailto:info@jamfinder.info" >info@jamfinder.info</a><br>Use <b>JamFinder</b> with your marketing to get </font>"walk-in customers".</p>';

	// message
	$message = $message . '<html><head><title>Password Service</title></head><body>';
	$message = $message . $mail_text;
	$message = $message . $login_link;
	$message = $message . $mail_footer;
	$message = $message . '</body></html>';

	//include "{$_SESSION['ENV']}stat/welcome_{$_SESSION['LANG']}.php";
	// To send HTML mail, the Content-type header must be set
	$headers = 'MIME-Version: 1.0' . "\r\n";
	$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

	// Additional headers
	$headers .= 'To:  ' . $email . "\r\n";
	// Welche Adresse soll als Absender angegeben werden?
	// (Manche Hoster lassen diese Angabe vor dem Versenden der Mail ueberschreiben)

	$headers .= 'From: info@jamfinder.info' . "\r\n";
	//$headers .= 'Cc: ' . "\r\n";
	//$headers .= 'Bcc: ' . "\r\n";

	// Mail it
	mail($to, $subject, $message, $headers);
	/*	echo "<table width='700'><tr><td class='titel'>";
	 echo "Password mail send !";
	 echo "	</td></tr>	</table>"; */
}

function mail_new_event($db, $sql, $loc_mail) {
	//echo "mail_new_event(", $sql;
	$result = mysqli_query($db, $sql);
	//Create an array
	$json_response = array();

	while ($row = mysqli_fetch_array($result)) {
		if ($email = htmlentities($row['email'])) {
			$firma = htmlentities($row['Firma']);
			$usermail = htmlentities($row['usermail']);

		} else {
			//$row_array['login'] = false;
			//echo "keinen datensatz gefunden";
		}
		//echo "hier usermail = ", $usermail, "hier email = ", $email;
	}
	// Bitte passen Sie die folgenden Werte an, bevor Sie das Script benutzen!
	// An welche Adresse sollen die Mails gesendet werden?
	$to = 'gd@jamfinder.info';
	//$email;
	//noch einbauen
	//mehrere empfaenger
	//"Bcc: infos@infos24.de\r\nBcc:regionalportal@infos24.de\r\nfrom:schulungen@infos24.de\r\n");

	// Welchen Betreff sollen die Mails erhalten?
	$subject = 'New Event at your location on Jamfinder.info';

	// Zu welcher Seite soll als "Danke-Seite" weitergeleitet werden?
	// Wichtig: Sie muessen hier eine gueltige HTTP-Adresse angeben!
	$strReturnhtml = "www.jamfinder.info#login";

	If (isset($email)) {
		$mail_text = '<h1 align="center"><br> Hello ' . $Firma . ' , <br> someone has added an NEW EVENT at your location. Please check if this was correct.<br>	If you have not logged in please requested your password for Jamfinder.info .
		<br><br><b>  Your email is: ' . $usermail . '<br><br></b></h1>';

		$login_link = '<h3 align="center"><a href="http://jamfinder.info#login">Login to Jamfinder.info</a></h3>';

	} else {
		$mail_text = '<h1 align="center"> Hello ' . $useremail . ' , <br> you have not registerd at Jamfinder.info.</h1> <h3 align="center"><a href="http://jamfinder.info/#register">Register on Jamfinder</a></h3>';
		$login_link = '';
	}

	//echo $mail_text ;

	$mail_footer = '<h1 align="center">Welcome to Jamfinder</h1><p align="center">We provide you daily<br><b>what is going on where you stay</b><br><b> Musicans </b>find information where is the next <b>JamSessions</b> in Town<br><b>Karaoke </b>fans will find next <b>Open Mic</b> location<br><b>Everyone</b> see where is a <b>concertor event</b> tonight.<br>Find <b>new guests</b> for yourlocation or <b>visitors</b> for your event.<br>Support us andadd your favorite event and share <b>JamFinder</b> with friends.<br><br><font color="red">Help us to make out Application better<br><a href="mailto:info@jamfinder.info" >info@jamfinder.info</a><br>Use <b>JamFinder</b> with your marketing to get </font>"walk-in customers".</p>';

	// message
	$message = $message . '<html><head><title>Password Service</title></head><body>';
	$message = $message . $mail_text;
	$message = $message . $login_link;
	$message = $message . $mail_footer;
	$message = $message . '</body></html>';

	//include "{$_SESSION['ENV']}stat/welcome_{$_SESSION['LANG']}.php";
	// To send HTML mail, the Content-type header must be set
	$headers = 'MIME-Version: 1.0' . "\r\n";
	$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

	// Additional headers . $email
	$headers .= 'To: '. $email . "\r\n";
	// Welche Adresse soll als Absender angegeben werden?
	// (Manche Hoster lassen diese Angabe vor dem Versenden der Mail ueberschreiben)

	$headers .= 'From: info@jamfinder.info' . "\r\n";
	$headers .= 'Cc: '. $useremail . "\r\n";
	//$headers .= 'Bcc: ' . "\r\n"; . $useremail

	// Mail it
	mail($to, $subject, $message, $headers);
	/*	echo "<table width='700'><tr><td class='titel'>";
	 echo "Password mail send !";
	 echo "	</td></tr>	</table>"; */
}
?>

