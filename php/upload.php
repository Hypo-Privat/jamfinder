<?php
header('Content-Type: text/plain; charset=utf-8');
date_default_timezone_set('Europe/Berlin');
/*
 echo 'upload.php = POST   ' ;
 var_dump($_POST);*/
echo '$_FILES =  ';
var_dump($_FILES);

// In PHP kleiner als 4.1.0 sollten Sie $HTTP_POST_FILES anstatt 
// $_FILES verwenden.

$uploaddir = './uploads/';
$uploadfile = $uploaddir . basename($_FILES['userfile']['name']);

echo '<pre>';
if (move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile)) {
    echo "Datei ist valide und wurde erfolgreich hochgeladen.\n";
} else {
    echo "Möglicherweise eine Dateiupload-Attacke!\n";
}

echo 'Weitere Debugging Informationen:';
print_r($_FILES);

print "</pre>";
?>