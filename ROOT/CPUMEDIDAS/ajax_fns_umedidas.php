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
include_once('html_fns_umedidas.php');

$request = $_REQUEST["request"]; 
switch($request){
	case "tabla":
		$codigo = $_REQUEST["codigo"];
		get_tabla($codigo);
		break;
	case "get":
		$codigo = $_REQUEST["codigo"];
		get_umedida($codigo);
		break;
	case "grabar":
		$desc = $_REQUEST["desc"];
		$abrev = $_REQUEST["abrev"];
		$clase = $_REQUEST["clase"];
		grabar_umedida($desc, $abrev, $clase);
		break;
	case "modificar":
		$codigo = $_REQUEST["codigo"];
		$desc = $_REQUEST["desc"];
		$abrev = $_REQUEST["abrev"];
		$clase = $_REQUEST["clase"];
		modificar_umedida($codigo,$desc, $abrev, $clase);
		break;
	case "situacion":
		$codigo = $_REQUEST["codigo"];
		$situacion = $_REQUEST["situacion"];
		situacion_umedida($codigo,$situacion);
		break;default:
		$arr_respuesta = array(
			"status" => false,
			"data" => [],
			"message" => "Seleccione un metodo..."
		);
		echo json_encode($arr_respuesta);
}

////////////////// VERSIONES /////////////////////////
function get_tabla($codigo){ 
	$ClsUmed = new ClsUmedida();
	$result = $ClsUmed->get_unidad('',$codigo);
	if(is_array($result)){
		$arr_respuesta = array(
			"status" => true,
			"tabla" => tabla_unidades_de_medida(''),
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


function get_umedida($codigo){ 
	$ClsUmed = new ClsUmedida();
	$result = $ClsUmed->get_unidad('',$codigo);
	$i = 0;
	$arr_data = array();
	if(is_array($result)){
		foreach($result as $row){
			$arr_data["codigo"] = trim($row["umed_codigo"]);
			$arr_data["desc"] = trim($row["umed_desc_lg"]);
			$arr_data["abrev"] = trim($row["umed_desc_ct"]);
			$arr_data["clase"] = trim($row["umed_clase"]);
			$i++;
		}
		$arr_respuesta = array(
			"status" => true,
			"data" => $arr_data,
			"tabla" => tabla_unidades_de_medida($codigo),
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


function grabar_umedida($desc, $abrev, $clase){
	$ClsUmed = new ClsUmedida();
	if($desc != "" && $abrev != "" && $clase != "" ){
		$codigo = $ClsUmed->max_umedida();
		$codigo++; /// Maximo codigo de Version
		$sql = $ClsUmed->insert_umedida($codigo, $desc, $abrev, $clase); /// Inserta Version
		$rs = $ClsUmed->exec_sql($sql);
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
				//"sql" => $sql,
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


function modificar_umedida($codigo,$desc, $abrev, $clase){
	$ClsUmed = new ClsUmedida();
	if($codigo != "" && $desc != "" && $abrev != "" && $clase != "" ){
		$sql = $ClsUmed->modifica_umedida($codigo,$desc, $abrev, $clase);
		$rs = $ClsUmed->exec_sql($sql);
		if($rs == 1){
			$arr_respuesta = array(
				"status" => true,
				"data" => [],
				"message" => "Registro actualizados satisfactoriamente...!"
			);
			echo json_encode($arr_respuesta);
		}else{
			$arr_respuesta = array(
				"status" => false,
				//"sql" => $sql,
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


function situacion_umedida($codigo,$situacion){ 
	$ClsUmed = new ClsUmedida();
	$sql = $ClsUmed->cambia_situacion_umedida($codigo,$situacion);
	$rs = $ClsUmed->exec_sql($sql);
	if($rs == 1){
		$arr_respuesta = array(
			"status" => true,
			"data" => [],
			"message" => "Versión eliminada exitosamente...!"
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