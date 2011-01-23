<?php

    // RETOURNE LE RESULTAT D'UNE REQUETE SOUS FORME DE TABLEAU
    function executer_requete_sql($instruction_sql) {
        // ouvrir la connexion a la BDD en session WEPICURE
        $connexion_oracle = odbc_connect('mod3', 'wepicure', 'epimod');
        
        // forcer le separateur des decimales a ',' dans une chaine numerique et a ' ' le separateur des milliers
        $modif_numeric = "ALTER SESSION SET NLS_NUMERIC_CHARACTERS = ', '";
        odbc_exec($connexion_oracle, $modif_numeric);
        
        $recordset = odbc_exec($connexion_oracle, $instruction_sql);
		    
		// passe toutes les valeurs du recordset dans un tableau associatif avec les noms des champs
		$i = 0;
        $result_array = array();
        
        while (odbc_fetch_row($recordset) == TRUE) {
            for ($j = 0; $j < odbc_num_fields($recordset); $j++) {
                $champ = odbc_field_name($recordset, $j+1);
                $valeur = odbc_result($recordset, $champ);
      	        $result_array[$i][$champ] = $valeur;
			}
			
            $i++;
        }  
			  
        // fermer la connexion
        odbc_close($connexion_oracle);
        return $result_array;
    }

    // TRANSFORME UN TABLEAU [0, val0, date0][1, val1, date1]... EN [date0 => val0][date1 => val1]...
    function retourner_donnees_organiser($array, $var1, $var2) {
        $lenght = count($array);
        $tab_date = array();
  
        for($i = 0; $i < $lenght; $i++) {
            $date = $array[$i][$var1];
            $valeur = $array[$i][$var2];
            
            if ($var2 != "") {
		    	// str_replace("chaine recherchée", "chaine de remplacement", "chaine à parser")
		        $date = str_replace("-04", " avril", $date);
		        $date = str_replace("-05", " mai", $date);
		        $date = str_replace("-06", " juin", $date);
		        $date = str_replace("-07", " juillet", $date);
		        $date = str_replace("-08", " août", $date);
		        $date = str_replace("-09", " septembre", $date);
		            
		        $tab_date[$date] = $valeur;
            }
        }
  
        return $tab_date;
    }
    
    // FONCTION pour ezcomponents
    function __autoload( $className ) {
        ezcBase::autoload( $className );
    }

    // GENERER LE GRAPHIQUE ezcomponents
    function generer_graphique($legend, $data, $pathname_of_pic, $title, $titre_y) {
        include_once ("F:\wamp\www\epicure2\lib\ezc\Base\src\base.php");
        
        $graph = new ezcGraphLineChart();
        $graph -> options -> font = "F:\wamp\www\epicure2\lib\ezc\Fonts\CALIBRI.ttf";
        $graph -> title = $title;
        //$graph -> background -> background = "http://www.vignevin-epicure.com/epicure2/extension/ezwebin/design/ezwebin/images/logo/logo_epicure_200.gif";
        $graph -> legend = true;
        $graph -> legend -> position = ezcGraph::BOTTOM;
        $graph -> legend -> symbolSize = 15;
  
        $graph -> xAxis -> axisLabelRenderer = new ezcGraphAxisRotatedLabelRenderer();
        $graph -> xAxis -> axisLabelRenderer -> angle = 30;
        $graph -> xAxis -> axisSpace = .2;
        $graph -> xAxis -> font -> minFontSize = 40;
        $graph -> xAxis -> label = "Temps";
        
        $graph -> yAxis -> label = $titre_y;
  
        $length = count($data);

        for ($i = 0; $i < $length; $i++) {
            $graph -> data[$legend[$i]] = new ezcGraphArrayDataSet($data[$i]);
            //$graph -> data[$legend[$i]] -> symbol = ezcGraph::BULLET;
            //$graph->data[$legend[$i]] -> symbolSize = 45;
        }
   
        $graph -> data -> symbolSize = 100;
    
        $graph -> driver = new ezcGraphGdDriver();
        $graph -> driver -> options -> supersampling = 2;
        $graph -> driver -> options -> jpegQuality = 40;
        
        $graph -> render( 500, 350, $pathname_of_pic );
    }

