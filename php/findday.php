<?php
header('content-type: application/json; charset=utf-8');
date_default_timezone_set('Europe/Berlin');

$db = mysqli_connect("server56.hostfactory.ch", "jamfinder_usr", "Name0815", "jamfinder");
if (mysqli_connect_errno()) {
	printf("Verbindung fehlgeschlagen: %s\n", mysqli_connect_error($db));
	exit();
}
mysqli_query($db, "SET NAMES 'utf8'");

$sql = " select t_location_id, t_day , t_date, t_iteration,  DAYOFWEEK(t_date) as day
    FROM termin   where current_date > t_date and t_iteration > 0" ;
echo "sql: ", $sql;

$result = mysqli_query($db, $sql) or die(mysqli_error($db));

while ($row = mysqli_fetch_array($result)) {
	$t_date = $row['t_date'];
	$t_day = $row['t_day'];
	$t_iteration = $row['t_iteration'];


	switch ($t_day) {
		case 1 :
			$w_day = "Sunday";
			break;
		case 2 :
			$w_day = "Monday";
			break;
		case 3 :
			$w_day = "Tuesday";
			break;
		case 4 :
			$w_day = "Wednesday";
			break;
		case 5 :
			$w_day = "Thursday";
			break;
		case 6 :
			$w_day = "Friday";
			break;
		case 7 :
			$w_day = "Saturday";
			break;
	}

	// echo '<br>$result($sql)' . $sql;
    //DAYOFWEEK(t_date) 1 = Sunday , 2 = monday ....
    /* 0 = einmalig
     * 1 = weekly
     * 2 = 14 t�gig
     * 3 = 1 im monat
     * 4 = 2 im Monat
     * 5 = 3 im Monat
     * 6 = letzter im Monat
      */
     // echo '$t_iteration - ' , $t_iteration;
	switch ($t_iteration) {
		case 0 :
			break;
		case 1 :
			$new_date = date_add($t_date, date_interval_create_from_date_string('7 days'));
			break;
		case 2 :
			$new_date = date_add($t_date, date_interval_create_from_date_string('14 days'));
			break;
		case 3 :
			$new_date = (date("Y-m-d", strtotime("first " . $w_day, mktime(1, 1, 1, date(m) + 1, 1, date("Y")))));

			break;
		case 4 :
			$new_date = (date("Y-m-d", strtotime("second " . $w_day, mktime(1, 1, 1, date(m) + 1, 1, date("Y")))));
			break;
		case 5 :
			$new_date = (date("Y-m-d", strtotime("third " . $w_day, mktime(1, 1, 1, date(m) + 1, 1, date("Y")))));
			break;
		case 6 :
			$new_date = (date("Y-m-d", strtotime("last" . $w_day, mktime(1, 1, 1, date(m) + 1, 1, date("Y")))));
		 break;
	}

//echo ' t_day ', $w_day ;
	//echo ' t_day ',  date("l", mktime($t_date)), ' day ', $t_day, ' date ', $t_date, "/n";
//	echo(date("Y-m-d", strtotime("first " . $w_day, mktime(1, 1, 1, date(m) + 1, 1, date("Y"))))), " first " . $w_day;
	//$new_date = (date("Y-m-d", strtotime("first " . $w_day, mktime(1, 1, 1, date(m) + 1, 1, date("Y")))));
	//$new = date_format($new_date, 'Y-m-d');
	echo '>> $tdate --  ' . $t_date  , " Newdate ---  " . $new_date , '<<' ;
	//echo $new_date("Y-m-d", strtotime($new_date)); // 07.11.2009


 	$upd = "update termin set t_date = '" . $new_date . "'
        where  t_location_id = " . $row['t_location_id'] . ";";
    echo "upd: ", $upd , "\n";
}

?>
