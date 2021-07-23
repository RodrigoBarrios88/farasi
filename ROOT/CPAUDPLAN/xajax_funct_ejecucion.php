<?php 
//inclu�mos las clases
require ("../xajax_core/xajax.inc.php");
include_once("html_fns_ejecucion.php");

//instanciamos el objeto de la clase xajax
$xajax = new xajax();
$xajax->setCharEncoding('ISO-8859-1');
date_default_timezone_set('America/Guatemala');


function Responder_Ponderacion($auditoria,$pregunta,$ejecucion,$seccion,$tipo,$peso,$aplica,$ponderacion){
   $respuesta = new xajaxResponse();
   $ClsEje = new ClsEjecucion();
   
   if($auditoria != "" && $pregunta != "" && $ejecucion != "" && $tipo != ""){
      $sql = $ClsEje->insert_respuesta($auditoria,$pregunta,$ejecucion,$seccion,$tipo,$peso,$aplica,$ponderacion);
		$rs = $ClsEje->exec_sql($sql);
      //$respuesta->alert("$sql");
      if($rs == 1){
         return $respuesta;
      }else{
         $respuesta->script('swal("Error", "Error en la transacci\u00F3n", "error").then((value)=>{ cerrar(); });');
      }	
	}

	return $respuesta;
}

function Responder_Texto($auditoria,$pregunta,$ejecucion,$seccion,$observacion){
   $respuesta = new xajaxResponse();
   $ClsEje = new ClsEjecucion();
   
   if($auditoria != "" && $pregunta != "" && $ejecucion != ""){
      $sql = $ClsEje->update_respuesta($auditoria,$pregunta,$ejecucion,$seccion,$observacion);
		$rs = $ClsEje->exec_sql($sql);
      //$observacion->alert("$sql");
      if($rs == 1){
         return $respuesta;
      }else{
         $respuesta->script('swal("Error", "Error en la transacci\u00F3n", "error").then((value)=>{ cerrar(); });');
      }	
	}

	return $respuesta;
}



function Observacion_Departamento($ejecucion,$departamento,$observacion){
   $respuesta = new xajaxResponse();
   $ClsEje = new ClsEjecucion();
   //$respuesta->alert("$ejecucion,$departamento,$observacion");
   if($ejecucion != "" && $departamento != ""){
      $sql = $ClsEje->insert_observaciones_departamento($ejecucion,$departamento,$observacion);
		$rs = $ClsEje->exec_sql($sql);
      //$respuesta->alert("$sql");
      if($rs == 1){
         return $respuesta;
      }else{
         $respuesta->script('swal("Error", "Error en la transacci\u00F3n", "error").then((value)=>{ cerrar(); });');
      }	
	}

	return $respuesta;
}


function Cerrar_Ejecucion($ejecucion,$nota,$correos,$responsable,$obs){
   $respuesta = new xajaxResponse();
   $ClsEje = new ClsEjecucion();
   $ClsAud = new ClsAuditoria();
   //--
   $responsable = trim($responsable);
   $obs = trim($obs);
   $responsable = utf8_encode($responsable);
   $obs = utf8_encode($obs);
   $responsable = utf8_decode($responsable);
   $obs = utf8_decode($obs);
   /////// Informacion de la ejecucion
   $result = $ClsEje->get_ejecucion($ejecucion);
   if(is_array($result)){
      foreach ($result as $row){
         $codigo_audit = trim($row["eje_auditoria"]);
			$codigo_progra = trim($row["eje_programacion"]);
         $tipo = trim($row["audit_ponderacion"]);
      }	
   }
   
	if($ejecucion != "" && $codigo_progra != ""){
      $sql = $ClsEje->cerrar_ejecucion($ejecucion,$responsable,$nota,$obs);
      $sql.= $ClsEje->correos_ejecucion($ejecucion,$correos);
      $sql.= $ClsAud->cambia_situacion_programacion($codigo_progra,2);
      $sql.= $ClsEje->insert_ejecucion_situacion($ejecucion,2,$obs);
		$rs = $ClsEje->exec_sql($sql);
      //$respuesta->alert("$ejecucion");
      if($rs == 1){
         mail_usuario($ejecucion);
         $respuesta->script('swal("Excelente!", "Auditor\u00EDa cerrada satisfactoriamente...", "success").then((value)=>{ window.location.href="FRMejecutar.php" });');
      }else{
         $respuesta->script('swal("Error", "Error en la transacci\u00F3n", "error").then((value)=>{ cerrar(); });');
      }	
	}

	return $respuesta;
}



