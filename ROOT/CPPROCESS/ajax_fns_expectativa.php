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

include_once('html_fns_proceso.php');

$request = $_REQUEST["request"];

switch ($request) {
	case "tabla_expectativa":
		tabla_expectativa();
		break;
	case "grabar_expectativa":
		$tipo = $_REQUEST["tipo"];
		$nombre  = $_REQUEST["nombre"];
		$descripcion = $_REQUEST["descripcion"];
		grabar_expectativa($tipo, $nombre, $descripcion);
		break;
	case "get":
		$codigo = $_REQUEST["codigo"];
		get($codigo);
		break;
	case "modificar_expectativa":
		$codigo = $_REQUEST['codigo'];
		$tipo = $_REQUEST["tipo"];
		$nombre  = $_REQUEST["nombre"];
		$descripcion = $_REQUEST["descripcion"];
		$situacion = $_REQUEST["situacion"];
		modificar_expectativa($codigo, $tipo, $nombre, $descripcion, $situacion);
		break;
	case "situacion":
		$codigo = $_REQUEST["codigo"];
		$situacion = $_REQUEST["situacion"];
		situacion_expectativa($codigo, $situacion);
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
function tabla_expectativa()
{
	$ClsExp = new ClsExpectativa();
	$result = $ClsExp->get_expectativa("");

	if (is_array($result)) {
		$arr_respuesta = array(
			"status" => true,
			"tabla" => tabla_expectativas("", "1,2"),
			"message" => ""
		);
	} else {
		$arr_respuesta = array(
			"status" => false,
			"data" => [],
			"message" => "Aún no hay datos registrados...!!"
		);
	}
	echo json_encode($arr_respuesta);
}

function get($codigo)
{
	$ClsExp = new ClsExpectativa();
	$result = $ClsExp->get_expectativa($codigo);
	$arr_data = array();
	if (is_array($result)) {
		foreach ($result as $row) {
			$arr_data["codigo"]		 = trim(		$row["exp_codigo"]);
			$arr_data["nombre"]		 = utf8_decode(	$row["exp_nombre"]);
			$arr_data["tipo"]		 = trim(		$row["exp_tipo"]);
			$arr_data["descripcion"] = utf8_decode( $row["exp_descripcion"]);
			$arr_data["situacion"]	 = trim(		$row["exp_situacion"]);
		}
		$arr_respuesta = array(
			"status" => true,
			"data" => $arr_data,
			"tabla" => tabla_expectativas($codigo, "1,2"),
			"message" => ""
		);
	} else {
		$arr_respuesta = array(
			"status" => false,
			"data" => [],
			"message" => "Se recibió una tabla vacía..."
		);
	}
	echo json_encode($arr_respuesta);
}

function grabar_expectativa($tipo, $nombre, $descripcion)
{
	$ClsExp = new ClsExpectativa();
	if ($tipo != "" && $nombre != "" && $descripcion != "") {
		$codigo = $ClsExp->max_expectativa();
		$codigo++;
		$sql = $ClsExp ->insert_Expectativa($codigo, $nombre, $tipo, $descripcion);
		$rs = $ClsExp->exec_sql($sql);
		if ($rs == 1) {
			$arr_respuesta = array(
				"status" => true,
				"data" => tabla_expectativas("", "1,2"),
				"message" => "Registro guardados satisfactoriamente...!"
			);
			echo json_encode($arr_respuesta);
		} else {
			$arr_respuesta = array(
				"status" => false,
				"data" => [],
				"message" => "Error en la transacción..."
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




function modificar_expectativa($codigo, $tipo, $nombre, $descripcion, $situacion)
{
	$ClsNec = new ClsExpectativa();
	if ($codigo != "" && $tipo != "" && $nombre != "" && $descripcion != "") {
		$sql = $ClsNec->modifica_expectativa($codigo, $tipo, $nombre, $descripcion, $situacion);
		$rs = $ClsNec->exec_sql($sql);
		if ($rs == 1) {
			$arr_respuesta = array(
				"status" => true,
				"data" => tabla_expectativas("", "1,2"),
				"message" => "Registro actualizado satisfactoriamente...!"
			);
			echo json_encode($arr_respuesta);
		} else {
			$arr_respuesta = array(
				"status" => false,
				"data" => [],
				"message" => "Error en la transacción..."
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



function situacion_expectativa($codigo, $situacion)
{
	$ClsExp = new ClsExpectativa();
	if ($codigo != "" && $situacion != "") {
		$sql = $ClsExp->cambia_situacion_expectativa($codigo, $situacion);
		$rs = $ClsExp->exec_sql($sql);
		if ($rs == 1) {
			$arr_respuesta = array(
				"status" => true,
				"message" => "Situación actualizada exitosamente...",
				"data" => tabla_expectativas("", "1,2")
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




function tabla_resultado()
{
	$ClsQue = new Clsnecesidad();
	$result = $ClsQue->get_necesidad("", "8");
	if (is_array($result)) {
		$arr_respuesta = array(
			"status" => true,
			"tabla" => tabla_Necesidades("", "8"),
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

function grabar_resultado($tipo, $nombre, $descripcion)
{
	$ClsNec = new ClsNecesidad();
	if ($tipo != "" && $nombre != "" && $descripcion != "") {
		$codigo = $ClsNec->max_necesidad();
		$codigo++;
		//$codigo = 2;
		$sql = $ClsNec->insert_necesidad($codigo, $tipo, $nombre, $descripcion);
		$rs = $ClsNec->exec_sql($sql);
		if ($rs == 1) {
			$arr_respuesta = array(
				"status" => true,
				"data" => tabla_Necesidades("", "8"),
				"message" => "Registro guardados satisfactoriamente...!"
			);
			echo json_encode($arr_respuesta);
		} else {
			$arr_respuesta = array(
				"status" => false,
				"data" => [],
				"message" => "Error en la transacción..."
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

function get_resultado($codigo)
{
	$ClsAct = new Clsnecesidad();
	$result = $ClsAct->get_necesidad($codigo, "8");
	$arr_data = array();
	if (is_array($result)) {
		foreach ($result as $row) {
			$arr_data["codigo"] = trim($row["ext_codigo"]);
			$arr_data["tipo"] = trim($row["ext_tipo"]);
			$arr_data["nombre"] = trim($row["ext_nombre"]);
			$arr_data["descripcion"] = utf8_decode(($row["ext_descripcion"]));
		}
		$arr_respuesta = array(
			"status" => true,
			"data" => $arr_data,
			"tabla" => tabla_Necesidades($codigo, "8"),
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



function modificar_resultado($codigo, $tipo, $nombre, $descripcion)
{
	$ClsNec = new ClsNecesidad();
	if ($codigo != "" && $tipo != "" && $nombre != "" && $descripcion != "") {
		$sql = $ClsNec->modifica_necesidad($codigo, $tipo, $nombre, $descripcion);
		$rs = $ClsNec->exec_sql($sql);
		if ($rs == 1) {
			$arr_respuesta = array(
				"status" => true,
				"data" => tabla_Necesidades("", "8"),
				"message" => "Registro actualizado satisfactoriamente...!"
			);
			echo json_encode($arr_respuesta);
		} else {
			$arr_respuesta = array(
				"status" => false,
				"data" => [],
				"message" => "Error en la transacción..."
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



function situacion_resultado($codigo, $situacion)
{
	$ClsNec = new ClsNecesidad();
	if ($codigo != "" && $situacion != "") {
		$sql = $ClsNec->cambia_situacion_Necesidad($codigo, $situacion);
		$rs = $ClsNec->exec_sql($sql);
		if ($rs == 1) {
			$arr_respuesta = array(
				"status" => true,
				"message" => "Situación actualizada exitosamente...",
				"data" => tabla_Necesidades("", "8")
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
