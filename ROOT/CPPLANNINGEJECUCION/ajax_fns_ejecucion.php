<?php
ob_start();
header("Cache-control: private, no-cache");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Pragma: no-cache");
header("Cache: no-cache");
ini_set('max_execution_time', 90000);
ini_set("memory_limit", -1);
//--
include_once('html_fns_ejecucion.php');

header("Access-Control-Allow-Methods: PUT, GET, POST, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding");
header("Access-Control-Allow-Origin: *");

///API REQUEST
$request = $_REQUEST["request"];
$_REQUEST = str_replace("undefined", "", $_REQUEST); ///valida campos "undefined" desde javascript

if ($request != "") {
	switch ($request) {
			// Ejecucion
		case "grabar":
			$observacion = $_REQUEST["observacion"];
			$programacion = $_REQUEST["programacion"];
			grabar($programacion, $observacion);
			break;
		case "delete":
			$codigo = $_REQUEST["codigo"];
			delete($codigo);
			break;
		case "modificar":
			$codigo = $_REQUEST["codigo"];
			$observacion = $_REQUEST["observacion"];
			modificar($codigo, $observacion);
			break;
		case "situacion":
			$codigo = $_REQUEST["codigo"];
			$situacion = $_REQUEST["situacion"];
			situacion($codigo, $situacion);
			break;
			case "situacion_programacion":
				$codigo = $_REQUEST["codigo"];
				$situacion = $_REQUEST["situacion"];
				situacion_programacion($codigo, $situacion);
				break;
		case "situacion_revision":
			$codigo = $_REQUEST["codigo"];
			$situacion = $_REQUEST["situacion"];
			$observacion = $_REQUEST["observacion"];
			situacion_revision($codigo, $situacion, $observacion);
			break;
		case "get":
			$codigo = $_REQUEST["codigo"];
			$objetivo = $_REQUEST["objetivo"];
			$usuario = $_REQUEST["usuario"];
			get($codigo, $objetivo, $usuario);
			break;
			// Objetivos
		case "aprobacion":
			$codigo = $_REQUEST["codigo"];
			$objetivo = $_REQUEST["objetivo"];
			solicitar_aprobacion($codigo, $objetivo);
			break;
			// Evaluacion
		case "grabar_evaluacion":
			$observacion = $_REQUEST["observacion"];
			$puntuacion = $_REQUEST["puntuacion"];
			$ejecucion = $_REQUEST["ejecucion"];
			grabar_evaluacion($ejecucion, $observacion, $puntuacion);
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

function grabar($programacion, $observacion)
{
	$ClsEje = new ClsEjecucion();
	$codigo = $ClsEje->max_ejecucion_accion();
	$codigo++;
	// Accion
	$sql = $ClsEje->insert_ejecucion_accion($codigo, $programacion, $observacion);
	$rs = $ClsEje->exec_sql($sql);
	if ($rs == 1) {
		$payload = array(
			"status" => true,
			"message" => "Ejecucion realizada satisfactoriamente..."
		);
		echo json_encode($payload);
	} else {
		//devuelve un mensaje de manejo de errores
		$payload = array(
			"status" => false,
			"message" => $sql
		);
		echo json_encode($payload);
	}
}

function modificar($codigo, $observacion)
{
	// Accion
	$ClsEje = new ClsEjecucion();
	$sql = $ClsEje->modifica_ejecucion_accion($codigo, $observacion);

	$rs = $ClsEje->exec_sql($sql);
	if ($rs == 1) {
		$payload = array(
			"status" => true,
			"message" => "Accion modificada satisfactoriamente..."
		);
		echo json_encode($payload);
	} else {
		//devuelve un mensaje de manejo de errores
		$payload = array(
			"status" => false,
			"message" => $sql
		);
		echo json_encode($payload);
	}
}
function delete($codigo)
{
	// Accion
	$ClsAcc = new ClsAccion();
	$sql = $ClsAcc->cambia_situacion_accion($codigo, 0);
	// Borrar las programaciones
	$sql .= $ClsAcc->cambia_situacion_programacion("", $codigo, 0);

	$rs = $ClsAcc->exec_sql($sql);
	if ($rs == 1) {
		$payload = array(
			"status" => true,
			"message" => "Accion eliminada satisfactoriamente..."
		);
		echo json_encode($payload);
	} else {
		//devuelve un mensaje de manejo de errores
		$payload = array(
			"status" => false,
			"message" => $sql
		);
		echo json_encode($payload);
	}
}

function get($codigo, $objetivo, $usuario)
{
	// Accion
	$ClsAcc = new ClsAccion();
	$rs = $ClsAcc->get_accion($codigo, $objetivo, "", $usuario);
	if (is_array($rs)) {
		$data = tabla_acciones($codigo, $objetivo, "", $usuario, "", "", "", "", 1, true);
		$payload = array(
			"status" => true,
			"data" => $data,
			"message" => ""
		);
		echo json_encode($payload);
	} else {
		//devuelve un mensaje de manejo de errores
		$payload = array(
			"status" => false,
			"message" => "Esta accion no existe..."
		);
		echo json_encode($payload);
	}
}

function situacion($codigo, $situacion) {
	// Accion
	$ClsEje = new ClsEjecucion();
	$sql = $ClsEje->cambia_situacion_ejecucion_accion($codigo, $situacion);
	$rs = $ClsEje->exec_sql($sql);
	if ($rs == 1) {
		$payload = array(
			"status" => true,
			"message" => "Operacion realizada satisfactoriamente"
		);
		echo json_encode($payload);
	} else {
		//devuelve un mensaje de manejo de errores
		$payload = array(
			"status" => false,
			"message" => $sql
		);
		echo json_encode($payload);
	}
}

function situacion_programacion($codigo, $situacion) {
	// Accion
	$ClsAcc = new ClsAccion();
	$sql = $ClsAcc->cambia_situacion_programacion($codigo,"", $situacion);
	$rs = $ClsAcc->exec_sql($sql);
	if ($rs == 1) {
		$payload = array(
			"status" => true,
			"message" => "Operacion realizada satisfactoriamente"
		);
		echo json_encode($payload);
	} else {
		//devuelve un mensaje de manejo de errores
		$payload = array(
			"status" => false,
			"message" => $sql
		);
		echo json_encode($payload);
	}
}
////////////////////// Objetivos ////////////////////////////
function solicitar_aprobacion($codigo, $objetivo)
{
	// TODO si ya existe la revision solo cambiarle situacion
	// Accion
	$ClsObj = new ClsObjetivo();
	if ($codigo == "") {
		$codigo = $ClsObj->max_revision();
		$codigo++;
	}
	$sql = $ClsObj->insert_revision($codigo, $objetivo, "", 0, "", "", 2);
	$rs = $ClsObj->exec_sql($sql);
	if ($rs == 1) {
		$payload = array(
			"status" => true,
			"message" => "Se solicita aprobacion correctamente"
		);
		echo json_encode($payload);
	} else {
		//devuelve un mensaje de manejo de errores
		$payload = array(
			"status" => false,
			"message" => $sql
		);
		echo json_encode($payload);
	}
}

function situacion_revision($codigo, $situacion, $observacion)
{
	// Accion
	$ClsObj = new ClsObjetivo();
	$sql = $ClsObj->cambia_situacion_revision($codigo, $observacion, $situacion);
	$rs = $ClsObj->exec_sql($sql);
	if ($rs == 1) {
		$payload = array(
			"status" => true,
			"message" => "Operacion realizada satisfactoriamente"
		);
		echo json_encode($payload);
	} else {
		//devuelve un mensaje de manejo de errores
		$payload = array(
			"status" => false,
			"message" => $sql
		);
		echo json_encode($payload);
	}
}

/////////////////////////// Evaluacion ///////////////////

function grabar_evaluacion($ejecucion, $observacion, $puntuacion)
{
	$ClsEve = new ClsEvaluacion();
	$codigo = $ClsEve->max_evaluacion();
	$codigo++;
	// Accion
	$sql = $ClsEve->insert_evaluacion($codigo, $ejecucion, $observacion, $puntuacion);
	$rs = $ClsEve->exec_sql($sql);
	if ($rs == 1) {
		$payload = array(
			"status" => true,
			"message" => "Evaluacion realizada satisfactoriamente..."
		);
		echo json_encode($payload);
	} else {
		//devuelve un mensaje de manejo de errores
		$payload = array(
			"status" => false,
			"message" => $sql
		);
		echo json_encode($payload);
	}
}
