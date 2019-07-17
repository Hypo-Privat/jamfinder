<?php
header('content-type: application/json; charset=utf-8');
date_default_timezone_set('Europe/Berlin');

echo "ttermin-upd.php";
echo $function = upd_termin() ;
function upd_termin() {
    // MYSQL config
echo "in function upd_termin /n ";
    $db = mysqli_connect("server56.hostfactory.ch", "jamfinder_usr", "Name0815", "jamfinder");
    if (mysqli_connect_errno()) {
        printf("Verbindung fehlgeschlagen: %s\n", mysqli_connect_error($db));
        exit();
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
    $sql = "   select t_location_id, t_date, t_iteration,  DAYOFWEEK(t_date) as day,
    CASE COALESCE(t_iteration) 
    WHEN 1 THEN DATE_ADD(t_date, INTERVAL 7 DAY)
    WHEN 2 THEN DATE_ADD(t_date, INTERVAL 14 DAY)
    WHEN 3 THEN DATE_ADD(t_date, INTERVAL 28 DAY)
     WHEN 4 THEN DATE_ADD(t_date, INTERVAL 31 DAY)
      WHEN 5 THEN DATE_ADD(t_date, INTERVAL 31 DAY)
       WHEN 6 THEN DATE_ADD(t_date, INTERVAL 31 DAY)
        WHEN 7 THEN DATE_ADD(t_date, INTERVAL 31 DAY)
         
    END as new_date
    FROM termin 
    where current_date > t_date and t_iteration > 0";
    echo "sql: ", $sql;
    $result = mysqli_query($db, $sql) or die(mysqli_error($db));

    while ($row = mysqli_fetch_array($result)) {
        $upd = "update termin set t_date = '" . $row['new_date'] . "', t_day = '" . $row['day'] . "'
        where  (t_location_id = " . $row['t_location_id'] . " and t_date = '" . $row['t_date'] ."');";
        echo "upd: ", $upd , "\n";
        mysqli_query($db, $upd) or die(mysqli_error());
    }
   // mysqli_query($db ,'commit');
    mysqli_close($db);
}
?>