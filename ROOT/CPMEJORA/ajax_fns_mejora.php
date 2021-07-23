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
	case "tabla_quejas":
		tablaQuejas();
		break;
	case "grabar_queja":
		$proceso = $_REQUEST["proceso"];
		$sistema = $_REQUEST["sistema"];
		$descripcion = $_REQUEST["descripcion"];
		$cliente = $_REQUEST["cliente"];
		$tipo = $_REQUEST["tipo"];
		grabar_queja($proceso, $sistema, $descripcion, $cliente, $tipo);
		break;
	case "modificar_queja":
		$codigo = $_REQUEST['codigo'];
		$proceso = $_REQUEST["proceso"];
		$sistema = $_REQUEST["sistema"];
		$descripcion = $_REQUEST["descripcion"];
		$cliente = $_REQUEST["cliente"];
		$tipo = $_REQUEST["tipo"];
		modificar_queja($codigo, $proceso, $sistema, $descripcion, $cliente, $tipo);
		break;
	case "update":
		$codigo = $_REQUEST["codigo"];
		$campo = $_REQUEST["campo"];
		$valor = $_REQUEST["valor"];
		update($codigo, $campo, $valor);
		break;
	case "update_externa_detalle":
		$codigo = $_REQUEST["codigo"];
		$campo = $_REQUEST["campo"];
		$valor = $_REQUEST["valor"];
		update_externa_detalle($codigo, $campo, $valor);
		break;
	case "update_interna_detalle":
		$codigo = $_REQUEST["codigo"];
		$campo = $_REQUEST["campo"];
		$valor = $_REQUEST["valor"];
		update_interna_detalle($codigo, $campo, $valor);
		break;
	case "update_tipo_evaluacion":
		$codigo = $_REQUEST["codigo"];
		$campo = $_REQUEST["campo"];
		$valor = $_REQUEST["valor"];
		update_tipo_evaluacion($codigo, $campo, $valor);
		break;
	case "situacion":
		$codigo = $_REQUEST["codigo"];
		$situacion = $_REQUEST["situacion"];
		situacion_queja($codigo, $situacion);
		break;
	case "get":
		$codigo = $_REQUEST["codigo"];
		get($codigo);
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
function tablaQuejas()
{
	$ClsQue = new ClsQuejas();
	$result = $ClsQue->get_quejas("");
	if (is_array($result)) {
		$arr_respuesta = array(
			"status" => true,
			"tabla" => tabla_quejas(""),
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

function get($codigo)
{
	$ClsAct = new ClsQuejas();
	$result = $ClsAct->get_quejas($codigo);
	$arr_data = array();
	if (is_array($result)) {
		foreach ($result as $row) {
			$arr_data["codigo"] = trim($row["que_codigo"]);
			$arr_data["proceso"] = trim($row["que_proceso"]);
			$arr_data["sistema"] = trim($row["que_sistema"]);
			$arr_data["descripcion"] = trim($row["que_descripcion"]);
			$arr_data["cliente"] = trim($row["que_cliente"]);
			$arr_data["tipo"] = trim($row["que_tipo"]);
		}
		$arr_respuesta = array(
			"status" => true,
			"data" => $arr_data,
			"tabla" => tabla_quejas($codigo),
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

function grabar_queja($proceso, $sistema, $descripcion, $cliente, $tipo)
{
	$ClsQue = new ClsQuejas();
	if ($descripcion != "" && $cliente != "" && $tipo != "") {
		$codigo = $ClsQue->max_quejas();
		$codigo++;
		//$codigo = 2;
		$sql = $ClsQue->insert_quejas($codigo, $proceso, $sistema, $descripcion, $cliente, $tipo);
		$rs = $ClsQue->exec_sql($sql);
		if ($rs == 1) {
			$arr_respuesta = array(
				"status" => true,
				"data" => tabla_quejas(""),
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

function modificar_queja($codigo, $proceso, $sistema, $descripcion, $cliente, $tipo)
{
	$ClsQue = new ClsQuejas();
	if ($descripcion != "" && $cliente != "" && $tipo != "" && $sistema != "" && $proceso != "") {
		$sql = $ClsQue->modifica_quejas($codigo, $proceso, $sistema, $descripcion, $cliente, $tipo);
		$rs = $ClsQue->exec_sql($sql);
		if ($rs == 1) {
			$arr_respuesta = array(
				"status" => true,
				"data" => tabla_quejas(""),
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

function update($codigo, $campo, $valor)
{
	$ClsHal = new ClsHallazgo();
	if ($codigo != "" && $campo != "") {
		switch ($campo) {
			case 1:
				$db_campo = "hal_tipo";
				break;
			default:
				$db_campo = "";
				break;
		}
		if ($db_campo != "") {
			$sql = $ClsHal->update_hallazgo($codigo, $db_campo, $valor);
		}
		$rs = $ClsHal->exec_sql($sql);
		if ($rs == 1) {
			$arr_respuesta = array(
				"status" => true,
				"sql" => $sql,
				"message" => "Registro actualizado satisfactoriamente...!"
			);
			echo json_encode($arr_respuesta);
		} else {
			$arr_respuesta = array(
				"status" => false,
				//"sql" => $sql,
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

function update_externa_detalle($codigo, $campo, $valor)
{
	$ClsAud = new ClsAuditoria();
	if ($codigo != "" && $campo != "") {
		switch ($campo) {
			case 1:
				$db_campo = "dext_proceso";
				break;
			case 2:
				$db_campo = "dext_sistema";
				break;
			default:
				$db_campo = "";
				break;
		}
		if ($db_campo != "") {
			$sql = $ClsAud->update_externa_detalle($codigo, $db_campo, $valor);
		}
		$rs = $ClsAud->exec_sql($sql);
		if ($rs == 1) {
			$arr_respuesta = array(
				"status" => true,
				"sql" => $sql,
				"message" => "Registro actualizado satisfactoriamente...!"
			);
			echo json_encode($arr_respuesta);
		} else {
			$arr_respuesta = array(
				"status" => false,
				//"sql" => $sql,
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

function update_interna_detalle($codigo, $campo, $valor)
{
	$ClsEje = new ClsEjecucion();
	if ($codigo != "" && $campo != "") {
		switch ($campo) {
			case 1:
				$db_campo = "eje_proceso";
				break;
			case 2:
				$db_campo = "eje_sistema";
				break;
			default:
				$db_campo = "";
				break;
		}
		if ($db_campo != "") {
			$sql = $ClsEje->update_ejecucion($codigo, $db_campo, $valor);
		}
		$rs = $ClsEje->exec_sql($sql);
		if ($rs == 1) {
			$arr_respuesta = array(
				"status" => true,
				"sql" => $sql,
				"message" => "Registro actualizado satisfactoriamente...!"
			);
			echo json_encode($arr_respuesta);
		} else {
			$arr_respuesta = array(
				"status" => false,
				//"sql" => $sql,
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

function update_tipo_evaluacion($codigo, $campo, $valor)
{
	$ClsTipEval = new ClsTipoEvaluacion();
	if ($codigo != "" && $campo != "") {
		switch ($campo) {
			case 1:
				$db_campo = "eva_proceso";
				break;
			case 2:
				$db_campo = "dext_sistema";
				break;
			default:
				$db_campo = "";
				break;
		}
		if ($db_campo != "") {
			$sql = $ClsTipEval->update_tipo_evaluacion($codigo, $db_campo, $valor);
		}
		$rs = $ClsTipEval->exec_sql($sql);
		if ($rs == 1) {
			$arr_respuesta = array(
				"status" => true,
				"sql" => $sql,
				"message" => "Registro actualizado satisfactoriamente...!"
			);
			echo json_encode($arr_respuesta);
		} else {
			$arr_respuesta = array(
				"status" => false,
				//"sql" => $sql,
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


function situacion_queja($codigo, $situacion)
{
	$ClsPla = new ClsQuejas();
	if ($codigo != "" && $situacion != "") {
		$sql = $ClsPla->cambia_situacion_quejas($codigo, $situacion);
		$rs = $ClsPla->exec_sql($sql);
		if ($rs == 1) {
			$arr_respuesta = array(
				"status" => true,
				"message" => "Situación actualizada exitosamente...",
				"data" => tabla_quejas("")
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
