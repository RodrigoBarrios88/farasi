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
		$usuario = $_REQUEST["usuario"];
		$programacion = $_REQUEST["programacion"];
		get_tabla($programacion,$usuario);
		break;
	case "get":
		$programacion = $_REQUEST["programacion"];
		$usuario = $_REQUEST["usuario"];
		get_participante($programacion,$usuario);
		break;
	case "grabar":
		$programacion = $_REQUEST["programacion"];
		$usuario = $_REQUEST["usuario"];
		$tratamiento = $_REQUEST["tratamiento"];
		$rol = $_REQUEST["rol"];
		$asignacion = $_REQUEST["asignacion"];
		grabar_participante($programacion,$usuario,$tratamiento,$rol,$asignacion);
		break;
	case "delete":
		$programacion = $_REQUEST["programacion"];
		$usuario = $_REQUEST["usuario"];
		delete_participante($programacion,$usuario);
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
function get_tabla($programacion,$usuario){ 
	$ClsAud = new ClsAuditoria();
	$result = $ClsAud->get_usuario_programacion($programacion,$usuario);
	if(is_array($result)){
		$arr_respuesta = array(
			"status" => true,
			"tabla" => tabla_participantes($programacion,$usuario),
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

function get_participante($programacion,$usuario){ 
	$ClsAud = new ClsAuditoria();
	$result = $ClsAud->get_usuario_programacion($programacion,$usuario);
	$i = 0;
	$arr_data = array();
	if(is_array($result)){
		foreach($result as $row){
			$arr_data["programacion"] = trim($row["pus_programacion"]);
			$arr_data["usuario"] = trim($row["pus_usuario"]);
			$arr_data["tratamiento"] = trim($row["pus_tratamiento"]);
			$arr_data["rol"] = trim($row["pus_rol"]);
			$arr_data["asignacion"] = trim($row["pus_asignacion"]);
			$arr_data["obs"] = trim($row["pus_observaciones"]);
			$i++;
		}
		$arr_respuesta = array(
			"status" => true,
			"data" => $arr_data,
			"tabla" => tabla_participantes($programacion,$usuario),
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



function grabar_participante($programacion,$usuario,$tratamiento,$rol,$asignacion){
	$ClsAud = new ClsAuditoria();
	if($usuario != ""  && $programacion != ""  && $tratamiento != "" && $rol != ""){
		$sql = $ClsAud->insert_usuario_programacion($programacion,$usuario,$tratamiento,$rol,$asignacion);
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


function delete_participante($programacion,$usuario){ 
	$ClsAud = new ClsAuditoria();
	$sql = $ClsAud->delete_usuario_programacion($programacion,$usuario);
	$rs = $ClsAud->exec_sql($sql);
	if($rs == 1){
		$arr_respuesta = array(
			"status" => true,
			"data" => [],
			"message" => "Participante eliminado satisfactoriamente...!"
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