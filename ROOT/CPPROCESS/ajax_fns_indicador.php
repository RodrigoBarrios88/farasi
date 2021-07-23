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

if ($request != "") {
	switch ($request) {
		case "modificar":
			$codigo = $_REQUEST["codigo"];
			$nombre = $_REQUEST["nombre"];
			$unidad = $_REQUEST["unidad"];
			$ideal = $_REQUEST["ideal"];
			$max = $_REQUEST["max"];
			$min = $_REQUEST["min"];
			$descripcion = $_REQUEST["descripcion"];
			modifica_indicador($codigo, $nombre, $unidad, $ideal, $max, $min, $descripcion);
			break;
		default:
			$payload = array(
				"status" => false,
				"data" => [],
				"message" => "Parametros invalidos..."
			);
			echo json_encode($payload);
			break;
	}
} else {
	//devuelve un mensaje de manejo de errores
	$payload = array(
		"status" => false,
		"data" => [],
		"message" => "Delimite el desde de consulta a realizar..."
	);
	echo json_encode($payload);
}

function modifica_indicador($codigo, $nombre, $unidad, $ideal, $max, $min, $descripcion)
{
	$ClsInd = new ClsIndicador();
	if ($codigo != "" ) {
		$sql = $ClsInd->modifica_indicador($codigo, $nombre, $unidad, $ideal, $max, $min, $descripcion); //El codigo se remplazará por el tipo, ya que solo existirá un control por tipo y por sistema
		$rs = $ClsInd->exec_sql($sql);
		if ($rs == 1) {
			$payload = array(
				"status" => true,
				"message" => "Indicador actualizado satisfactoriamente..."
			);
			echo json_encode($payload);
		} else {
			//devuelve un mensaje de manejo de errores
			$payload = array(
				"status" => false,
				//"sql" => $sql,
				"data" => [],
				"message" => "Error en la transacci\u00F3n".$sql
			);
			echo json_encode($payload);
		}
	} else {
		$arr_respuesta = array(
			"status" => false,
			"message" => "Debe llenar los campos obligatorios..."
		);echo json_encode($arr_respuesta);
	}
}
