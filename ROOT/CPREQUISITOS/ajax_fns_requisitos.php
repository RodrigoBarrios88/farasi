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
include_once('html_fns_requisitos.php');

$request = $_REQUEST["request"];
switch ($request) {
	case "tabla_requisitos":
		tabla();
		break;
	case "get":
		$codigo = $_REQUEST["codigo"];
		get($codigo);
		break;
	case "grabar_requisito":
		$nomenclatura = $_REQUEST["nomenclatura"];
		$documento = $_REQUEST["documento"];
		$descripcion = $_REQUEST["descripcion"];
		$documento_soporte = $_REQUEST['documento_soporte'];
		$requisito = $_REQUEST['requisito'];
		$comentario = $_REQUEST["comentario"];
		grabar_requisito($nomenclatura, $documento, $descripcion, $requisito, $comentario, $documento_soporte);
		break;
	case "modificar_requisito":
		$codigo = $_REQUEST['codigo'];
		$nomenclatura = $_REQUEST["nomenclatura"];
		$documento = $_REQUEST["documento"];
		$descripcion = $_REQUEST["descripcion"];
		$requisito = $_REQUEST["requisito"];
		$comentario = $_REQUEST["comentario"];
		$documento_soporte = $_REQUEST['documento_soporte'];
		modificar_requisito($codigo, $nomenclatura,$documento,$descripcion,$requisito,$comentario,$documento_soporte);
		break;
	case "situacion":
		$codigo = $_REQUEST["codigo"];
		$situacion = $_REQUEST["situacion"];
		situacion_requisito($codigo, $situacion);
		break;
	case "asignar_requisito":
		$requisito = $_REQUEST["requisito"];
		$usuarios = $_REQUEST["usuarios"];
		asignar_requisito($requisito, $usuarios);
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

function tabla()
{
	$ClsReq = new ClsRequisito();
	$result = $ClsReq->get_requisito("");

	if (is_array($result)) {
		$arr_respuesta = array(
			"status" => true,
			"tabla" => tabla_requisito(""),
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

function grabar_requisito($nomenclatura, $documento, $descripcion, $requisito, $comentario,$documento_soporte)
{
	$ClsReq = new ClsRequisito();
	if ($nomenclatura !=  "" && $documento != "" && $descripcion != "") {
		$codigo = $ClsReq->max_requisito();
		$codigo++;
		$sql = $ClsReq->insert_requisito($codigo, $nomenclatura,$documento,$descripcion, $requisito, $comentario, $documento_soporte);
		$rs = $ClsReq->exec_sql($sql);

		if ($rs == 1) {
			$arr_respuesta = array(
				"status" => true,
				"data" => tabla_requisito(""),
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
function modificar_requisito($codigo,  $nomenclatura,$documento,$descripcion,$requisito,$comentario,$documento_soporte)
{
	$ClsReq = new ClsRequisito();
	if ($nomenclatura !=  "" && $documento != "" && $descripcion != "" && $requisito != "") {

		$sql = $ClsReq->modifica_requisito($codigo, $nomenclatura,$documento,$descripcion, $requisito, $comentario,$documento_soporte);
		$rs = $ClsReq->exec_sql($sql);
       //var_dump($rs);
       //die();
		if ($rs == 1) {
			$arr_respuesta = array(
				"status" => true,
				"data" => tabla_requisito(""),
				"message" => "Registro guardado satisfactoriamente...!"
			);
			echo json_encode($arr_respuesta);
		} else {
			$arr_respuesta = array(
				"status" => false,
				"data" => [],
				"message" => "Error en la transacción...".$sql
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
	$ClsReq = new ClsRequisito();
	$result = $ClsReq->get_requisito($codigo);
	$arr_data = array();
	if (is_array($result)) {
		foreach ($result as $row) {
			$arr_data["codigo"] = trim($row["req_codigo"]);
			$arr_data["nomenclatura"] = trim($row["req_nomenclatura"]);
			$arr_data["codigo_documento"] = trim($row["req_documento"]);
			$arr_data["titulo_documento"] = trim($row["doc_titulo"]);
			$arr_data["descripcion"] = trim($row["req_descripcion"]);
			$arr_data["requisito"] = trim($row["req_tipo"]);
			$arr_data["comentario"] =trim( $row["req_comentario"]);
			$arr_data["documento_soporte"] = $row["req_documento_soporte"];
		}
		$arr_respuesta = array(
			"status" => true,
			"data" => $arr_data,
			"tabla" => tabla_requisito($codigo),
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
function situacion_requisito($codigo, $situacion)
{
	$ClsReq = new ClsRequisito();
	if ($codigo != "" && $situacion != "") {
		$sql = $ClsReq->cambia_situacion_requisito($codigo, $situacion);
		$rs = $ClsReq->exec_sql($sql);
		if ($rs == 1) {
			$arr_respuesta = array(
				"status" => true,
				"message" => "Situación actualizada exitosamente...",
				"data" => tabla_requisito("")
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


function asignar_requisito($requisito, $usuarios)
{
	$ClsReq = new ClsRequisito();
	if ($requisito!= "") {
		$codigo = $ClsReq->max_requisito_usuario($requisito);
		$codigo++;
		$sql = $ClsReq->delete_requisito_usuario($requisito);
		if ($usuarios != "") {
			$arrUsuarios = explode(",", $usuarios);
			$count = count($arrUsuarios); //cuenta cuantas vienen en el array
		} else {
			$count = 0;
		}
		for ($i = 0; $i < $count; $i++) {
			$usuario = $arrUsuarios[$i];
			$sql .= $ClsReq->insert_requisito_usuario($codigo, $requisito, $usuario);
			$codigo++;
		}
		$rs = $ClsReq->exec_sql($sql);
		if ($rs == 1) {
			$arr_respuesta = array(
				"status" => true,
				"sql" => $sql,
				"data" => [],
				"message" => "Registro guardado satisfactoriamente...!"
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
		);echo json_encode($arr_respuesta);
	}
}