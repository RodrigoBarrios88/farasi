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
	case "tabla":
		$codigo = $_REQUEST["codigo"];
		$indicador = $_REQUEST["indicador"];
		$departamento = $_REQUEST["departamento"];
		$clasificacion = $_REQUEST["clasificacion"];
		$categoria = $_REQUEST["categoria"];
		get_tabla($codigo, $indicador, $departamento, $clasificacion, $categoria);
		break;
	case "situacion":
		$codigo = $_REQUEST["codigo"];
		$situacion = $_REQUEST["situacion"];
		situacion($codigo, $situacion);
		break;
	case "modificar":
		$codigo = $_REQUEST["codigo"];
		$campo = $_REQUEST["campo"];
		$valor = $_REQUEST["valor"];
		modificar($codigo, $campo, $valor);
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
function get_tabla($codigo, $indicador, $departamento, $clasificacion, $categoria)
{
	$ClsInd = new ClsIndicador();
	$result = $ClsInd->get_programacion($codigo, $indicador, $departamento, $clasificacion, $categoria);
	if (is_array($result)) {
		$arr_respuesta = array(
			"status" => true,
			"tabla" => tabla_ejecucion($codigo, $indicador, $departamento, $clasificacion, $categoria),
			"message" => ""
		);
	} else {
		$arr_respuesta = array(
			"status" => false,
			"data" => [],
			"message" => "Aún no hay datos registrados..."
		);
	}
	echo json_encode($arr_respuesta);
}


function situacion($codigo, $situacion)
{
	$ClsRev = new ClsRevision();
	if ($codigo != "" && $situacion != "") {
		$sql = $ClsRev->situacion_revision_indicador($codigo, $situacion);
		$rs = $ClsRev->exec_sql($sql);
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

function modificar($codigo, $campo, $valor)
{
	$ClsRev = new ClsRevision();
	if ($codigo != "" && $campo != "") {
		switch ($campo) {
			case 1:
				$db_campo = "rev_lectura";
				break;
			case 2:
				$db_campo = "rev_observaciones";
				break;
			default:
				$db_campo = "";
				break;
		}
		if ($db_campo != "") {
			$sql = $ClsRev->modifica_revision_indicador($codigo, $db_campo, $valor);
		}
		$rs = $ClsRev->exec_sql($sql);
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
