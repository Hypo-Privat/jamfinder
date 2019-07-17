<?php

session_start();

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
mysqli_query($db, "SET NAMES 'utf8'");

if (isset($_GET["function"])) {
	$function = strip_tags($_GET['function']);
}
//echo 'Function' , $function;$_FILES['band_logo']['name'])

//Test record echo $function , ' <br>'; $function = 'user_login_check';

if ($function === 'ActorNew') {
	//echo 'hier' , $function;

	$band_typ = strip_tags($_GET['band_typ']);
	$band_name = strip_tags($_GET['band_name']);
	$band_mail = strip_tags($_GET['band_mail']);
	$band_url = strip_tags($_GET['band_url']);
	$band_text = strip_tags($_GET['band_text']);
	$band_usermail = strip_tags($_GET['band_usermail']);

	//echo 'name: ', $band_usermail;
	$sql = ("INSERT INTO actor (band_id, band_name, band_url, band_mail,  band_text, band_usermail, band_typ)
         VALUES ('" . $timestamp . "' , '" . $band_name . "' , '" . $band_mail . "' , '" . $band_url . "' , '" . $band_text . "' , '" . $band_usermail . "' , '" . $band_typ . "')");
	//echo ' in function UserInsUpd' ,$sql;

	echo ActorInsUpd($db, $sql);

} elseif ($function === 'ActorFile') {

	upload_files();

} elseif ($function === 'getActorDetail') {
	//echo "function hier getUserDetail ";
	$band_id = strip_tags($_GET['band_id']);

	//zum testen	 $email = 'demo@a-t-c.ch';     $pwd = 'demo1234';

	$sql = " SELECT distinct *  FROM actor     where (band_id = '" . $band_id . "') ";

	echo getActorDetail($db, $sql);
} elseif ($function === 'getActorList') {
	//echo "function hier getActorList ";
	$band_usermail = strip_tags($_GET['band_usermail']);

	$sql = " (SELECT distinct *	FROM actor     where (band_usermail = '" . $band_usermail . "') and band_id > 0)";
	echo getActorList($db, $sql);
} elseif ($function === 'getActorListAll') {
	//echo "function hier getActorListAll ";
	$band_usermail = strip_tags($_GET['band_usermail']);

	//zum testen	 $email = 'demo@a-t-c.ch';     $pwd = 'demo1234';
	if (isset($band_usermail)) {
		$sql = "SELECT distinct *   FROM actor     where (band_usermail = '" . $band_usermail . "') and band_id > 0
			union
		SELECT distinct *  FROM actor where band_id > 0	 order by band_name ";
	} else {
		$sql = " SELECT distinct *    FROM actor where band_id > 0  order by band_name ";
	}
	//echo $sql;

	echo getActorList($db, $sql);
} elseif ($function === 'ActorUpdate') {
	//echo "function hier userUpdate ";
	$band_id = strip_tags($_GET['band_id']);
	$band_typ = strip_tags($_GET['band_typ']);
	$band_name = strip_tags($_GET['band_name']);
	$band_mail = strip_tags($_GET['band_mail']);
	$band_url = strip_tags($_GET['band_url']);
	$band_text = strip_tags($_GET['band_text']);
	$band_logo = strip_tags($_GET['band_logo']);
	$band_usermail = strip_tags($_GET['band_usermail']);

	$sql = " UPDATE actor SET
	band_typ 		    =  '" . $band_typ . "' ,
band_name 		    =  '" . $band_name . "' ,
band_mail 		    =  '" . $band_mail . "' ,
band_url  		    =  '" . $band_url . "' ,
band_text 		    =  '" . $band_text . "' ,
band_logo 		    =  '" . $band_logo . "' ,
band_usermail    =  '" . $band_usermail . "'
where (band_id= '" . $band_id . "') ";
	echo ActorInsUpd($db, $sql);

	echo json_decode('success');

	}



function ActorInsUpd($db, $sql) {
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

function getActorDetail($db, $sql) {
	//echo ' in function getUserDetail' ,$sql;

	$result = mysqli_query($db, $sql);
	//Create an array
	$json_response = array();
	while ($row = mysqli_fetch_array($result)) {
		if ($band_usermail = $row['band_usermail']) {
			$row_array['band_id'] = htmlentities($row['band_id']);
			$row_array['band_typ'] = htmlentities($row['band_typ']);
			$row_array['band_name'] = htmlentities($row['band_name']);
			$row_array['band_mail'] = htmlentities($row['band_mail']);
			$row_array['band_url'] = htmlentities($row['band_url']);
			$row_array['band_text'] = htmlentities($row['band_text']);
			$row_array['band_logo'] = htmlentities($row['band_logo']);
			$row_array['band_usermail'] = htmlentities($row['band_usermail']);

		} else {
			//echo "keinen datensatz gefunden";
		}

		utf8_encode_deep($row_array);
		array_push($json_response, $row_array);
	}
	//Close the database connection
	//mysqli_close($db);
	echo json_encode ( $json_response, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK );

}

function getActorList($db, $sql) {
	// echo ' in function getActorList' ,$sql;

	$result = mysqli_query($db, $sql);
	//Create an array
	$json_response = array();
	while ($row = mysqli_fetch_array($result)) {

		$row_array['band_id'] = htmlentities($row['band_id']);
		$row_array['band_typ'] = htmlentities($row['band_typ']);
		$row_array['band_name'] = htmlentities($row['band_name']);
		$row_array['band_mail'] = htmlentities($row['band_mail']);
		$row_array['band_url'] = htmlentities($row['band_url']);
		$row_array['band_text'] = htmlentities($row['band_text']);
		$row_array['band_logo'] = htmlentities($row['band_logo']);
		$row_array['band_usermail'] = htmlentities($row['band_usermail']);

		//utf8_encode_deep($row_array);
		array_push($json_response, $row_array);
	}
	//Close the database connection
	//mysqli_close($db);
	echo json_encode ( $json_response, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK );
	
}

function upload_files() {

	echo ' in function upload_files(', $band_usermail;
	/* http://www.ibm.com/developerworks/opensource/tutorials/os-phptut2/os-phptut2.html

	 The uploaded information

	 When you upload a file through the browser, PHP receives an array of information about it.
	 You can find this information in the $_FILE array, based on the name of the input field.
	 For example, your form has a file input with the name ufile, so all the information about that file is contained in the array $_FILE['ufile'].

	 This array allows the user to upload multiple files. As long as each of the files has its own name, it will have its own array.

	 Now, notice "$_FILE" is being called an array.
	 In Part 1 of this series, you had a situation in which an array value was itself an array when you passed multiple form values with the
	 same name for the password. In this case, each value of the $_FILE array is itself an associative array.
	 For example, your ufile file has the following information:

	 $_FILE['ufile']['name']—The name of the file (for example, uploadme.txt)
	 $_FILE['ufile']['type']—The type of the file (for example, image/jpg)
	 $_FILE['ufile']['size']—The size, in bytes, of the file that was uploaded
	 $_FILE['ufile']['tmp_name']—The temporary name and location of the file uploaded on the server
	 $_FILE['ufile']['error']—The error code, if any, that resulted from this upload
	 */

	//Read loaclstorage

	// $band_usermail = strip_tags($_POST['band_usermail']);
	echo "function hier upload_files ", $band_usermail, '<br>', $_POST['band_usermail'];

	var_dump($_POST['band_logo']);
	var_dump($_FILES['band_logo']['name']);

	print_r($_FILES['band_logo']);

	define("UPLOADEDFILES", "/tmp/");

	if (isset($_FILES['band_logo']['name'])) {
		echo "<p>Uploading: " . $_FILES['band_logo']['name'] . "</p>";

		$tmpName = $_FILES['band_logo']['tmp_name'];
		$newName = UPLOADEDFILES . $_FILES['band_logo']['name'] . xx;

		if (!is_uploaded_file($tmpName) || !move_uploaded_file($tmpName, $newName)) {
			echo "FAILED TO UPLOAD " . $_FILES['band_logo']['name'] . "<br>Temporary Name: $tmpName <br>";
		} else {

			save_document_info_json($_FILES['band_logo']);

			//echo "<h3>Available Files</h3>";

			display_files();
		}

	} else {
		echo "You need to select a file.  Please try again.";
	}

}

function save_document_info_json($file) {

	$jsonfile = UPLOADEDFILES . "docinfo.json";
	echo ' $jsonfile : ', $jsonfile;
	/*{"status":"pending","submittedBy":null,"approvedBy":"",
	 * "fileName":"Bildschirmfoto vom 2014-03-02 09:01:25.png",
	 * "location":"\/tmp\/",
	 * "fileType":"image\/png","size":207169}]}*/

	if (is_file($jsonfile)) {
		$jsonText = file_get_contents($jsonfile);
		$workflow = json_decode($jsonText, true);
		$statistics = $workflow["statistics"];
	} else {
		$jsonText = '{"statistics": {"total": 0, "approved": 0}, "fileInfo":[]}';
		$workflow = json_decode($jsonText, true);
		$workflow["statistics"]["approved"] = 0;
	}

	$filename = $file['name'];
	$filetype = $file['type'];
	$filesize = $file['size'];

	$fileInfo["status"] = "pending";
	$fileInfo["submittedBy"] = $_SESSION["username"];
	$fileInfo["approvedBy"] = "";

	$fileInfo["fileName"] = $filename;
	$fileInfo["location"] = UPLOADEDFILES;
	$fileInfo["fileType"] = $filetype;
	$fileInfo["size"] = $filesize;

	array_push($workflow["fileInfo"], $fileInfo);

	$total = count($workflow["fileInfo"]);
	$workflow["statistics"]["total"] = $total;

	file_put_contents($jsonfile, json_encode($workflow , JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK));
	
	
	/* noch einbauen
	 $sql = " UPDATE actor SET
	 band_logo     =  '" . $band_logo . "'
	 where (band_id= (select MAX'" . $band_id . "' from  actor whereband_usermail = '" . $band_usermail . "' )
	 and band_usermail = '" . $band_usermail . "' ) ";
	 echo ActorInsUpd($db, $sql);

	 */

}
?>
