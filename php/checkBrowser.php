<?php
/* in php.ini einfügen
 [browscap]
 ;http://php.net/browscap
 browscap = C:/Apache24/htdocs/buch81/php/full_php_browscap.ini
 */
echo $_SERVER['HTTP_USER_AGENT'] . " <br>  <br> ";

//alternative ohne php.ini
echo "Browser : ", $_SERVER['HTTP_USER_AGENT'];
$br = $_SERVER['HTTP_USER_AGENT'];
//echo " <br>  <br> ". $br;
//echo " <br>  <br> " . strlen($br);

$browser = get_browser(null, true);
//print_r($browser);

// Ersatz fuer extract()
function myExtract($arr) {
    // diese Schleife koennte auch durch array_merge ersetzt werden,
    // die Schleife passt aber besser zu den naechsten beiden Beispielen
    foreach ($arr as $key => $val) {
        $GLOBALS[$key] = $val;
        // echo $val ;
        // echo $GLOBALS[$key] ;
    }
}

myExtract($browser);

$_GET['browser'] = ($browser . ' ' . $version);

echo "NEW : ", $_GET['browser'];
?>