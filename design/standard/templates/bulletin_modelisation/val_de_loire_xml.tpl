<?xml version="1.0" encoding="UTF-8"?>
{* FICHIER XML DU VAL DE LOIRE
**************************************}

{* CARTE VAL  : definition des chemins *}
{def $path_VL = "http://www.vignevin-epicure.com/carto/modele_49/"}

{* dossiers des maladies *}
{def $path_mildiou_fta = "Modelisation/Mildiou/c_orgt/"
     $path_mildiou_risque = "Modelisation/Mildiou/risque/"
     $path_oidium_fta = "Modelisation/Black_rot/ocpritsc/"
     $path_oidium_risque = "Modelisation/Black_rot/orisque/"
     $path_black_fta = "Modelisation/Black_rot/bcprtso1/"
     $path_black_risque = "Modelisation/Black_rot/brisque/"
     $path_meteo = "Meteo/pluie/"}


{* RECUPERATION DE L'id DU DERNIER BULLETIN PUBLIE *}
{def $dernier_publie = fetch( 'content', 'list',
                              hash( 'parent_node_id', 775,
                              	    'limit', 1,
                                    'sort_by', array( 'published', false() ),
                                    'limitation', array( hash( 'Section', array( 1, 6 ) ))
                              )
                       )}

{def $id_max = $dernier_publie.[0].node_id}


{* RECUPERATION DES INTITULES DES CARTES *}
{def $node = fetch( 'content', 'node', hash( 'node_id', $id_max ))}
{def $numero_semaine = gettime($node.object.published)[weeknumber]
     $annee = gettime($node.object.published)[year]
     $mois_debut = gettime($node.object.published)[month]
     $jour_debut = gettime($node.object.published)[day]
     $carte_meteo = concat("4", $annee, $mois_debut, $jour_debut, ".jpg")
     $mois_fin = gettime(sum($node.object.published, mul(7,24,60,60)))[month]
     $jour_fin = gettime(sum($node.object.published, mul(7,24,60,60)))[day]

     $carte_mildiou_risque_1 = concat("2", $annee, $mois_debut, dec($jour_debut), ".jpg")
     $carte_mildiou_risque_2 = concat("2", $annee, $mois_fin, $jour_fin, ".jpg")
     $carte_mildiou_fta_1 = concat("2", $annee, $mois_debut, dec($jour_debut), ".jpg")
     $carte_mildiou_fta_2 = concat("2", $annee, $mois_fin, $jour_fin, ".jpg")

     $carte_oidium_risque_1 = concat("2"$id_max, $annee$id_max, $mois_debut$id_max, dec($jour_debut)$id_max, ".jpg")
     $carte_oidium_risque_2 = concat("2"$id_max, $annee$id_max, $mois_fin$id_max, $jour_fin$id_max, ".jpg")
     $carte_oidium_fta_1 = concat("2"$id_max, $annee$id_max, $mois_debut$id_max, dec($jour_debut)$id_max, ".jpg")
     $carte_oidium_fta_2 = concat("2"$id_max, $annee$id_max, $mois_fin$id_max, $jour_fin$id_max, ".jpg")

     $carte_black_rot_risque_1 = concat("2"$id_max, $annee$id_max, $mois_debut$id_max, dec($jour_debut)$id_max, ".jpg")
     $carte_black_rot_risque_2 = concat("2"$id_max, $annee$id_max, $mois_fin$id_max, $jour_fin$id_max, ".jpg")
     $carte_black_rot_fta_1 = concat("2"$id_max, $annee$id_max, $mois_debut$id_max, dec($jour_debut)$id_max, ".jpg")
     $carte_black_rot_fta_2 = concat("2"$id_max, $annee$id_max, $mois_fin$id_max, $jour_fin$id_max, ".jpg")}

{* formatage du mois sur 2 chiffres *}
{if le($mois_debut, 10)}
  {set $month_begin = concat("0", $mois_debut)}
{/if}
{if le($mois_fin, 10)}
  {set $month_end = concat("0", $mois_fin)}
{/if}

{def $date_debut = $node.object.published|datetime( 'custom', '%d %F %Y' )
     $date_fin = sum($node.object.published, mul(7,24,60,60))|datetime( 'custom', '%d %F %Y' )
     $jour = $node.object.published|datetime( 'custom', '%l' ) }


<bulletin>
	<header_image>
    	<img src = "../../../extension/ezwebin/design/ezwebin/images/logo/ifv.jpg" alt = "IFV" />
    	<img src = "../../../extension/ezwebin/design/ezwebin/images/logo/biosysteme.gif"  alt = "Biosysteme" style = "margin-left:700px" />
	</header_image>

    <titre>
		{$node.data_map.titre.content|wash()}
    </titre>

    <date_jour>{$jour} {$date_debut}</date_jour>

	<date_modification>{$node.modified_subnode}</date_modification>

    <meteorologie>
		Météorologie
	 	<pluviometrie>
	    {if $node.data_map.pluviometrie.content.is_empty|not}
	    	{attribute_view_gui attribute = $node.data_map.pluviometrie}
	    {/if}
	    </pluviometrie>

	    <image>
		Cumul Pluviométrie semaine dernière
		 <a href="http://www.vignevin-epicure.com/carto/modele_49/export//met_cpluie70.htm" onclick="window.open(this.href, 'exemple', 'height=530, width=700, top=100, left=100, toolbar=no, menubar=yes, location=no, resizable=yes, scrollbars=no, status=no'); return false;"><img src="http://www.vignevin-epicure.com/carto/modele_49/export/vmet_cpluie70.jpg"  width=200 height=150  alt="" /></a>
	    </image>
    </meteorologie>

	<parcelles_temoins>
		{set $numero_semaine = dec($numero_semaine)}
		Préconisations générales pour les parcelles du réseau expérimental
		<bloque_text_parcelle>
			{if $node.data_map.parcelles_tnt.content.is_empty|not}
				{attribute_view_gui attribute=$node.data_map.parcelles_tnt}
			{/if}
		</bloque_text_parcelle>
	</parcelles_temoins>

	<mildiou>
		Informations des Modèles Mildiou Potentiel Système version 2010
		Mildiou :
		<bloque_txt_mildiou>
			{if $node.data_map.mildiou_potentiel_systeme.content.is_empty|not}
				{attribute_view_gui attribute = $node.data_map.mildiou_potentiel_systeme}
			{/if}
		</bloque_txt_mildiou>

		<vignette_image_1>
		</vignette_image_1>

		<image_1>
			Carte de représentation du risque d'épidémie de mildiou établie à partir de l'EPI, modèle Potentiel Système version 2010
			{$date_debut} (J)
			<a href="http://www.vignevin-epicure.com/carto/modele_49/export/mil_risque0.htm" onclick="window.open(this.href, 'exemple', 'height=530, width=700, top=200, left=200, toolbar=no, menubar=yes, location=no, resizable=yes, scrollbars=no, status=no'); return false;"><img src="http://www.vignevin-epicure.com/carto/modele_49/export/vmil_risque0.jpg"  alt="" /></a>
		</image_1>

		<vignette_image_2>
		</vignette_image_2>

		<image_2>
			Carte de représentation de la FTA du mildiou, modèle Potentiel Système version 2010
			{$date_debut} (J)
			<a href="http://www.vignevin-epicure.com/carto/modele_49/export/mil_fta0.htm" onclick="window.open(this.href, 'exemple', 'height=530, width=700, top=200, left=200, toolbar=no, menubar=yes, location=no, resizable=yes, scrollbars=no, status=no'); return false;"><img src="http://www.vignevin-epicure.com/carto/modele_49/export/vmil_fta0.jpg"  alt="" /></a>
		</image_2>

		<vignette_image_3>
		</vignette_image_3>

		<image_3>
		</image_3>

		<vignette_image_4>
		</vignette_image_4>

		<image_4>
		</image_4>
	</mildiou>

	<oidium>
		Informations du Modèle Oïdium Potentiel Système version 2010
    	Oïdium :
    	<bloque_txt_oidium>
   			{if $node.data_map.oidium_potentiel_systeme.content.is_empty|not}
   				{attribute_view_gui attribute = $node.data_map.oidium_potentiel_systeme}
   			{/if}
     	</bloque_txt_oidium>

    	<vignette_image_1>
    	</vignette_image_1>

    	<image_1>
    	</image_1>

    	<vignette_image_2>
    	</vignette_image_2>

    	<image_2>
     		Carte de représentation du risque d'épidémie d'oïdium établie à partir de l'EPI, modèle Potentiel Système version 2010
     		{$date_debut} (J)
    	 	<a href="http://www.vignevin-epicure.com/carto/modele_49/export/bla_orisque0.htm" onclick="window.open(this.href, 'exemple', 'height=530, width=700, top=200, left=200, toolbar=no, menubar=yes, location=no, resizable=yes, scrollbars=no, status=no'); return false;"><img src="http://www.vignevin-epicure.com/carto/modele_49/export/vbla_orisque0.jpg"  alt="" /></a>
		</image_2>

    	<vignette_image_3>
		</vignette_image_3>

    	<image_3>
       		Carte de représentation de la FTA de l'oïdium, modèle Potentiel Système version 2010
       		{$date_debut} (J)
     		<a href="http://www.vignevin-epicure.com/carto/modele_49/export/bla_ocpritsc0.htm" onclick="window.open(this.href, 'exemple', 'height=530, width=700, top=200, left=200, toolbar=no, menubar=yes, location=no, resizable=yes, scrollbars=no, status=no'); return false;"><img src="http://www.vignevin-epicure.com/carto/modele_49/export/vbla_ocpritsc0.jpg"  alt="" /></a>
		</image_3>

	    <vignette_image_4>
	    </vignette_image_4>

	    <image_4>
	    </image_4>
	</oidium>

	<black_rot>
		Informations du Modèle Black-rot Potentiel Système version 2010
		Black-rot :
		<bloque_txt_black_rot>
			{if $node.data_map.black_rot_potentiel_systeme.content.is_empty|not}
   				{attribute_view_gui attribute = $node.data_map.black_rot_potentiel_systeme}
   			{/if}
		</bloque_txt_black_rot>

	    <vignette_image_1>
	    </vignette_image_1>

	    <image_1>
		    Carte de représentation du risque d'épidémie de black-rot établie à partir de l'EPI, modèle Potentiel Système version 2010
		    {$date_debut} (J)
		    <a href="http://www.vignevin-epicure.com/carto/modele_49/export/bla_brisque0.htm" onclick="window.open(this.href, 'exemple', 'height=530, width=700, top=200, left=200, toolbar=no, menubar=yes, location=no, resizable=yes, scrollbars=no, status=no'); return false;"><img src="http://www.vignevin-epicure.com/carto/modele_49/export/vbla_brisque0.jpg"  alt="" /></a>
		</image_1>

	    <vignette_image_2>
	    </vignette_image_2>

	    <image_2>
		    Carte de représentation de la FTA du black-rot, modèle Potentiel Système version 2010
		    {$date_debut} (J)
		    <a href="http://www.vignevin-epicure.com/carto/modele_49/export/bla_bcprtso10.htm" onclick="window.open(this.href, 'exemple', 'height=530, width=700, top=200, left=200, toolbar=no, menubar=yes, location=no, resizable=yes, scrollbars=no, status=no'); return false;"><img src="http://www.vignevin-epicure.com/carto/modele_49/export/vbla_bcprtso10.jpg"  alt="" /></a>
		</image_2>

    	<vignette_image_3>
		</vignette_image_3>

   	 	<image_3>
		</image_3>

    	<vignette_image_4>
		</vignette_image_4>

    	<image_4>
 		</image_4>
	</black_rot>

	<information_modeles>
    Informations du modèle
    FTA: Frequence Theorique d'Attaque (%)
	ITA: Intensite Theorique d'Attaque (%)
	FTO: Frequence Theorique d'Observee (%)
	IAO: Intensite d'Attaque Observee  (%)
	</information_modeles>

	<pied_de_page>
    	{attribute_view_gui attribute = $node.pied_de_page}
  	</pied_de_page>
</bulletin>


