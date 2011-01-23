REM ETAPE 1 : récupérer les *.htm et *.jpg et les copier dans bordeaux_xml

REM ETAPE 2 : supprimer le precedent zip
del F:\wamp\www\epicure2\var\storage\val_de_loire_xml\archive.zip

REM ETAPE 3 : zipper les différents documments grace a 7-Zip
F:
cd F:\wamp\www\epicure2\var\storage\val_de_loire_xml\

FOR %%D IN (htm xml xsl jpg) DO (
  C:
  cd C:\Program Files\7-Zip
  7z a F:\wamp\www\epicure2\var\storage\val_de_loire_xml\archive.zip F:\wamp\www\epicure2\var\storage\val_de_loire_xml\*.%%D
)

REM ETAPE 4 : creer le *.zip de sauvegarde en y ajouter les différents graphiques

REM ETAPE 5 : supprimer les différents fichiers SAUF xml, xsl et css et les logos

F:
cd F:\wamp\www\epicure2\extension\bulletin_xml\cronjobs
