<?php
header('content-type: application/json; charset=utf-8');
date_default_timezone_set('Europe/Berlin');
echo readHTML();

function readHTML() {
    /*http://www.schattenbaum.net/php/for.php
     einlesen von webseiten und speichern source as text*/
    /*
     Modus    Funktion               Dateizeiger    Anlegen?
     ==========================================================
     r        Lesen                  Anfang         Nein
     r+       Lesen und Schreiben    Anfang         Nein
     w        Schreiben              Anfang         Ja
     w+       Lesen und Schreiben    Anfang         Ja
     a        Schreiben              Ende           Ja
     a+       Lesen und Schreiben    Ende           Ja
     */

    $datei = fopen("C:\Apache24\htdocs\buch81\DB\test.txt", "w+");
    echo $datei;
    if (is_readable($datei)) {
        echo 'Die Datei ist lesbar';
    } else {
        echo 'Die Datei ist nicht lesbar';
    }

    $id = 30;
    $start = 7349;
    $date = date('Y/m/d H:i:s');

    fwrite($datei, $date);

    for ($count = 1; $count < $id; $count++) {

        //$homepage = file_get_contents('http://www.bandweb.ch/ad_det.asp?id='.$start);
        $homepage = file_get_contents('http://jamfinder.info');
        //echo $homepage;
        fwrite($datei, $homepage);

        $start = $start + $count;
        echo $count, ", ";
    }
    $date = date('Y/m/d H:i:s');
    fwrite($datei, $date);
    fclose($datei);

    //parseFile($datei);

}

function parseFile($datei) {

    $input = fopen($datei, "r+");
    $output = fopen("email.txt", "r+");

    //string parsen

    fclose($output);
    fclose($input);
}
?>