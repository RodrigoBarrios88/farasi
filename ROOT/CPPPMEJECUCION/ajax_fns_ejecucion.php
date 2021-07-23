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
include_once('html_fns_ejecucion.php');

$request = $_REQUEST["request"];
switch ($request) {
	case "responder_pregunta":
		$programacion = $_REQUEST["programacion"];
		$cuestionario = $_REQUEST["cuestionario"];
		$pregunta = $_REQUEST["pregunta"];
		$respuesta = $_REQUEST["respuesta"];
		responder_pregunta($programacion, $cuestionario, $pregunta, $respuesta);
		break;
	case "update":
		$codigo = $_REQUEST["codigo"];
		$valor = $_REQUEST["valor"];
		$campo = $_REQUEST["campo"];
		update($codigo, $campo, $valor);
		break;
	case "situacion_programacion":
		$codigo = $_REQUEST["codigo"];
		$situacion = $_REQUEST["situacion"];
		$observacion = $_REQUEST["observacion"];
		situacion_programacion($codigo, $situacion, $observacion);
		break;
		////PRESUPUESTO
	case "presupuesto":
		$codigo = $_REQUEST["codigo"];
		$presupuesto = $_REQUEST["presupuesto"];
		$observacion = $_REQUEST["observacion"];
		presupuesto($codigo, $presupuesto, $observacion);
		break;
	default:
		$arr_respuesta = array(
			"status" => false,
			"data" => [],
			"message" => "Seleccione un metodo..."
		);
		echo json_encode($arr_respuesta);
}

////////////////// VERSIONES /////////////////////////
function responder_pregunta($programacion, $cuestionario, $pregunta, $respuesta)
{
	$ClsPro = new ClsProgramacionPPM();
	if ($programacion != "" && $cuestionario != "" && $pregunta != "" && $respuesta != "") {
		$sql = $ClsPro->insert_respuesta($programacion, $cuestionario, $pregunta, $respuesta);
		$rs = $ClsPro->exec_sql($sql);
		if ($rs == 1) {
			$arr_respuesta = array(
				"status" => true,
				"data" => [],
				"message" => "Pregunta grabada exitosamente..."
			);
		} else {
			$arr_respuesta = array(
				"status" => false,
				"data" => [],
				"message" => "Error en la transaccion..."
			);
		}
		echo json_encode($arr_respuesta);
	}
}

function update($codigo, $campo, $valor)
{
	$ClsPro = new ClsProgramacionPPM();
	if ($codigo != "" && $campo != "") {
		switch ($campo) {
			case 1:
				$db_campo = "pro_observaciones_ejecucion";
				break;
			default:
				$db_campo = "";
				break;
		}
		if ($db_campo != "") {
			$sql = $ClsPro->update_programacion($codigo, $db_campo, $valor);
		}
		$rs = $ClsPro->exec_sql($sql);
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
		);echo json_encode($arr_respuesta);
	}
}
function situacion_programacion($codigo, $situacion, $observacion)
{
	$ClsPro = new ClsProgramacionPPM();
	$observacion = trim($observacion);
	if ($codigo != "" && $situacion != "") {
		$sql = $ClsPro->update_observaciones_ejecucion($codigo, $observacion);
		$sql .= $ClsPro->cambia_sit_programacion($codigo, date("d/m/Y H:i:s"), $situacion);
		$rs = $ClsPro->exec_sql($sql);
		if ($rs == 1) {
			$arr_respuesta = array(
				"status" => true,
				"data" => [],
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



///////// PRESUPUESTO ////////

function presupuesto($codigo, $presupuesto, $observaciones)
{
	$ClsPro = new ClsProgramacionPPM();
	$observaciones = trim($observaciones);

	if ($codigo != "" && $presupuesto != "") {
		$sql = $ClsPro->update_presupuesto_ejecucion($codigo, $presupuesto, $observaciones);
		$rs = $ClsPro->exec_sql($sql);
		if ($rs == 1) {
			$arr_respuesta = array(
				"status" => true,
				"data" => [],
				"message" => "Presupuesto actualizada exitosamente..."
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
