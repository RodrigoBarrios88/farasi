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
include_once('html_fns_cc.php');

$request = $_REQUEST["request"]; 
switch($request){
	case "tabla":
		$codigo = $_REQUEST["codigo"];
		get_tabla($codigo);
		break;
	case "get":
		$codigo = $_REQUEST["codigo"];
		get_centro_costo($codigo);
		break;
	case "grabar":
		$nombre = $_REQUEST["nombre"];
		grabar_centro_costo($nombre);
		break;
	case "modificar":
		$codigo = $_REQUEST["codigo"];
		$nombre = $_REQUEST["nombre"];
		modificar_centro_costo($codigo,$nombre);
		break;
	case "situacion":
		$codigo = $_REQUEST["codigo"];
		$situacion = $_REQUEST["situacion"];
		situacion_centro_costo($codigo,$situacion);
		break;	
	default:
		$arr_respuesta = array(
			"status" => false,
			"data" => [],
			"message" => "Seleccione un metodo..."
		);
		echo json_encode($arr_respuesta);
}

////////////////// DEPARTAMENTOS /////////////////////////

function get_tabla($codigo){ 
	$ClsCC = new ClsCentroCosto();
	$result = $ClsCC->get_centro_costo($codigo);
	if(is_array($result)){
		$arr_respuesta = array(
			"status" => true,
			"tabla" => tabla_centro_costos(''),
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


function get_centro_costo($codigo){ 
	$ClsCC = new ClsCentroCosto();
	$result = $ClsCC->get_centro_costo($codigo);
	$i = 0;
	$arr_data = array();
	if(is_array($result)){
		foreach($result as $row){
			$arr_data["codigo"] = trim($row["cc_codigo"]);
			$arr_data["nombre"] = trim($row["cc_nombre"]);
			$i++;
		}
		$arr_respuesta = array(
			"status" => true,
			"data" => $arr_data,
			"tabla" => tabla_centro_costos($codigo),
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


function grabar_centro_costo($nombre){
	$ClsCC = new ClsCentroCosto();
	if($nombre != ""){
		$codigo = $ClsCC->max_centro_costo();
		$codigo++; /// Maximo codigo de Version
		$sql = $ClsCC->insert_centro_costo($codigo,$nombre); /// Inserta Version
		$rs = $ClsCC->exec_sql($sql);
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


function modificar_centro_costo($codigo,$nombre){
	$ClsCC = new ClsCentroCosto();
	if($codigo != "" && $nombre != ""){
		$sql = $ClsCC->modifica_centro_costo($codigo,$nombre);
		$rs = $ClsCC->exec_sql($sql);
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


function situacion_centro_costo($codigo,$situacion){ 
	$ClsCC = new ClsCentroCosto();
	$sql = $ClsCC->cambia_situacion_centro_costo($codigo,$situacion);
	$rs = $ClsCC->exec_sql($sql);
	if($rs == 1){
		$arr_respuesta = array(
			"status" => true,
			"data" => [],
			"message" => "Cambio de situación con éxito...!"
		);
		echo json_encode($arr_respuesta);
	}else{
		$arr_respuesta = array(
			"status" => false,
			"sql" => $sql,
			"data" => [],
			"message" => "Error en la ejecución.."
		);
		echo json_encode($arr_respuesta);
	}
}?>