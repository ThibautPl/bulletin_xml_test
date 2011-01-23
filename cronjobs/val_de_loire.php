<?php

// VARIABLES
$ftp_rename_file = "archive.zip";
$ftp_server = "192.168.16.21";
$ftp_username = "public";
$ftp_userpassword = "public";
$ftp_mode_envoi = FTP_BINARY;

echo "TRAITEMENT : debut du traitement du XML VdL. \n";

// MISE A JOUR DU TIMESTAMP DU FICHIER
update_file_timestamp("F:\wamp\www\epicure2\extension\bulletin_xml\cronjobs\val_de_loire", $derniere_maj);

// OUVERTURE ET ECRITURE DU FICHIER
$file_xml = fopen($path_vdl_xml, "w+");

$contenu = "<#xml version='1.0' encoding='iso-8859-1'#>
	<#xml-stylesheet href='ifv_bulletin.xsl' type='text/xsl'#>
    <ROOT>
	    <RUBRIQUE>
			<LETITRE>BULLETIN</LETITRE>
	      	<LETEXTE>
				<![CDATA[ ";

$texte = contenu_balise("<titre>", "</titre>", $homepage);
$texte = str_replace("<p>", "", str_replace("</p>", " ; ", $texte));
$texte = substr($texte, 0, strrpos($texte, " ; "));
$contenu .= strip_tags($texte);

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
			<LETITRE>PLUVIOMETRIE</LETITRE>
			<LETEXTE>
				<![CDATA[";

$texte = contenu_balise("<pluviometrie>", "</pluviometrie>", $homepage);
$texte = str_replace("<p>", "", str_replace("</p>", " ; ", $texte));
$texte = substr($texte, 0, strrpos($texte, " ; "));
$contenu .= strip_tags($texte);

$contenu .= "]]>
			</LETEXTE>
		</RUBRIQUE>
		<RUBRIQUE>
			<LETITRE>OBS</LETITRE>
			<LETEXTE>
				<![CDATA[";

$texte = contenu_balise("<bloque_text_parcelle>", "</bloque_text_parcelle>", $homepage);
$texte = str_replace("<p>", "", str_replace("</p>", " ; ", $texte));
$texte = substr($texte, 0, strrpos($texte, " ; "));
$contenu .= strip_tags($texte);

$contenu .= "]]>
			</LETEXTE>
		</RUBRIQUE>
		<RUBRIQUE>
			<LETITRE>MILDIOU</LETITRE>
			<LETEXTE>
				<![CDATA[";

$texte = contenu_balise("<bloque_txt_mildiou>", "</bloque_txt_mildiou>", $homepage);
$texte = str_replace("<p>", "", str_replace("</p>", " ; ", $texte));
$texte = substr($texte, 0, strrpos($texte, " ; "));
$contenu .= strip_tags($texte);

$contenu .= "]]>
			</LETEXTE>
		</RUBRIQUE>
		<RUBRIQUE>
			<LETITRE>OIDIUM</LETITRE>
			<LETEXTE>
				<![CDATA[";

$texte = contenu_balise("<bloque_txt_oidium>", "</bloque_txt_oidium>", $homepage);
$texte = str_replace("<p>", "", str_replace("</p>", " ; ", $texte));
$texte = substr($texte, 0, strrpos($texte, " ; "));
$contenu .= strip_tags($texte);

$contenu .= "]]>
			</LETEXTE>
		</RUBRIQUE>
		<RUBRIQUE>
			<LETITRE>BLACK_ROT</LETITRE>
			<LETEXTE>
				<![CDATA[";

$texte = contenu_balise("<bloque_txt_black_rot>", "</bloque_txt_black_rot>", $homepage);
$texte = str_replace("<p>", "", str_replace("</p>", " ; ", $texte));
$texte = substr($texte, 0, strrpos($texte, " ; "));
$contenu .= strip_tags($texte);

$contenu .= "]]>
			</LETEXTE>
		</RUBRIQUE>
	</ROOT>";

$contenu = utf8_decode($contenu);

$contenu = str_replace("?", "'", $contenu);
$contenu = str_replace("#", "?", $contenu);
$contenu = str_replace("&nbsp;", " ", $contenu);

fwrite($file_xml, $contenu);
fclose($file_xml);

echo "TRAITEMENT : fin du traitement du XML. \n";

// CREER LE ZIP via un fichier *.bat
exec("F:\wamp\www\epicure2\extension\bulletin_xml\cronjobs\zip_interloire.bat");
echo "TRAITEMENT : creation de l'archive *.zip OK. \n";

// ENVOI DU FICHIER par mail
/*$destinataires = "epicure@vignevin.com,david.lafond@vignevin.com,m.chereau@vinsdeloire.fr,c.mandroux@vinsvaldeloire.fr,f.bodin@vinsdeloire.fr";
//envoi_mail($destinataires, "bulletin XML Interloire","L'archive a été envoyé avec succes.");
$mail = "Bonjour.\nLe bulletin modélisation est sur le FTP : ftp2.aquitaine-valley.fr\nPour toutes remarques, nous contacter : epicure@vignevin.com.\nCordialement, l'équipe Modélisation.";
$mail .= "\n\nIFV Bordeaux Aquitaine : 05.56.35.58.80";
//envoi_mail("epicure@vignevin.com", "Message automatique de la part de l'IFV", $mail);
echo "TRAITEMENT : fin du traitement. \n";

envoi_mail();*/

?>