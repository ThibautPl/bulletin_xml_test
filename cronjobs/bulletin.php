<?php

// FONCTIONS
//*******************************************************
include_once("bulletin_function.php");

// TRAITEMENT : on ne lance le traitement que si le bulletin est plus récent que le zip
//***************************************************************************************
// CAS DE BORDEAUX
$path_civb_xml = "F://wamp/www/epicure2/var/storage/bordeaux_xml/ifv_bulletin.xml";
$homepage = file_get_contents("http://www.vignevin-epicure.com/epicure2/index.php/fre/bulletin_modelisation/bordeaux_aquitaine_xml");
$path_file = "F://wamp/www/epicure2/var/storage/bordeaux_xml/carto.zip";

// VARIABLES : date de la derniere mise a jour du .tpl
$contenu_date_modification = contenu_balise("<date_modification>", "</date_modification>", $homepage);
$derniere_mis_a_jour = substr($contenu_date_modification, strpos($contenu_date_modification, " ") + 1, 15);

if ($derniere_mis_a_jour < 0) {
	 $derniere_mis_a_jour = 0;
}

echo "Derniere MAJ Bordeaux : $derniere_mis_a_jour \n";

// VERIFIER QUE LE FICHIER XML EXISTE ET AVOIR SA DATE DE MODIFICATION
if (file_exists($path_civb_xml)) {
	date_default_timezone_set("UTC");

	// date de modification du fichier XML
	$date_modification_export_xml = date("U", filemtime($path_civb_xml));
    echo "Modif XML Bordeaux = ".date('d/m/Y H:s', $date_modification_export_xml).". \n";

    // CONVERSION en nombres entiers
    $derniere_mis_a_jour = intval($derniere_mis_a_jour);
	$date_modification_export_xml = intval($date_modification_export_xml);

    // SI DATE DE MODIFICATION null, ARRETER le traitement
    if ($date_modification_export_xml == "") {
		echo "ERREUR : date de modification du XML Bordeaux absente. \n";
	}

	//  SI DATE DE MODIFICATION valide, CONTINUER le traitement
	elseif ($derniere_mis_a_jour > $date_modification_export_xml) {
	  	echo "TRAITEMENT : debut du traitement Bordeaux. \n";

		include("civb.php");
		include("graphiques_bdx.php");
		//include("pdf_bdx.php");

		echo "TRAITEMENT : fin du traitement Bordeaux. \n";
	}
	else {
	    // SI timestamp inferieur ALORS xml et zip a jour
		echo "Les fichiers XML et XSL de Bordeaux CIVB  sont a jour. \n";
	}
}
// SI LE FICHIER CIVB n'existe pas
else {
	echo "ERREUR : fichier XML du CIVB introuvable. \n";
	envoi_mail("epicure@vignevin.com", "bulletin XML CIVB", "ERREUR : fichier XML du CIVB introuvable.");
}


// TRAITEMENT : on ne lance le traitement que si le bulletin est plus récent que le zip
//***************************************************************************************
// CAS DU VAL DE LOIRE
$path_vdl_xml = "F://wamp/www/epicure2/var/storage/val_de_loire_xml/ifv_bulletin.xml";
$homepage = file_get_contents("http://www.vignevin-epicure.com/epicure2/index.php/fre/bulletin_modelisation/val_de_loire_xml");
$path_file = "F://wamp/www/epicure2/var/storage/val_de_loire_xml/archive.zip"; // export.'aaaa_mm_jj'

// VARIABLES : date de la derniere mise a jour du .tpl
$derniere_maj = contenu_balise("<date_modification>", "</date_modification>", $homepage);

if ($derniere_maj < 0) {
	 $derniere_maj = 0;
}

echo "Derniere MAJ VdL : ".date('d/m/Y H:s', $derniere_maj).". \n";

// VERIFIER QUE LE FICHIER XML EXISTE ET AVOIR SA DATE DE MODIFICATION
if (file_exists($path_vdl_xml)) {
	date_default_timezone_set("UTC");

	// date de modification du fichier XML
	$date_modif_xml = date("U", filemtime($path_vdl_xml));
    echo "Modif XML VdL = ".date('d/m/Y H:s', $date_modif_xml).". \n";

    // CONVERSION en nombres entiers
    $derniere_maj = intval($derniere_maj);
	$date_modif_xml = intval($date_modif_xml);

    // SI DATE DE MODIFICATION null, ARRETER le traitement
    if ($date_modif_xml == "") {
      echo "ERREUR : date de modification du XML VdL absente. \n";
	}

	//  SI DATE DE MODIFICATION valide, CONTINUER le traitement
	elseif ($derniere_maj > $date_modif_xml) {
	  	echo "TRAITEMENT : debut du traitement VdL. \n";

		include("val_de_loire.php");

		echo "TRAITEMENT : fin du traitement VdL. \n";
	}
	else {
	    // SI timestamp inferieur ALORS xml et zip a jour
		echo "Les fichiers XML et XSL d'Interloire sont a jour. \n";
	}
}
// SI LE FICHIER CIVB n'existe pas
else {
	echo "ERREUR : fichier XML d'Interloire introuvable. \n";
	envoi_mail("epicure@vignevin.com", "bulletin XML Val de Loire", "ERREUR : fichier XML Interloire introuvable.");
}


?>