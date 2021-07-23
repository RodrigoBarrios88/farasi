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
include_once('html_fns_tipo_recursos.php');

$request = $_REQUEST["request"]; 
switch($request){
	case "tabla":
		$codigo = $_REQUEST["codigo"];
		get_tabla($codigo);
		break;
	case "get":
		$codigo = $_REQUEST["codigo"];
		get_tipo_recursos($codigo);
		break;
	case "grabar":
		$nombre = $_REQUEST["nombre"];
		grabar_tipo_recursos($nombre);
		break;
	case "modificar":
		$codigo = $_REQUEST["codigo"];
		$nombre = $_REQUEST["nombre"];
		modificar_tipo_recursos($codigo,$nombre);
		break;
	case "situacion":
		$codigo = $_REQUEST["codigo"];
		$situacion = $_REQUEST["situacion"];
		situacion_tipo_recursos($codigo,$situacion);
		break;	
	default:
		$arr_respuesta = array(
			"status" => false,
			"data" => [],
			"message" => "Seleccione un metodo..."
		);
		echo json_encode($arr_respuesta);
}

////////////////// TIPO DE RECURSOS /////////////////////////

function get_tabla($codigo){ 
	$ClsRec = new ClsRecursos();
	$result = $ClsRec->get_tipo_recursos($codigo);
	if(is_array($result)){
		$arr_respuesta = array(
			"status" => true,
			"tabla" => tabla_tipo_recursos(''),
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


function get_tipo_recursos($codigo){ 
	$ClsRec = new ClsRecursos();
	$result = $ClsRec->get_tipo_recursos($codigo);
	$i = 0;
	$arr_data = array();
	if(is_array($result)){
		foreach($result as $row){
			$arr_data["codigo"] = trim($row["tip_codigo"]);
			$arr_data["nombre"] = trim($row["tip_nombre"]);
			$i++;
		}
		$arr_respuesta = array(
			"status" => true,
			"data" => $arr_data,
			"tabla" => tabla_tipo_recursos($codigo),
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


function grabar_tipo_recursos($nombre){
	$ClsRec = new ClsRecursos();
	if($nombre != ""){
		$codigo = $ClsRec->max_tipo_recursos();
		$codigo++; /// Maximo codigo de Version
		$sql = $ClsRec->insert_tipo_recursos($codigo,$nombre); /// Inserta Version
		$rs = $ClsRec->exec_sql($sql);
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


function modificar_tipo_recursos($codigo,$nombre){
	$ClsRec = new ClsRecursos();
	if($codigo != "" && $nombre != ""){
		$sql = $ClsRec->modifica_tipo_recursos($codigo,$nombre);
		$rs = $ClsRec->exec_sql($sql);
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


function situacion_tipo_recursos($codigo,$situacion){ 
	$ClsRec = new ClsRecursos();
	$sql = $ClsRec->cambia_situacion_tipo_recursos($codigo,$situacion);
	$rs = $ClsRec->exec_sql($sql);
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