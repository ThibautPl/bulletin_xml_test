<?php

// INITIALISATION DU PDF
//******************************************************************************
// includes
require_once('F:\wamp\www\epicure2\lib\tcpdf\config\lang\eng.php');
require_once('F:\wamp\www\epicure2\lib\tcpdf\tcpdf.php');
$home = file_get_contents('http://www.vitidecid.com/epicure2/index.php/fre/bulletin_modelisation/bordeaux_aquitaine_xml');

// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', true);

// set document information
$pdf -> SetCreator(PDF_CREATOR);
$pdf-> SetAuthor('Marcos');
$pdf -> SetTitle('Bulletin modélisation Bordeaux-Aquitaine');
$pdf -> SetSubject('Bulletin Modélisation');
$pdf -> SetKeywords('bulletin, bulletin Bordeaux, modélisation, bulletin modélisation');

// remove default header/footer
$pdf -> setPrintHeader(false);
$pdf -> setPrintFooter(false);

$pdf -> SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf -> setImageScale(PDF_IMAGE_SCALE_RATIO);
$pdf -> setLanguageArray($l);

// set font
$pdf -> SetFont('times', '', 11);

$pdf -> AddPage();


// Image example
// Image($file, $x='', $y='', $w=0, $h=0, $type='', $link='', $align='', $resize=false, $dpi=300, $palign='', $ismask=false, $imgmask=false, $border=0, $fitbox=false, $hidden=false, $fitonpage=false)
$pdf -> Image('F:\wamp\www\epicure2\extension\ezwebin\design\ezwebin\images\logo\biosysteme.gif', 165, 3, 34, 30, 'gif', '', '', false, 150, '', false, false, 0, false, false, false);
$pdf -> Image('F:\wamp\www\epicure2\extension\ezwebin\design\ezwebin\images\logo\ifv.jpeg', 5, 3, 53, 30, 'jpeg', '', '', false, 150, '', false, false, 0, false, false, false);


// INSERTION DU CONTENU
//******************************************************************************
$contenu = contenu_balise("<titre>", "</titre>", $home);
$conca = "<style>
    h1 {
        color : black;
        font-size : 70px;
        text-align: center; 
    }
    
    h2 {
	      color : black;
          font-size : 50px;
          text-align: center;
          text-decoration: underline; 
	}
	
	h3 {
	      color : black;
          font-size : 40px;
          text-align:left; 
          font-family: Arial Black, Arial, Verdana, serif;
	}
  </style>
  <h1>$contenu</h1>";

$pdf -> writeHTML($conca, true, false, true, false, '');
$date_jour = contenu_balise("<date_jour>", "</date_jour>", $home);
$contenu = "<br/><h4>Blanquefort, $date_jour</h4>";
$pdf -> writeHTML($contenu, true, false, true, false, '');
$semaine = contenu_balise("<semaine>", "</semaine>", $home);
$date_plus = contenu_balise("<date_jour_plus>", "</date_jour_plus>", $home);
$contenu = "<h4>Prévision Modélisation semaine n°$semaine du $date_jour au $date_plus</h4>";
$pdf -> writeHTML($contenu, true, false, true, false, '');
$contenu = "<br/><h2><u>Préconisations générales issues des modèles</u></h2>";
$conca = "$contenu";
$pdf -> writeHTML($conca, true, false, true, false, '');
$contenu = "<h3>Mildiou :</h3>";
$pdf -> writeHTML($contenu, true, false, true, false, '');
$contenu = str_replace("</p>", " ", str_replace("<p>", " ", contenu_balise("<preconisation_mildiou>", "</preconisation_mildiou>", $home)));
$pdf -> writeHTML($contenu, true, false, true, false, '');
$contenu = "<br /><h3>Oïdium :</h3>";
$pdf -> writeHTML($contenu, true, false, true, false, '');
$contenu = str_replace("</p>", " ", str_replace("<p>", " ", contenu_balise("<preconisation_oidium>", "</preconisation_oidium>", $home)));
$pdf -> writeHTML($contenu, true, false, true, false, '');
$contenu = "<br /><h3>Black-rot :</h3>";
$pdf -> writeHTML($contenu, true, false, true, false, '');
$contenu = str_replace("</p>", " ", str_replace("<p>", " ", contenu_balise("<preconisation_black_rot>", "</preconisation_black_rot>", $home)));
$pdf -> writeHTML($contenu, true, false, true, false, '');
$contenu = "<br/><h2><u>Tableau synoptique de la validation temps réel des modèles</u></h2>";
$conca = "$contenu";
$pdf -> writeHTML($conca, true, false, true, false, '');

