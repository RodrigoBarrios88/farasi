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
include_once('html_fns_mejora.php');

$request = $_REQUEST["request"];
switch ($request) {
		////////////////// VERSIONES /////////////////////////
	case "tabla":
		$codigo = $_REQUEST["codigo"];
		tabla($codigo);
		break;
	case "get":
		$codigo = $_REQUEST["codigo"];
		get($codigo);
		break;
	case "grabar":
		$fecha = $_REQUEST["fecha"];
		$tipo = $_REQUEST["tipo"];
		$entidad = $_REQUEST["entidad"];
		$objetivo = $_REQUEST["objetivo"];
		$resumen = $_REQUEST["resumen"];
		grabar($fecha, $tipo, $entidad, $objetivo, $resumen);
		break;
	case "modificar":
		$codigo = $_REQUEST["codigo"];
		$fecha = $_REQUEST["fecha"];
		$tipo = $_REQUEST["tipo"];
		$entidad = $_REQUEST["entidad"];
		$objetivo = $_REQUEST["objetivo"];
		$resumen = $_REQUEST["resumen"];
		modificar($codigo, $fecha, $tipo, $entidad, $objetivo, $resumen);
		break;
	case "situacion":
		$codigo = $_REQUEST["codigo"];
		$situacion = $_REQUEST["situacion"];
		situacion($codigo, $situacion);
		break;
		///////////////////////// DETALLE //////////////////////
	case "tabla_detalle":
		$codigo = $_REQUEST["codigo"];
		$auditoria = $_REQUEST["auditoria"];
		tabla_detalle($codigo, $auditoria);
		break;
	case "get_detalle":
		$codigo = $_REQUEST["codigo"];
		get_detalle($codigo);
		break;
	case "grabar_detalle":
		$auditoria = $_REQUEST["auditoria"];
		$descripcion = $_REQUEST["descripcion"];
		grabar_detalle($descripcion, $auditoria);
		break;
	case "modificar_detalle":
		$codigo = $_REQUEST["codigo"];
		$auditoria = $_REQUEST["auditoria"];
		$descripcion = $_REQUEST["descripcion"];
		modificar_detalle($codigo, $auditoria, $descripcion);
		break;
	case "situacion_detalle":
		$codigo = $_REQUEST["codigo"];
		$situacion = $_REQUEST["situacion"];
		situacion_detalle($codigo, $situacion);
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
function get($codigo)
{
	$ClsAud = new ClsAuditoria();
	$result = $ClsAud->get_externa($codigo);
	$arr_data = array();
	if (is_array($result)) {
		foreach ($result as $row) {
			$arr_data["codigo"] = trim($row["ext_codigo"]);
			$arr_data["tipo"] = trim($row["ext_tipo"]);
			$arr_data["entidad"] = trim($row["ext_entidad"]);
			$arr_data["objetivo"] = trim($row["ext_objetivo"]);
			$arr_data["resumen"] = trim($row["ext_resumen"]);
			$arr_data["fecha"] = cambia_fecha($row["ext_fecha_auditoria"]);
		}
		$arr_respuesta = array(
			"status" => true,
			"data" => $arr_data,
			"tabla" => tabla_externas($codigo),
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
function grabar($fecha, $tipo, $entidad, $objetivo, $resumen)
{
	$ClsAud = new ClsAuditoria();
	if ($fecha != "" && $tipo != "" && $entidad != "" && $objetivo != "" && $resumen != "") {
		$codigo = $ClsAud->max_externa();
		$codigo++;
		$sql = $ClsAud->insert_externa($codigo, $tipo, $entidad, $objetivo, $resumen, $fecha); /// Inserta Version
		$rs = $ClsAud->exec_sql($sql);
		if ($rs == 1) {
			$arr_respuesta = array(
				"status" => true,
				"data" => tabla_externas(),
				"sql" => $sql,
				"message" => "Registro guardado satisfactoriamente...!"
			);
			echo json_encode($arr_respuesta);
		} else {
			$arr_respuesta = array(
				"status" => false,
				"sql" => $sql,
				"data" => [],
				"message" => "Error en la transacción..."
			);
			echo json_encode($arr_respuesta);
		}
	} else {
		$arr_respuesta = array(
			"status" => false,
			"message" => "Debe llenar los campos obligatorios..."
		);

		echo json_encode($arr_respuesta);
	}
}
function modificar($codigo, $fecha, $tipo, $entidad, $objetivo, $resumen)
{
	$ClsAud = new ClsAuditoria();
	if ($codigo != "" && $fecha != "" && $tipo != "" && $entidad != "" && $objetivo != "" && $resumen != "") {
		$sql = $ClsAud->modifica_externa($codigo, $tipo, $entidad, $objetivo, $resumen, $fecha); /// Inserta Version
		$rs = $ClsAud->exec_sql($sql);
		if ($rs == 1) {
			$arr_respuesta = array(
				"status" => true,
				"data" => tabla_externas(),
				"sql" => $sql,
				"message" => "Registro guardado satisfactoriamente...!"
			);
			echo json_encode($arr_respuesta);
		} else {
			$arr_respuesta = array(
				"status" => false,
				"sql" => $sql,
				"data" => [],
				"message" => "Error en la transacción..."
			);
			echo json_encode($arr_respuesta);
		}
	} else {
		$arr_respuesta = array(
			"status" => false,
			"message" => "Debe llenar los campos obligatorios..."
		);

		echo json_encode($arr_respuesta);
	}
}
function tabla($codigo)
{
	$ClsAud = new ClsAuditoria();
	$result = $ClsAud->get_externa($codigo);
	if (is_array($result)) {
		$arr_respuesta = array(
			"status" => true,
			"tabla" => tabla_externas($codigo),
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
	$ClsAud = new ClsAuditoria();
	if ($codigo != "" && $situacion != "") {
		$act = $ClsAud->get_externa($codigo);
		if (is_array($act)) {
			foreach ($act as $row) {
				$sql = $ClsAud->cambia_situacion_externa($codigo, $situacion);
				$rs = $ClsAud->exec_sql($sql);
				if ($rs == 1) {
					$arr_respuesta = array(
						"status" => true,
						"data" => tabla_externas(),
						"message" => "Situación actualizada exitosamente..."
					);
				} else {
					$arr_respuesta = array(
						"status" => false,
						"data" => [],
						"message" => $sql
					);
				}
			}
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
///////////////////////// DETALLE //////////////////////
function get_detalle($codigo)
{
	$ClsAud = new ClsAuditoria();
	$result = $ClsAud->get_externa_detalle($codigo);
	$arr_data = array();
	if (is_array($result)) {
		foreach ($result as $row) {
			$arr_data["codigo"] = trim($row["dext_codigo"]);
			$arr_data["descripcion"] = trim($row["dext_descripcion"]);
		}
		$arr_respuesta = array(
			"status" => true,
			"data" => $arr_data,
			"tabla" => tabla_externa_detalle($codigo),
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
function grabar_detalle($descripcion, $auditoria)
{
	$ClsAud = new ClsAuditoria();
	if ($auditoria != "" && $descripcion != "") {
		$codigo = $ClsAud->max_externa_detalle();
		$codigo++;
		$sql = $ClsAud->insert_externa_detalle($codigo, $auditoria, $descripcion); /// Inserta Version
		$rs = $ClsAud->exec_sql($sql);
		if ($rs == 1) {
			$arr_respuesta = array(
				"status" => true,
				"data" => tabla_externa_detalle("",$auditoria),
				"sql" => $sql,
				"message" => "Registro guardado satisfactoriamente...!"
			);
			echo json_encode($arr_respuesta);
		} else {
			$arr_respuesta = array(
				"status" => false,
				"sql" => $sql,
				"data" => [],
				"message" => "Error en la transacción..."
			);
			echo json_encode($arr_respuesta);
		}
	} else {
		$arr_respuesta = array(
			"status" => false,
			"message" => "Debe llenar los campos obligatorios..."
		);

		echo json_encode($arr_respuesta);
	}
}
function modificar_detalle($codigo, $auditoria, $descripcion)
{
	$ClsAud = new ClsAuditoria();
	if ($codigo != "" && $descripcion != "") {
		$sql = $ClsAud->modifica_externa_detalle($codigo, $descripcion); /// Inserta Version
		$rs = $ClsAud->exec_sql($sql);
		if ($rs == 1) {
			$arr_respuesta = array(
				"status" => true,
				"data" => tabla_externa_detalle("",$auditoria),
				"sql" => $sql,
				"message" => "Registro guardado satisfactoriamente...!"
			);
			echo json_encode($arr_respuesta);
		} else {
			$arr_respuesta = array(
				"status" => false,
				"sql" => $sql,
				"data" => [],
				"message" => "Error en la transacción..."
			);
			echo json_encode($arr_respuesta);
		}
	} else {
		$arr_respuesta = array(
			"status" => false,
			"message" => "Debe llenar los campos obligatorios..."
		);

		echo json_encode($arr_respuesta);
	}
}
function tabla_detalle($codigo, $auditoria)
{
	$ClsAud = new ClsAuditoria();
	$result = $ClsAud->get_externa_detalle($codigo, $auditoria);
	if (is_array($result)) {
		$arr_respuesta = array(
			"status" => true,
			"tabla" => tabla_externa_detalle($codigo, $auditoria),
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
function situacion_detalle($codigo, $situacion)
{
	$ClsAud = new ClsAuditoria();
	if ($codigo != "" && $situacion != "") {
		$act = $ClsAud->get_externa_detalle($codigo);
		if (is_array($act)) {
			foreach ($act as $row) {
				$auditoria = trim($row["dext_auditoria"]);
				$sql = $ClsAud->cambia_situacion_externa_detalle($codigo, $situacion);
				$rs = $ClsAud->exec_sql($sql);
				if ($rs == 1) {
					$arr_respuesta = array(
						"status" => true,
						"data" => tabla_externa_detalle("",$auditoria),
						"message" => "Situación actualizada exitosamente..."
					);
				} else {
					$arr_respuesta = array(
						"status" => false,
						"data" => [],
						"message" => $sql
					);
				}
			}
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
