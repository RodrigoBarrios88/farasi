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
include_once('html_fns_status.php');

$request = $_REQUEST["request"]; 
switch($request){
	case "tabla":
		$codigo = $_REQUEST["codigo"];
		get_tabla($codigo);
		break;
	case "get":
		$codigo = $_REQUEST["codigo"];
		get_status_hd($codigo);
		break;
	case "grabar":
		$posicion = $_REQUEST["posicion"];
		$nombre = $_REQUEST["nombre"];
		$color = $_REQUEST["color"];
		grabar_status_hd($posicion,$nombre,$color);
		break;
	case "modificar":
		$codigo = $_REQUEST["codigo"];
		$posicion = $_REQUEST["posicion"];
		$nombre = $_REQUEST["nombre"];
		$color = $_REQUEST["color"];
		modificar_status_hd($codigo,$posicion,$nombre,$color);
		break;
	case "situacion":
		$codigo = $_REQUEST["codigo"];
		$situacion = $_REQUEST["situacion"];
		situacion_status_hd($codigo,$situacion);
		break;default:
		$arr_respuesta = array(
			"status" => false,
			"data" => [],
			"message" => "Seleccione un metodo..."
		);
		echo json_encode($arr_respuesta);
}

////////////////// STATUS /////////////////////////
function get_tabla($codigo){ 
	$ClsSta = new ClsStatus();
	$result = $ClsSta->get_status_hd($codigo);
	if(is_array($result)){
		$arr_respuesta = array(
			"status" => true,
			"tabla" => tabla_status_helpdesk(''),
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


function get_status_hd($codigo){ 
	$ClsSta = new ClsStatus();
	$result = $ClsSta->get_status_hd($codigo);
	$i = 0;
	$arr_data = array();
	if(is_array($result)){
		foreach($result as $row){
			$arr_data["codigo"] = trim($row["sta_codigo"]);
			$arr_data["posicion"] = trim($row["sta_posicion"]);
			$arr_data["nombre"] = trim($row["sta_nombre"]);
			$arr_data["color"] = trim($row["sta_color"]);
			$i++;
		}
		$arr_respuesta = array(
			"status" => true,
			"data" => $arr_data,
			"tabla" => tabla_status_helpdesk($codigo),
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


function grabar_status_hd($posicion,$nombre,$color){
	$ClsSta = new ClsStatus();
	if($posicion != ""  && $nombre != "" && $color != ""){
		$codigo = $ClsSta->max_status_hd();
		$codigo++; /// Maximo codigo de Version
		$sql = $ClsSta->insert_status_hd($codigo,$posicion,$nombre,$color); /// Inserta Version
		$rs = $ClsSta->exec_sql($sql);
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


function modificar_status_hd($codigo,$posicion,$nombre,$color){
	$ClsSta = new ClsStatus();
	if($codigo != "" && $posicion != ""  && $nombre != "" && $color != ""){
		$sql = $ClsSta->modifica_status_hd($codigo,$posicion,$nombre,$color);
		$rs = $ClsSta->exec_sql($sql);
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
				"sql" => $sql,
				//"data" => [],
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


function situacion_status_hd($codigo,$situacion){ 
	$ClsSta = new ClsStatus();
	$sql = $ClsSta->cambia_situacion_status_hd($codigo,$situacion);
	$rs = $ClsSta->exec_sql($sql);
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