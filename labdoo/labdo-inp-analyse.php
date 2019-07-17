<?php
// This program reads a file content and store the result in a database
// after it's done the inputfile was moved to archive
//$search_de = array('Beschreibung:', 'Produkt:', 'Hersteller:', 'Version:', 'Seriennummer:', 'Breite:');

/* How to use
 * 1; create your input file on client
 * 		sudo lshw >> /tmp/labdoo-ssd-$(date "+%b_%d_%Y_%m_%s")
 * 		sudo lshw -businfo
 *
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
 *hö
 * 7; run labdo-inp-analyse.php
 *
 * 8; Rechnernamen anpassen
 * https://wiki.ubuntuusers.de/Rechnername/
 * Der Rechnername muss in zwei Dateien in einer bestimmten Reihenfolge geändert werden. Die Änderungen kann man mit jedem beliebigen Editor[2][3] ausführen.

 Als erstes muss die Datei /etc/hosts geändert werden:

 127.0.0.1       localhost
 127.0.1.1       meinrechnername
 ...

 Nun (anschließend!) muss die Datei /etc/hostname angepasst werden:

 meinrechnername

 Wird dies als 1. Schritt gemacht, sperrt man sich aus und Programme können nicht mehr standardmäßig starten, da der Hostname nicht aufgelöst werden kann!

 Als letztes wird der Rechnername mittels des Befehls [3][4]:

 sudo hostname -F /etc/hostname

 gesetzt.
 */

//header('content-type: application/json; charset=utf-8'); //  behindert im Moment HTML ausgabe
date_default_timezone_set('Europe/Berlin');

// Create Database connection
//umwandeln sonderzeichen aus db WICHTIG überall
//include ('encode.inc');
session_start ();

// echo 'hallo : tJamContacts <br>';
header ( 'content-type: application/json; charset=utf-8' );
date_default_timezone_set ( 'Europe/Berlin' );

$timestamp = time ();
$datum = date ( "Y-m-d", $timestamp );
$uhrzeit = date ( "H:i:s", $timestamp );
// echo $datum, " - ", $uhrzeit, " Uhr <br>";


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

//echo "directory: " . $directory ;


// Text, ob ein Verzeichnis angegeben wurde
if (is_dir($directory)) {
	echo "(is_dir($directory: " . $directory ;
	// öffnen des Verzeichnisses
	if ($handle = opendir($directory)) {
		// einlesen der Verzeichnisses
		while (($file = readdir($handle)) !== false) {

			 echo "<li>Dateiname: " . $file;
			 echo " -- Dateityp: " . filetype($file);
			 echo "</li>" . "\n";


			if (is_file($file)) {
				$i = $i + 1;
				$input_files[$i] = $file;
					echo "<ul><li> input_files[$i] = " . $input_files[$i];
				$filename = $input_files[$i];
					echo "</li></ul>" . "\n";
				// call parser for file
				// must name of system input file
				echo " pos = filename: " . $filename;
				$pos = stripos($filename, 'labdoo-ssd-');

				if ($pos !== false) {
					echo "filename: " . $filename;
					echo parseFile($filename, $db);
				}
			}

		}
		closedir($handle);
		// use for testing labdoo-ssd-Apr_12_2016_04_1460441146  -- labdooscpssd-Apr_07_2016_04_1460002577*/
		//	$filename = "labdoo-ssd-Apr_12_2016_04_1460441146";
		//echo parseFile($filename, $db);

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
	$search_de = array('Beschreibung', 'Produkt', 'Hersteller', 'Version', 'Seriennummer', 'Breite');

	// Read lines from file
	$lines = file($filename);
	$i = 0;
	$myfile = file_get_contents($filename);

	foreach ($search_de as $search) {

		echo $search;
		$i = 0;

		foreach ($lines as $line) {
			$pos = 0;
			$len = 0;
			$pos = strpos($line, $search) + 2;
			//+2 erstezt ": "
			$len = strlen($search);
			//echo ' pos = ' ,$pos , ' $len =  ', $len ;

			// Check if the line contains the string we're looking for, and print if it does
			if (strpos($line, $search) !== false) {
				if ($i < 1) {
					echo "<ul><li> ";
					$value = substr($line, ($pos + strlen($search)), 50);

					switch ($search) {
						case Beschreibung :
							echo $value;
							$Beschreibung = $value;
							break;

						case description:
							echo $value;
							$Beschreibung = $value;
							break;

						case Produkt :
							echo $value;
							$Produkt = $value;
							break;

							case product:
							echo $value;
							$Produkt = $value;
							break;

						case Hersteller :
							echo $value;
							$Hersteller = $value;
							break;


						case vendor:
							echo $value;
							$Hersteller = $value;
							break;


						case Version :
							echo $value;
							$Version = $value;
							break;

						case Seriennummer :
							echo $value;
							$Seriennummer = $value;
							break;


						case  serial:
							echo $value;
							$Seriennummer = $value;
							break;



						case Breite :
							echo $value;
							$Breite = $value;
							break;

						case width:
							echo $value;
							$Breite = $value;
							break;
					}

					echo "</li></ul>\n";
					$i++;
				}
			}
		} //ende lines in
	}//ende search_de array

	$heute = date("Y-m-d");
	/* Insert Data in Database*/
	/*INSERT INTO `labdoo`(`insert_date`, `source_name`, `Beschreibung`, `Produkt`, `Hersteller`, `Version`, `Seriennummer`, `Bios`, `Labdoo-Nr`, `Client_Nr`, `Eingangsdatum`, `Ausgangsdatum`)
	 *  VALUES ([value-1],[value-2],[value-3],[value-4],[value-5],[value-6],[value-7],[value-8],[value-9],[value-10],[value-11],[value-12]) */
	/*INSERT INTO `labdoo_clients`(`Type`, `Name`, `Vorname`, `Contact_over`, `Client_Nr`)
	 * VALUES ([value-1],[value-2],[value-3],[value-4],[value-5]) */
	/*INSERT INTO `labdoo_files`(`source_name`, `source_content`) VALUES ([value-1],[value-2])*/

	$sql = "INSERT INTO labdoo(insert_date, source_name, Beschreibung, Produkt, Hersteller, Version, Seriennummer, Bios, Eingangsdatum )
	 VALUES( '" . $heute . "' ,  '" . $filename . "' ,  '" . $Beschreibung . "'   , '" . $Produkt . "', '" . $Hersteller . "' , '" . $Version . "', '" . $Seriennummer . "', '" . $Breite . "', '" . $heute . "') ";
	echo "sql: ", $sql;
	mysqli_query($db, $sql) or die(mysqli_error($db));

	$sql = "INSERT INTO labdoo_files(source_name, source_content) VALUES('" . $filename . "' ,  '" . $myfile . "' )";
	echo "sql: ", $sql;
	mysqli_query($db, $sql) or die(mysqli_error($db));

	//call procedur
	echo moveFile($filename);
};

function moveFile($filename) {
	echo "<ul><li> ";
	echo '-- moveFile = ' . $filename;
	echo "</li></ul>\n";
	//verschieben der bearbeiteten files
	rename($filename, '/srv/www/vhosts/jamfinder.info/httpdocs/labdoo/tmp/'. $filename);

	//If you want to keep the existing file on the same place you should use copy
	//copy($filename, '/srv/www/vhosts/jamfinder.info/httpdocs/labdoo/tmp/'. $filename);
};
echo "</ol>";
?>
