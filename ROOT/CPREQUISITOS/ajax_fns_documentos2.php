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
include_once('html_fns_requisitos2.php');

$request = $_REQUEST["request"];
switch ($request) {
	case "tabla_documentos":
		tabla_documento();
		break;
	case "get":
		$codigo = $_REQUEST["codigo"];
		get_documento($codigo);
		break;
	case "grabar_documento":
		$titulo = $_REQUEST["titulo"];
		$tipo = $_REQUEST["tipo"];
		$entidad = $_REQUEST["entidad"];
	//	$sistema = $_REQUEST["sistema"];
		$vigencia = $_REQUEST["vigencia"];
		grabar_documento($titulo, $tipo, $entidad, $vigencia);
		break;
	case "modificar_documento":
		$codigo = $_REQUEST['codigo'];
		$titulo = $_REQUEST["titulo"];
		$tipo = $_REQUEST["tipo"];
		$entidad = $_REQUEST["entidad"];
		$vigencia = $_REQUEST["vigencia"];
		$situacion = $_REQUEST["situacion"];
		modificar_documento($codigo, $titulo, $tipo, $entidad,  $vigencia, $situacion);
		break;
	case "situacion":
		$codigo = $_REQUEST["codigo"];
		$situacion = $_REQUEST["situacion"];
		situacion_documento($codigo, $situacion);
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

function tabla_documento()
{
	$ClsDoc = new ClsDocumento2();
	$result = $ClsDoc->get_documento("", "","","","", 1);
	//var_dump($result);
	//die();
	if (is_array($result)) {
		$arr_respuesta = array(
			"status" => true,
			"tabla" => tabla_documentos(""),
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

function grabar_documento($titulo, $tipo, $entidad, $vigencia)
{
	$ClsDoc = new ClsDocumento2();
	if ($titulo !=  "" && $tipo != "" && $entidad != "" && $vigencia != "") {
		$codigo = $ClsDoc->max_documento();
		$codigo++;
		$sql = $ClsDoc->insert_documento($codigo, $titulo, $tipo, $entidad, $vigencia);
		$rs = $ClsDoc->exec_sql($sql);
		//var_dump($rs);s
		//die();

		if ($rs == 1) {
			$arr_respuesta = array(
				"status" => true,
				"data" => tabla_documentos(""),
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
			"message" => "Debe llenar los campos obligatorios...!!!"
		);

		echo json_encode($arr_respuesta);
	}
}

function modificar_documento($codigo, $titulo, $tipo, $entidad, $vigencia, $situacion)
{
	$ClsDoc = new ClsDocumento2();
	if ($codigo != "" &&  $titulo !=  "" && $tipo != "" && $entidad != ""  && $vigencia != "" && $situacion != "") {

		$sql = $ClsDoc->modifica_documento($codigo, $titulo, $tipo, $entidad, $vigencia, $situacion);
		$rs = $ClsDoc->exec_sql($sql);
		//var_dump($rs);
		//die();

		if ($rs == 1) {
			$arr_respuesta = array(
				"status" => true,
				"data" => tabla_documentos(""),
				"message" => "Registro modificado satisfactoriamente...!"
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

function get_documento($codigo)
{
	$ClsDoc = new ClsDocumento2();
	$result = $ClsDoc->get_documento($codigo, '', '', '', '', '', 1);
	$arr_data = array();
	if (is_array($result)) {
		foreach ($result as $row) {
			$arr_data["codigo"] = trim($row["doc_codigo"]);
			$arr_data["titulo"] = utf8_decode($row["doc_titulo"]);
			$arr_data["tipo"] = utf8_decode($row["doc_tipo"]);
			$arr_data["entidad"] = utf8_decode($row["doc_entidad"]);
			$arr_data["sistema"] = trim($row["doc_sistema"]);
			$arr_data["fecha"] = $row["doc_fecha_ingreso"];
			$arr_data["vigencia"] = $row["doc_vigencia"];
			$arr_data["situacion"] = trim($row["doc_situacion"]);
		}
		$arr_respuesta = array(
			"status" => true,
			"data" => $arr_data,
			"tabla" => tabla_documentos($codigo),
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

function situacion_documento($codigo, $situacion)
{
	$ClsDoc = new ClsDocumento2();
	if ($codigo != "" && $situacion != "") {
		$sql = $ClsDoc->cambia_situacion_doc($codigo, $situacion);
		$rs = $ClsDoc->exec_sql($sql);
		if ($rs == 1) {
			$arr_respuesta = array(
				"status" => true,
				"message" => "Situación actualizada exitosamente...",
				"data" => tabla_documentos("")
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