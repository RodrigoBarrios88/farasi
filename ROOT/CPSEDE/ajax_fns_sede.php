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
include_once('html_fns_sede.php');

$request = $_REQUEST["request"]; 
switch($request){
	case "mundep":
		$departamento = $_REQUEST["departamento"];
		get_municipio($departamento);
		break;
	case "tabla":
		$codigo = $_REQUEST["codigo"];
		get_tabla($codigo);
		break;
	case "get":
		$codigo = $_REQUEST["codigo"];
		get_sede($codigo);
		break;
	case "grabar":
		$nombre = $_REQUEST["nombre"];
		$departamento = $_REQUEST["departamento"];
		$municipio = $_REQUEST["municipio"];
		$direccion = $_REQUEST["direccion"];
		$zona = $_REQUEST["zona"];
		$lat = $_REQUEST["lat"];
		$long = $_REQUEST["long"];
		grabar_sede($nombre,$departamento,$municipio,$direccion,$zona,$lat,$long);
		break;
	case "modificar":
		$codigo = $_REQUEST["codigo"];
		$nombre = $_REQUEST["nombre"];
		$departamento = $_REQUEST["departamento"];
		$municipio = $_REQUEST["municipio"];
		$direccion = $_REQUEST["direccion"];
		$zona = $_REQUEST["zona"];
		$lat = $_REQUEST["lat"];
		$long = $_REQUEST["long"];
		modificar_sede($codigo,$nombre,$departamento,$municipio,$direccion,$zona,$lat,$long);
		break;
	case "situacion":
		$codigo = $_REQUEST["codigo"];
		$situacion = $_REQUEST["situacion"];
		situacion_sede($codigo,$situacion);
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
function get_municipio($departamento){ 
	$arr_respuesta = array(
		"status" => true,
		"combo" => municipio_html($departamento,"municipio","","select2")
	);
	echo json_encode($arr_respuesta);
}


function get_tabla($codigo){ 
	$ClsSed = new ClsSede();
	$result = $ClsSed->get_sede($codigo);
	if(is_array($result)){
		$arr_respuesta = array(
			"status" => true,
			"tabla" => tabla_sedes(''),
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


function get_sede($codigo){ 
	$ClsSed = new ClsSede();
	$result = $ClsSed->get_sede($codigo);
	$i = 0;
	$arr_data = array();
	if(is_array($result)){
		foreach($result as $row){
			$arr_data["codigo"] = trim($row["sed_codigo"]);
			$arr_data["nombre"] = trim($row["sed_nombre"]);
			$arr_data["departamento"] = trim($row["sed_departamento"]);
			$departamento = trim($row["sed_departamento"]);
			$arr_data["municipio"] = trim($row["sed_municipio"]);
			$arr_data["zona"] = trim($row["sed_zona"]);
			$arr_data["direccion"] = trim($row["sed_direccion"]);
			$arr_data["latitud"] = trim($row["sed_latitud"]);
			$arr_data["longitud"] = trim($row["sed_longitud"]);
			$i++;
		}
		$arr_respuesta = array(
			"status" => true,
			"data" => $arr_data,
			"tabla" => tabla_sedes($codigo),
			"combo" => municipio_html($departamento,"municipio","","select2"),
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


function grabar_sede($nombre,$departamento,$municipio,$direccion,$zona,$lat,$long){
	$ClsSed = new ClsSede();
	if($nombre != "" && $direccion != "" && $lat != "" && $long != ""){
		$codigo = $ClsSed->max_sede();
		$codigo++; /// Maximo codigo de Version
		$sql = $ClsSed->insert_sede($codigo,$nombre,$departamento,$municipio,$direccion,$zona,$lat,$long); /// Inserta Version
		$rs = $ClsSed->exec_sql($sql);
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


function modificar_sede($codigo,$nombre,$departamento,$municipio,$direccion,$zona,$lat,$long){
	$ClsSed = new ClsSede();
	if($codigo != "" && $nombre != "" && $direccion != "" && $lat != "" && $long != ""){
		$sql = $ClsSed->modifica_sede($codigo,$nombre,$departamento,$municipio,$direccion,$zona,$lat,$long);
		$rs = $ClsSed->exec_sql($sql);
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


function situacion_sede($codigo,$situacion){ 
	$ClsSed = new ClsSede();
	$sql = $ClsSed->cambia_situacion_sede($codigo,$situacion);
	$rs = $ClsSed->exec_sql($sql);
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