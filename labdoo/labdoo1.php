<?php
// This program reads a file content and store the result in a database
// after it's done the inputfile was moved to archive
//$search_de = array('Beschreibung:', 'Produkt:', 'Hersteller:', 'Version:', 'Seriennummer:', 'Breite:');

/* How to use
 * 1; create your input file on client
 * 		sudo lshw >> /tmp/labdoo-ssd-$(date "+%b_%d_%Y_%m_%s")
 *
 * 2; VSFTPD_Installation_und_Einrichtung
 * 		http://www.netcup-wiki.de/wiki/VSFTPD_Installation_und_Einrichtung
 * 		http://de.wikihow.com/Einen-FTP-Server-in-Ubuntu-Linux-einrichten
 *
 * 3; FTPs starten
 * 		sudo restart vsftpd
 *
 * 4; send file from client to your host
 * 		scp Quelldatei.bsp Benutzer@Host:Verzeichnis/Zieldatei.bsp
 *
 * pingu@pingu-HP-ENVY-TS-15-Notebook-PC:/tmp$ scp
 * 		usage: scp [-12346BCpqrv] [-c cipher] [-F ssh_config] [-i identity_file]
 [-l limit] [-o ssh_option] [-P port] [-S program]
 [[user@]host1:]file1 ... [[user@]host2:]file2
 *
 * 5; check your host input dir if files are there
 *
 * 6; create your database
 *
 * 7; run labdo-inp-analyse.php
 */

//header('content-type: application/json; charset=utf-8'); //  behindert im Moment HTML ausgabe
date_default_timezone_set('Europe/Berlin');

// Create Database connection
//umwandeln sonderzeichen aus db WICHTIG überall
//include ('encode.inc');
echo "<ol>";

$db = mysqli_connect("www.jamfinder.info", "jamfinder_usr", "Name0815", "jamfinder");
if (mysqli_connect_errno()) {
	printf("Verbindung fehlgeschlagen: %s\n", mysqli_connect_error());
	exit();
} else {
	//printf("Verbindung erfolgreich: %s\n", mysqli_connect_error());
}

//Ist verzeichnis wo php ausgeführt wird /var/www/html (hier muss der zu lesende File liegen.)
// musst du anpassen jedoch der "." muss bleiben
$directory = ".";
$input_files[] = '';
$i = 0;
echo $directory ;

// Text, ob ein Verzeichnis angegeben wurde
if (is_dir($directory)) {

	// öffnen des Verzeichnisses
	if ($handle = opendir($directory)) {
		// einlesen der Verzeichnisses
		while (($file = readdir($handle)) !== false) {
			/*
			 echo "<li>Dateiname: ";
			 echo $file;
			 echo " -- Dateityp: ";
			 echo filetype($file);
			 echo "</li><\n";
			 */

			if (is_file($file)) {
				$i = $i + 1;
				$input_files[$i] = $file;

			//	echo "<ul><li> ";
			//	echo $input_files[$i];
				$filename = $input_files[$i];
			//	echo "</li></ul>\n";
				// call parser for file
				echo parseFile($filename, $db);
			}

		}
		closedir($handle);
		// use for testing
		/* $filename = "labdooscpssd-Apr_07_2016_04_1460002577";
		 echo parseFile($filename, $db);
		 */
	}
}

function parseFile($filename, $db) {
	echo "<ul><li> ";
	echo '-- NEW FILE -- parseFile($filename, $db) = ', $filename;
	echo "</li></ul>\n";

	/*
	 Beschreibung: Notebook
	 Produkt: HP ENVY TS 15 Notebook PC (E1U31EA#UUZ)
	 Hersteller: Hewlett-Packard
	 Version: 0887100000305B00000320100
	 Seriennummer: 5CG327011M
	 Breite: 64 bits
	 **/

	// What to look for -- change array for each language you need

	$search = ('Produkt:');
	$search_de = array('Beschreibung:', 'Produkt:', 'Hersteller:', 'Version:', 'Seriennummer:', 'Breite:');

	// Read from file
	$lines = file($filename);
	$i = 0;
	foreach ($search_de as $search) {

		echo $search;

		$pos = 0;
		$len = 0;
		$pos = strpos($line, $search);
		$len = strlen($search);

		foreach ($lines as $line) {
			$pos = 0;
			$len = 0;
			$pos = strpos($line, $search);
			$len = strlen($search);
			//echo ' pos = ' ,$pos , ' $len =  ', $len ;

			// Check if the line contains the string we're looking for, and print if it does
			if (strpos($line, $search) !== false) {

				echo "<ul><li> ";
				$value = substr($line, ($pos + strlen($search)), 50);
				echo $value;
				echo "</li></ul>\n";

			}
		} //ende lines in
	}//ende search_de array

	/* Insert Data in Database
	 $sql = "INSERT INTO JamContacts ( insert_date, source_name , Beschreibung, Produkt, Hersteller, Version, Seriennummer, tackt, source_file)
                          VALUES( '" . current date . "' ,  '" . $filename . "' ,  '" . $url . "'   , '" . $timestamp . "', '" . $timestamp . "' , '" . $street . "', '" . $postcode . "', '" . $city . "', '" . $country . "')";

	 echo "sql: ", $sql;
	 *
	 */
	//call procedur
	echo moveFile($filename);
};

function moveFile($filename) {
	echo "<ul><li> ";
	echo ' ---- moveFile = ' . $filename;
	echo "</li></ul>\n";
	//verschieben der bearbeiteten files
	rename($filename, ' /srv/www/vhosts/jamfinder.info/httpdocs/labdoo/tmp/' . $filename);

	//If you want to keep the existing file on the same place you should use copy
	//copy($filename, '/srv/www/vhosts/jamfinder.info/httpdocs/labdoo//tmp/' . $filename);
};
echo "</ol>";
?>

