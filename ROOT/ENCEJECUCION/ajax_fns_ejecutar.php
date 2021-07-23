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
		$encuesta = $_REQUEST["encuesta"];
		$pregunta = $_REQUEST["pregunta"];
		$ejecucion = $_REQUEST["ejecucion"];
		$seccion = $_REQUEST["seccion"];
		$tipo = $_REQUEST["tipo"];
		$peso = $_REQUEST["peso"];
		$respuesta = $_REQUEST["respuesta"];
		responder_ponderacion($encuesta,$pregunta,$ejecucion,$seccion,$tipo,$peso,$respuesta);
		break;
	case "responderTexto":
		$encuesta = $_REQUEST["encuesta"];
		$pregunta = $_REQUEST["pregunta"];
		$ejecucion = $_REQUEST["ejecucion"];
		$seccion = $_REQUEST["seccion"];
		$observacion = $_REQUEST["observacion"];
		responder_texto($encuesta,$pregunta,$ejecucion,$seccion,$observacion);
		break;
	case "update_campos":
		$ejecucion = $_REQUEST["ejecucion"];
		$campo = $_REQUEST["campo"];
		$valor = $_REQUEST["valor"];
		update_campos($ejecucion,$campo,$valor);
		break;
	case "cerrar":
		$ejecucion = $_REQUEST["ejecucion"];
		cerrar_ejecucion($ejecucion);
		break;
	case "situacion":
		$ejecucion = $_REQUEST["ejecucion"];
		$situacion = $_REQUEST["situacion"];
		situacion_ejecucion($ejecucion,$situacion);
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

function responder_ponderacion($encuesta,$pregunta,$ejecucion,$seccion,$tipo,$peso,$respuesta){
   $ClsRes = new ClsEncuestaResolucion();
   
   if($encuesta != "" && $pregunta != "" && $ejecucion != "" && $tipo != ""){
		$sql = $ClsRes->insert_respuesta($encuesta,$pregunta,$ejecucion,$seccion,$tipo,$peso,$respuesta);
		$rs = $ClsRes->exec_sql($sql);
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


function responder_texto($encuesta,$pregunta,$ejecucion,$seccion,$observacion){
   $ClsRes = new ClsEncuestaResolucion();
   
	if($encuesta != "" && $pregunta != "" && $ejecucion != ""){
		$sql = $ClsRes->update_respuesta($encuesta,$pregunta,$ejecucion,$seccion,$observacion);
		$rs = $ClsRes->exec_sql($sql);
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


function update_campos($ejecucion,$campo,$valor){ 
	$ClsRes = new ClsEncuestaResolucion();
	if($ejecucion != "" && $campo != ""){
		switch($campo){
			case 1: $db_campo = "eje_respondio"; break;
			case 2: $db_campo = "eje_correo"; break;
			case 3: $db_campo = "eje_telefono"; break;
			case 4: $db_campo = "eje_observaciones"; break;
		}
		$sql = $ClsRes->update_ejecucion($ejecucion,$db_campo,$valor);
		$rs = $ClsRes->exec_sql($sql);
		if($rs == 1){
			$arr_respuesta = array(
				"status" => true,
				"data" => [],
				"message" => "Campo actualizado...!"
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



function cerrar_ejecucion($ejecucion){
	$ClsRes = new ClsEncuestaResolucion();
	$ClsEnc = new ClsEncuesta();
	if($ejecucion != ""){
		$sql = $ClsRes->cerrar_ejecucion($ejecucion);
		$rs = $ClsRes->exec_sql($sql);
		if($rs == 1){
			$arr_respuesta = array(
				"status" => true,
				"data" => [],
				"message" => "Gacias por tu colaboración..!"
			);
			echo json_encode($arr_respuesta);
		}else{
			$arr_respuesta = array(
				"status" => false,
				//"sql" => $sql,
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


function situacion_ejecucion($ejecucion,$situacion){ 
	$ClsRes = new ClsEncuestaResolucion();
	$sql = $ClsRes->cambia_situacion_ejecucion($ejecucion,$situacion);
	$rs = $ClsRes->exec_sql($sql);
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