function Situacion_Ejecucion($ejecucion,$situacion,$obs){
   $respuesta = new xajaxResponse();
   $ClsEje = new ClsEjecucion();
   //--
   $obs = trim($obs);
   $obs = utf8_encode($obs);
   $obs = utf8_decode($obs);
   /////// Informacion de la ejecucion
   if($ejecucion != "" && $situacion != ""){
      $sql = $ClsEje->cambia_situacion_ejecucion($ejecucion,$situacion);
      $sql.= $ClsEje->insert_ejecucion_situacion($ejecucion,$situacion,$obs);
		$rs = $ClsEje->exec_sql($sql);
      //$respuesta->alert("$sql");
      if($rs == 1){
         //mail_usuario_situacion($ejecucion);
         $respuesta->script('swal("Excelente!", "Cambio de situaci\u00F3n realizado satisfactoriamente...", "success").then((value)=>{ window.location.reload(); });');
      }else{
         $respuesta->script('swal("Error", "Error en la transacci\u00F3n", "error").then((value)=>{ cerrar(); });');
      }	
	}

	return $respuesta;
}



/////////////////////////// INFORME FINAL DE AUDITORIA ///////////////////////////////////
function Responder_Fecha($auditoria,$pregunta,$solucion,$fecha){
   $respuesta = new xajaxResponse();
   $ClsPla = new ClsPlan();
   
   if($auditoria != "" && $pregunta != "" && $solucion != "" && $fecha != ""){
      $sql = $ClsPla->insert_fecha($auditoria,$pregunta,$solucion,$fecha);
		$rs = $ClsPla->exec_sql($sql);
      //$respuesta->alert("$sql");
      if($rs == 1){
         return $respuesta;
      }else{
         $respuesta->script('swal("Error", "Error en la transacci\u00F3n", "error").then((value)=>{ cerrar(); });');
      }	
	}

	return $respuesta;
}

function Responder_Solucion($auditoria,$pregunta,$solucion,$observacion){
   $respuesta = new xajaxResponse();
   $ClsPla = new ClsPlan();
   
   if($auditoria != "" && $pregunta != "" && $solucion != ""){
      $sql = $ClsPla->insert_respuesta($auditoria,$pregunta,$solucion,$observacion);
		$rs = $ClsPla->exec_sql($sql);
      //$respuesta->alert("$sql");
      if($rs == 1){
         return $respuesta;
      }else{
         $respuesta->script('swal("Error", "Error en la transacci\u00F3n", "error").then((value)=>{ cerrar(); });');
      }	
	}

	return $respuesta;
}


function Responder_Responsable($auditoria,$pregunta,$solucion,$responsable){
   $respuesta = new xajaxResponse();
   $ClsPla = new ClsPlan();
   
   if($auditoria != "" && $pregunta != "" && $solucion != ""){
      $sql = $ClsPla->insert_responsable($auditoria,$pregunta,$solucion,$responsable);
		$rs = $ClsPla->exec_sql($sql);
      //$respuesta->alert("$sql");
      if($rs == 1){
         return $respuesta;
      }else{
         $respuesta->script('swal("Error", "Error en la transacci\u00F3n", "error").then((value)=>{ cerrar(); });');
      }	
	}

	return $respuesta;
}



