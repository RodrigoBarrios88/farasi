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
include_once('html_fns_correo.php');

$request = $_REQUEST["request"]; 
switch($request){
	case "tabla":
		$codigo = $_REQUEST["codigo"];
		get_tabla($codigo);
		break;
	case "get":
		$codigo = $_REQUEST["codigo"];
		get_correo($codigo);
		break;
	case "grabar":
		$sede = $_REQUEST["sede"];
		$auditoria = $_REQUEST["auditoria"];
		$nombre = $_REQUEST["nombre"];
		$correo = $_REQUEST["correo"];
		grabar_correo($sede,$auditoria,$nombre,$correo);
		break;
	case "modificar":
		$codigo = $_REQUEST["codigo"];
		$sede = $_REQUEST["sede"];
		$auditoria = $_REQUEST["auditoria"];
		$nombre = $_REQUEST["nombre"];
		$correo = $_REQUEST["correo"];
		modificar_correo($codigo,$sede,$auditoria,$nombre,$correo);
		break;
	case "delete":
		$codigo = $_REQUEST["codigo"];
		$situacion = $_REQUEST["situacion"];
		delete_correo($codigo,$situacion);
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
	$ClsAud = new ClsAuditoria();
	$result = $ClsAud->get_correo($codigo,'');
	if(is_array($result)){
		$arr_respuesta = array(
			"status" => true,
			"tabla" => tabla_correos(''),
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


function get_correo($codigo){ 
	$ClsAud = new ClsAuditoria();
	$result = $ClsAud->get_correo($codigo,'');
	$i = 0;
	$arr_data = array();
	if(is_array($result)){
		foreach($result as $row){
			$arr_data["codigo"] = trim($row["cor_codigo"]);
			$arr_data["sede"] = trim($row["cor_sede"]);
			$arr_data["auditoria"] = trim($row["cor_auditoria"]);
			$arr_data["nombre"] = trim($row["cor_nombre"]);
			$arr_data["correo"] = trim($row["cor_correo"]);
			$i++;
		}
		$arr_respuesta = array(
			"status" => true,
			"data" => $arr_data,
			"tabla" => tabla_correos($codigo),
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


function grabar_correo($sede,$auditoria,$nombre,$correo){
	$ClsAud = new ClsAuditoria();
	if($sede != ""  && $auditoria != "" && $nombre != "" && $correo != ""){
		$codigo = $ClsAud->max_correo();
		$codigo++; /// Maximo codigo de Version
		$sql = $ClsAud->insert_correo($codigo,$sede,$auditoria,$nombre,$correo); /// Inserta Version
		$rs = $ClsAud->exec_sql($sql);
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


function modificar_correo($codigo,$sede,$auditoria,$nombre,$correo){
	$ClsAud = new ClsAuditoria();
	if($codigo != "" && $sede != ""  && $auditoria != "" && $nombre != "" && $correo != ""){
		$sql = $ClsAud->modifica_correo($codigo,$sede,$auditoria,$nombre,$correo);
		$rs = $ClsAud->exec_sql($sql);
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


function delete_correo($codigo){ 
	$ClsAud = new ClsAuditoria();
	$sql = $ClsAud->delete_correo($codigo);
	$rs = $ClsAud->exec_sql($sql);
	if($rs == 1){
		$arr_respuesta = array(
			"status" => true,
			"data" => [],
			"message" => "Correo eliminado satisfactoriamente...!"
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