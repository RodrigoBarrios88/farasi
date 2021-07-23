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
			$tipo = $_REQUEST["tipo"];
			nuevo_elemento($proceso,$tipo);
			break;
		case "grabar":
			$codigo = $_REQUEST["codigo"];
			$proceso = $_REQUEST["proceso"];
			$tipo = $_REQUEST["tipo"];
			$titulo = $_REQUEST["titulo"];
			$descripcion = $_REQUEST["descripcion"];
			grabar_elemento($codigo,$proceso,$tipo,$titulo,$descripcion);
			break;
		case "get":
			$codigo = $_REQUEST["codigo"];
			$proceso = $_REQUEST["proceso"];
			$tipo = $_REQUEST["tipo"];
			get_elemento($codigo,$proceso,$tipo);
			break;
		case "delete":
			$codigo = $_REQUEST["codigo"];
			$proceso = $_REQUEST["proceso"];
			$tipo = $_REQUEST["tipo"];
			delete_elemento($codigo,$proceso,$tipo);
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


////////////////////////////////////////////////// Elementos de Desarrollo del proceso ////////////////////////////////////////////////////
function nuevo_elemento($proceso,$tipo){
	$ClsFic = new ClsFicha();
	$codigo = $ClsFic->max_elemento($proceso);
	$codigo++;
	$sql = $ClsFic->insert_elemento($codigo,$proceso,$tipo,'','');
	$rs = $ClsFic->exec_sql($sql);
	if($rs == 1) {
		$data = tabla_elemento($codigo,$proceso,$tipo);
		$payload = array(
			"status" => true,
			"codigo" => $codigo,
			"data" => $data,
			"message" => "Elemento actualizado satisfactoriamente...");
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


function grabar_elemento($codigo,$proceso,$tipo,$titulo,$descripcion){
	$ClsFic = new ClsFicha();
	if($codigo != "" && $proceso != "" && $tipo != "" && $titulo != ""){
		$sql = $ClsFic->insert_elemento($codigo,$proceso,$tipo,$titulo,$descripcion);
		$rs = $ClsFic->exec_sql($sql);
		if($rs == 1) {
			$data = tabla_elemento('',$proceso,$tipo);
			$payload = array(
				"status" => true,
				"data" => $data,
				"message" => "Elemento actualizado satisfactoriamente... $sql");
			echo json_encode($payload);
		}else{
			//devuelve un mensaje de manejo de errores
			$payload = array(
				"status" => false,
				//"sql" => $sql,
				"data" => [],
				"message" => "Error en la transacci\u00F3n $sql");
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


function get_elemento($codigo,$proceso,$tipo){
	$ClsFic = new ClsFicha();
	$result = $ClsFic->get_elemento($codigo,$proceso,$tipo);
	if(is_array($result)){
		$data = tabla_elemento($codigo,$proceso,$tipo);
		
		$payload = array(
			"status" => true,
			"data" => $data,
			"sistema" => $titulo,
			"message" => "");
		echo json_encode($payload);
	}else{
		//devuelve un mensaje de manejo de errores
		$payload = array(
			"status" => false,
			//"parametros" => "$result",
			"message" => "Este elemento del Elemento no existe...");
		echo json_encode($payload);
	}
}


function delete_elemento($codigo,$proceso,$tipo){
	$ClsFic = new ClsFicha();
	$sql = $ClsFic->cambia_situacion_elemento($codigo,$proceso, 0);
	$rs = $ClsFic->exec_sql($sql);
	if($rs == 1) {
		$data = tabla_elemento('',$proceso,$tipo);
		$payload = array(
			"status" => true,
			"data" => $data,
			//"alert" => $sql,
			"message" => "Elemento eliminado satisfactoriamente...");
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