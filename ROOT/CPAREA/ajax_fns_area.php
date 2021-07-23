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
include_once('html_fns_area.php');

$request = $_REQUEST["request"]; 
switch($request){
	case "sector":
		$sede = $_REQUEST["sede"];
		get_sector($sede);
		break;
	case "tabla":
		$codigo = $_REQUEST["codigo"];
		get_tabla($codigo);
		break;
	case "get":
		$codigo = $_REQUEST["codigo"];
		get_area($codigo);
		break;
	case "grabar":
		$sede = $_REQUEST["sede"];
		$sector = $_REQUEST["sector"];
		$nivel = $_REQUEST["nivel"];
		$nombre = $_REQUEST["nombre"];
		grabar_area($sede,$sector,$nivel,$nombre);
		break;
	case "modificar":
		$codigo = $_REQUEST["codigo"];
		$sede = $_REQUEST["sede"];
		$sector = $_REQUEST["sector"];
		$nivel = $_REQUEST["nivel"];
		$nombre = $_REQUEST["nombre"];
		modificar_area($codigo,$sede,$sector,$nivel,$nombre);
		break;
	case "situacion":
		$codigo = $_REQUEST["codigo"];
		$situacion = $_REQUEST["situacion"];
		situacion_area($codigo,$situacion);
		break;
	//////----
	case "tablaQR":
		$codigo = $_REQUEST["codigo"];
		get_tablaQR($codigo);
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
function get_sector($sede){ 
	$arr_respuesta = array(
		"status" => true,
		"combo" => sector_html("sector",$sede,"","select2")
	);
	echo json_encode($arr_respuesta);
}


function get_tabla($codigo){ 
	$ClsAre = new ClsArea();
	$result = $ClsAre->get_area($codigo);
	if(is_array($result)){
		$arr_respuesta = array(
			"status" => true,
			"tabla" => tabla_areas(''),
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

function get_tablaQR($codigo){ 
	$ClsAre = new ClsArea();
	$result = $ClsAre->get_area($codigo);
	if(is_array($result)){
		$arr_respuesta = array(
			"status" => true,
			"tabla" => tabla_areasQR(''),
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


function get_area($codigo){ 
	$ClsAre = new ClsArea();
	$result = $ClsAre->get_area($codigo);
	$i = 0;
	$arr_data = array();
	if(is_array($result)){
		foreach($result as $row){
			$arr_data["codigo"] = trim($row["are_codigo"]);
			$arr_data["sede"] = trim($row["are_sede"]);
			$sede = trim($row["are_sede"]);
			$arr_data["sector"] = trim($row["are_sector"]);
			$arr_data["nombre"] = trim($row["are_nombre"]);
			$arr_data["nivel"] = trim($row["are_nivel"]);
			$i++;
		}
		$arr_respuesta = array(
			"status" => true,
			"data" => $arr_data,
			"tabla" => tabla_areas($codigo),
			"combo" => sector_html("sector",$sede,"","select2"),
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


function grabar_area($sede,$sector,$nivel,$nombre){
	$ClsAre = new ClsArea();
	if($sede != "" && $sector != "" && $nivel != "" && $nombre != ""){
		$codigo = $ClsAre->max_area();
		$codigo++; /// Maximo codigo de Version
		$sql = $ClsAre->insert_area($codigo,$sede,$sector,$nivel,$nombre);
		$rs = $ClsAre->exec_sql($sql);
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


function modificar_area($codigo,$sede,$sector,$nivel,$nombre){
	$ClsAre = new ClsArea();
	if($codigo != "" && $sede != "" && $sector != "" && $nivel != "" && $nombre != ""){
		$sql = $ClsAre->modifica_area($codigo,$sede,$sector,$nivel,$nombre);
		$rs = $ClsAre->exec_sql($sql);
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


function situacion_area($codigo,$situacion){ 
	$ClsAre = new ClsArea();
	$sql = $ClsAre->cambia_situacion_area($codigo,$situacion);
	$rs = $ClsAre->exec_sql($sql);
	if($rs == 1){
		$arr_respuesta = array(
			"status" => true,
			"sql" => $sql,
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