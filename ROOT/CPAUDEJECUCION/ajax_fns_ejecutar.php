<?php
ob_start();
header("Cache-control: private, no-cache");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Pragma: no-cache");
header("Cache: no-cache");
ini_set('max_execution_time', 90000);
ini_set("memory_limit", -1);

require_once("../recursos/mandrill/src/Mandrill.php"); //--correos
require_once("../../CONFIG/constructor.php"); //--correos
include_once('html_fns_ejecucion.php');

$request = $_REQUEST["request"]; 
switch($request){
	case "responderPonderacion":
		$auditoria = $_REQUEST["auditoria"];
		$pregunta = $_REQUEST["pregunta"];
		$ejecucion = $_REQUEST["ejecucion"];
		$seccion = $_REQUEST["seccion"];
		$tipo = $_REQUEST["tipo"];
		$peso = $_REQUEST["peso"];
		$aplica = $_REQUEST["aplica"];
		$respuesta = $_REQUEST["respuesta"];
		responder_ponderacion($auditoria,$pregunta,$ejecucion,$seccion,$tipo,$peso,$aplica,$respuesta);
		break;
	case "responderTexto":
		$auditoria = $_REQUEST["auditoria"];
		$pregunta = $_REQUEST["pregunta"];
		$ejecucion = $_REQUEST["ejecucion"];
		$seccion = $_REQUEST["seccion"];
		$observacion = $_REQUEST["observacion"];
		responder_texto($auditoria,$pregunta,$ejecucion,$seccion,$observacion);
		break;
	case "observacion":
		$ejecucion = $_REQUEST["ejecucion"];
		$departamento = $_REQUEST["departamento"];
		$observacion = $_REQUEST["observacion"];
		observacion_departamento($ejecucion,$departamento,$observacion);
		break;
	case "cerrar":
		$ejecucion = $_REQUEST["ejecucion"];
		$nota = $_REQUEST["nota"];
		$correos = $_REQUEST["correos"];
		$responsable = $_REQUEST["responsable"];
		$obs = $_REQUEST["obs"];
		cerrar_ejecucion($ejecucion,$nota,$correos,$responsable,$obs);
		break;
	case "situacion":
		$ejecucion = $_REQUEST["ejecucion"];
		$situacion = $_REQUEST["situacion"];
		$obs = $_REQUEST["obs"];
		situacion_ejecucion($ejecucion,$situacion,$obs);
		break;
	default:
		$arr_respuesta = array(
			"status" => false,
			"data" => [],
			"message" => "Seleccione un metodo..."
		);
		echo json_encode($arr_respuesta);
}

////////////////// EJECUCION /////////////////////////

function responder_ponderacion($auditoria,$pregunta,$ejecucion,$seccion,$tipo,$peso,$aplica,$respuesta){
   $ClsEje = new ClsEjecucion();
   
   if($auditoria != "" && $pregunta != "" && $ejecucion != "" && $tipo != ""){
		$sql = $ClsEje->insert_respuesta($auditoria,$pregunta,$ejecucion,$seccion,$tipo,$peso,$aplica,$respuesta);
		$rs = $ClsEje->exec_sql($sql);
		if($rs == 1){
			$arr_respuesta = array(
				"status" => true,
				//"sql" => $sql,
				"data" => [],
				"message" => "Respuesta agregada con exito..."
			);
			echo json_encode($arr_respuesta);
		}else{
			$arr_respuesta = array(
				"status" => false,
				//"sql" => $sql,
				"data" => [],
				"message" => "Error en la ejecución..."
			);
			echo json_encode($arr_respuesta);
		}	
	}
}


function responder_texto($auditoria,$pregunta,$ejecucion,$seccion,$observacion){
   $ClsEje = new ClsEjecucion();
   
	if($auditoria != "" && $pregunta != "" && $ejecucion != ""){
		$sql = $ClsEje->update_respuesta($auditoria,$pregunta,$ejecucion,$seccion,$observacion);
		$rs = $ClsEje->exec_sql($sql);
		if($rs == 1){
			$arr_respuesta = array(
				"status" => true,
				//"sql" => $sql,
				"data" => [],
				"message" => "Respuesta agregada con éxito..."
			);
			echo json_encode($arr_respuesta);
		}else{
			$arr_respuesta = array(
				"status" => false,
				//"sql" => $sql,
				"data" => [],
				"message" => "Error en la ejecución..."
			);
			echo json_encode($arr_respuesta);
		}	
	}
}


function observacion_departamento($ejecucion,$departamento,$observacion){ 
	$ClsEje = new ClsEjecucion();
	if($ejecucion != "" && $departamento != ""){
		$sql = $ClsEje->insert_observaciones_departamento($ejecucion,$departamento,$observacion);
		$rs = $ClsEje->exec_sql($sql);
		if($rs == 1){
			$arr_respuesta = array(
				"status" => true,
				"data" => [],
				"message" => "Observación agregada...!"
			);
			echo json_encode($arr_respuesta);
		}else{
			$arr_respuesta = array(
				"status" => false,
				"sql" => $sql,
				"data" => [],
				"message" => "Error en la ejecución..."
			);
			echo json_encode($arr_respuesta);
		}
	}else{
		$arr_respuesta = array(
			"status" => false,
			"data" => [],
			"message" => "Debe llenar los campos obligatorios..."
		);
		echo json_encode($arr_respuesta);
	}
}



function cerrar_ejecucion($ejecucion,$nota,$correos,$responsable,$obs){
	$ClsEje = new ClsEjecucion();
	$ClsAud = new ClsAuditoria();
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
		if($rs == 1){
			$mail_result = mail_usuario($ejecucion);
			$arr_respuesta = array(
				"status" => true,
				"data" => [],
				"message" => "Auditoría cerrada satisfactoriamente..."
			);
			echo json_encode($arr_respuesta);
		}else{
			$arr_respuesta = array(
				"status" => false,
				"sql" => $sql,
				"data" => [],
				"message" => "Error en la transacción..."
			);
			echo json_encode($arr_respuesta);
		}
	}else{
		$arr_respuesta = array(
			"status" => false,
			"data" => [],
			"message" => "Debe llenar los campos obligatorios..."
		);
		
		echo json_encode($arr_respuesta);
	}
}


function situacion_ejecucion($ejecucion,$situacion,$obs){ 
	$ClsEje = new ClsEjecucion();
	$sql = $ClsEje->cambia_situacion_ejecucion($ejecucion,$situacion);
	$sql.= $ClsEje->insert_ejecucion_situacion($ejecucion,$situacion,$obs);
	$rs = $ClsEje->exec_sql($sql);
	if($rs == 1){
		$arr_respuesta = array(
			"status" => true,
			"situacion" => intval($situacion),
			"data" => [],
			"message" => "Situación actualizada satisfactoriamente...!"
		);
		echo json_encode($arr_respuesta);
	}else{
		$arr_respuesta = array(
			"status" => false,
			"sql" => $sql,
			"data" => [],
			"message" => "Error en la ejecución"
		);
		
		echo json_encode($arr_respuesta);
	}
}?>