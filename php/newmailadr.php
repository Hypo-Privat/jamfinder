<?php
{
	// echo 'hallo : newmail.php ' . "\n" ;
	header ( 'content-type: application/json; charset=utf-8' );
	date_default_timezone_set ( 'Europe/Berlin' );
	
	$timestamp = time ();
	$datum = date ( "Y-m-d", $timestamp );
	$uhrzeit = date ( "H:i:s", $timestamp );
	
	// echo $datum, " - ", $uhrzeit, " Uhr <br>";
	
	// db connect einbinden
	
	$db = mysqli_connect ( "www.jamfinder.info", "jamfinder_usr", "Name0815", "jamfinder" );
	if (mysqli_connect_errno ()) {
		printf ( "Verbindung fehlgeschlagen: %s\n", mysqli_connect_error () );
		exit ();
	}
	mysqli_query ( $db, "SET NAMES 'utf8'" );
	
	$z = 0;
	
	// sende mail an Jamfinder
	$sql = " SELECT distinct EMAILADDRESS , PREFIX, FIRSTNAME , LASTNAME 
			FROM CONTACTS where substr(NOTE,1,4) != 'done' and EMAILADDRESS like '%@%' or EMAILADDRESS != NULL order by 1 DESC
			limit 0, 50 ";
	// limit 5 , 3 ";
	// where substr(NOTE,1,4) != 'done' and EMAILADDRESS != '' or EMAILADDRESS = NULL limit 0, 50
	// where EMAILADDRESS = 'gert.dorn@a-t-c.ch'
	// where substr(NOTE,1,4) != 'done' and EMAILADDRESS != '' or EMAILADDRESS = NULL limit 0, 50 "
	// The later will select rows starting from 5 and return three rows.
	
	/*
	 * (SELECT EMAILADDRESS , PREFIX, FIRSTNAME , LASTNAME FROM CONTACTS)
	 * (SELECT EMAILADDRESS , PREFIX, FIRSTNAME , LASTNAME FROM JamContacts);
	 */
	
	$result = mysqli_query ( $db, $sql );
	// echo $sql;
	// echo 'result ' .$result;
	while ( $row = mysqli_fetch_array ( $result, MYSQLI_ASSOC ) ) {
		$z = $z + 1;
		$email = htmlentities ( $row ['EMAILADDRESS'] );
		$prefix = htmlentities ( $row ['PREFIX'] );
		$first = htmlentities ( $row ['FIRSTNAME'] );
		$last = htmlentities ( $row ['LASTNAME'] );
		
		text_mail ( $email, $prefix, $first, $last, $z, $db );
		
		/*
		 * if (!isset($first) ) { $first = strip_tags($email);}
		 * //echo $first ,"<br>";
		 */
		// echo $email , " - " , $first ," - " , $last ,"<br>";
	}
}
function update_record($Empfaenger, $db) {
	$timestamp = time ();
	$datum = date ( "Y-m-d", $timestamp );
	$uhrzeit = date ( "H:i:s", $timestamp );
	
	$sql = " UPDATE CONTACTS SET	NOTE 	= 'done-" . $datum . $uhrzeit . "'	where EMAILADDRESS  = '" . $Empfaenger . "'";
	
	$sql = htmlentities ( $sql );
	mysqli_query ( $db, $sql );
	
	if (mysqli_errno ( $db )) {
		printf ( "update fehlgeschlagen: %s\n", mysqli_errno () );
		exit ();
	} else {
		// echo (" - success: %s\n");
	}
	
	// echo $sql ;
}
function text_mail($email, $prefix, $first, $last, $z, $db) {
	strip_tags ( $email );
	// echo $email , " - " , $first ," - " , $last ,"<br>";
	
	$ImgName = '<img src="jam-session.jpg" alt="jam-session" border="1">';
	
	// anschreiben contacts
	
	$nachricht = '<html><body>
	<table	style="border: 2px solid black; background-color: #FFFFE0; border-collapse: collapse; font-family: Georgia, Garamond, Serif; color: black;">
		<thead>
			<tr>
				<th colspan=2
					style="width: 50%; font: bold 24px/1.1em Arial, Helvetica, Sans-Serif; text-shadow: 1px 1px 4px black; letter-spacing: 0.3em; background-color: #BDB76B; color: white;">
					Deutsch - <a href=http://jamfinder.info> jamfinder.info </a> Jam
					Sessions, Karaoke, Poetry Slam in Ihrer Umgebung
				</th>
			</tr>
		</thead>
		<tbody style="font-style: normal;">
			<tr style="background-color: #FFF0FB;">
				<td><h2>
						Guten Tag ' . $first . ' ' . $last . ', <br> <br> wir
						sind ein Startup und haben eine App jamfinder.info entwickelt
						welche Ihnen im Umkreis von 100km ihrer Umgebung Tagesaktuell
						Jamsessions, Karaoke, Poetry Slam und andere Events anzeigt. <a
							href=http://jamfinder.info> jamfinder.info </a> <br>Im
						Moment haben wir vor allem Schweiz und Deutschland Events .
						Einzelne auch bereits weltweit. Die Informationen werden vor allem
						von Musikern/Akteuren/Bandmanager welche eine Tour planen und

						Veranstaltern/Veranstaltungsorten/Lokalen die eine Event haben
						eingetragen.<br> W&auml;re es nicht auch f&uuml;r euch
						Interessant die Veranstaltungen bei uns einzutragen.<br> Wir
						w&uuml;rden und freuen wenn Sie und unterst&uuml;tzen und Ihre
						Veranstaltung bei uns erfassen.
					</h2></td>
								</tr>
		</tbody>
		<tfoot style="border: 1px solid black; background-color: white;">
			<tr>
				<td colspan="4" class="rounded-foot-left"
					style="width: 50%; font: bold 18px/1.1em Arial, Helvetica, Sans-Serif;"><em>Sollten
						If you have any questions about our offer, please reply to this
						e-mail. We will contact you as soon as possible. Your <a
						href=http://jamfinder.info> jamfinder.info </a> Team
				</em></td>
			</tr>
		</tfoot>
	</table>
	<br>
	<br>
	<br>
	<table
		style="border: 2px solid black; background-color: #FFFFE0; border-collapse: collapse; font-family: Georgia, Garamond, Serif; color: black;">
		<thead>
			<tr>
				<th colspan=2
					style="width: 50%; font: bold 24px/1.1em Arial, Helvetica, Sans-Serif; text-shadow: 1px 1px 4px black; letter-spacing: 0.3em; background-color: #BDB76B; color: white;">
					ENGLISH -<a href=http://jamfinder.info> jamfinder.info </a> Jam
					Sessions, Karaoke, Poetry Slam in Ihrer Umgebung
				</th>
			</tr>
		</thead>
		<tbody style="font-style: normal;">
			<tr style="background-color: #FFF0FB;">
				<td><h2>
						Hello ' . $first . ' ' . $last . ', <br> <br> We are a
						startup and have developed an app <a href=http://jamfinder.info>
							jamfinder.info </a> which shows you daily jam sessions, karaoke,
						poetry slam and other events within 100km of its surroundings.
						Jamfinder.info At the moment we have mainly events in Switzerland
						and Germany. Individuals already worldwide. The information is
						mainly entered by musicians / actors / bandmanagers who plan a
						tour and organizers / venues / venues which have an event. Would
						not it be interesting for you to enter the events with us. We
						would be delighted to assist you and support you in your event.
					</h2></td>
			</tr>
		</tbody>
		<tfoot style="border: 1px solid black; background-color: white;">
			<tr>
				<td colspan="4" class="rounded-foot-left"
					style="width: 50%; font: bold 18px/1.1em Arial, Helvetica, Sans-Serif;"><em>Sollten
						Sie Fragen zu unserem Angebot haben, antworten sie auf diese
						E-Mail. Wir werden uns dann umgeghend bei Ihnen melden.<br>
						Ihr <a href=http://jamfinder.info> jamfinder.info </a> Team
				</em></td>
			</tr>
		</tfoot>
	</table> </body></html>';
	
	
	/*
	 * // Anschreiben User
	 *
	 * $nachricht = ' <html> <body>
	 * <p style="line-height: normal;">
	 * <span style="font-size: 12pt; font-family: &amp; amp;">Guten
	 * Tag,<br> <br> seit einiger Zeit werden&nbsp;Ihre Location
	 * und Ihre Veranstaltungen auf&nbsp; <a style="font-weight: bold;"
	 * href="http://jamfinder.info/"><span>jamfinder.info</span></a>
	 * angezeigt.
	 * </span>
	 * </p>
	 * <p style="line-height: normal;">
	 * <span style="font-size: 12pt; font-family: &amp; amp;">Um die
	 * Qualit&auml;t unseres Angebots zu erh&ouml;hen, bitten wir Sie sich
	 * mit Ihrer E Mail einzuloggen <br> und die angegebenen Termine zu
	 * pr&uuml;fen&nbsp;und zu aktualisieren. Falls Sie Ihr Passwort
	 * vergessen haben k&ouml;nnen Sie dieses in Menue login anfordern.
	 * </span>
	 * </p>
	 * <p style="line-height: normal;">
	 * <span style="font-size: 12pt; font-family: &amp; amp;"><br>
	 * Viele Musiker &ndash; K&uuml;nstler - Bandmanager welche ine Tour
	 * planen oder&nbsp;Veranstalter die einen Event machen nutzen unser
	 * Angebot.<br> <br> Wir w&uuml;rden uns freuen, wenn Sie und
	 * unterst&uuml;tzen und Ihre Daten aktualisieren und neue Events
	 * erfassen.<br> Wenn sie bereits erfasste Events sehen möschten
	 * suchen sie nach Berlin, Nürnberg oder Zürich.</span>
	 * </p>
	 * <p style="line-height: normal;">
	 * <span style="font-size: 12pt; font-family: &amp; amp;">Musikalische
	 * Gr&uuml;sse Team </span>
	 * </p>
	 * <p style="line-height: normal;">
	 * <span style="font-size: 12pt; font-family: &amp; amp;"><a
	 * style="font-weight: bold;" href="http://jamfinder.info/"><span>JAMFINDER.INFO</span></a>
	 * </span>
	 * </p>
	 * <p>
	 * <span
	 * style="font-size: 12pt; line-height: 107%; font-family: &amp; amp;"><o:p>&nbsp;</o:p></span>
	 * </p>
	 * <p>
	 * <span
	 * style="font-size: 12pt; line-height: 107%; font-family: &amp; amp;"
	 * lang="EN-GB">ENGLISH</span>
	 * </p>
	 * <p>
	 * <span style="font-size: 12pt; font-family: &amp; amp;" lang="EN">Good
	 * day,<br> <br> For some time your location and your events
	 * are displayed on<span style="font-weight: bold;"> </span><a
	 * style="font-weight: bold;" href="jamfinder.info"><span>jamfinder.info</span></a><span
	 * style="font-weight: bold;">.</span><br> To you to increase
	 * quality of our offer, please use your e mail address to log in<br>
	 * and check the dates specified and update. If you have forgotten your
	 * password you can request in menue login.<br> <br> Many musicians - artists - event
	 * manager planning a tour or organize an event use our offer.<br>
	 * <br> We would appreciate if you support us and update your data
	 * or insert new events.<br> If you like to see how it work, please
	 * search for Berlin, Zürich or Nürnberg, there are a lot of events.
	 * </span>
	 * </p>
	 * <p>
	 * <span style="font-size: 12pt; font-family: &amp; amp;" lang="EN"><br>
	 * Thank your team</span>
	 * </p>
	 * <p>
	 * <span style="font-size: 12pt; font-family: &amp; amp;" lang="EN"><br>
	 * <a style="font-weight: bold;" href="jamfinder.info"><span>JAMFINDER.INFO</span></a></span><span
	 * style="font-size: 12pt; font-family: &amp; amp;" lang="EN-GB"></span>
	 * </p>
	 * <p>
	 * <span><a href="http://jamfinder.info/"></a></span>
	 * </p></body> </html> ';
	 */
	
	// f�r HTML-E-Mails muss der 'Content-type'-Header gesetzt werden
	//echo $nachricht;
	  $header = "MIME-Version: 1.0. \r\n";
	  $header .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
	  $header .= "From: info@jamfinder.info\r\n";
	  $header .= "Reply-To: info@jamfinder.info\r\n";
	
	  $header .= 'Hello ' . $prefix . ' ' . $first . ' ' . $last ;
	 // $header .= $nachricht;
	
	 
	// f&uuml; r HTML-E-Mails muss der 'Content-type'-Header gesetzt werden
	
	// $Empfaenger = 'gd@jamfinder.info';
	$Empfaenger = $email;
	$Betreff = 'Jamsessions, Karaoke, Poetry Slam and other Events';
	mail ( $Empfaenger, $Betreff, " ", $header, $nachricht );
	echo ($z . ' ' . $email . ' ' . $first . ' ' . $last . "\r\n");
	// echo $nachricht;
	
	// update_record ( $Empfaenger, $db );
	
	return;
}

?>

