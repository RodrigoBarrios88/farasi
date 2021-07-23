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
include_once('html_fns_sistema.php');

$request = $_REQUEST["request"]; 
switch($request){
	case "tabla":
		$codigo = $_REQUEST["codigo"];
		get_tabla($codigo);
		break;
	case "get":
		$codigo = $_REQUEST["codigo"];
		get_sistema($codigo);
		break;
	case "grabar":
		$nombre = $_REQUEST["nombre"];
		$color = $_REQUEST["color"];
		$usuario = $_REQUEST["usuario"];
		$politica = $_REQUEST["politica"];
		grabar_sistema($nombre,$color,$usuario, $politica);
		break;
	case "modificar":
		$codigo = $_REQUEST["codigo"];
		$nombre = $_REQUEST["nombre"];
		$color = $_REQUEST["color"];
		$usuario = $_REQUEST["usuario"];
		$politica = $_REQUEST["politica"];
		modificar_sistema($codigo,$nombre,$color,$usuario, $politica);
		break;
	case "situacion":
		$codigo = $_REQUEST["codigo"];
		$situacion = $_REQUEST["situacion"];
		situacion_sistema($codigo,$situacion);
		break;default:
		$arr_respuesta = array(
			"status" => false,
			"data" => [],
			"message" => "Seleccione un metodo..."
		);
		echo json_encode($arr_respuesta);
}

////////////////// SISTEMAS /////////////////////////
function get_tabla($codigo){ 
	$ClSis = new ClsSistema();
	$result = $ClSis->get_sistema($codigo);
	if(is_array($result)){
		$arr_respuesta = array(
			"status" => true,
			"tabla" => tabla_sistemas(''),
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


function get_sistema($codigo){ 
	$ClSis = new ClsSistema();
	$result = $ClSis->get_sistema($codigo);
	$i = 0;
	$arr_data = array();
	if(is_array($result)){
		foreach($result as $row){
			$arr_data["codigo"] = trim($row["sis_codigo"]);
			$arr_data["nombre"] = trim($row["sis_nombre"]);
			$arr_data["usuario"] = trim($row["sis_usuario"]);
			$arr_data["color"] = trim($row["sis_color"]);
			$arr_data["politica"] = trim($row["sis_politica"]);
			$i++;
		}
		$arr_respuesta = array(
			"status" => true,
			"data" => $arr_data,
			"tabla" => tabla_sistemas($codigo),
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


function grabar_sistema($nombre,$color,$usuario, $politica){
	$ClSis = new ClsSistema();
	if($nombre != "" && $color != ""){
		$codigo = $ClSis->max_sistema();
		$codigo++; /// Maximo codigo de Version
		$sql = $ClSis->insert_sistema($codigo,$nombre,$color,$usuario, $politica); /// Inserta Version
		$rs = $ClSis->exec_sql($sql);
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


function modificar_sistema($codigo,$nombre,$color, $usuario, $politica){
	$ClSis = new ClsSistema();
	if($codigo != "" && $nombre != "" && $color != ""){
		$sql = $ClSis->modifica_sistema($codigo,$nombre,$color, $usuario, $politica);
		$rs = $ClSis->exec_sql($sql);
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
				"message" => $sql
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


function situacion_sistema($codigo,$situacion){ 
	$ClSis = new ClsSistema();
	$sql = $ClSis->cambia_situacion_sistema($codigo,$situacion);
	$rs = $ClSis->exec_sql($sql);
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