echo "GRAPHIQUES : debut du traitement. \n";
$dateJ7 = date("d-m", mktime(0, 0, 0, date("m"), date("d") + 8));
   
    
// EVOLUTION DES VARIABLES MILDIOU calculees par Potentiel Systeme en 2010
//*****************************************************************************
// Recuperation des valeurs dans la vue SIM_MILDIOU10
$fta = "SELECT avg(c_orgt) AS fta, avg(fta_incube) AS fta_inc, avg(c_orgte) AS ftaef, avg(c_orgps - c_orgte) AS ftas,
	to_char(date_calc, 'dd-mm') AS date_calcul FROM sim_mildiou10 WHERE date_calc > to_date('01-04-2010', 'dd-mm-yyyy') 
	AND zone <= 7 AND (c_orgt IS NOT null OR fta_incube IS NOT null OR c_orgte IS NOT null OR c_orgte IS NOT null)
    GROUP BY date_calc ORDER BY date_calc";
$tab_fta = executer_requete_sql($fta);

$var1 = 'DATE_CALCUL';

$var2 = 'FTA';
$tableau_fta_moy = retourner_donnees_organiser($tab_fta, $var1, $var2);
$var2 = 'FTA_INC';
$tableau_fta_incube = retourner_donnees_organiser($tab_fta, $var1, $var2);
$var2 = 'FTAEF';
$tableau_ftae = retourner_donnees_organiser($tab_fta, $var1, $var2);
$var2 = 'FTAS';
$tableau_ftasec = retourner_donnees_organiser($tab_fta, $var1, $var2);


// Construction du tableau de donnees
$data_mul = array(0 => $tableau_fta_moy, 1 => $tableau_fta_incube, 2 => $tableau_ftae, 3 => $tableau_ftasec);
$data_legend = array(0 => " moyenne", 1 => " incubation", 2 => " exprimée", 3 => " conta secondaires");

$path_pic = 'F:\wamp\www\epicure2\extension\bulletin_xml\design\standard\images\graphique\fta_mildiou10.jpeg';
$title = "Evolution des FTA du Mildiou calculée par le modèle Potentiel Système en 2010";
generer_graphique($data_legend, $data_mul, $path_pic, $title, "FTA Mildiou");

echo "GRAPHIQUES : FTA mildiou 2010 OK. \n";


// EVOLUTION DE LA FTA MILDIOU calculees par PS de 2007 a 2010
//*****************************************************************************
// Recuperation des valeurs dans les vues SIM_MILDIOU
$ft2010 = "SELECT avg(c_orgt), to_char(date_calc, 'dd-mm') AS date_calcul FROM sim_mildiou10 
    WHERE (date_calc BETWEEN to_date('01-04-10', 'dd-mm-yy') AND to_date('".$dateJ7."-10', 'dd-mm-yy')) 
    AND zone <= 7 AND c_orgt IS NOT null GROUP BY date_calc ORDER BY date_calc";
$fta2010 = executer_requete_sql($ft2010);

$ft2009 = "SELECT avg(c_orgt), to_char(date_calc, 'dd-mm') AS date_calcul FROM sim_mildiou09 
    WHERE (date_calc BETWEEN to_date('01-04-09', 'dd-mm-yy') AND to_date('".$dateJ7."-09', 'dd-mm-yy')) 
    AND zone <= 7 AND c_orgt IS NOT null GROUP BY date_calc ORDER BY date_calc";
$fta2009 = executer_requete_sql($ft2009);

$ft2008 = "SELECT avg(c_orgt), to_char(date_calc, 'dd-mm') AS date_calcul FROM sim_mildiou08 
    WHERE (date_calc BETWEEN to_date('01-04-08', 'dd-mm-yy') AND to_date('".$dateJ7."-08', 'dd-mm-yy')) 
    AND zone <= 7 AND c_orgt IS NOT null GROUP BY date_calc ORDER BY date_calc";
$fta2008 = executer_requete_sql($ft2008);

$ft2007 = "SELECT avg(c_orgt), to_char(date_calc, 'dd-mm') AS date_calcul FROM sim_mildiou07 
    WHERE (date_calc BETWEEN to_date('01-04-07', 'dd-mm-yy') AND to_date('".$dateJ7."-07', 'dd-mm-yy')) 
    AND zone <= 7 AND c_orgt IS NOT null GROUP BY date_calc ORDER BY date_calc";
$fta2007 = executer_requete_sql($ft2007);

$var1 = 'DATE_CALCUL';
$var2 = 'AVG(C_ORGT)';

$tableau_fta2010 = retourner_donnees_organiser($fta2010, $var1, $var2);
$tableau_fta2009 = retourner_donnees_organiser($fta2009, $var1, $var2);
$tableau_fta2008 = retourner_donnees_organiser($fta2008, $var1, $var2);
$tableau_fta2007 = retourner_donnees_organiser($fta2007, $var1, $var2);

