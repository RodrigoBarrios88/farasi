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
		////////////////////// Causa ////////////////
	case "tabla":
		$plan = $_REQUEST["plan"];
		$pertenece = $_REQUEST["pertenece"];
		tabla($plan, $pertenece);
		break;
	case "get":
		$codigo = $_REQUEST["codigo"];
		get($codigo);
		break;
	case "grabar":
		$plan = $_REQUEST["plan"];
		$causa = $_REQUEST["causa"];
		$pertenece = $_REQUEST["pertenece"];
		grabar($plan, $causa, $pertenece);
		break;
	case "modificar":
		$codigo = $_REQUEST["codigo"];
		$causa = $_REQUEST["causa"];
		$plan = $_REQUEST["plan"];
		modificar($codigo, $causa, $plan);
		break;
	case "update":
		$codigo = $_REQUEST["codigo"];
		$campo = $_REQUEST["campo"];
		$valor = $_REQUEST["valor"];
		update($codigo, $campo, $valor);
		break;
	case "situacion":
		$codigo = $_REQUEST["codigo"];
		$situacion = $_REQUEST["situacion"];
		situacion($codigo, $situacion);
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
	$ClsCau = new ClsCausa();
	$result = $ClsCau->get_causa($codigo);
	$arr_data = array();
	if (is_array($result)) {
		foreach ($result as $row) {
			$arr_data["codigo"] = trim($row["cau_codigo"]);
			$arr_data["pertenece"] = trim($row["cau_pertenece"]);
			$arr_data["causa"] = trim($row["cau_descripcion"]);
		}
		$arr_respuesta = array(
			"status" => true,
			"data" => $arr_data,
			"tabla" => tabla_causa($codigo, "", $arr_data["pertenece"]),
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
function grabar($plan, $causa, $pertenece)
{
	$pertenece = ($pertenece == "") ? 0 : $pertenece;
	$ClsCau = new ClsCausa();
	if ($plan != "" && $causa != "") {
		$codigo = $ClsCau->max_causa();
		$codigo++;
		$sql = $ClsCau->insert_causa($codigo, $plan, $pertenece, $causa); /// Inserta Version
		$rs = $ClsCau->exec_sql($sql);
		if ($rs == 1) {
			$arr_respuesta = array(
				"status" => true,
				"data" => tabla_causa('', $plan, $pertenece),
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
function modificar($codigo, $causa, $plan)
{
	$ClsCau = new ClsCausa();
	if ($codigo != "" && $causa != "") {
		$sql = $ClsCau->modifica_causa($codigo, $causa); /// Inserta Version
		$rs = $ClsCau->exec_sql($sql);
		if ($rs == 1) {
			$arr_respuesta = array(
				"status" => true,
				"data" => tabla_causa('', $plan, 0),
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
function tabla($plan, $pertenece)
{
	$pertenece = ($pertenece == "") ? 0 : $pertenece;
	$ClsCau = new ClsCausa();
	$result = $ClsCau->get_causa("", $plan, $pertenece);
	if (is_array($result)) {
		$arr_respuesta = array(
			"status" => true,
			"tabla" => tabla_causa("", $plan, $pertenece),
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
	$ClsCau = new ClsCausa();
	if ($codigo != "" && $situacion != "") {
		$act = $ClsCau->get_causa($codigo);
		if (is_array($act)) {
			foreach ($act as $row) {
				$plan = trim($row["cau_plan"]);
				$pertenece = trim($row["cau_pertenece"]);
				$sql = $ClsCau->cambia_situacion_causa($codigo, $situacion);
				$rs = $ClsCau->exec_sql($sql);
				if ($rs == 1) {
					$arr_respuesta = array(
						"status" => true,
						"data" => tabla_causa("", $plan, $pertenece),
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
		} else {
			$arr_respuesta = array(
				"status" => false,
				"data" => [],
				"message" => "No se encuentra el codigo: "
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
function update($codigo, $campo, $valor)
{
	$ClsCau = new ClsCausa();
	if ($codigo != "" && $campo != "") {
		switch ($campo) {
			case 1:
				$db_campo = "act_comentario";
				break;
			default:
				$db_campo = "";
				break;
		}
		if ($db_campo != "") {
			$sql = $ClsCau->update_causa($codigo, $db_campo, $valor);
		}
		$rs = $ClsCau->exec_sql($sql);
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
