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
		case "grabar_objetivo":
			$proceso = $_REQUEST["proceso"];
			$sistema = $_REQUEST["sistema"];
			$descripcion = $_REQUEST["descripcion"];
			grabar_objetivo($proceso, $sistema, $descripcion);
			break;
		case "modificar":
			$codigo = $_REQUEST["codigo"];
			$descripcion = $_REQUEST["descripcion"];
			modifica_objetivo($codigo, $descripcion);
			break;
		case "delete":
			$codigo = $_REQUEST["codigo"];
			$proceso = $_REQUEST["proceso"];
			$sistema = $_REQUEST["sistema"];
			delete_objetivo($codigo, $proceso, $sistema);
			break;
		case "get":
			$codigo = $_REQUEST["codigo"];
			$proceso = $_REQUEST["proceso"];
			$sistema = $_REQUEST["sistema"];
			get_objetivo($codigo, $proceso, $sistema);
			break;
		case "tabla":
			$proceso = $_REQUEST["proceso"];
			$sistema = $_REQUEST["sistema"];
			get_tabla($proceso, $sistema);
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

function get_tabla($proceso, $sistema){
	$data = objetivos_acordion($proceso, $sistema);
	$payload = array(
		"status" => true,
		"data" => $data,
	);
	echo json_encode($payload);
}

function grabar_objetivo($proceso, $sistema, $descripcion)
{
	// A cada objetivo crearle su indicador y su control
	$ClsObj = new ClsObjetivo();
	$ClsCon = new ClsControl();
	$ClsInd = new ClsIndicador();
	if ($proceso != "" && $sistema != "") {
		$codigo_objetivo = $ClsObj->max_objetivo();
		$codigo_objetivo++;
		$sql = $ClsObj->insert_objetivo($codigo_objetivo, $proceso, $sistema, $descripcion);
		$codigo_control = $ClsCon->max_control();
		$codigo_control++;
		$sql .= $ClsCon->insert_control($codigo_control, $codigo_objetivo, "");
		$codigo_indicador = $ClsInd->max_indicador();
		$codigo_indicador++;
		$sql .= $ClsInd->insert_indicador($codigo_indicador, $codigo_objetivo, 0, "", "", 0, 0, 0,2);$rs = $ClsObj->exec_sql($sql);
		if ($rs == 1) {
			$payload = array(
				"status" => true,
				"codigo" => $codigo_objetivo,
				"message" => "Objetivo creado satisfactoriamente..."
			);
			echo json_encode($payload);
		} else {
			//devuelve un mensaje de manejo de errores
			$payload = array(
				"status" => false,
				//"sql" => $sql,
				"data" => [],
				"message" => "Error en la transacci\u00F3n"
			);
			echo json_encode($payload);
		}
	} else {
		$arr_respuesta = array(
			"status" => false,
			"data" => [],
			"message" => "Debe llenar los campos obligatorios..."
		);echo json_encode($arr_respuesta);
	}
}

function modifica_objetivo($codigo, $descripcion)
{
	$ClsObj = new ClsObjetivo();
	if ($codigo != "") {
		$sql = $ClsObj->modifica_objetivo($codigo, $descripcion); //El codigo se remplazará por el tipo, ya que solo existirá un objetivo por tipo y por sistema
		$rs = $ClsObj->exec_sql($sql);
		if ($rs == 1) {
			$payload = array(
				"status" => true,
				"message" => "Objetivo actualizado satisfactoriamente..."
			);
			echo json_encode($payload);
		} else {
			//devuelve un mensaje de manejo de errores
			$payload = array(
				"status" => false,
				//"sql" => $sql,
				"data" => [],
				"message" => "Error en la transacci\u00F3n"
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

function delete_objetivo($codigo, $proceso, $sistema)
{
	$ClsObj = new ClsObjetivo();
	if ($codigo != "" && $proceso != "" && $sistema != "") {$sql = $ClsObj->cambia_situacion_objetivo($codigo, 0);
		$rs = $ClsObj->exec_sql($sql);
		if ($rs == 1) {
			$data = objetivos_acordion($proceso, $sistema);
			$payload = array(
				"status" => true,
				"data" => $data,
				"message" => "Objetivo eliminado satisfactoriamente..."
			);
			echo json_encode($payload);
		} else {
			//devuelve un mensaje de manejo de errores
			$payload = array(
				"status" => false,
				//"sql" => $sql,
				"data" => [],
				"message" => "Error en la transacci\u00F3n"
			);
			echo json_encode($payload);
		}
	} else {
		$arr_respuesta = array(
			"status" => false,
			"data" => [],
			"message" => "Debe llenar los campos obligatorios..."
		);echo json_encode($arr_respuesta);
	}
}

function get_objetivo($codigo, $proceso, $sistema)
{
	$ClsObj = new ClsObjetivo();
	if ($codigo != "" && $proceso != "" && $sistema != "") {$rs = $ClsObj->get_objetivo($codigo, $proceso, $sistema);
		if (is_array($rs)) {
			$data = tabla_objetivos_detalle($codigo, $proceso, $sistema);
			$payload = array(
				"status" => true,
				"data" => $data,
				"message" => "Objetivo eliminado satisfactoriamente..."
			);
			echo json_encode($payload);
		} else {
			//devuelve un mensaje de manejo de errores
			$payload = array(
				"status" => false,
				//"sql" => $sql,
				"data" => [],
				"message" => "Error en la transacci\u00F3n"
			);
			echo json_encode($payload);
		}
	} else {
		$arr_respuesta = array(
			"status" => false,
			"data" => [],
			"message" => "Debe llenar los campos obligatorios..."
		);echo json_encode($arr_respuesta);
	}
}
