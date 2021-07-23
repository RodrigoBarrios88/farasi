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
		case "situacion":
			$codigo = $_REQUEST["codigo"];
			$situacion = $_REQUEST["situacion"];
			situacion($codigo, $situacion);
			break;
		case "update":
			$codigo = $_REQUEST["codigo"];
			$campo = $_REQUEST["campo"];
			$valor = $_REQUEST["valor"];
			update($codigo, $campo, $valor);
			break;
			// Crea un backup de las actividades y pone el plan en edicion de nuevo
		case "reiniciar":
			$codigo = $_REQUEST["codigo"];
			reiniciar($codigo);
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

function reiniciar($codigo)
{
	$ClsPla = new ClsPlan();
	$sql = $ClsPla->reinicia_plan_mejora($codigo);
	$rs = $ClsPla->exec_sql($sql);
	if ($rs == 1) {
		$arr_respuesta = array(
			"status" => true,
			"sql" => $sql,
			"message" => "Plan reiniciado exitosamente..."
		);
	} else {
		$arr_respuesta = array(
			"status" => false,
			"data" => [],
			"message" => $sql
		);
	}
	echo json_encode($arr_respuesta);
}

function update($codigo, $campo, $valor)
{
	$ClsAct = new ClsActividad();
	if ($codigo != "" && $campo != "") {
		switch ($campo) {
			case 1:
				$db_campo = "pro_ejecucion";
				break;
			case 2:
				$db_campo = "pro_fecha";
				break;
			case 3:
				$db_campo = "pro_fecha";
				break;
			case 4:
				$db_campo = "pro_evaluacion";
				break;
			case 5:
				$db_campo = "pro_puntuacion";
				break;
			case 6:
				$db_campo = "pro_evalua";
				break;
			case 7:
				$db_campo = "pro_fecha_evaluacion";
				break;
			default:
				$db_campo = "";
				break;
		}
		if ($db_campo != "") {
			$sql = $ClsAct->modifica_programacion_mejora($codigo, $db_campo, $valor);
		}
		$rs = $ClsAct->exec_sql($sql);
		if ($rs == 1) {
			$arr_respuesta = array(
				"status" => true,
				"data" => [],
				"message" => "Registro actualizado satisfactoriamente...!"
			);
			echo json_encode($arr_respuesta);
		} else {
			$arr_respuesta = array(
				"status" => false,
				//"sql" => $sql,
				"data" => [],
				"message" => "Error en la transacción..." . $sql
			);
			echo json_encode($arr_respuesta);
		}
	} else {
		$arr_respuesta = array(
			"status" => false,
			"data" => [],
			"message" => "Debe llenar los campos obligatorios..."
		);
		echo json_encode($arr_respuesta);
	}
}

function situacion($codigo, $situacion)
{
	$ClsAct = new ClsActividad();
	if ($codigo != "" && $situacion != "") {
		$sql = $ClsAct->cambia_situacion_programacion_mejora($codigo, "", $situacion);
		$rs = $ClsAct->exec_sql($sql);
		if ($rs == 1) {
			$arr_respuesta = array(
				"status" => true,
				"message" => "Situación actualizada exitosamente..."
			);
		} else {
			$arr_respuesta = array(
				"status" => false,
				"data" => [],
				"message" => $sql
			);
		}
	} else {
		$arr_respuesta = array(
			"status" => false,
			"data" => [],
			"message" => "Debe llenar datos obligatorios"
		);
	}
	echo json_encode($arr_respuesta);
}
