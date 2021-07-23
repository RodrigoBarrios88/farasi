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
include_once('html_fns_cuestionario.php');

$request = $_REQUEST["request"]; 
switch($request){
	case "tabla":
		$codigo = $_REQUEST["codigo"];
		get_tabla($codigo);
		break;
	case "get":
		$codigo = $_REQUEST["codigo"];
		get_cuestionario($codigo);
		break;
	case "grabar":
		$categoria = $_REQUEST["categoria"];
		$titulo = $_REQUEST["titulo"];
		$descripcion = $_REQUEST["descripcion"];
		$objetivo = $_REQUEST["objetivo"];
		grabar_cuestionario($categoria,$titulo,$descripcion,$objetivo);
		break;
	case "modificar":
		$codigo = $_REQUEST["codigo"];
		$categoria = $_REQUEST["categoria"];
		$titulo = $_REQUEST["titulo"];
		$descripcion = $_REQUEST["descripcion"];
		$objetivo = $_REQUEST["objetivo"];
		modificar_cuestionario($codigo,$categoria,$titulo,$descripcion,$objetivo);
		break;
	case "situacion":
		$codigo = $_REQUEST["codigo"];
		$situacion = $_REQUEST["situacion"];
		situacion_cuestionario($codigo,$situacion);
		break;
	default:
		$arr_respuesta = array(
			"status" => false,
			"data" => [],
			"message" => "Seleccione un metodo..."
		);
		echo json_encode($arr_respuesta);
}

////////////////// STATUS /////////////////////////
function get_tabla($codigo){ 
	$ClsEnc = new ClsEncuesta();
	$result = $ClsEnc->get_cuestionario($codigo);
	if(is_array($result)){
		$arr_respuesta = array(
			"status" => true,
			"tabla" => tabla_cuestionarios($codigo),
			"message" => ""
		);
	}else{
		$arr_respuesta = array(
			"status" => false,
			"data" => [],
			"message" => "Aún no hay datos registrados..."
		);
	}
	echo json_encode($arr_respuesta);
}

function get_cuestionario($codigo){ 
	$ClsEnc = new ClsEncuesta();
	$result = $ClsEnc->get_cuestionario($codigo);
	$i = 0;
	$arr_data = array();
	if(is_array($result)){
		foreach($result as $row){
			$arr_data["codigo"] = trim($row["cue_codigo"]);
			$arr_data["categoria"] = trim($row["cue_categoria"]);
			$arr_data["titulo"] = trim($row["cue_titulo"]);
			$arr_data["descripcion"] = trim($row["cue_descripcion"]);
			$arr_data["objetivo"] = trim($row["cue_objetivo"]);
			$i++;
		}
		$arr_respuesta = array(
			"status" => true,
			"data" => $arr_data,
			"tabla" => tabla_cuestionarios($codigo),
			"message" => ""
		);
	}else{
		$arr_respuesta = array(
			"status" => false,
			"data" => [],
			"message" => "Aún no hay datos registrados..."
		);
	}
	echo json_encode($arr_respuesta);
}


function grabar_cuestionario($categoria,$titulo,$descripcion,$objetivo){
	$ClsEnc = new ClsEncuesta();
	if($categoria != ""  && $titulo != ""){
		$codigo = $ClsEnc->max_cuestionario();
		$codigo++; /// Maximo codigo de Cuestionario
		$sql = $ClsEnc->insert_cuestionario($codigo,$categoria,$titulo,$descripcion,$objetivo);
		$rs = $ClsEnc->exec_sql($sql);
		if($rs == 1){
			$arr_respuesta = array(
				"status" => true,
				"data" => [],
				"message" => "Registro guardado satisfactoriamente...!"
			);
			echo json_encode($arr_respuesta);
		}else{
			$arr_respuesta = array(
				"status" => false,
				"sql" => $sql,
				"data" => [],
				"message" => "Error en la transacción..."
			);
			echo json_encode($arr_respuesta);
		}
	}else{
		$arr_respuesta = array(
			"status" => false,
			"data" => [],
			"message" => "Debe llenar los campos obligatorios..."
		);
		
		echo json_encode($arr_respuesta);
	}
}


function modificar_cuestionario($codigo,$categoria,$titulo,$descripcion,$objetivo){
	$ClsEnc = new ClsEncuesta();
	if($codigo != ""  && $categoria != ""  && $titulo != ""){
		$sql = $ClsEnc->modifica_cuestionario($codigo,$categoria,$titulo,$descripcion,$objetivo);
		$rs = $ClsEnc->exec_sql($sql);
		if($rs == 1){
			$arr_respuesta = array(
				"status" => true,
				"data" => [],
				"message" => "Registro actualizado satisfactoriamente...!"
			);
			echo json_encode($arr_respuesta);
		}else{
			$arr_respuesta = array(
				"status" => false,
				"sql" => $sql,
				"data" => [],
				"message" => "Error en la transacción..."
			);
			echo json_encode($arr_respuesta);
		}
	}else{
		$arr_respuesta = array(
			"status" => false,
			"data" => [],
			"message" => "Debe llenar los campos obligatorios..."
		);
		
		echo json_encode($arr_respuesta);
	}
}


function situacion_cuestionario($codigo,$situacion){ 
	$ClsEnc = new ClsEncuesta();
	$sql = $ClsEnc->cambia_situacion_cuestionario($codigo,$situacion);
	$rs = $ClsEnc->exec_sql($sql);
	if($rs == 1){
		$arr_respuesta = array(
			"status" => true,
			"data" => [],
			"message" => "Situación actualizada satisfactoriamente...!"
		);
		
		echo json_encode($arr_respuesta);
	}else{
		$arr_respuesta = array(
			"status" => false,
			"sql" => $sql,
			"data" => [],
			"message" => "Error en la ejecución"
		);
		
		echo json_encode($arr_respuesta);
	}
}?>