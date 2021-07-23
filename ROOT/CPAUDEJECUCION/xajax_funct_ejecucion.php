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

function Responder_Texto($auditoria,$pregunta,$ejecucion,$observacion){
   $respuesta = new xajaxResponse();
   $ClsEje = new ClsEjecucion();
   
   if($auditoria != "" && $pregunta != "" && $ejecucion != ""){
      $sql = $ClsEje->update_respuesta($auditoria,$pregunta,$ejecucion,$observacion);
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
         if($situacion == 4 || $situacion == 5){
            $respuesta->script('swal("Excelente!", "Cambio de situaci\u00F3n realizado satisfactoriamente...", "success").then((value)=>{ window.location.href="FRMaprobaciones.php"; });');
         }else{
            $respuesta->script('swal("Excelente!", "Cambio de situaci\u00F3n realizado satisfactoriamente...", "success").then((value)=>{ window.location.reload(); });');
         }
      }else{
         $respuesta->script('swal("Error", "Error en la transacci\u00F3n", "error").then((value)=>{ cerrar(); });');
      }	
	}

	return $respuesta;
}



/////////////////////////// INFORME FINAL DE AUDITORIA ///////////////////////////////////
function Responder_Aprobacion($auditoria,$pregunta,$ejecucion,$resultado,$observacion){
   $respuesta = new xajaxResponse();
   $ClsEje = new ClsEjecucion();
   $observacion = trim($observacion);
   $observacion = utf8_encode($observacion);
   $observacion = utf8_decode($observacion);
   
   if($auditoria != "" && $pregunta != "" && $ejecucion != ""){
      $usuario = $_SESSION["codigo"];
      $sql = $ClsEje->insert_ejecucion_revision($auditoria,$pregunta,$ejecucion,$resultado,$observacion,$usuario);
		$rs = $ClsEje->exec_sql($sql);
      //$respuesta->alert("$sql");
      if($rs == 1){
         $respuesta->assign("resultado$pregunta","value",$resultado);
         return $respuesta;
      }else{
         $respuesta->script('swal("Error", "Error en la transacci\u00F3n", "error").then((value)=>{ cerrar(); });');
      }	
	}

	return $respuesta;
}


//////////////////// DISOLUCION DE HALLAZGOS /////////////////////////////////////////////////
function Disolver_Hallazgo($auditoria,$pregunta,$ejecucion,$seccion,$tipo,$peso,$ponderacion,$observacion,$justificacion){
   $respuesta = new xajaxResponse();
   $ClsEje = new ClsEjecucion();
   $observacion = trim($observacion);
   $justificacion = trim($justificacion);
   $observacion = utf8_encode($observacion);
   $justificacion = utf8_encode($justificacion);
   $observacion = utf8_decode($observacion);
   $justificacion = utf8_decode($justificacion);
   ///
   if($auditoria != "" && $pregunta != "" && $ejecucion != "" && $tipo != ""){
      $sql = $ClsEje->insert_respuesta($auditoria,$pregunta,$ejecucion,$seccion,$tipo,$peso,1,$ponderacion,$justificacion);
      $sql.= $ClsEje->update_respuesta($auditoria,$pregunta,$ejecucion,$seccion,$observacion);
      $sql.= $ClsEje->insert_disolucion_hallazgo($auditoria,$pregunta,$ejecucion,$justificacion);
		$rs = $ClsEje->exec_sql($sql);
      //$respuesta->alert("$sql");
      if($rs == 1){
         $respuesta->script('window.location.reload();');
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

//////////////////---- APROBACION DE FORMULARIO -----/////////////////////////////////////////////
$xajax->register(XAJAX_FUNCTION, "Responder_Aprobacion");

//////////////////---- DISOLUCION DE HALLAZGOS -----/////////////////////////////////////////////
$xajax->register(XAJAX_FUNCTION, "Disolver_Hallazgo");

//El objeto xajax tiene que procesar cualquier petici&oacute;n
$xajax->processRequest();?>  