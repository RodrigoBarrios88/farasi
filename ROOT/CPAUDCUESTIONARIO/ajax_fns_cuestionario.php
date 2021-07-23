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
		get_tabla($codigo);
		break;
	case "get":
		$codigo = $_REQUEST["codigo"];
		get_cuestionario($codigo);
		break;
	case "grabar":
		$categoria = $_REQUEST["categoria"];
		$criterios = $_REQUEST["criterio"];
		$nombre = $_REQUEST["nombre"];
		$ponderacion = $_REQUEST["pondera"];
		$objetivo = $_REQUEST["objetivo"];
		$riesgo = $_REQUEST["riesgo"];
		$alcance = $_REQUEST["alcance"];
		grabar_cuestionario($categoria,$nombre,$ponderacion,$criterios,$objetivo,$riesgo,$alcance);
		break;
	case "modificar":
		$codigo = $_REQUEST["codigo"];
		$categoria = $_REQUEST["categoria"];
		$criterios = $_REQUEST["criterio"];
		$nombre = $_REQUEST["nombre"];
		$ponderacion = $_REQUEST["pondera"];
		$objetivo = $_REQUEST["objetivo"];
		$riesgo = $_REQUEST["riesgo"];
		$alcance = $_REQUEST["alcance"];
		modificar_cuestionario($codigo,$categoria,$nombre,$ponderacion,$criterios,$objetivo,$riesgo,$alcance);
		break;
	case "situacion":
		$codigo = $_REQUEST["codigo"];
		$situacion = $_REQUEST["situacion"];
		situacion_cuestionario($codigo,$situacion);
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
function get_tabla($codigo){ 
	$ClsAud = new ClsAuditoria();
	$result = $ClsAud->get_cuestionario($codigo);
	if(is_array($result)){
		$arr_respuesta = array(
			"status" => true,
			"tabla" => tabla_cuestionarios($codigo),
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

function get_cuestionario($codigo){ 
	$ClsAud = new ClsAuditoria();
	$result = $ClsAud->get_cuestionario($codigo);
	$i = 0;
	$arr_data = array();
	if(is_array($result)){
		foreach($result as $row){
			$arr_data["codigo"] = trim($row["audit_codigo"]);
			$arr_data["categoria"] = trim($row["audit_categoria"]);
			$arr_data["nombre"] = trim($row["audit_nombre"]);
			$arr_data["pondera"] = trim($row["audit_ponderacion"]);
			$arr_data["criterio"] = trim($row["audit_criterios"]);
			$arr_data["objetivo"] = trim($row["audit_objetivo"]);
			$arr_data["riesgo"] = trim($row["audit_riesgos"]);
			$arr_data["alcance"] = trim($row["audit_alcance"]);
			$i++;
		}
		$arr_respuesta = array(
			"status" => true,
			"data" => $arr_data,
			"tabla" => tabla_cuestionarios($codigo),
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


function grabar_cuestionario($categoria,$nombre,$ponderacion,$criterios,$objetivo,$riesgo,$alcance){
	$ClsAud = new ClsAuditoria();
	if($categoria != ""  && $nombre != "" && $ponderacion != "" && $criterios != ""){
		$codigo = $ClsAud->max_cuestionario();
		$codigo++; /// Maximo codigo de Cuestionario
		$sql = $ClsAud->insert_cuestionario($codigo,$categoria,$nombre,$ponderacion,$criterios,$objetivo,$riesgo,$alcance);
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


function modificar_cuestionario($codigo,$categoria,$nombre,$ponderacion,$criterios,$objetivo,$riesgo,$alcance){
	$ClsAud = new ClsAuditoria();
	if($codigo != ""  && $categoria != ""  && $nombre != "" && $ponderacion != "" && $criterios != ""){
		$sql = $ClsAud->modifica_cuestionario($codigo,$categoria,$nombre,$ponderacion,$criterios,$objetivo,$riesgo,$alcance);
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


function situacion_cuestionario($codigo,$situacion){ 
	$ClsAud = new ClsAuditoria();
	$sql = $ClsAud->cambia_situacion_cuestionario($codigo,$situacion);
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