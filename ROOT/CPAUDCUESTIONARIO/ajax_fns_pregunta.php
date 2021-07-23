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
		get_pregunta($codigo,$auditoria);
		break;
	case "grabar":
		$auditoria = $_REQUEST["auditoria"];
		$seccion = $_REQUEST["seccion"];
		$pregunta = $_REQUEST["pregunta"];
		$tipo = $_REQUEST["tipo"];
		$peso = $_REQUEST["peso"];
		grabar_pregunta($auditoria,$seccion,$pregunta,$tipo,$peso);
		break;
	case "modificar":
		$codigo = $_REQUEST["codigo"];
		$auditoria = $_REQUEST["auditoria"];
		$seccion = $_REQUEST["seccion"];
		$pregunta = $_REQUEST["pregunta"];
		$tipo = $_REQUEST["tipo"];
		$peso = $_REQUEST["peso"];
		modificar_pregunta($codigo,$auditoria,$seccion,$pregunta,$tipo,$peso);
		break;
	case "situacion":
		$codigo = $_REQUEST["codigo"];
		$auditoria = $_REQUEST["auditoria"];
		$situacion = $_REQUEST["situacion"];
		situacion_pregunta($codigo,$auditoria,$situacion);
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
	$result = $ClsAud->get_pregunta($codigo,$auditoria);
	if(is_array($result)){
		$arr_respuesta = array(
			"status" => true,
			"tabla" => tabla_preguntas($codigo,$auditoria),
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

function get_pregunta($codigo,$auditoria){ 
	$ClsAud = new ClsAuditoria();
	$result = $ClsAud->get_pregunta($codigo,$auditoria);
	$i = 0;
	$arr_data = array();
	if(is_array($result)){
		foreach($result as $row){
			$arr_data["codigo"] = trim($row["pre_codigo"]);
			$arr_data["auditoria"] = trim($row["pre_auditoria"]);
			$arr_data["seccion"] = trim($row["pre_seccion"]);
			$arr_data["pregunta"] = trim($row["pre_pregunta"]);
			$arr_data["tipo"] = trim($row["pre_tipo"]);
			$arr_data["peso"] = trim($row["pre_peso"]);
			$i++;
		}
		$arr_respuesta = array(
			"status" => true,
			"data" => $arr_data,
			"tabla" => tabla_preguntas($codigo,$auditoria),
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


function grabar_pregunta($auditoria,$seccion,$pregunta,$tipo,$peso){
	$ClsAud = new ClsAuditoria();
	if($auditoria != ""  && $seccion != "" && $pregunta != "" && $tipo != "" && $peso != ""){
		$codigo = $ClsAud->max_pregunta($auditoria);
		$codigo++; /// Maximo codigo de Seccion
		$sql = $ClsAud->insert_pregunta($codigo,$auditoria,$seccion,$pregunta,$tipo,$peso); /// Inserta Seccion
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


function modificar_pregunta($codigo,$auditoria,$seccion,$pregunta,$tipo,$peso){
	$ClsAud = new ClsAuditoria();
	if($codigo != ""  && $auditoria != ""  && $seccion != "" && $pregunta != "" && $tipo != "" && $peso != ""){
		$sql = $ClsAud->modifica_pregunta($codigo,$auditoria,$seccion,$pregunta,$tipo,$peso);
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


function situacion_pregunta($codigo,$auditoria,$situacion){ 
	$ClsAud = new ClsAuditoria();
	$sql = $ClsAud->cambia_situacion_pregunta($codigo,$auditoria,$situacion);
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