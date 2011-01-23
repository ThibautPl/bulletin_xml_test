<?php
// RENVOIE LE CONTENU DES BALISES XML
function contenu_balise($str1,$str2,$chaine) {
  	$pos1 = strrpos($chaine, $str1);
  	$pos2 = strrpos($chaine, $str2);
  	$longueur_balise = strlen($str1);
  	$conc = "";
  	$pos1 = $pos1+$longueur_balise;

  	for($i = $pos1; $i < $pos2; $i++)
   		$conc = $conc.$chaine[$i];

  	return $conc;
}


// ENVOI D'UN FICHIER ARCHIVE (*.zip) VERS LE SERVEUR FTP
function envoi_archive_via_ftp($path_file, $ftp_rename_file, $ftp_server, $ftp_username, $ftp_userpassword, $ftp_mode_envoi) {
	$fp = fopen($path_file, 'r');
	$conn_id = ftp_connect($ftp_server);

	if($conn_id == true) {
		$login_result = ftp_login($conn_id, $ftp_username, $ftp_userpassword);

		if($login_result) {
        	$ret = ftp_nb_put($conn_id, $ftp_rename_file, $path_file, $ftp_mode_envoi);

        	while ($ret == FTP_MOREDATA) {
        		echo ".";
        		$ret = ftp_nb_continue($conn_id);
       		}

	        if ($ret != FTP_FINISHED) {
	          	echo "ECHEC ENVOI FTP envoi_archive_via_ftp: il y a eu un probleme lors du chargement du fichier. \n";
	          	exit(1);
	        }

	        ftp_close($conn_id);
	       	fclose($fp);

	       	echo "ENVOI FTP : envoi du fichier avec succes. \n";
     	}
 	}
}


// ENVOI D'UN MAIL
function envoi_mail($to, $subject, $message) {
    $headers = "From: webmaster" . "\r\n" .
               "Reply-To: epicure@vignevin.com" . "\r\n" .
                 "X-Mailer: PHP/";

     mail($to, $subject, $message, $headers);
}



// RETOURNE LE timestamp DU FICHIER XML
// REMARQUE : $pathname chemin absolu ?
 function return_file_xml_timestamp($pathname) {
     $descripteur = fopen($pathname, "r");
     $timestamp = fread($descripteur, filesize($pathname));
     fclose($descripteur);
     return $timestamp;
}


// MISE A JOUR FORCEE DU timestamp DU FICHIER
function update_file_timestamp($pathname, $timestamp) {
    echo "FONCTION update_file_timestamp : ouverture du fichier pour le mettre a jour \n";

    $descripteur = fopen($pathname,"w+");
	fwrite($descripteur,$timestamp);
	fclose($descripteur);

	echo "FONCTION update_file_timestamp : fermeture du fichier mis a jour \n";
}
?>