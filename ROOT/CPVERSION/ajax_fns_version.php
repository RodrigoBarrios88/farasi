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
include_once('html_fns_version.php');

$request = $_REQUEST["request"]; 
switch($request){
	case "tabla":
		$codigo = $_REQUEST["codigo"];
		get_tabla($codigo);
		break;
	case "get":
		$codigo = $_REQUEST["codigo"];
		get_version($codigo);
		break;
	case "grabar":
		$software = $_REQUEST["software"];
		$plataforma = $_REQUEST["plataforma"];
		$version = $_REQUEST["version"];
		grabar_version($software,$plataforma,$version);
		break;
	case "modificar":
		$codigo = $_REQUEST["codigo"];
		$software = $_REQUEST["software"];
		$plataforma = $_REQUEST["plataforma"];
		$version = $_REQUEST["version"];
		modificar_version($codigo,$software,$plataforma,$version);
		break;
	case "eliminar":
		$codigo = $_REQUEST["codigo"];
		eliminar_version($codigo);
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
	$ClsVer = new ClsVersion();
	$result = $ClsVer->get_version($codigo);
	if(is_array($result)){
		$arr_respuesta = array(
			"status" => true,
			"tabla" => tabla_version(''),
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


function get_version($codigo){ 
	$ClsVer = new ClsVersion();
	$result = $ClsVer->get_version($codigo);
	$i = 0;
	$arr_data = array();
	if(is_array($result)){
		foreach($result as $row){
			$arr_data["codigo"] = trim($row["ver_codigo"]);
			$arr_data["software"] = trim($row["ver_software"]);
			$arr_data["plataforma"] = trim($row["ver_plataforma"]);
			$arr_data["version"] = trim($row["ver_version"]);
			$i++;
		}
		$arr_respuesta = array(
			"status" => true,
			"data" => $arr_data,
			"tabla" => tabla_version($codigo),
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


function grabar_version($software,$plataforma,$version){
	$ClsVer = new ClsVersion();
	if($software != "" && $plataforma != "" && $version != ""){
		$codigo = $ClsVer->max_version();
		$codigo++; /// Maximo codigo de Version
		$sql = $ClsVer->insert_version($codigo,$software,$plataforma,$version); /// Inserta Version
		$rs = $ClsVer->exec_sql($sql);
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


function modificar_version($codigo,$software,$plataforma,$version){
	$ClsVer = new ClsVersion();
	if($codigo != "" && $software != "" && $plataforma != "" && $version != ""){
		$sql = $ClsVer->modifica_version($codigo,$software,$plataforma,$version);
		$rs = $ClsVer->exec_sql($sql);
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


function eliminar_version($codigo){ 
	$ClsVer = new ClsVersion();
	$sql = $ClsVer->delete_version($codigo);
	$rs = $ClsVer->exec_sql($sql);
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