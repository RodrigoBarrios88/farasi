<?php 
//inclu�mos las clases
require ("../xajax_core/xajax.inc.php");
include_once("html_fns_config.php");

//instanciamos el objeto de la clase xajax
$xajax = new xajax();
$xajax->setCharEncoding('ISO-8859-1');
date_default_timezone_set('America/Guatemala');

//////////////////---- DEPMUN -----/////////////////////////////////////////////
function depmun($dep,$idmun,$idsmun){
   $respuesta = new xajaxResponse();
  // $respuesta->alert("$dep,$idmun,$idsmun");
	$contenido = municipio_html($dep,$idmun);
	$respuesta->assign($idsmun,"innerHTML",$contenido);return $respuesta;
}

////////////////////////// Configuracion de Credenciales //////////////////////
function Modificar_Credenciales($nombre,$nombre_reporte,$direccion1,$direccion2,$departamento,$municipio,$telefono,$correo,$website){
   //instanciamos el objeto para generar la respuesta con ajax
   $respuesta = new xajaxResponse();
   $ClsConf = new ClsConfig();
  	//$respuesta->alert("$nombre,$direccion1,$direccion2,$departamento,$municipio,$telefono,$correo,$website");
   $sql = $ClsConf->update_credenciales($nombre,$nombre_reporte,$direccion1,$direccion2,$departamento,$municipio,$telefono,$correo,$website);
   $rs = $ClsConf->exec_sql($sql);
   //$respuesta->alert("$sql");
   if($rs == 1){
      /// SETEA VARIABLES DE SESION
      $_SESSION["nombre_colegio"] = $nombre;
      $_SESSION["cliente_nombre_reporte"] = $nombre_reporte;
      $_SESSION["cliente_direccion"] = $direccion1." ".$direccion2;
      $_SESSION["cliente_departamento"] = $departamento;
      $_SESSION["cliente_municipio"] = $municipio;
      //
      $respuesta->script('swal("Excelente!", "Configuraci\u00F3n actualizada satisfactoriamente!!!", "success").then((value)=>{ window.location.reload(); });');
   }else{
      $respuesta->script('swal("Error", "Error en la transacci\u00F3n", "error").then((value)=>{ cerrar(); });');
   }	

   return $respuesta;
}


function Modulos($arrmodulos,$arrsituacion,$filas){
   //instanciamos el objeto para generar la respuesta con ajax
   $respuesta = new xajaxResponse();
   $ClsConf = new ClsConfig();
	if($filas > 0){
      $sql = "";
      for($i = 1; $i <= $filas; $i++){
         $codigo = $arrmodulos[$i];
         $situacion = $arrsituacion[$i];
         $sql.= $ClsConf->update_situacion_modulos($codigo,$situacion);
      }
		$rs = $ClsConf->exec_sql($sql);
		//$respuesta->alert($sql);
		if($rs == 1){
			$respuesta->script('swal("Excelente!", "M\u00F3dulos actualizados satisfactoriamente!!!.", "success").then((value)=>{ window.location.reload(); });');
		}else{
			$respuesta->script('swal("Error", "Error en la transacci\u00F3n", "error").then((value)=>{ cerrar(); });');
		}	
	}
	
   return $respuesta;
}


///////////// CONFIGURACION INICIAL //////////////////////////////////
$xajax->register(XAJAX_FUNCTION, "Modificar_Credenciales");
$xajax->register(XAJAX_FUNCTION, "Modulos");

//El objeto xajax tiene que procesar cualquier petici\u00F3n
$xajax->processRequest();?>  