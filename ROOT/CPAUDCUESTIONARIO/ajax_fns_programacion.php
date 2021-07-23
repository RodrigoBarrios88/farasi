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
		$auditoria = $_REQUEST["auditoria"];
		get_tabla($codigo,$auditoria);
		break;
	case "get":
		$codigo = $_REQUEST["codigo"];
		get_programacion($codigo);
		break;
	case "grabar":
		$auditoria = $_REQUEST["auditoria"];
		$sede = $_REQUEST["sede"];
		$departamento = $_REQUEST["departamento"];
		$fecha = $_REQUEST["fecha"];
		$hora = $_REQUEST["hora"];
		$objetivo = $_REQUEST["objetivo"];
		$riesgo = $_REQUEST["riesgo"];
		$alcance = $_REQUEST["alcance"];
		$obs = $_REQUEST["obs"];
		grabar_programacion($auditoria,$sede,$departamento,$fecha,$hora,$objetivo,$riesgo,$alcance,$obs);
		break;
	case "modificar":
		$codigo = $_REQUEST["codigo"];
		$auditoria = $_REQUEST["auditoria"];
		$sede = $_REQUEST["sede"];
		$departamento = $_REQUEST["departamento"];
		$fecha = $_REQUEST["fecha"];
		$hora = $_REQUEST["hora"];
		$objetivo = $_REQUEST["objetivo"];
		$riesgo = $_REQUEST["riesgo"];
		$alcance = $_REQUEST["alcance"];
		$obs = $_REQUEST["obs"];
		modificar_programacion($codigo,$auditoria,$sede,$departamento,$fecha,$hora,$objetivo,$riesgo,$alcance,$obs);
		break;
	case "situacion":
		$codigo = $_REQUEST["codigo"];
		$situacion = $_REQUEST["situacion"];
		situacion_programacion($codigo,$situacion);
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
function get_tabla($codigo,$auditoria){ 
	$ClsAud = new ClsAuditoria();
	$result = $ClsAud->get_programacion($codigo,$auditoria);
	if(is_array($result)){
		$arr_respuesta = array(
			"status" => true,
			"tabla" => tabla_programacion($codigo,$auditoria),
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

function get_programacion($codigo){ 
	$ClsAud = new ClsAuditoria();
	$result = $ClsAud->get_programacion($codigo,'');
	$i = 0;
	$arr_data = array();
	if(is_array($result)){
		foreach($result as $row){
			$arr_data["codigo"] = trim($row["pro_codigo"]);
			$arr_data["auditoria"] = trim($row["pro_auditoria"]);
			$arr_data["sede"] = trim($row["pro_sede"]);
			$arr_data["departamento"] = trim($row["pro_departamento"]);
			$arr_data["fecha"] = cambia_fecha($row["pro_fecha"]);
			$arr_data["hora"] = trim($row["pro_hora"]);
			$arr_data["objetivo"] = trim($row["pro_objetivo"]);
			$arr_data["riesgo"] = trim($row["pro_riesgo"]);
			$arr_data["alcance"] = trim($row["pro_alcance"]);
			$arr_data["obs"] = trim($row["pro_observaciones"]);
			$i++;
		}
		$arr_respuesta = array(
			"status" => true,
			"data" => $arr_data,
			"tabla" => tabla_programacion($codigo,''),
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


function grabar_programacion($auditoria,$sede,$departamento,$fecha,$hora,$objetivo,$riesgo,$alcance,$obs){
	$ClsAud = new ClsAuditoria();
	if($auditoria != ""  && $sede != "" && $departamento != "" && $fecha != "" && $hora != ""){
		$codigo = $ClsAud->max_programacion();
		$codigo++; /// Maximo codigo de Cuestionario
		$sql = $ClsAud->insert_programacion($codigo,$auditoria,$sede,$departamento,$fecha,$hora,$objetivo,$riesgo,$alcance,$obs);
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


function modificar_programacion($codigo,$auditoria,$sede,$departamento,$fecha,$hora,$objetivo,$riesgo,$alcance,$obs){
	$ClsAud = new ClsAuditoria();
	if($codigo != ""  && $auditoria != ""  && $sede != "" && $departamento != "" && $fecha != "" && $hora != ""){
		$sql = $ClsAud->modifica_programacion($codigo,$auditoria,$sede,$departamento,$fecha,$hora,$objetivo,$riesgo,$alcance,$obs);
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


function situacion_programacion($codigo,$situacion){ 
	$ClsAud = new ClsAuditoria();
	$sql = $ClsAud->cambia_situacion_programacion($codigo,$situacion);
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