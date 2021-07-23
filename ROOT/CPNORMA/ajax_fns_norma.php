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
include_once('html_fns_norma.php');

$request = $_REQUEST["request"]; 
switch($request){
	case "tabla":
		$codigo = $_REQUEST["codigo"];
		get_tabla($codigo);
		break;
	case "get":
		$codigo = $_REQUEST["codigo"];
		get_norma($codigo);
		break;
	case "grabar":
		$nombre = $_REQUEST["nombre"];
		grabar_norma($nombre);
		break;
	case "modificar":
		$codigo = $_REQUEST["codigo"];
		$nombre = $_REQUEST["nombre"];
		modificar_norma($codigo,$nombre);
		break;
	case "situacion":
		$codigo = $_REQUEST["codigo"];
		$situacion = $_REQUEST["situacion"];
		situacion_norma($codigo,$situacion);
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
	$ClsNor = new ClsNorma();
	$result = $ClsNor->get_norma($codigo);
	if(is_array($result)){
		$arr_respuesta = array(
			"status" => true,
			"tabla" => tabla_normas(''),
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


function get_norma($codigo){ 
	$ClsNor = new ClsNorma();
	$result = $ClsNor->get_norma($codigo);
	$i = 0;
	$arr_data = array();
	if(is_array($result)){
		foreach($result as $row){
			$arr_data["codigo"] = trim($row["nor_codigo"]);
			$arr_data["nombre"] = trim($row["nor_nombre"]);
			$i++;
		}
		$arr_respuesta = array(
			"status" => true,
			"data" => $arr_data,
			"tabla" => tabla_normas($codigo),
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


function grabar_norma($nombre){
	$ClsNor = new ClsNorma();
	if($nombre != ""){
		$codigo = $ClsNor->max_norma();
		$codigo++; /// Maximo codigo de Version
		$sql = $ClsNor->insert_norma($codigo,$nombre); /// Inserta Version
		$rs = $ClsNor->exec_sql($sql);
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


function modificar_norma($codigo,$nombre){
	$ClsNor = new ClsNorma();
	if($codigo != "" && $nombre != ""){
		$sql = $ClsNor->modifica_norma($codigo,$nombre);
		$rs = $ClsNor->exec_sql($sql);
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


function situacion_norma($codigo,$situacion){ 
	$ClsNor = new ClsNorma();
	$sql = $ClsNor->cambia_situacion_norma($codigo,$situacion);
	$rs = $ClsNor->exec_sql($sql);
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