// Construction du tableau de donnees
$data_mul = array(0 => $tableau_fta2007, 1 => $tableau_fta2008, 2 => $tableau_fta2009, 3 => $tableau_fta2010);
$data_legend = array(0 => ' 2007', 1 => ' 2008', 2 => " 2009", 3 => " 2010");

$path_pic = 'F:\wamp\www\epicure2\extension\bulletin_xml\design\standard\images\graphique\fta_mildiou07_10.jpeg';
$title = "Evolution de la FTA Mildiou calculée par le modèle Potentiel Système sur les campagnes 2007 -> 2010";
generer_graphique($data_legend, $data_mul, $path_pic, $title, "FTA Mildiou");

echo "GRAPHIQUES : FTA mildiou 2007-2010 OK. \n";


// EVOLUTION DE LA FTA OIDIUM calculees par PS de 2004 a 2010
//*****************************************************************************
// Recuperation des valeurs dans les vues SIM_BLACK_ROT
$ft2010 = "SELECT avg(ocpritsc) AS fta, to_char(date_calc,'dd-mm') AS date_calcul FROM sim_black_rot10
    WHERE (date_calc BETWEEN to_date('01-04-10', 'dd-mm-yy') AND to_date('".$dateJ7."-10', 'dd-mm-yy')) 
    AND zone <= 7 AND ocpritsc IS NOT null GROUP BY date_calc ORDER BY date_calc";
$fta2010 = executer_requete_sql($ft2010);

$ft2009 = "SELECT avg(ocpritsc) AS fta, to_char(date_calc,'dd-mm') AS date_calcul FROM sim_black_rot09
    WHERE (date_calc BETWEEN to_date('01-04-09', 'dd-mm-yy') AND to_date('".$dateJ7."-09', 'dd-mm-yy')) 
    AND zone <= 7 AND ocpritsc IS NOT null GROUP BY date_calc ORDER BY date_calc";
$fta2009 = executer_requete_sql($ft2009);

$ft2008 = "SELECT avg(ocpritsc) AS fta, to_char(date_calc,'dd-mm') AS date_calcul FROM sim_black_rot08
    WHERE (date_calc BETWEEN to_date('01-04-08', 'dd-mm-yy') AND to_date('".$dateJ7."-08', 'dd-mm-yy')) 
    AND zone <= 7 AND ocpritsc IS NOT null GROUP BY date_calc ORDER BY date_calc";
$fta2008 = executer_requete_sql($ft2008);

$ft2007 = "SELECT avg(ocpritsc) AS fta, to_char(date_calc,'dd-mm') AS date_calcul FROM sim_black_rot07_33
    WHERE (date_calc BETWEEN to_date('01-04-07', 'dd-mm-yy') AND to_date('".$dateJ7."-07', 'dd-mm-yy')) 
    AND zone <= 7 AND ocpritsc IS NOT null GROUP BY date_calc ORDER BY date_calc";
$fta2007 = executer_requete_sql($ft2007);

$ft2006 = "SELECT avg(ocpritsc) AS fta, to_char(date_calc,'dd-mm') AS date_calcul FROM calc_black_rot06
    JOIN station_meteo ON lien_station = id_station WHERE (date_calc BETWEEN to_date('01-04-06', 'dd-mm-yy') 
    AND to_date('01-10-06', 'dd-mm-yy')) AND zone <= 7 AND ocpritsc IS NOT null GROUP BY date_calc ORDER BY date_calc";
$fta2006 = executer_requete_sql($ft2006);

$ft2005 = "SELECT avg(ocpritsc) AS fta, to_char(date_calc,'dd-mm') AS date_calcul FROM sim_black_rot05
    WHERE (date_calc BETWEEN to_date('01-04-05', 'dd-mm-yy') AND to_date('".$dateJ7."-05', 'dd-mm-yy')) 
    AND zone <= 7 AND ocpritsc IS NOT null GROUP BY date_calc ORDER BY date_calc";
$fta2005 = executer_requete_sql($ft2005);

$ft2004 = "SELECT avg(ocprits) AS fta, to_char(date_calc,'dd-mm') AS date_calcul FROM calc_oidium04
    JOIN station_meteo ON lien_station = id_station WHERE (date_calc BETWEEN to_date('01-04-04', 'dd-mm-yy') 
    AND to_date('".$dateJ7."-04', 'dd-mm-yy')) AND zone <= 7 AND ocprits IS NOT null
    GROUP BY date_calc ORDER BY date_calc";
