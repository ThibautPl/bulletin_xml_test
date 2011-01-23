<?php
 
// Introduction in the development of eZ Publish extensions
 
$Module = array( 'name' => 'Module xML' ); 
$ViewList = array();
 

 
 
$ViewList['list'] = array( 'script' => 'list.php', 
                           'functions' => array( 'read' ), 
                           'params' => array('ParamOne', 'ParamTwo'),
                           'unordered_params' => array('param3' => '3Param',
                                                       'param4' => '4Param') );

$ViewList['bordeaux_aquitaine_xml'] = array( 'script' => 'bordeaux_aquitaine_xml.php', 
                           'functions' => array( 'read' ), 
                           'params' => array('ParamOne', 'ParamTwo'),
                           'unordered_params' => array('param3' => '3Param',
                                                       'param4' => '4Param') );

$ViewList['civb_xml'] = array( 'script' => 'civb_xml.php', 
                           'functions' => array( 'read' ), 
                           'params' => array('ParamOne', 'ParamTwo'),
                           'unordered_params' => array('param3' => '3Param',
                                                       'param4' => '4Param') );

$ViewList['val_de_loire_xml'] = array( 'script' => 'val_de_loire_xml.php', 
                           'functions' => array( 'read' ), 
                           'params' => array('ParamOne', 'ParamTwo'),
                           'unordered_params' => array('param3' => '3Param',
                                                       'param4' => '4Param') );
  
                                                       
// The entries in the user rights 
// are used in the View definition, to assign rights to own View functions 
// in the user roles
 
$FunctionList = array(); 
$FunctionList['read'] = array(); 

 
?>