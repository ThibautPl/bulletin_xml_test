REM ETAPE 1 : récupérer les HTM, JPG et PNG et les copier dans bordeaux_xml
cd F:\wamp\www\epicure2\var\storage\bordeaux_xml
xcopy /E /Q /Y \\srvarcview\modelisation\data\modele_33_24\export F:\wamp\www\epicure2\var\storage\bordeaux_xml

REM ETAPE 2 : supprimer le precedent zip
del F:\wamp\www\epicure2\var\storage\bordeaux_xml\carto.zip

REM ETAPE 3 : zipper les différents documments pour le bulletin CIVB grace a 7-Zip
F:
cd F:\wamp\www\epicure2\var\storage\bordeaux_xml\

FOR %%D IN (htm xml xsl jpg png gif) DO (
  C:
  cd C:\Program Files\7-Zip
  7z a F:\wamp\www\epicure2\var\storage\bordeaux_xml\carto.zip F:\wamp\www\epicure2\var\storage\bordeaux_xml\*.%%D
)

REM ETAPE 4 : creer le *.zip de sauvegarde en y ajouter les différents graphiques
cd F:\wamp\www\epicure2\var\storage\bordeaux_xml
copy /E F:\wamp\www\epicure2\var\storage\bordeaux_xml\carto.zip \\srvarcview\modelisation\bulletin_modelisation\bulletin_2010\carto_%DATE:~6,4%_%DATE:~3,2%_%DATE:~0,2%.zip 

REM ETAPE 5 : supprimer les différents fichiers SAUF xml, xsl et css et les logos
F:
cd F:\wamp\www\epicure2\extension\bulletin_xml\cronjobs
