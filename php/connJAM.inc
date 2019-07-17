<?php

function get_MY_connect($sql) {
    // MYSQL config

    $db = mysqli_connect("server56.hostfactory.ch", "jamfinder_usr", "Name0815", "jamfinder");
    if (mysqli_connect_errno()) {
        printf("Verbindung fehlgeschlagen: %s\n", mysqli_connect_error());
        exit();
    }
    $result = mysqli_query($db, $sql) ;

    // echo '<br>$result($sql)' . $sql;
  
}
?>
