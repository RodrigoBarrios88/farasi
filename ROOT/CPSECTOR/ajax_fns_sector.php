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
include_once('html_fns_sector.php');

$request = $_REQUEST["request"]; 
switch($request){
	case "tabla":
		$codigo = $_REQUEST["codigo"];
		get_tabla($codigo);
		break;
	case "get":
		$codigo = $_REQUEST["codigo"];
		get_sector($codigo);
		break;
	case "grabar":
		$sede = $_REQUEST["sede"];
		$nombre = $_REQUEST["nombre"];
		grabar_sector($sede,$nombre);
		break;
	case "modificar":
		$codigo = $_REQUEST["codigo"];
		$sede = $_REQUEST["sede"];
		$nombre = $_REQUEST["nombre"];
		modificar_sector($codigo,$sede,$nombre);
		break;
	case "situacion":
		$codigo = $_REQUEST["codigo"];
		$situacion = $_REQUEST["situacion"];
		situacion_sector($codigo,$situacion);
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

function get_tabla($codigo){ 
	$ClsSec = new ClsSector();
	$result = $ClsSec->get_sector($codigo);
	if(is_array($result)){
		$arr_respuesta = array(
			"status" => true,
			"tabla" => tabla_sectores(''),
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


function get_sector($codigo){ 
	$ClsSec = new ClsSector();
	$result = $ClsSec->get_sector($codigo);
	$i = 0;
	$arr_data = array();
	if(is_array($result)){
		foreach($result as $row){
			$arr_data["codigo"] = trim($row["sec_codigo"]);
			$arr_data["sede"] = trim($row["sec_sede"]);
			$arr_data["nombre"] = trim($row["sec_nombre"]);
			$i++;
		}
		$arr_respuesta = array(
			"status" => true,
			"data" => $arr_data,
			"tabla" => tabla_sectores($codigo),
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


function grabar_sector($sede,$nombre){
	$ClsSec = new ClsSector();
	if($nombre != "" && $sede != ""){
		$codigo = $ClsSec->max_sector();
		$codigo++; /// Maximo codigo de Version
		$sql = $ClsSec->insert_sector($codigo,$sede,$nombre); /// Inserta Version
		$rs = $ClsSec->exec_sql($sql);
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


function modificar_sector($codigo,$sede,$nombre){
	$ClsSec = new ClsSector();
	if($codigo != "" && $nombre != "" && $sede != ""){
		$sql = $ClsSec->modifica_sector($codigo,$sede,$nombre);
		$rs = $ClsSec->exec_sql($sql);
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


function situacion_sector($codigo,$situacion){ 
	$ClsSec = new ClsSector();
	$sql = $ClsSec->cambia_situacion_sector($codigo,$situacion);
	$rs = $ClsSec->exec_sql($sql);
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
			//"sql" => $sql,
			"data" => [],
			"message" => "Error en la ejecución.."
		);
		
		echo json_encode($arr_respuesta);
	}
}?>