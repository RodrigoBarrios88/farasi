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
	case "tabla_necesidad":
		tablanecesidad();
		break;
	case "grabar_necesidad":
		$tipo = $_REQUEST["tipo"];
		$nombre  = $_REQUEST["nombre"];
		$descripcion = $_REQUEST["descripcion"];
		grabar_necesidad($tipo, $nombre, $descripcion);
		break;
	case "get":
		$codigo = $_REQUEST["codigo"];
		get($codigo);
		break;
	case "modificar_necesidad":
		$codigo = $_REQUEST['codigo'];
		$tipo = $_REQUEST["tipo"];
		$nombre  = $_REQUEST["nombre"];
		$descripcion = $_REQUEST["descripcion"];
		modificar_necesidad($codigo, $tipo, $nombre, $descripcion);
		break;
	case "situacion":
		$codigo = $_REQUEST["codigo"];
		$situacion = $_REQUEST["situacion"];
		situacion_necesidad($codigo, $situacion);
		break;

	case "tabla_Resultados":
		tabla_resultado();
		break;
	case "grabar_resultado":
		$tipo = $_REQUEST["tipo"];
		$nombre  = $_REQUEST["nombre"];
		$descripcion = $_REQUEST["descripcion"];
		grabar_resultado($tipo, $nombre, $descripcion);
		break;
	case "getResultado":
		$codigo = $_REQUEST["codigo"];
		get_resultado($codigo);
		break;
	case "modificar_resultado":
		$codigo = $_REQUEST['codigo'];
		$tipo = $_REQUEST["tipo"];
		$nombre  = $_REQUEST["nombre"];
		$descripcion = $_REQUEST["descripcion"];
		modificar_resultado($codigo, $tipo, $nombre, $descripcion);
		break;
	case "situacion_resultado":
		$codigo = $_REQUEST["codigo"];
		$situacion = $_REQUEST["situacion"];
		situacion_resultado($codigo, $situacion);
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
function tablanecesidad()
{
	$ClsQue = new Clsnecesidad();
	$result = $ClsQue->get_necesidad("", "1,2");
	if (is_array($result)) {
		$arr_respuesta = array(
			"status" => true,
			"tabla" => tabla_Necesidades("", "1,2"),
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

function grabar_necesidad($tipo, $nombre, $descripcion)
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
				"data" => tabla_Necesidades("", "1,2"),
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

function get($codigo)
{
	$ClsAct = new Clsnecesidad();
	$result = $ClsAct->get_necesidad($codigo, "1,2");
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
			"tabla" => tabla_Necesidades($codigo, "1,2"),
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



function modificar_necesidad($codigo, $tipo, $nombre, $descripcion)
{
	$ClsNec = new ClsNecesidad();
	if ($codigo != "" && $tipo != "" && $nombre != "" && $descripcion != "") {
		$sql = $ClsNec->modifica_necesidad($codigo, $tipo, $nombre, $descripcion);
		$rs = $ClsNec->exec_sql($sql);
		if ($rs == 1) {
			$arr_respuesta = array(
				"status" => true,
				"data" => tabla_Necesidades("", "1,2"),
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



function situacion_necesidad($codigo, $situacion)
{
	$ClsNec = new ClsNecesidad();
	if ($codigo != "" && $situacion != "") {
		$sql = $ClsNec->cambia_situacion_Necesidad($codigo, $situacion);
		$rs = $ClsNec->exec_sql($sql);
		if ($rs == 1) {
			$arr_respuesta = array(
				"status" => true,
				"message" => "Situación actualizada exitosamente...",
				"data" => tabla_Necesidades("", "1,2")
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
