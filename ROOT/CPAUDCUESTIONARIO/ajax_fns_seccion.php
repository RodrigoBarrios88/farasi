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
include_once('html_fns_cuestionario.php');

$request = $_REQUEST["request"]; 
switch($request){
	case "tabla":
		$codigo = $_REQUEST["codigo"];
		$auditoria = $_REQUEST["auditoria"];
		get_tabla($codigo,$auditoria);
		break;
	case "get":
		$codigo = $_REQUEST["codigo"];
		$auditoria = $_REQUEST["auditoria"];
		get_seccion($codigo,$auditoria);
		break;
	case "grabar":
		$auditoria = $_REQUEST["auditoria"];
		$numero = $_REQUEST["numero"];
		$titulo = $_REQUEST["titulo"];
		$proposito = $_REQUEST["proposito"];
		grabar_seccion($auditoria,$numero,$titulo,$proposito);
		break;
	case "modificar":
		$codigo = $_REQUEST["codigo"];
		$auditoria = $_REQUEST["auditoria"];
		$numero = $_REQUEST["numero"];
		$titulo = $_REQUEST["titulo"];
		$proposito = $_REQUEST["proposito"];
		modificar_seccion($codigo,$auditoria,$numero,$titulo,$proposito);
		break;
	case "situacion":
		$codigo = $_REQUEST["codigo"];
		$auditoria = $_REQUEST["auditoria"];
		$situacion = $_REQUEST["situacion"];
		situacion_seccion($codigo,$auditoria,$situacion);
		break;
	default:
		$arr_respuesta = array(
			"status" => false,
			"data" => [],
			"message" => "Seleccione un metodo..."
		);
		echo json_encode($arr_respuesta);
}

////////////////// STATUS /////////////////////////
function get_tabla($codigo,$auditoria){ 
	$ClsAud = new ClsAuditoria();
	$result = $ClsAud->get_secciones($codigo,$auditoria);
	if(is_array($result)){
		$arr_respuesta = array(
			"status" => true,
			"tabla" => tabla_secciones($codigo,$auditoria),
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

function get_seccion($codigo,$auditoria){ 
	$ClsAud = new ClsAuditoria();
	$result = $ClsAud->get_secciones($codigo,$auditoria);
	$i = 0;
	$arr_data = array();
	if(is_array($result)){
		foreach($result as $row){
			$arr_data["codigo"] = trim($row["sec_codigo"]);
			$arr_data["auditoria"] = trim($row["sec_auditoria"]);
			$arr_data["numero"] = trim($row["sec_numero"]);
			$arr_data["titulo"] = trim($row["sec_titulo"]);
			$arr_data["proposito"] = trim($row["sec_proposito"]);
			$i++;
		}
		$arr_respuesta = array(
			"status" => true,
			"data" => $arr_data,
			"tabla" => tabla_secciones($codigo,$auditoria),
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


function grabar_seccion($auditoria,$numero,$titulo,$proposito){
	$ClsAud = new ClsAuditoria();
	if($auditoria != ""  && $numero != "" && $titulo != ""){
		$codigo = $ClsAud->max_secciones($auditoria);
		$codigo++; /// Maximo codigo de Seccion
		$sql = $ClsAud->insert_secciones($codigo,$auditoria,$numero,$titulo,$proposito); /// Inserta Seccion
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
				"sql" => $sql,
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


function modificar_seccion($codigo,$auditoria,$numero,$titulo,$proposito){
	$ClsAud = new ClsAuditoria();
	if($codigo != ""  && $auditoria != ""  && $numero != "" && $titulo != ""){
		$sql = $ClsAud->modifica_secciones($codigo,$auditoria,$numero,$titulo,$proposito);
		$rs = $ClsAud->exec_sql($sql);
		if($rs == 1){
			$arr_respuesta = array(
				"status" => true,
				"data" => [],
				"message" => "Registro actualizado satisfactoriamente...!"
			);
			echo json_encode($arr_respuesta);
		}else{
			$arr_respuesta = array(
				"status" => false,
				"sql" => $sql,
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


function situacion_seccion($codigo,$auditoria,$situacion){ 
	$ClsAud = new ClsAuditoria();
	$sql = $ClsAud->cambia_situacion_seccion($codigo,$auditoria,$situacion);
	$rs = $ClsAud->exec_sql($sql);
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