<?php

// VARIABLES : ftp
$ftp_rename_file = "carto.zip";
$ftp_server = "ftp2.aquitaine-valley.fr";
$ftp_username = "civbviti";
$ftp_userpassword = "ERT*741";
$ftp_mode_envoi = FTP_BINARY;

echo "TRAITEMENT : debut du traitement du XML. \n";

// MISE A JOUR DU TIMESTAMP DU FICHIER
update_file_timestamp("carto", $derniere_mis_a_jour);

// OUVERTURE DU FICHIER XML et recuperation des donnees
$file_xml = fopen($path_civb_xml, "w+");

$contenu = "<#xml version='1.0' encoding='iso-8859-1'#>
	<#xml-stylesheet href='ifv_bulletin.xsl' type='text/xsl'#>
	<ROOT>
		<RUBRIQUE>
			<LETITRE>BULLETIN</LETITRE>
			<LETEXTE>
				<![CDATA[ ";

$contenu .= str_replace("</h1>", "", str_replace("<h1>", "", contenu_balise("<titre>", "</titre>", $homepage)));

$contenu .= "]]>
			</LETEXTE>
		</RUBRIQUE>
		<RUBRIQUE>
			<LETITRE>DATE_JOUR</LETITRE>
			<LETEXTE>
				<![CDATA[";

$contenu .= contenu_balise("<date_jour>", "</date_jour>", $homepage);

$contenu .= "]]>
			</LETEXTE>
		</RUBRIQUE>
		<RUBRIQUE>
			<LETITRE>SEMAINE</LETITRE>
			<LETEXTE>
				<![CDATA[";

$contenu .= contenu_balise("<semaine>", "</semaine>", $homepage);

$contenu .= "]]>
			</LETEXTE>
		</RUBRIQUE>
		<RUBRIQUE>
			<LETITRE>SEMAIN-1</LETITRE>
			<LETEXTE>
				<![CDATA[";

$contenu .= contenu_balise("<semaine_moins>", "</semaine_moins>", $homepage);

$contenu .= "]]>
			</LETEXTE>
		</RUBRIQUE>
		<RUBRIQUE>
			<LETITRE>JOUR_DEB</LETITRE>
			<LETEXTE>
				<![CDATA[";

$contenu .= contenu_balise("<date_jour>", "</date_jour>", $homepage);

$contenu .= "]]>
			</LETEXTE>
		</RUBRIQUE>
		<RUBRIQUE>
			<LETITRE>DATE_J-1</LETITRE>
			<LETEXTE>
				<![CDATA[";

$contenu .= contenu_balise("<date_jour>", "</date_jour>", $homepage);

$contenu .= "]]>
			</LETEXTE>
		</RUBRIQUE>
		<RUBRIQUE>
			<LETITRE>DATE_J+6</LETITRE>
			<LETEXTE>
				<![CDATA[";

$contenu .= contenu_balise("<date_jour_plus>", "</date_jour_plus>", $homepage);

$contenu .= "]]>
			</LETEXTE>
		</RUBRIQUE>
		<RUBRIQUE>
			<LETITRE>JOUR_FIN</LETITRE>
			<LETEXTE>
				<![CDATA[";

$contenu .= contenu_balise("<date_jour_plus>", "</date_jour_plus>", $homepage);

$contenu .= "]]>
			</LETEXTE>
		</RUBRIQUE>
		<RUBRIQUE>
			<LETITRE>PROCHAIN</LETITRE>
			<LETEXTE>
				<![CDATA[";

$contenu .= contenu_balise("<date_jour_plus>", "</date_jour_plus>", $homepage);

$contenu .= "]]>
			</LETEXTE>
		</RUBRIQUE>
		<RUBRIQUE>
			<LETITRE>METEO_PREVUE</LETITRE>
			<LETEXTE>
				<![CDATA[";

$contenu .= contenu_balise("<prevision>", "</prevision>", $homepage);

$contenu .= "]]>
			</LETEXTE>
		</RUBRIQUE>
		<RUBRIQUE>
			<LETITRE>PLUVIOMETRIE</LETITRE>
			<LETEXTE>
				<![CDATA[";

$contenu .= contenu_balise("<pluviometrie>", "</pluviometrie>", $homepage);

$contenu .= "]]>
			</LETEXTE>
		</RUBRIQUE>
		<RUBRIQUE>
			<LETITRE>MILDIOU</LETITRE>
			<LETEXTE>
				<![CDATA[";

$contenu .= contenu_balise("<preconisation_mildiou>", "</preconisation_mildiou>", $homepage);

$contenu .= "]]>
			</LETEXTE>
		</RUBRIQUE>
		<RUBRIQUE>
			<LETITRE>OIDIUM</LETITRE>
			<LETEXTE>
				<![CDATA[";

$contenu .= contenu_balise("<preconisation_oidium>", "</preconisation_oidium>", $homepage);

$contenu .= "]]>
			</LETEXTE>
		</RUBRIQUE>
		<RUBRIQUE>
			<LETITRE>BLACK ROT</LETITRE>
			<LETEXTE>
				<![CDATA[";

$contenu .= contenu_balise("<preconisation_black_rot>", "</preconisation_black_rot>", $homepage);

$contenu .= "]]>
			</LETEXTE>
		</RUBRIQUE>
		<RUBRIQUE>
			<LETITRE>PARCELLE</LETITRE>
			<LETEXTE>
				<![CDATA[";

$contenu .= contenu_balise("<bloque_text_parcelle>","</bloque_text_parcelle>",$homepage);

$contenu .= "]]>
			</LETEXTE>
		</RUBRIQUE>
		<RUBRIQUE>
			<LETITRE>POT_SYS</LETITRE>
			<LETEXTE>
			</LETEXTE>
		</RUBRIQUE>
		<RUBRIQUE>
			<LETITRE>MODELE MILDIO</LETITRE>
			<LETEXTE>
				<![CDATA[";

$contenu .= contenu_balise("<bloque_txt_mildiou>", "</bloque_txt_mildiou>", $homepage);

$contenu .= "]]>
			</LETEXTE>
		</RUBRIQUE>
		<RUBRIQUE>
			<LETITRE>MODELE OIDIU</LETITRE>
			<LETEXTE>
				<![CDATA[";

$contenu .= contenu_balise("<bloque_txt_oidium>", "</bloque_txt_oidium>", $homepage);

$contenu .= "]]>
			</LETEXTE>
		</RUBRIQUE>
		<RUBRIQUE>
			<LETITRE>MODELE BLACK RO</LETITRE>
			<LETEXTE>
				<![CDATA[";

$contenu .= contenu_balise("<bloque_txt_black_rot>", "</bloque_txt_black_rot>", $homepage);

$contenu .= "]]>
				</LETEXTE>
			</RUBRIQUE>
		</ROOT>";

$contenu = utf8_decode($contenu);

$contenu = str_replace("</p>", " ", str_replace("<p>", "", $contenu));
$contenu = str_replace("?", "'", $contenu);
$contenu = str_replace("#", "?", $contenu);
$contenu = str_replace("&nbsp;", " ", $contenu);

fwrite($file_xml, $contenu);
fclose($file_xml);

echo "TRAITEMENT : fin du traitement du XML. \n";

// CREER LE ZIP via un fichier *.bat
exec("F:\wamp\www\epicure2\extension\bulletin_xml\cronjobs\zip_civb.bat");
echo "TRAITEMENT : creation de l'archive *.zip OK. \n";

// ENVOI DU FICHIER sur le FTP
envoi_archive_via_ftp($path_file, $ftp_rename_file, $ftp_server, $ftp_username, $ftp_userpassword, $ftp_mode_envoi);
envoi_archive_via_ftp("F://wamp/www/epicure2/var/storage/bordeaux_xml/z.log", "z.log", $ftp_server, $ftp_username, $ftp_userpassword, $ftp_mode_envoi);

//envoi_mail("epicure@vignevin.com,laurent.charlier@vins-bordeaux.fr,yann.slostowski@vins-bordeaux.fr,jf.audet@cis-valley.fr", "bulletin XML CIVB","L'archive a été envoyé avec succes.");
$mail = "Bonjour.\nLe bulletin modélisation est sur le FTP : ftp2.aquitaine-valley.fr\nPour toutes remarques, nous contacter : epicure@vignevin.com.\nCordialement, l'équipe Modélisation.";
$mail .= "\n\nIFV Bordeaux Aquitaine : 05.56.35.58.80";
envoi_mail("epicure@vignevin.com", "Message automatique de la part de l'IFV", $mail);
echo "TRAITEMENT : fin du traitement. \n";

?>