// TABLEAU SYNOPTIQUE
$synoptique = "F:\wamp\www\epicure2\extension\ezwebin\design\ezwebin\images\logo\synoptique.jpg";
$pdf -> Image($synoptique, 10, 175, 170, 100, 'jpeg', '', '', true, 150, '', false, false, 0, false, false, false);
$pdf -> AddPage();
$contenu = "<br/><h2><u>Météorologie</u></h2>";
$conca = "$contenu";
$pdf -> writeHTML($conca, true, false, true, false, '');
$contenu = "<h3>Prévisions :</h3>";
$pdf -> writeHTML($contenu, true, false, true, false, '');
$contenu = str_replace("</p>", " ", str_replace("<p>", " ", contenu_balise("<prevision>", "</prevision>", $home)));
$pdf -> writeHTML($contenu, true, false, true, false, '');
$contenu = "<br /><h3>Pluviomètrie :</h3>";
$pdf -> writeHTML($contenu, true, false, true, false, '');
$contenu = str_replace("</p>", " ", str_replace("<p>", " ", contenu_balise("<pluviometrie>", "</pluviometrie>", $home)));
$pdf -> writeHTML($contenu, true, false, true, false, '');
$semaine = contenu_balise("<semaine_moins>", "</semaine_moins>", $home);
$contenu = "<br/><h2><u>Observations du réseau expérimental semaine n°$semaine</u></h2>";
$conca = "$contenu";
$pdf -> writeHTML($conca, true, false, true, false, '');
$contenu = str_replace("</p>", " ", str_replace("<p>", " ", contenu_balise("<bloque_text_parcelle>", "</bloque_text_parcelle>", $home)));
$pdf -> writeHTML($contenu, true, false, true, false, '');
$contenu = "<br/><h2><u>Informations des modèles Potentiel Système version 2010</u></h2>";
$conca = "$contenu";
$pdf -> writeHTML($conca, true, false, true, false, '');
$contenu = "<h3>Mildiou</h3>";
$pdf -> writeHTML($contenu, true, false, true, false, '');
$contenu = str_replace("</p>"," ",str_replace("<p>", " ", contenu_balise("<bloque_txt_mildiou>", "</bloque_txt_mildiou>", $home)));
$pdf -> writeHTML($contenu, true, false, true, false, '');
$contenu = "<br/><h3>Oïdium</h3>";
$pdf -> writeHTML($contenu, true, false, true, false, '');
$contenu = str_replace("</p>"," ",str_replace("<p>", " ", contenu_balise("<bloque_txt_oidium>", "</bloque_txt_oidium>", $home)));
$pdf -> writeHTML($contenu, true, false, true, false, '');
$contenu = "<br/><h3>Black-rot</h3>";
$pdf -> writeHTML($contenu, true, false, true, false, '');
$contenu = str_replace("</p>"," ",str_replace("<p>", " ", contenu_balise("<bloque_txt_black_rot>", "</bloque_txt_black_rot>", $home)));
$pdf -> writeHTML($contenu, true, false, true, false, '');

$html = <<<EOF
<br /><br /><h4>Informations du modèle :</h4>
  <ul>
  <li>
    * FTA: Fréquence Théorique d'Attaque (%)
  </li>
  <li>  
    * ITA: Intensité Théorique d'Attaque (%)
  </li>
  <li>
    * FTO: Fréquence Theorique d'Observée (%)
  </li>
  <li>
    * IAO: Intensité d'Attaque Observée (%)
  </li>
  </ul>
  <br/>
  <br/>
EOF;
$pdf -> writeHTML($html, true, false, true, false, '');
$pdf -> writeHTML(contenu_balise("<pied_de_page>", "</pied_de_page>", $home), true, false, true, false, '');

$pdf -> AddPage();
$pdf -> Image('F:\wamp\www\epicure2\extension\bulletin_xml\design\standard\images\graphique\fta_mildiou10.jpeg', 20, 20, 500, 350, 'jpeg', '', '', true, 150, '', false, false, 1, false, false, false);
$pdf -> Image('F:\wamp\www\epicure2\extension\bulletin_xml\design\standard\images\graphique\fta_mildiou07_10.jpeg', 20, 400, 500, 350, 'jpeg', '', '', true, 150, '', false, false, 1, false, false, false);
$pdf -> AddPage();
$pdf -> Image('F:\wamp\www\epicure2\extension\bulletin_xml\design\standard\images\graphique\fta_oidium04_10.jpeg', 20, 20, 500, 350, 'jpeg', '', '', true, 150, '', false, false, 1, false, false, false);
$pdf -> Image('F:\wamp\www\epicure2\extension\bulletin_xml\design\standard\images\graphique\stade_pheno07_10.jpeg', 20, 400, 500, 350, 'jpeg', '', '', true, 150, '', false, false, 1, false, false, false);


$pdf -> lastPage();
$pdf -> Output('F:\wamp\www\epicure2\var\storage\bulletin_pdf\bulletin_bordeaux.pdf', 'F');

?>
