<?xml version="1.0" encoding="UTF-8"?>
{* FICHIER XML DE BORDEAUX-AQUITAINE
**************************************}

{* CARTE AQUITAINE : definition des chemins *}
{def $path_AQ = "http://www.vignevin-epicure.com/carto/modele_33_24/"}

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
                              hash( 'parent_node_id', 774,
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
     $mois_debut =  gettime($node.object.published)[month]
     $jour_debut = gettime($node.object.published)[day]
     $mois_fin = gettime(sum($node.object.published,mul(7,24,60,60)))[month]
     $jour_fin = gettime(sum($node.object.published,mul(7,24,60,60)))[day]

     $carte_meteo = concat("4", $annee, $mois_debut, $jour_debut, ".jpg")

     $carte_mildiou_risque_1 = concat("2",$annee,$mois_debut,dec($jour_debut),".jpg")
     $carte_mildiou_risque_2 =  concat("2",$annee,$mois_fin,$jour_fin,".jpg")
     $carte_mildiou_fta_1 = concat("2",$annee,$mois_debut,dec($jour_debut),".jpg")
     $carte_mildiou_fta_2 =  concat("2",$annee,$mois_fin,$jour_fin,".jpg")

     $carte_oidium_risque_1 = concat("2",$annee,$mois_debut,dec($jour_debut),".jpg")
     $carte_oidium_risque_2 =  concat("2",$annee,$mois_fin,$jour_fin,".jpg")
     $carte_oidium_fta_1 = concat("2",$annee,$mois_debut,dec($jour_debut),".jpg")
     $carte_oidium_fta_2 =  concat("2",$annee,$mois_fin,$jour_fin,".jpg")

     $carte_black_rot_risque_1 = concat("2",$annee,$mois_debut,dec($jour_debut),".jpg")
     $carte_black_rot_risque_2 =  concat("2",$annee,$mois_fin,$jour_fin,".jpg")
     $carte_black_rot_fta_1 = concat("2",$annee,$mois_debut,dec($jour_debut),".jpg")
     $carte_black_rot_fta_2 =  concat("2",$annee,$mois_fin,$jour_fin,".jpg")}

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

  <sous_titre>
    {$node.data_map.sous_titre.content|wash()} le <date_jour>{$jour} {$date_debut}</date_jour>
    {$node.data_map.sous_titre.content|wash()} semaine n°<semaine> {$numero_semaine}</semaine> du {$date_debut}
          au <date_jour_plus>{$date_fin}</date_jour_plus>
  </sous_titre>

  <date_modification>
    {$node.modified_subnode}
  </date_modification>


  <preconisation>
    {if or($node.data_map.preconisation_mildiou.content.is_empty|not, $node.data_map.preconisation_mildiou.content.is_empty|not,$node.data_map.preconisation_black_rot.content.is_empty|not)}
        Préconisations générales issues des modèles

        Mildiou:
        <preconisation_mildiou>
          {if $node.data_map.preconisation_mildiou.content.is_empty|not}
            {attribute_view_gui attribute = $node.data_map.preconisation_mildiou}
          {/if}
        </preconisation_mildiou>

        Oïdium:
        <preconisation_oidium>
          {if $node.data_map.preconisation_oidium.content.is_empty|not}
            {attribute_view_gui attribute = $node.data_map.preconisation_oidium}
          {/if}
        </preconisation_oidium>

        Black-rot:
        <preconisation_black_rot>
          {if $node.data_map.preconisation_black_rot.content.is_empty|not}
            {attribute_view_gui attribute = $node.data_map.preconisation_black_rot}
          {/if}
        </preconisation_black_rot>
    {/if}
  </preconisation>


  <tableau_synoptique>
    {if eq( ezini( 'article', 'ImageInFullView', 'content.ini' ), 'enabled' )}
      {if $node.data_map.tableau_synoptique.has_content}
        Tableau synoptique de la validation temps réel des modèles
        <a href = {concat("http://www.vignevin-epicure.com/epicure2/",$node.data_map.tableau_synoptique.content.original.url)} onclick = "window.open(this.href, 'exemple', 'height=530, width=700, top=100, left=100, toolbar=no, menubar=yes, location=no, resizable=yes, scrollbars=no, status=no'); return false;"><img src = {concat("http://www.vignevin-epicure.com/epicure2/", $node.data_map.tableau_synoptique.content.imagelarge.url)} alt = "Tableau synoptique" /></a>
      {/if}
    {/if}
  </tableau_synoptique>

  <meteorologie>
    Météorologie
    Prévisions
    <prevision>
      {if $node.data_map.previsions_meteo.content.is_empty|not}
        {attribute_view_gui attribute = $node.data_map.previsions_meteo}
      {/if}
    </prevision>

    Pluviométrie
    <pluviometrie>
      {if $node.data_map.pluviometrie.content.is_empty|not}
        {attribute_view_gui attribute = $node.data_map.pluviometrie}
      {/if}
    </pluviometrie>
  </meteorologie>

  <parcelles_temoins>
    {set $numero_semaine = dec($numero_semaine)}
    Observations du réseau expérimental semaine n°<semaine_moins>{$numero_semaine}</semaine_moins>
  	<bloque_text_parcelle>
      {if $node.data_map.parcelles_tnt.content.is_empty|not}
        {attribute_view_gui attribute = $node.data_map.parcelles_tnt}
      {/if}
    </bloque_text_parcelle>

    <image>
      Observations sur Témoins Non Traités semaine n°{$numero_semaine}
        <a href = "{concat($path_AQ, 'export/ter_mff_mgf_ca-7.htm')}" onclick="window.open(this.href, 'exemple', 'height=530, width=700, top=200, left=200, toolbar=no, menubar=yes, location=no, resizable=yes, scrollbars=no, status=no'); return false;"><img src = "{concat($path_AQ, 'export/vter_mff_mgf_ca-7.jpg')}" alt = "Obs Mildiou" /></a>
        <a href = "{concat($path_AQ, 'export/ter_off_ogf_ca-7.htm')}" onclick="window.open(this.href, 'exemple', 'height=530, width=700, top=200, left=200, toolbar=no, menubar=yes, location=no, resizable=yes, scrollbars=no, status=no'); return false;"><img src = "{concat($path_AQ, 'export/vter_off_ogf_ca-7.jpg')}" alt = "Obs Oidium" /></a>
    </image>
  </parcelles_temoins>


  <mildiou>
    Mildiou

    <bloque_txt_mildiou>
    {if $node.data_map.mildiou_potentiel_systeme.content.is_empty|not}
      {attribute_view_gui attribute = $node.data_map.mildiou_potentiel_systeme}
    {/if}
    </bloque_txt_mildiou>

    <vignette_image_1>
    </vignette_image_1>

    <image_1>
      Carte de représentation du risque d'épidémie de mildiou établie à partir de l'EPI, modèle Potentiel Système version 2010
      <a href = "{concat($path_AQ, 'export/mil_risque0.htm')}" onclick="window.open(this.href, 'exemple', 'height=530, width=700, top=200, left=200, toolbar=no, menubar=yes, location=no, resizable=yes, scrollbars=no, status=no'); return false;"><img src = "{concat($path_AQ, 'export/vmil_risque0.jpg')}" alt = "Risque Mildiou" /></a>
    </image_1>

    <vignette_image_2>
    </vignette_image_2>

    <image_2>
      <a href="{concat($path_AQ, 'export/mil_risque7.htm')}" onclick="window.open(this.href, 'exemple', 'height=530, width=700, top=200, left=200, toolbar=no, menubar=yes, location=no, resizable=yes, scrollbars=no, status=no'); return false;"><img src="{concat($path_AQ, 'export/vmil_risque7.jpg')}"  alt="Risque Mildiou" /></a>
    </image_2>

    <vignette_image_3>
    </vignette_image_3>

    <image_3>
      Carte de représentation de la FTA du mildiou, modèle Potentiel Système version 2010
      <a href = "{concat($path_AQ, 'export/mil_c_orgt0.htm')}" onclick = "window.open(this.href, 'exemple', 'height=530, width=700, top=200, left=200, toolbar=no, menubar=yes, location=no, resizable=yes, scrollbars=no, status=no'); return false;"><img src = "{concat($path_AQ, 'export/vmil_c_orgt0.jpg')}" alt = "FTA Mildiou" /></a>
    </image_3>

    <vignette_image_4>
    </vignette_image_4>

    <image_4>
      <a href = "{concat($path_AQ, 'export/mil_c_orgt7.htm')}" onclick = "window.open(this.href, 'exemple', 'height=530, width=700, top=200, left=200, toolbar=no, menubar=yes, location=no, resizable=yes, scrollbars=no, status=no'); return false;"><img src = "{concat($path_AQ, 'export/vmil_c_orgt7.jpg')}" alt = "FTA Mildiou" /></a>
    </image_4>
  </mildiou>

  <oidium>
    Oidium

    <bloque_txt_oidium>
      {if $node.data_map.oidium_potentiel_systeme.content.is_empty|not}
        {attribute_view_gui attribute = $node.data_map.oidium_potentiel_systeme}
      {/if}
     </bloque_txt_oidium>

    <vignette_image_1>
    </vignette_image_1>

    <image_1>
      Carte de représentation du risque d'épidémie d'oïdium établie à partir de l'EPI, modèle Potentiel Système version 2010
      <a href = "{concat($path_AQ, 'export/bla_orisque0.htm')}" onclick = "window.open(this.href, 'exemple', 'height=530, width=700, top=200, left=200, toolbar=no, menubar=yes, location=no, resizable=yes, scrollbars=no, status=no'); return false;"><img src = "{concat($path_AQ, 'export/vbla_orisque0.jpg')}" alt = "Risque Oidium" /></a>
    </image_1>

    <vignette_image_2>
    </vignette_image_2>

    <image_2>
      <a href = "{concat($path_AQ, 'export/bla_orisque7.htm')}" onclick = "window.open(this.href, 'exemple', 'height=530, width=700, top=200, left=200, toolbar=no, menubar=yes, location=no, resizable=yes, scrollbars=no, status=no'); return false;">
        <img src = "{concat($path_AQ, 'export/vbla_orisque7.jpg')}" alt = "Risque Oidium" /></a>
    </image_2>

    <vignette_image_3>
    </vignette_image_3>

    <image_3>
      Carte de représentation de la FTA de l'oïdium, modèle Potentiel Système version 2010
      <a href = "{concat($path_AQ, 'export/bla_ocpritsc0.htm')}" onclick = "window.open(this.href, 'exemple', 'height=530, width=700, top=200, left=200, toolbar=no, menubar=yes, location=no, resizable=yes, scrollbars=no, status=no'); return false;">
      <img src = "{concat($path_AQ, 'export/vbla_ocpritsc0.jpg')}" alt = "FTA Oidium" /></a>
    </image_3>

    <vignette_image_4>
    </vignette_image_4>

    <image_4>
      <a href = "{concat($path_AQ, 'export/bla_ocpritsc7.htm')}" onclick="window.open(this.href, 'exemple', 'height=530, width=700, top=200, left=200, toolbar=no, menubar=yes, location=no, resizable=yes, scrollbars=no, status=no'); return false;">
      <img src = "{concat($path_AQ, 'export/vbla_ocpritsc7.jpg')}" alt = "FTA Oidium" /></a>
    </image_4>
  </oidium>

  <black_rot>
    Black-rot

    <bloque_txt_black_rot>
      {if $node.data_map.black_rot_potentiel_systeme.content.is_empty|not}
        {attribute_view_gui attribute = $node.data_map.black_rot_potentiel_systeme}
      {/if}
    </bloque_txt_black_rot>

    <vignette_image_1>
    </vignette_image_1>

    <image_1>
      Carte de représentation du risque d'épidémie de black-rot établie à partir de l'EPI, modèle Potentiel Système version 2010
      <a href = "{concat($path_AQ, 'export/bla_brisque0.htm')}" onclick = "window.open(this.href, 'exemple', 'height=530, width=700, top=200, left=200, toolbar=no, menubar=yes, location=no, resizable=yes, scrollbars=no, status=no'); return false;"><img src = "{concat($path_AQ, 'export/vbla_brisque0.jpg')}" alt = "Risque BR" /></a>
    </image_1>

    <vignette_image_2>
    </vignette_image_2>

    <image_2>
      <a href = "{concat($path_AQ, 'export/bla_brisque7.htm')}" onclick = "window.open(this.href, 'exemple', 'height=530, width=700, top=200, left=200, toolbar=no, menubar=yes, location=no, resizable=yes, scrollbars=no, status=no'); return false;"><img src = "{concat($path_AQ, 'export/vbla_brisque7.jpg')}" alt = "Risque BR" /></a>
    </image_2>

    <vignette_image_3>

    </vignette_image_3>

    <image_3>
      Carte de représentation de la FTA du black-rot, modèle Potentiel Système version 2010
      <a href = "{concat($path_AQ, 'export/bla_bcprtso10.htm')}" onclick = "window.open(this.href, 'exemple', 'height=530, width=700, top=200, left=200, toolbar=no, menubar=yes, location=no, resizable=yes, scrollbars=no, status=no'); return false;">
      <img src = "{concat($path_AQ, 'export/vbla_bcprtso10.jpg')}" alt = "FTA BR" /></a>
    </image_3>

    <vignette_image_4>
    </vignette_image_4>

    <image_4>
      <a href = "{concat($path_AQ, 'export/bla_bcprtso17.htm')}" onclick = "window.open(this.href, 'exemple', 'height=530, width=700, top=200, left=200, toolbar=no, menubar=yes, location=no, resizable=yes, scrollbars=no, status=no'); return false;"><img src = "{concat($path_AQ, 'export/vbla_bcprtso17.jpg')}" alt = "FTA BR" /></a>
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


