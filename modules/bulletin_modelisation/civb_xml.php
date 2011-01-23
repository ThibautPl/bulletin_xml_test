<?php
 
// take current object of type eZModule 
$Module =& $Params['Module'];
 
// read parameter Ordered View 
// http://.../modul1/list/ $Params['ParamOne'] / $Params['ParamTwo'] 
// for example .../modul1/list/view/5
$valueParamOne = $Params['ParamOne']; 
$valueParamTwo = $Params['ParamTwo'];
 
// read parameter UnOrdered View 
// http://.../modul1/list/param4/$Params['4Param']/param3/$Params['3Param'] 
// for example.../modul1/list/.../.../param4/141/param3/131
$valueParam3 = $Params['3Param']; 
$valueParam4 = $Params['4Param'];

// library for template functions
include_once( "kernel/common/template.php" );
 
// Example array with strings
$dataArray = array ('Axel','Volker','Dirk','Jan','Felix');
 

// inicialize Templateobject
$tpl = templateInit();
// create view array parameter to put in the template
$viewParameters = array(  'param_one' => $valueParamOne,
                                       'param_two' => $valueParamTwo,
                                       'unordered_param3' => $valueParam3,
                                       'unordered_param4' => $valueParam4 );
// transport the View parameter Array to the template
$tpl->setVariable( 'view_parameters', $viewParameters );
 
// create example Array in the template => {$data_array}
$tpl->setVariable( 'data_array', $dataArray );

// use find/replace (parsing) in the template and save the result for $module_result.content
$Result ['content'] = $tpl->fetch ( 'design:bulletin_modelisation/civb_xml.tpl' );

 
?>