$fta2004 = executer_requete_sql($ft2004);


$var1 = 'DATE_CALCUL';
$var2 = 'FTA';

$tableau_fta2010 = retourner_donnees_organiser($fta2010, $var1, $var2);
$tableau_fta2009 = retourner_donnees_organiser($fta2009, $var1, $var2);
$tableau_fta2008 = retourner_donnees_organiser($fta2008, $var1, $var2);
$tableau_fta2007 = retourner_donnees_organiser($fta2007, $var1, $var2);
$tableau_fta2006 = retourner_donnees_organiser($fta2006, $var1, $var2);
$tableau_fta2005 = retourner_donnees_organiser($fta2005, $var1, $var2);
$tableau_fta2004 = retourner_donnees_organiser($fta2004, $var1, $var2);

// Construction du tableau de donnees
$data_mul = array(0 => $tableau_fta2004, 1 => $tableau_fta2005, 2 => $tableau_fta2006, 3 => $tableau_fta2007, 4 => $tableau_fta2008, 5 => $tableau_fta2009, 6 => $tableau_fta2010);
$data_legend=array(0 => ' 2004', 1 => ' 2005', 2 => " 2006", 3 => " 2007", 4 => " 2008" , 5 => " 2009", 6 => " 2010");

$path_pic = 'F:\wamp\www\epicure2\extension\bulletin_xml\design\standard\images\graphique\fta_oidium04_10.jpeg';
$title = "Evolution de la FTA oïdium calculée par le modèle Potentiel Système sur les campagnes 2004 -> 2010";
generer_graphique($data_legend, $data_mul, $path_pic, $title, "FTA Oidium");

echo "GRAPHIQUES : FTA oidium 2004-2010 OK. \n";


// EVOLUTION DES STADES PHENOLOGIQUES de 2007 a 2010
//*****************************************************************************
// Recuperation des valeurs dans la vue V_OBS_PRO_5_6
$stades = "SELECT avg(stade_pheno), semaine_obs, millesime FROM v_obs_pro_4_5_6
    WHERE (date_obs BETWEEN to_date('01-04-10', 'dd-mm-yy') AND to_date('".$dateJ7."-10', 'dd-mm-yy'))
    OR (date_obs BETWEEN to_date('01-04-09', 'dd-mm-yy') AND to_date('".$dateJ7."-09', 'dd-mm-yy'))
    OR (date_obs BETWEEN to_date('01-04-08', 'dd-mm-yy') AND to_date('".$dateJ7."-08', 'dd-mm-yy'))
    OR (date_obs BETWEEN to_date('01-04-07', 'dd-mm-yy') AND to_date('".$dateJ7."-07', 'dd-mm-yy'))
    AND lien_reseau IN (1, 2, 14) AND stade_pheno NOT IN (-1) GROUP BY semaine_obs, millesime 
    ORDER BY millesime, semaine_obs";
$tab_pheno = executer_requete_sql($stades);

foreach ($tab_pheno AS $pheno) {
    $date = $pheno['SEMAINE_OBS'];        
    
    if ($pheno['MILLESIME'] == "2007") {
        $tab_pheno2007[$date] = $pheno['AVG(STADE_PHENO)'];
    }
    if ($pheno['MILLESIME'] == "2008") {
        $tab_pheno2008[$date] = $pheno['AVG(STADE_PHENO)'];
    }
    if ($pheno['MILLESIME'] == "2009") {
        $tab_pheno2009[$date] = $pheno['AVG(STADE_PHENO)'];
    }
    if ($pheno['MILLESIME'] == "2010") {
        $tab_pheno2010[$date] = $pheno['AVG(STADE_PHENO)'];
    }
}

// Construction du tableau de donnees
$data_mul = array(0 => $tab_pheno2007, 1 => $tab_pheno2008, 2 => $tab_pheno2009, 3 => $tab_pheno2010);
$data_legend = array(0 => ' 2007', 1 => ' 2008', 2 => ' 2009', 3 => ' 2010');

$path_pic = 'F:\wamp\www\epicure2\extension\bulletin_xml\design\standard\images\graphique\stade_pheno07_10.jpeg';
$title = "Stades phénologiques moyens sur le réseau Modélisation : vignoble de Bordeaux";
generer_graphique($data_legend, $data_mul, $path_pic, $title, "Stade");

echo "GRAPHIQUES : Stades phenos 2007-2010 OK. \n";
echo "GRAPHIQUES : fin du traitement. \n";
 
?>
