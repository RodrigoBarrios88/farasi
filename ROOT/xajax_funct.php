<?php 
//inclu�mos las clases
require ('xajax_core/xajax.inc.php');
include_once('html_fns.php');
include_once('html_menus.php');

//instanciamos el objeto de la clase xajax
$xajax = new xajax();
//$xajax->setCharEncoding('ISO-8859-1');
date_default_timezone_set('America/Guatemala');
 
function AlertaStatus($usuario,$prioridad,$tipo){
   //instanciamos el objeto para generar la respuesta con ajax
   $respuesta = new xajaxResponse();
   
     $ClsEnc = new ClsEncomienda();
	$result = $ClsEnc->get_encomienda('',$usuario,$prioridad,$tipo,'','','','1,2,3,4');
	if(is_array($result)){
		$i=1;
		foreach($result as $row){
			//status
			$sit = trim($row["enc_situacion"]);
			$cod = $row["enc_codigo"];
               $respuesta->script("compruebaStatus($cod,$sit,$i);");
			$i++;
		}
		$i--;
     }
   $respuesta->script("setAlert();");
	
   return $respuesta;
}

///////////// CONFIGURACION INICIAL //////////////////////////////////
$xajax->register(XAJAX_FUNCTION, "AlertaStatus");

//El objeto xajax tiene que procesar cualquier petici�n
$xajax->processRequest();?>  