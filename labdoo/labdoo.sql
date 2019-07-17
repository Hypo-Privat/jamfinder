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
