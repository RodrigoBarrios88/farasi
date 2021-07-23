<?php 
//inclu�mos las clases
require ("../xajax_core/xajax.inc.php");
include_once("html_fns_escalones.php");

//instanciamos el objeto de la clase xajax
$xajax = new xajax();
$xajax->setCharEncoding('ISO-8859-1');
date_default_timezone_set('America/Guatemala');

//////////////////---- SEDES -----/////////////////////////////////////////////
function Grabar_Prioridad($nombre,$trespuesta,$tsolucion,$trecordar,$color,$sms){
   //instanciamos el objeto para generar la respuesta con ajax
   $respuesta = new xajaxResponse();
   $ClsPri = new ClsPrioridad();
   //trim a cadena
		$nombre = trim($nombre);
	//--------
	//decodificaciones de tildes y �'s
		$nombre = utf8_encode($nombre);
		//--
		$nombre = utf8_decode($nombre);
	//--------
   //$respuesta->alert("$nombre,$trespuesta,$tsolucion,$trecordar");
   if($nombre != "" && $trespuesta != "" && $tsolucion != "" && $trecordar != "" && $color != ""){
		$codigo = $ClsPri->max_prioridad();
		$codigo++; /// Maximo codigo de Prioridad
		//$respuesta->alert("$id");
		$sql = $ClsPri->insert_prioridad($codigo,$nombre,$trespuesta,$tsolucion,$trecordar,$color,$sms); /// Inserta Prioridad
      //$respuesta->alert("$sql");
		$rs = $ClsPri->exec_sql($sql);
		if($rs == 1){
			$respuesta->script('swal("Excelente!", "Registros guardados satisfactoriamente!!!", "success").then((value)=>{ window.location.reload(); });');
		}else{
			$respuesta->script('swal("Error", "Error en la transacci\u00F3n", "error").then((value)=>{ cerrar(); });');
         $respuesta->script('document.getElementById("gra").className = "btn btn-primary"');
		}
	}else{
		$respuesta->script('swal("Error", "Error en la transacci\u00F3n", "error").then((value)=>{ cerrar(); });');
      $respuesta->script('document.getElementById("gra").className = "btn btn-primary"');
	}
   
   return $respuesta;
}


function Buscar_Prioridad($codigo){
   $respuesta = new xajaxResponse();
   $ClsPri = new ClsPrioridad();
   
   $result = $ClsPri->get_prioridad($codigo);
   //$respuesta->alert("$cont");
   if(is_array($result)){
      foreach($result as $row){
         $codigo = $row["pri_codigo"];
         $respuesta->assign("cod","value",$codigo);
         $nombre = utf8_decode($row["pri_nombre"]);
         $respuesta->assign("nom","value",$nombre);
         //tiempo respuesta
			$tiempo = trim($row["pri_respuesta"]);
			$tiempo = substr($tiempo,0,5);
			$respuesta->assign("trespuesta","value",$tiempo);
			//tiempo solucion
			$tiempo = trim($row["pri_solucion"]);
			$tiempo = substr($tiempo,0,5);
			$respuesta->assign("tsolucion","value",$tiempo);
			//tiempo recordatorio
			$tiempo = trim($row["pri_recordatorio"]);
			$tiempo = substr($tiempo,0,5);
			$respuesta->assign("trecordar","value",$tiempo);
         //color
			$color = trim($row["pri_color"]);
			$respuesta->assign("color","value",$color);
         //sms
			$sms = trim($row["pri_sms"]);
			$respuesta->assign("sms","value",$sms);
      }
      $respuesta->script('$('.select2').select2({ width: '100%' });');
      //abilita y desabilita botones
      $contenido = tabla_prioridades($codigo);
      $respuesta->assign("result","innerHTML",$contenido);
      $respuesta->script("document.getElementById('btn-modificar').className = 'btn btn-primary';");
      $respuesta->script("document.getElementById('btn-grabar').className = 'btn btn-primary hidden';");
      $respuesta->script("cerrar();");
   }	
   return $respuesta;
}


function Modificar_Prioridad($codigo,$nombre,$trespuesta,$tsolucion,$trecordar,$color,$sms){
   $respuesta = new xajaxResponse();
   $ClsPri = new ClsPrioridad();
	//trim a cadena
		$nombre = trim($nombre);
	//--------
	//decodificaciones de tildes y �'s
		$nombre = utf8_encode($nombre);
		//--
		$nombre = utf8_decode($nombre);
	//--------
   //$respuesta->alert("$nombre,$dep,$mun,$direc,$zona,$lat,$long");
   if($codigo != "" && $nombre != "" && $trespuesta != "" && $tsolucion != "" && $trecordar != "" && $color != ""){
		$sql = $ClsPri->modifica_prioridad($codigo,$nombre,$trespuesta,$tsolucion,$trecordar,$color,$sms);
		//$respuesta->alert($sql);
		$rs = $ClsPri->exec_sql($sql);
      if($rs == 1){
         $respuesta->script('swal("Excelente!", "Registros actualizados satisfactoriamente!!!", "success").then((value)=>{ window.location.reload(); });');
      }else{
         $respuesta->script('swal("Error", "Error en la transacci\u00F3n", "error").then((value)=>{ cerrar(); });');
         $respuesta->script('document.getElementById("mod").className = "btn btn-primary"');
      }	
	}else{
		$respuesta->script('swal("Error", "Error en la transacci\u00F3n", "error").then((value)=>{ cerrar(); });');
      $respuesta->script('document.getElementById("mod").className = "btn btn-primary"');
	}
   		
   return $respuesta;
}


function Situacion_Prioridad($codigo){
   $respuesta = new xajaxResponse();
   $ClsPri = new ClsPrioridad();
	if($codigo != ""){
		$sql = $ClsPri->cambia_sit_prioridad($codigo,0);
		//$respuesta->alert($sql);
		$rs = $ClsPri->exec_sql($sql);
      if($rs == 1){
         $respuesta->script('swal("Ok!", "Prioridad eliminada satisfactoriamente!!!", "success").then((value)=>{ window.location.reload(); });');
      }else{
         $respuesta->script('swal("Error", "Error en la transacci\u00F3n", "error").then((value)=>{ cerrar(); });');
         $respuesta->script('document.getElementById("mod").className = "btn btn-primary"');
      }	
	}else{
		$respuesta->script('swal("Error", "Error en la transacci\u00F3n", "error").then((value)=>{ cerrar(); });');
      $respuesta->script('document.getElementById("mod").className = "btn btn-primary"');
	}
   		
   return $respuesta;
}

//////////////////---- SEDES -----/////////////////////////////////////////////
$xajax->register(XAJAX_FUNCTION, "Grabar_Prioridad");
$xajax->register(XAJAX_FUNCTION, "Buscar_Prioridad");
$xajax->register(XAJAX_FUNCTION, "Modificar_Prioridad");
$xajax->register(XAJAX_FUNCTION, "Situacion_Prioridad");

//El objeto xajax tiene que procesar cualquier petici&oacute;n
$xajax->processRequest();?>  