function Responder_Situacion($auditoria,$pregunta,$solucion,$status,$obs){
   $respuesta = new xajaxResponse();
   $ClsPla = new ClsPlan();
   //--
   $obs = trim($obs);
   $obs = utf8_encode($obs);
   $obs = utf8_decode($obs);
   //
   //$respuesta->alert("$auditoria,$pregunta,$solucion,$status,$obs");
   if($auditoria != "" && $pregunta != "" && $solucion != ""){
      $cont = $ClsPla->count_solucion($solucion,$auditoria,$pregunta);
      if($cont > 0){
         $sql = $ClsPla->insert_plan_status($auditoria,$pregunta,$solucion,$status,$obs);
         $sql.= $ClsPla->situacion_responsable($auditoria,$pregunta,$solucion,$status);
         $rs = $ClsPla->exec_sql($sql);
         //$respuesta->alert("$sql");
         if($rs == 1){
            $respuesta->script("window.location.reload();");
         }else{
            $respuesta->script('swal("Error", "Error en la transacci\u00F3n", "error").then((value)=>{ cerrar(); });');
         }	
      }else{
         $respuesta->script('swal("Alto", "Primero tiene que seleccionar a un responsable para solucionar...", "warning").then((value)=>{ cerrar(); });');
      }
	}

	return $respuesta;
}


function Cerrar_Plan($plan,$tratamiento,$numbre_usuario,$rol_usuario,$observaciones){
   $respuesta = new xajaxResponse();
   $ClsPla = new ClsPlan();
   //--
   $tratamiento = trim($tratamiento);
   $numbre_usuario = trim($numbre_usuario);
   $rol_usuario = trim($rol_usuario);
   $observaciones = trim($observaciones);
   //--
   $tratamiento = utf8_encode($tratamiento);
   $numbre_usuario = utf8_encode($numbre_usuario);
   $rol_usuario = utf8_encode($rol_usuario);
   $observaciones = utf8_encode($observaciones);
   //--
   $tratamiento = utf8_decode($tratamiento);
   $numbre_usuario = utf8_decode($numbre_usuario);
   $rol_usuario = utf8_decode($rol_usuario);
   $observaciones = utf8_decode($observaciones);
   /////// Informacion de la ejecucion
	if($plan != ""){
      $sql = $ClsPla->update_plan($plan,$tratamiento,$numbre_usuario,$rol_usuario,$observaciones);
      $sql.= $ClsPla->cambia_situacion_plan($plan,2);
      $rs = $ClsPla->exec_sql($sql);
      //$respuesta->alert("$sql");
      if($rs == 1){
         $respuesta->script('swal("Excelente!", "Plan de Auditor\u00EDa guardado satisfactoriamente...", "success").then((value)=>{ window.open("CPREPORTES/REPplan.php?ejecucion='.$plan.'", "_blank"); window.location.reload(); });');
      }else{
         $respuesta->script('swal("Error", "Error en la transacci\u00F3n", "error").then((value)=>{ cerrar(); });');
      }	
	}

	return $respuesta;
}


//////////////////---- LISTAS -----/////////////////////////////////////////////
$xajax->register(XAJAX_FUNCTION, "Responder_Ponderacion");
$xajax->register(XAJAX_FUNCTION, "Responder_Texto");
$xajax->register(XAJAX_FUNCTION, "Observacion_Departamento");
$xajax->register(XAJAX_FUNCTION, "Cerrar_Ejecucion");
$xajax->register(XAJAX_FUNCTION, "Situacion_Ejecucion");

//////////////////---- INFORME FINAL DE AUDITORIA -----/////////////////////////////////////////////
$xajax->register(XAJAX_FUNCTION, "Responder_Fecha");
$xajax->register(XAJAX_FUNCTION, "Responder_Solucion");
$xajax->register(XAJAX_FUNCTION, "Responder_Responsable");
$xajax->register(XAJAX_FUNCTION, "Responder_Situacion");
$xajax->register(XAJAX_FUNCTION, "Cerrar_Plan");

//El objeto xajax tiene que procesar cualquier petici&oacute;n
$xajax->processRequest();?>  