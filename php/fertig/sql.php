<?php

ini_set('max_execution_time', 300);

function get_MY_connect($sql) {
    //echo "sql", $sql;

    //Create Database connection
    mysql_pconnect("www.jamfinder.info", "jamfinder_usr", "Name0815");
    //mysql_pconnect("localhost", "test", "") or die(mysql_error('mysql_pconnect NOT Connected persistent to MySQL <br />'));
    //echo " mysql_pconnect Connected to Database jamfinder"; ;

    //. mysql_error());
    mysql_select_db('jamfinder') or die(mysql_error('mysql_select_db NOT Connected to Database Jamfinder <br />'));
    //echo "mysql_select_db Jamfinder";

}

function update_location($float_lat, $float_lng, $location_id) {
    /* update lat und lng on location */
    //echo 'Location_id: ' . $location_id . '; float_lat: ' . $float_lat . 'float_lng: ' . $float_lng . '<br />' . "\n";
    //bei table location
    $sql = "UPDATE location SET   latitude =  '" . $float_lat . "',
	 longitude = '" . $float_lng . "'  WHERE location_id = '" . $location_id . "'";

    /* bei tJamContacts
     $sql = "UPDATE JamContacts SET   latitude =  '" . $float_lat . "',
     longitude = '" . $float_lng . "'  WHERE indexkey = '" . $location_id . "'";
     */
    $ergebnis = mysql_query($sql);
  //  echo '  Ergebnis: ' . $ergebnis . '  ' . $sql . '<br />' . "\n";
}
?>