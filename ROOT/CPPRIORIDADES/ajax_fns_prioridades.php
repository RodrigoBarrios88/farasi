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
include_once('html_fns_prioridades.php');

$request = $_REQUEST["request"]; 
switch($request){
	case "tabla":
		$codigo = $_REQUEST["codigo"];
		get_tabla($codigo);
		break;
	case "get":
		$codigo = $_REQUEST["codigo"];
		get_prioridad($codigo);
		break;
	case "grabar":
		$nombre = $_REQUEST["nombre"];
		$respuesta = $_REQUEST["respuesta"];
		$solucion = $_REQUEST["solucion"];
		$recordar = $_REQUEST["recordar"];
		$color = $_REQUEST["color"];
		$sms = $_REQUEST["sms"];
		grabar_prioridad($nombre,$respuesta,$solucion,$recordar,$color,$sms);
		break;
	case "modificar":
		$codigo = $_REQUEST["codigo"];
		$nombre = $_REQUEST["nombre"];
		$respuesta = $_REQUEST["respuesta"];
		$solucion = $_REQUEST["solucion"];
		$recordar = $_REQUEST["recordar"];
		$color = $_REQUEST["color"];
		$sms = $_REQUEST["sms"];
		modificar_prioridad($codigo,$nombre,$respuesta,$solucion,$recordar,$color,$sms);
		break;
	case "situacion":
		$codigo = $_REQUEST["codigo"];
		$situacion = $_REQUEST["situacion"];
		situacion_prioridad($codigo,$situacion);
		break;
	default:
		$arr_respuesta = array(
			"status" => false,
			"data" => [],
			"message" => "Seleccione un metodo..."
		);
		echo json_encode($arr_respuesta);
}

////////////////// PRIORIDADES /////////////////////////
function get_tabla($codigo){ 
	$ClsPri = new ClsPrioridad();
	$result = $ClsPri->get_prioridad($codigo);
	if(is_array($result)){
		$arr_respuesta = array(
			"status" => true,
			"tabla" => tabla_prioridades(''),
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


function get_prioridad($codigo){ 
	$ClsPri = new ClsPrioridad();
	$result = $ClsPri->get_prioridad($codigo);
	$i = 0;
	$arr_data = array();
	if(is_array($result)){
		foreach($result as $row){
			$arr_data["codigo"] = trim($row["pri_codigo"]);
			$arr_data["nombre"] = trim($row["pri_nombre"]);
			$arr_data["respuesta"] = trim($row["pri_respuesta"]);
			$arr_data["solucion"] = trim($row["pri_solucion"]);
			$arr_data["recordatorio"] = trim($row["pri_recordatorio"]);
			$arr_data["color"] = trim($row["pri_color"]);
			$arr_data["sms"] = trim($row["pri_sms"]);
			$i++;
		}
		$arr_respuesta = array(
			"status" => true,
			"data" => $arr_data,
			"tabla" => tabla_prioridades($codigo),
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


function grabar_prioridad($nombre,$respuesta,$solucion,$recordar,$color,$sms){
	$ClsPri = new ClsPrioridad();
	if($nombre != "" && $respuesta != "" && $solucion != "" && $recordar != "" && $color != ""){
		$codigo = $ClsPri->max_prioridad();
		$codigo++; /// Maximo codigo de Version
		$sql = $ClsPri->insert_prioridad($codigo,$nombre,$respuesta,$solucion,$recordar,$color,$sms); /// Inserta Version
		$rs = $ClsPri->exec_sql($sql);
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


function modificar_prioridad($codigo,$nombre,$respuesta,$solucion,$recordar,$color,$sms){
	$ClsPri = new ClsPrioridad();
	if($codigo != "" && $nombre != "" && $respuesta != "" && $solucion != "" && $recordar != "" && $color != ""){
		$sql = $ClsPri->modifica_prioridad($codigo,$nombre,$respuesta,$solucion,$recordar,$color,$sms);
		$rs = $ClsPri->exec_sql($sql);
		if($rs == 1){
			$arr_respuesta = array(
				"status" => true,
				"data" => [],
				"message" => "Registro actualizados satisfactoriamente...!"
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


function situacion_prioridad($codigo,$situacion){ 
	$ClsPri = new ClsPrioridad();
	$sql = $ClsPri->cambia_situacion_prioridad($codigo,$situacion);
	$rs = $ClsPri->exec_sql($sql);
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