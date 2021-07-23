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
include_once('html_fns_revision.php');

$request = $_REQUEST["request"];
switch ($request) {
	case "cerrar":
		$codigo = $_REQUEST["codigo"];
		$observacion = $_REQUEST["observacion"];
		cerrar_revision($codigo, $observacion);
		break;
	case "responder":
		$revision = $_REQUEST["revision"];
		$lista = $_REQUEST["lista"];
		$pregunta = $_REQUEST["pregunta"];
		$respuesta = $_REQUEST["respuesta"];
		responder_pregunta($revision, $lista, $pregunta, $respuesta);
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
function cerrar_revision($codigo, $observacion)
{
	$ClsRev = new ClsRevision();
	$sql = $ClsRev->cerrar_revision($codigo, $observacion);
	$rs = $ClsRev->exec_sql($sql);
	if ($rs == 1) {
		$arr_respuesta = array(
			"status" => true,
			"data" => [],
			"message" => "Checklist cerrada exitosamente...!"
		);echo json_encode($arr_respuesta);
	} else {
		$arr_respuesta = array(
			"status" => false,
			"sql" => $sql,
			"data" => [],
			"message" => "Error en la ejecución"
		);echo json_encode($arr_respuesta);
	}
}

function responder_pregunta($revision, $lista, $pregunta, $respuesta)
{
	$ClsRev = new ClsRevision();
	$sql = $ClsRev->insert_respuesta($revision, $lista, $pregunta, $respuesta);
	$rs = $ClsRev->exec_sql($sql);
	if ($rs == 1) {
		$arr_respuesta = array(
			"status" => true,
			"data" => [],
			"message" => "Pregunta respondida exitosamente...!"
		);echo json_encode($arr_respuesta);
	} else {
		$arr_respuesta = array(
			"status" => false,
			"sql" => $sql,
			"data" => [],
			"message" => "Error en la ejecución"
		);echo json_encode($arr_respuesta);
	}
}
