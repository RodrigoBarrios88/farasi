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
			nuevo_recurso($proceso);
			break;
		case "grabar":
			$codigo = $_REQUEST["codigo"];
			$proceso = $_REQUEST["proceso"];
			$tipo = $_REQUEST["clase"];
			$descripcion = $_REQUEST["descripcion"];
			grabar_recurso($codigo,$proceso,$tipo,$descripcion);
			break;
		case "get":
			$codigo = $_REQUEST["codigo"];
			$proceso = $_REQUEST["proceso"];
			get_recurso($codigo,$proceso);
			break;
		case "delete":
			$codigo = $_REQUEST["codigo"];
			$proceso = $_REQUEST["proceso"];
			delete_recurso($codigo,$proceso);
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
function nuevo_recurso($proceso){
	$ClsRec = new ClsRecursos();
	$codigo = $ClsRec->max_recurso($proceso);
	$codigo++;
	$sql = $ClsRec->insert_recurso($codigo,$proceso,0,'');
	$rs = $ClsRec->exec_sql($sql);
	if($rs == 1) {
		$data = tabla_recurso($codigo,$proceso);
		$payload = array(
			"status" => true,
			"codigo" => $codigo,
			"data" => $data,
			"message" => "Recurso actualizado satisfactoriamente...");
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


function grabar_recurso($codigo,$proceso,$tipo,$descripcion){
	$ClsRec = new ClsRecursos();
	if($codigo != "" && $proceso != "" && $tipo != "" && $descripcion != ""){
		$sql = $ClsRec->insert_recurso($codigo,$proceso,$tipo,$descripcion);
		$rs = $ClsRec->exec_sql($sql);
		if($rs == 1) {
			$data = tabla_recurso('',$proceso);
			$payload = array(
				"status" => true,
				"data" => $data,
				"message" => "Recurso actualizado satisfactoriamente...");
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


function get_recurso($codigo,$proceso){
	$ClsRec = new ClsRecursos();
	$result = $ClsRec->get_recurso($codigo,$proceso);
	if(is_array($result)){
		foreach($result as $row){
			$tipo = trim($row["rec_tipo_recurso"]);
		}	
		$data = tabla_recurso($codigo,$proceso);
		$payload = array(
			"status" => true,
			"clase" => $tipo,
			"data" => $data,
			"message" => "");
		echo json_encode($payload);
	}else{
		//devuelve un mensaje de manejo de errores
		$payload = array(
			"status" => false,
			//"parametros" => "$result",
			"message" => "Este elemento Recurso no existe...");
		echo json_encode($payload);
	}
}


function delete_recurso($codigo,$proceso){
	$ClsRec = new ClsRecursos();
	$sql = $ClsRec->cambia_situacion_recurso($codigo,$proceso, 0);
	$rs = $ClsRec->exec_sql($sql);
	if($rs == 1) {
		$data = tabla_recurso('',$proceso);
		$payload = array(
			"status" => true,
			"data" => $data,
			//"alert" => $sql,
			"message" => "Recurso eliminado satisfactoriamente...");
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