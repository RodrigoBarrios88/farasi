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
include_once('html_fns_cuestionario.php');

$request = $_REQUEST["request"]; 
switch($request){
	case "tabla":
		$codigo = $_REQUEST["codigo"];
		$programacion = $_REQUEST["programacion"];
		get_tabla($codigo,$programacion);
		break;
	case "get":
		$codigo = $_REQUEST["codigo"];
		$programacion = $_REQUEST["programacion"];
		get_actividad($codigo,$programacion);
		break;
	case "grabar":
		$programacion = $_REQUEST["programacion"];
		$fecha = $_REQUEST["fecha"];
		$hora = $_REQUEST["hora"];
		$descripcion = $_REQUEST["descripcion"];
		$obs = $_REQUEST["obs"];
		grabar_actividad($programacion,$fecha,$hora,$descripcion,$obs);
		break;
	case "modificar":
		$codigo = $_REQUEST["codigo"];
		$programacion = $_REQUEST["programacion"];
		$fecha = $_REQUEST["fecha"];
		$hora = $_REQUEST["hora"];
		$descripcion = $_REQUEST["descripcion"];
		$obs = $_REQUEST["obs"];
		modificar_actividad($codigo,$programacion,$fecha,$hora,$descripcion,$obs);
		break;
	case "situacion":
		$codigo = $_REQUEST["codigo"];
		$programacion = $_REQUEST["programacion"];
		$situacion = $_REQUEST["situacion"];
		situacion_actividad($codigo,$programacion,$situacion);
		break;
	default:
		$arr_respuesta = array(
			"status" => false,
			"data" => [],
			"message" => "Seleccione un metodo..."
		);
		echo json_encode($arr_respuesta);
}

////////////////// STATUS /////////////////////////
function get_tabla($codigo,$programacion){ 
	$ClsAud = new ClsAuditoria();
	$result = $ClsAud->get_actividades($codigo,$programacion);
	if(is_array($result)){
		$arr_respuesta = array(
			"status" => true,
			"tabla" => tabla_actividades($codigo,$programacion),
			"message" => ""
		);
	}else{
		$arr_respuesta = array(
			"status" => false,
			"data" => [],
			"message" => "Aún no hay datos registrados..."
		);
	}
	echo json_encode($arr_respuesta);
}

function get_actividad($codigo,$programacion){ 
	$ClsAud = new ClsAuditoria();
	$result = $ClsAud->get_actividades($codigo,$programacion);
	$i = 0;
	$arr_data = array();
	if(is_array($result)){
		foreach($result as $row){
			$arr_data["codigo"] = trim($row["act_codigo"]);
			$arr_data["programacion"] = trim($row["act_programacion"]);
			$arr_data["fecha"] = cambia_fecha($row["act_fecha"]);
			$arr_data["hora"] = trim($row["act_hora"]);
			$arr_data["descripcion"] = trim($row["act_descripcion"]);
			$arr_data["obs"] = trim($row["act_observaciones"]);
			$i++;
		}
		$arr_respuesta = array(
			"status" => true,
			"data" => $arr_data,
			"tabla" => tabla_actividades($codigo,$programacion),
			"message" => ""
		);
	}else{
		$arr_respuesta = array(
			"status" => false,
			"data" => [],
			"message" => "Aún no hay datos registrados..."
		);
	}
	echo json_encode($arr_respuesta);
}


function grabar_actividad($programacion,$fecha,$hora,$descripcion,$obs){
	$ClsAud = new ClsAuditoria();
	if($programacion != ""  && $fecha != "" && $hora != "" && $descripcion != "" ){
		$codigo = $ClsAud->max_actividades($programacion);
		$codigo++; /// Maximo codigo de Seccion
		$sql = $ClsAud->insert_actividades($codigo,$programacion,$fecha,$hora,$descripcion,$obs); /// Inserta Seccion
		$rs = $ClsAud->exec_sql($sql);
		if($rs == 1){
			$arr_respuesta = array(
				"status" => true,
				"data" => [],
				"message" => "Registro guardado satisfactoriamente...!"
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


function modificar_actividad($codigo,$programacion,$fecha,$hora,$descripcion,$obs){
	$ClsAud = new ClsAuditoria();
	if($codigo != ""  && $programacion != ""  && $fecha != "" && $hora != "" && $descripcion != ""){
		$sql = $ClsAud->modifica_actividades($codigo,$programacion,$fecha,$hora,$descripcion);
		$rs = $ClsAud->exec_sql($sql);
		if($rs == 1){
			$arr_respuesta = array(
				"status" => true,
				"data" => [],
				"message" => "Registro actualizado satisfactoriamente...!"
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


function situacion_actividad($codigo,$programacion,$situacion){ 
	$ClsAud = new ClsAuditoria();
	$sql = $ClsAud->cambia_situacion_actividad($codigo,$programacion,$situacion);
	$rs = $ClsAud->exec_sql($sql);
	if($rs == 1){
		$arr_respuesta = array(
			"status" => true,
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