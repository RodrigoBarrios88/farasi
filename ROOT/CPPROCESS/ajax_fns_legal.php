<?php
ob_start();
header("Cache-control: private, no-cache");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Pragma: no-cache");
header("Cache: no-cache");
ini_set('max_execution_time', 90000);
ini_set("memory_limit", -1);
//--
include_once('html_fns_proceso.php');

header("Access-Control-Allow-Methods: PUT, GET, POST, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding");
header("Access-Control-Allow-Origin: *");

///API REQUEST
$request = $_REQUEST["request"];
$_REQUEST = str_replace("undefined", "", $_REQUEST); ///valida campos "undefined" desde javascript

if($request != ""){
	switch($request){
		case "nuevo":
			$proceso = $_REQUEST["proceso"];
			nuevo_legal($proceso);
			break;
		case "grabar":
			$codigo = $_REQUEST["codigo"];
			$proceso = $_REQUEST["proceso"];
			$descripcion = $_REQUEST["descripcion"];
			grabar_legal($codigo,$proceso,$descripcion);
			break;
		case "get":
			$codigo = $_REQUEST["codigo"];
			$proceso = $_REQUEST["proceso"];
			get_legal($codigo,$proceso);
			break;
		case "delete":
			$codigo = $_REQUEST["codigo"];
			$proceso = $_REQUEST["proceso"];
			delete_legal($codigo,$proceso);
			break;
		default:
			$payload = array(
			"status" => false,
			"data" => [],
			"message" => "Parametros invalidos...");
			echo json_encode($payload);
			break;
	}
}else{
	//devuelve un mensaje de manejo de errores
	$payload = array(
		"status" => false,
		"data" => [],
		"message" => "Delimite el desde de consulta a realizar...");
		echo json_encode($payload);
}


////////////////////////////////////////////////// Representante de Escalones ////////////////////////////////////////////////////
function nuevo_legal($proceso){
	$ClsFic = new ClsFicha();
	$codigo = $ClsFic->max_requisitos_legales($proceso);
	$codigo++;
	$sql = $ClsFic->insert_requisitos_legales($codigo,$proceso,'');
	$rs = $ClsFic->exec_sql($sql);
	if($rs == 1) {
		$data = tabla_legal($codigo,$proceso);
		$payload = array(
			"status" => true,
			"codigo" => $codigo,
			"data" => $data,
			"message" => "Legal actualizado satisfactoriamente...");
		echo json_encode($payload);
	}else{
		//devuelve un mensaje de manejo de errores
		$payload = array(
			"status" => false,
			"data" => [],
			"message" => "Error en la transacci\u00F3n");
		echo json_encode($payload);
	}
}


function grabar_legal($codigo,$proceso,$descripcion){
	$ClsFic = new ClsFicha();
	if($codigo != "" && $proceso != "" && $descripcion != ""){
		$sql = $ClsFic->insert_requisitos_legales($codigo,$proceso,$descripcion);
		$rs = $ClsFic->exec_sql($sql);
		if($rs == 1) {
			$data = tabla_legal('',$proceso);
			$payload = array(
				"status" => true,
				"data" => $data,
				"message" => "Legal actualizado satisfactoriamente...");
			echo json_encode($payload);
		}else{
			//devuelve un mensaje de manejo de errores
			$payload = array(
				"status" => false,
				//"sql" => $sql,
				"data" => [],
				"message" => "Error en la transacci\u00F3n");
			echo json_encode($payload);
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


function get_legal($codigo,$proceso){
	$ClsFic = new ClsFicha();
	$result = $ClsFic->get_requisitos_legales($codigo,$proceso);
	if(is_array($result)){
		$data = tabla_legal($codigo,$proceso);
		
		$payload = array(
			"status" => true,
			"data" => $data,
			"message" => "");
		echo json_encode($payload);
	}else{
		//devuelve un mensaje de manejo de errores
		$payload = array(
			"status" => false,
			//"parametros" => "$result",
			"message" => "Este elemento Legal no existe...");
		echo json_encode($payload);
	}
}


function delete_legal($codigo,$proceso){
	$ClsFic = new ClsFicha();
	$sql = $ClsFic->cambia_situacion_requisitos_legales($codigo,$proceso, 0);
	$rs = $ClsFic->exec_sql($sql);
	if($rs == 1) {
		$data = tabla_legal('',$proceso);
		$payload = array(
			"status" => true,
			"data" => $data,
			//"alert" => $sql,
			"message" => "Legal eliminado satisfactoriamente...");
		echo json_encode($payload);
	}else{
		//devuelve un mensaje de manejo de errores
		$payload = array(
			"status" => false,
			"data" => [],
			//"sql" => $sql,
			"message" => "Error en la transacci\u00F3n");
		echo json_encode($payload);
	}

}